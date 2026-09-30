import { createClient } from "npm:@supabase/supabase-js@2.102.0";
const URL = Deno.env.get("SUPABASE_URL")!;
const service = createClient(URL, Deno.env.get("SUPABASE_SERVICE_ROLE_KEY")!, {
  auth: { persistSession: false, autoRefreshToken: false },
});
const client = () =>
  createClient(URL, Deno.env.get("SUPABASE_ANON_KEY")!, {
    auth: { persistSession: false, autoRefreshToken: false },
  });
class HttpError extends Error {
  constructor(
    public status: number,
    message: string,
  ) {
    super(message);
  }
}
const fail = (m: string, s = 400): never => {
  throw new HttpError(s, m);
};
const text = (d: any, k: string, max = 100, required = false) => {
  const v = d[k] ?? "";
  if (typeof v !== "string" || v.length > max || (required && !v.trim()))
    fail("Please check " + k + ".");
  return v.trim();
};
const password = (d: any, k = "password") => {
  const v = d[k];
  if (
    typeof v !== "string" ||
    new TextEncoder().encode(v).length < 12 ||
    new TextEncoder().encode(v).length > 72
  )
    fail(
      "Use a password of 12–72 bytes (at least 12 characters for plain English).",
    );
  return v;
};
const rawPassword = (d: any, k = "password") => {
  const v = d[k];
  if (typeof v !== "string" || !v || new TextEncoder().encode(v).length > 72)
    fail("Please check your password.");
  return v;
};
const email = (d: any, required = false) => {
  const v = text(d, "email", 254, required);
  if (v && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v))
    fail("Enter a valid email address.");
  return v;
};
const hash = async (s: string) =>
  Array.from(
    new Uint8Array(
      await crypto.subtle.digest("SHA-256", new TextEncoder().encode(s)),
    ),
  )
    .map((x) => x.toString(16).padStart(2, "0"))
    .join("");
async function rate(key: string, limit: number, seconds: number) {
  const { data, error } = await service.rpc("ss_rate_limit", {
    p_bucket: await hash(key),
    p_limit: limit,
    p_seconds: seconds,
  });
  if (error) fail("Service temporarily unavailable.", 503);
  if (!data) fail("Too many attempts. Please wait and try again.", 429);
}
async function settings() {
  const { data, error } = await service
    .from("ss_settings")
    .select("*")
    .eq("id", true)
    .single();
  if (error) fail("Company settings are unavailable.", 503);
  return data;
}
const audit = async (actor: string, action: string, target?: string) => {
  await service.from("ss_audit").insert({ actor, action, target });
};
async function profile(d: any) {
  const s = await settings();
  const birthday = s.hr_details_enabled ? text(d, "birthday", 10) : "";
  if (
    birthday &&
    (!/^\d{4}-\d{2}-\d{2}$/.test(birthday) ||
      !Number.isFinite(Date.parse(birthday)) ||
      new Date(birthday).toISOString().slice(0, 10) !== birthday ||
      birthday > new Date().toISOString().slice(0, 10))
  )
    fail("Please check your birthday.");
  const gender = s.hr_details_enabled ? text(d, "gender", 30) : "";
  if (!["", "Woman", "Man", "Non-binary", "Self-described"].includes(gender))
    fail("Invalid gender.");
  return {
    first_name: text(d, "first_name", 100, true),
    last_name: text(d, "last_name", 100, true),
    middle_name: text(d, "middle_name", 100) || null,
    email: email(d) || null,
    phone: text(d, "phone", 30) || null,
    address: text(d, "address", 300) || null,
    birthday: birthday || null,
    gender: gender || null,
  };
}
async function current(token: string, allowChange = false) {
  if (!token) fail("Please sign in.", 401);
  const { data, error } = await service.auth.getUser(token);
  if (error || !data.user) fail("Please sign in again.", 401);
  const { data: p, error: e } = await service
    .from("ss_profiles")
    .select("*")
    .eq("id", data.user.id)
    .single();
  if (e || !p || !p.approved)
    fail("Your employee account is awaiting IT approval.", 403);
  let claims: any;
  try {
    claims = JSON.parse(
      atob(token.split(".")[1].replace(/-/g, "+").replace(/_/g, "/")),
    );
  } catch {
    fail("Please sign in again.", 401);
  }
  if ((claims.app_metadata?.ss_version ?? 0) !== p.session_version)
    fail("Please sign in again.", 401);
  if (p.must_change_password && !allowChange)
    fail("Please change your temporary password first.", 403);
  return { ...p, auth_email: data.user.email };
}
function publicProfile(p: any) {
  const { auth_email, session_version, approved, ...u } = p;
  return u;
}
const authSession = (s: any) => ({
  access_token: s.access_token,
  refresh_token: s.refresh_token,
  expires_in: s.expires_in,
});
async function createAccount(
  d: any,
  role = "employee",
  approved = false,
  initial = false,
) {
  const p = await profile(d);
  const username = text(d, "username", 80, true);
  if (!/^[a-zA-Z0-9_.-]{3,80}$/.test(username))
    fail(
      "Use 3–80 letters, numbers, dots, dashes or underscores for your username.",
    );
  const authEmail = initial
    ? username.toLowerCase() + "@sunson.invalid"
    : email(d, true);
  const pw = initial ? text(d, "password", 72, true) : password(d);
  if (initial && pw.length < 8) fail("Initial password is too short.");
  const department = text(d, "department", 100, true);
  const { data: existing } = await service
    .from("ss_profiles")
    .select("id")
    .eq("username", username.toLowerCase())
    .maybeSingle();
  if (existing) fail("That username is unavailable.", 409);
  const { data, error } = await service.auth.admin.createUser({
    email: authEmail,
    password: pw,
    email_confirm: approved,
    app_metadata: { ss_version: 1 },
    user_metadata: {
      first_name: p.first_name,
      last_name: p.last_name,
      middle_name: p.middle_name,
      username: username.toLowerCase(),
      department: "Employee",
      email: authEmail,
    },
  });
  if (error || !data.user)
    fail(
      "Unable to create this account. Check the username and email or contact IT.",
      409,
    );
  const { error: e } = await service
    .from("ss_profiles")
    .insert({
      ...p,
      id: data.user.id,
      username: username.toLowerCase(),
      department,
      role,
      approved,
      must_change_password: initial,
      email: initial ? null : p.email,
    });
  if (e) {
    console.error("profile-insert-failed", e.code, e.message);
    await service.from("profiles").delete().eq("id", data.user.id);
    await service.auth.admin.deleteUser(data.user.id);
    fail("Unable to save the account. Please contact IT.", 503);
  }
  return data.user.id;
}
const writeActions = new Set([
  "login",
  "refresh",
  "register",
  "bootstrap",
  "enquiries",
  "logout",
  "password",
  "profile",
  "employees",
  "reset-password",
  "approve-registration",
  "check-in",
]);
Deno.serve(async (req: Request) => {
  let action = "";
  try {
    const url = new globalThis.URL(req.url);
    action = url.pathname.split("/").filter(Boolean).pop() || "";
    if (!["GET", "POST"].includes(req.method)) fail("Method not allowed.", 405);
    if (req.method === "POST" && !writeActions.has(action))
      fail("Endpoint not found.", 404);
    if (
      req.method === "GET" &&
      ![
        "session",
        "public-config",
        "attendance",
        "team-attendance",
        "receipt",
        "enquiries",
        "registrations",
      ].includes(action)
    )
      fail("Endpoint not found.", 404);
    let d: any = {};
    if (req.method === "POST") {
      if (!req.headers.get("content-type")?.startsWith("application/json"))
        fail("JSON request required.", 415);
      const raw = await req.text();
      if (raw.length > 12000) fail("Request too large.", 413);
      try {
        d = JSON.parse(raw);
      } catch {
        fail("Invalid request.");
      }
      if (!d || Array.isArray(d) || typeof d !== "object")
        fail("Invalid request.");
    }
    const token = (req.headers.get("authorization") || "").replace(
      /^Bearer /i,
      "",
    );
    const ip = req.headers.get("x-forwarded-for")?.split(",")[0] || "unknown";
    let result: any;
    if (action === "public-config") {
      const s = await settings();
      result = {
        base_url: "https://sun-son-solar.plovindino1.chatgpt.site",
        address: s.address,
        phone: s.phone,
        maps_url: s.maps_url,
        response_time: s.response_time,
        ga_id: s.ga_id,
        hr_details_enabled: s.hr_details_enabled,
      };
    } else if (action === "bootstrap") {
      const digest = await hash(text(d, "token", 200, true));
      const { data: allowed, error } = await service
        .from("ss_bootstrap")
        .update({ consumed: true })
        .eq("token_hash", digest)
        .eq("consumed", false)
        .gt("expires_at", new Date().toISOString())
        .select("token_hash")
        .maybeSingle();
      if (error || !allowed) fail("Not authorized.", 403);
      if (!Array.isArray(d.accounts) || d.accounts.length !== 2)
        fail("Invalid bootstrap.");
      const ids = [];
      for (const a of d.accounts) {
        if (!["ceo", "it_head"].includes(a.role)) fail("Invalid role.");
        ids.push(await createAccount(a, a.role, true, true));
      }
      result = { ok: true, created: ids.length };
    } else if (action === "register") {
      await rate("registration-global", 30, 3600);
      await rate("register:" + ip, 5, 3600);
      if (text(d, "website", 200)) fail("Unable to register.");
      if (d.consent !== "on")
        fail("Please acknowledge the terms and privacy notice.");
      if (d.password !== d.password_confirmation)
        fail("Your passwords do not match.");
      await createAccount(d);
      result = { ok: true, status: "pending_approval" };
    } else if (action === "login") {
      const username = text(d, "username", 80, true);
      await rate("login:" + username.toLowerCase(), 10, 900);
      await rate("login-ip:" + ip, 60, 900);
      const { data: p } = await service
        .from("ss_profiles")
        .select("*")
        .eq("username", username.toLowerCase())
        .maybeSingle();
      if (!p) fail("Username or password is incorrect.", 401);
      const { data: au, error: ae } = await service.auth.admin.getUserById(
        p.id,
      );
      if (ae) fail("Username or password is incorrect.", 401);
      const pw = rawPassword(d);
      const { data, error } = await client().auth.signInWithPassword({
        email: au.user.email!,
        password: pw,
      });
      if (error || !data.session)
        fail(
          "Username or password is incorrect, or your account is awaiting approval.",
          401,
        );
      if (!p.approved) fail("Your account is awaiting IT approval.", 403);
      await audit(p.id, "login");
      result = { ok: true, _session: authSession(data.session) };
    } else if (action === "refresh") {
      const { data, error } = await client().auth.refreshSession({
        refresh_token: text(d, "refresh_token", 2000, true),
      });
      if (error || !data.session) fail("Please sign in again.", 401);
      await current(data.session.access_token, true);
      result = { ok: true, _session: authSession(data.session) };
    } else if (action === "session") {
      const p = await current(token, true);
      result = { user: publicProfile(p) };
    } else if (action === "enquiries" && req.method === "POST") {
      await rate("enquiries-global", 60, 3600);
      await rate("enquiry:" + ip, 5, 3600);
      if (text(d, "website", 200)) fail("Unable to submit.");
      if (d.consent !== "on") fail("Please acknowledge the privacy notice.");
      const message = text(d, "message", 3000, true);
      if (message.length < 10)
        fail("Please add at least 10 characters about your project.");
      const interest = text(d, "interest", 80, true);
      if (
        ![
          "Complete solar system",
          "Solar panels",
          "Inverters",
          "Batteries",
          "Racking & mounting",
          "Wires & connections",
          "Consultation",
          "Designing",
          "Permitting",
          "Installation",
          "Maintenance",
          "Repair",
          "Monitoring",
        ].includes(interest)
      )
        fail("Choose a valid product or service.");
      const { data, error } = await service
        .from("ss_enquiries")
        .insert({
          name: text(d, "name", 120, true),
          email: email(d, true),
          phone: text(d, "phone", 30) || null,
          interest,
          message,
        })
        .select("reference")
        .single();
      if (error) fail("Unable to save your enquiry.", 503);
      result = { reference: data.reference };
    } else if (action === "receipt") {
      const ref = url.searchParams.get("reference") || "";
      if (!/^SS[A-F0-9]{14}$/.test(ref))
        fail("No recent enquiry receipt found.", 404);
      const { data } = await service
        .from("ss_enquiries")
        .select("reference")
        .eq("reference", ref)
        .maybeSingle();
      if (!data) fail("Receipt not found.", 404);
      result = data;
    } else {
      const p = await current(token, ["logout", "password"].includes(action));
      const admin = ["ceo", "it_head"].includes(p.role);
      const it = p.role === "it_head";
      if (
        [
          "employees",
          "reset-password",
          "registrations",
          "approve-registration",
        ].includes(action) &&
        !it
      )
        fail("IT Head access required.", 403);
      if (["enquiries", "team-attendance"].includes(action) && !admin)
        fail("Administrator access required.", 403);
      if (action === "logout") {
        const version = p.session_version + 1;
        const { error: a } = await service.auth.admin.updateUserById(p.id, {
          app_metadata: { ss_version: version },
        });
        if (a) fail("Could not end sessions.", 503);
        const { error: b } = await service
          .from("ss_profiles")
          .update({ session_version: version })
          .eq("id", p.id);
        if (b) fail("Could not end sessions.", 503);
        await service.auth.admin.signOut(token, "global");
        result = { ok: true };
      } else if (action === "profile") {
        const fields = await profile(d); // Contact email is not silently changed into a new login identity.
        const { error } = await service
          .from("ss_profiles")
          .update(fields)
          .eq("id", p.id);
        if (error) fail("Could not save your profile.", 503);
        await audit(p.id, "profile_update");
        result = { ok: true };
      } else if (action === "password") {
        const next = password(d, "new_password");
        const old = rawPassword(d, "current_password");
        if (old === next) fail("Choose a different password.");
        await rate("password:" + p.id, 10, 900);
        const { error: bad } = await client().auth.signInWithPassword({
          email: p.auth_email,
          password: old,
        });
        if (bad) fail("Current password is incorrect.", 403);
        const version = p.session_version + 1;
        const { error } = await service.auth.admin.updateUserById(p.id, {
          password: next,
          app_metadata: { ss_version: version },
        });
        if (error) fail("Could not update password.");
        const { error: pe } = await service
          .from("ss_profiles")
          .update({ session_version: version, must_change_password: false })
          .eq("id", p.id);
        if (pe) fail("Contact IT to complete your password update.", 503);
        const { data: auth, error: le } =
          await client().auth.signInWithPassword({
            email: p.auth_email,
            password: next,
          });
        if (le || !auth.session)
          fail("Password changed. Please sign in again.", 401);
        await audit(p.id, "password_change");
        result = { ok: true, _session: authSession(auth.session) };
      } else if (action === "employees") {
        const id = await createAccount(d, "employee", true, false);
        await service
          .from("ss_profiles")
          .update({ must_change_password: true })
          .eq("id", id);
        await audit(p.id, "employee_created", id);
        result = { ok: true };
      } else if (action === "registrations") {
        const { data, error } = await service
          .from("ss_profiles")
          .select(
            "id,username,first_name,last_name,department,email,created_at",
          )
          .eq("approved", false)
          .eq("role", "employee")
          .order("created_at")
          .limit(100);
        if (error) fail("Unable to load registrations.", 503);
        result = { records: data };
      } else if (action === "approve-registration") {
        const id = text(d, "user_id", 36, true);
        const { data: target } = await service
          .from("ss_profiles")
          .select("id")
          .eq("id", id)
          .eq("approved", false)
          .eq("role", "employee")
          .maybeSingle();
        if (!target) fail("Registration is no longer pending.", 409);
        const { error: a } = await service.auth.admin.updateUserById(id, {
          email_confirm: true,
        });
        if (a) fail("Could not activate account.", 503);
        const { error } = await service
          .from("ss_profiles")
          .update({ approved: true })
          .eq("id", id)
          .eq("role", "employee");
        if (error) fail("Could not approve account.", 503);
        await audit(p.id, "registration_approved", id);
        result = { ok: true };
      } else if (action === "reset-password") {
        const username = text(d, "username", 80, true);
        const { data: t } = await service
          .from("ss_profiles")
          .select("*")
          .eq("username", username.toLowerCase())
          .maybeSingle();
        if (!t) fail("Account not found.", 404);
        if (t.id === p.id)
          fail("Use your password change form for your own account.");
        const version = t.session_version + 1;
        const { error } = await service.auth.admin.updateUserById(t.id, {
          password: password(d),
          app_metadata: { ss_version: version },
        });
        if (error) fail("Could not reset password.", 503);
        const { error: e } = await service
          .from("ss_profiles")
          .update({ session_version: version, must_change_password: true })
          .eq("id", t.id);
        if (e) fail("Contact your administrator to complete reset.", 503);
        await audit(p.id, "password_reset", t.id);
        result = { ok: true };
      } else if (action === "enquiries") {
        const { data, error } = await service
          .from("ss_enquiries")
          .select("reference,name,email,phone,interest,message,created_at")
          .order("created_at", { ascending: false })
          .limit(100);
        if (error) fail("Unable to load enquiries.", 503);
        result = { records: data };
      } else if (action === "attendance" || action === "team-attendance") {
        let q = service
          .from("ss_attendance")
          .select(
            "checked_at,latitude,longitude,accuracy,ss_profiles(first_name,last_name)",
          )
          .order("checked_at", { ascending: false })
          .limit(action === "attendance" ? 30 : 100);
        if (action === "attendance") q = q.eq("user_id", p.id);
        const { data, error } = await q;
        if (error) fail("Unable to load attendance.", 503);
        result = {
          records: data.map((r: any) => ({
            ...r,
            name:
              (r.ss_profiles?.first_name || "") +
              " " +
              (r.ss_profiles?.last_name || ""),
          })),
        };
      } else if (action === "check-in") {
        const s = await settings();
        if (s.site_latitude === null || s.site_longitude === null)
          fail(
            "The worksite coordinates have not been configured. Please contact IT.",
            503,
          );
        const lat = Number(d.latitude),
          lon = Number(d.longitude),
          accuracy = Number(d.accuracy);
        if (
          ["latitude", "longitude", "accuracy"].some(
            (k) =>
              d[k] === null || d[k] === undefined || typeof d[k] !== "number",
          ) ||
          ![lat, lon, accuracy].every(Number.isFinite) ||
          Math.abs(lat) > 90 ||
          Math.abs(lon) > 180 ||
          accuracy < 0 ||
          accuracy > s.max_accuracy_m
        )
          fail("Your location accuracy is insufficient. Please try again.");
        const rad = (x: number) => (x * Math.PI) / 180;
        const a =
          Math.sin(rad(lat - s.site_latitude) / 2) ** 2 +
          Math.cos(rad(lat)) *
            Math.cos(rad(s.site_latitude)) *
            Math.sin(rad(lon - s.site_longitude) / 2) ** 2;
        const distance =
          6371000 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(Math.max(0, 1 - a)));
        if (distance + accuracy > s.site_radius_m)
          fail(
            "Your location could not be confirmed inside the worksite. Please contact your supervisor.",
            403,
          );
        const { data, error } = await service
          .from("ss_attendance")
          .insert({ user_id: p.id, latitude: lat, longitude: lon, accuracy })
          .select("checked_at")
          .single();
        if (error?.code === "23505")
          fail("You have already checked in today.", 409);
        if (error) fail("Unable to record attendance.", 503);
        await audit(p.id, "check_in");
        result = data;
      } else fail("Endpoint not found.", 404);
    }
    return Response.json(result, { headers: { "Cache-Control": "no-store" } });
  } catch (e) {
    const known = e instanceof HttpError;
    return Response.json(
      {
        error: known
          ? e.message
          : "Service temporarily unavailable. Please try again.",
      },
      {
        status: known ? e.status : 503,
        headers: { "Cache-Control": "no-store" },
      },
    );
  }
});
