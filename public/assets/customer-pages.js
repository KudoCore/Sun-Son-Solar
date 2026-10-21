"use strict";

(() => {
  const api = document.body.dataset.apiUrl;
  let csrf = document.body.dataset.csrf || "";
  const esc = (value) => String(value ?? "").replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" })[c]);
  const formData = (form) => Object.fromEntries(new FormData(form));

  async function request(path, data) {
    if (data && !csrf) {
      const sessionResponse = await fetch(`${api}/session`, { credentials: "same-origin" });
      const session = await sessionResponse.json();
      if (!sessionResponse.ok || !session.csrf) throw new Error(session.error || "Please sign in again.");
      csrf = session.csrf;
    }
    const response = await fetch(`${api}/${path}`, {
      method: data ? "POST" : "GET",
      credentials: "same-origin",
      headers: data ? { "Content-Type": "application/json", "X-CSRF-Token": csrf } : {},
      body: data ? JSON.stringify(data) : undefined,
    });
    const result = await response.json();
    if (!response.ok) throw new Error(result.error || "We could not complete that request.");
    return result;
  }

  const profileForm = document.querySelector("#profile-form");
  profileForm?.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!profileForm.reportValidity()) return;
    const status = profileForm.querySelector(".form-status");
    const button = profileForm.querySelector('[type="submit"]');
    const data = formData(profileForm);
    data.promotions = profileForm.elements.promotions.checked;
    data.schedule_updates = profileForm.elements.schedule_updates.checked;
    button.disabled = true;
    status.textContent = "Saving your profile…";
    try { const result = await request("portal/profile", data); status.textContent = result.message || "Profile saved."; }
    catch (error) { status.textContent = error.message; }
    finally { button.disabled = false; }
  });

  const passwordForm = document.querySelector("#password-form");
  passwordForm?.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!passwordForm.reportValidity()) return;
    const status = passwordForm.querySelector(".form-status");
    const button = passwordForm.querySelector('[type="submit"]');
    button.disabled = true;
    status.textContent = "Updating your password…";
    try {
      await request("portal/password", formData(passwordForm));
      status.textContent = "Password updated. Please sign in again.";
      window.setTimeout(() => location.assign(document.body.dataset.loginUrl), 1200);
    } catch (error) { status.textContent = error.message; button.disabled = false; }
  });

  const requestForm = document.querySelector("#request-form");
  requestForm?.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!requestForm.reportValidity()) return;
    const status = requestForm.querySelector(".form-status");
    const button = requestForm.querySelector('[type="submit"]');
    button.disabled = true;
    status.textContent = "Sending your service request…";
    try {
      const result = await request("portal/booking", formData(requestForm));
      status.textContent = `${result.message} Your request number is #${result.id}.`;
      requestForm.reset();
      requestForm.elements.service_address.value = requestForm.dataset.defaultAddress || "";
    } catch (error) { status.textContent = error.message; }
    finally { button.disabled = false; }
  });

  const bookings = document.querySelector("#profile-bookings");
  if (bookings) request("portal/data").then((data) => {
    const rows = data.bookings || [];
    bookings.innerHTML = rows.length ? `<ul class="home-booking-list">${rows.map((item) => {
      const date = item.scheduled_start || item.requested_at;
      const when = date ? new Date(`${String(date).replace(" ", "T")}Z`).toLocaleString() : "Time to be confirmed";
      return `<li><strong>${esc(item.service_name)}</strong><span>Request #${esc(item.id)} · ${esc(String(item.status).replaceAll("_", " "))}</span><small>${esc(when)}</small></li>`;
    }).join("")}</ul>` : "<p>No service requests yet. Use Let’s talk solar to send your first request.</p>";
  }).catch((error) => { bookings.textContent = error.message; });

  document.querySelectorAll("[data-signout]").forEach((button) => button.addEventListener("click", async () => {
    button.disabled = true;
    try { await request("logout", {}); location.assign(document.body.dataset.homeUrl); }
    catch (error) { button.disabled = false; window.alert(error.message); }
  }));
})();
