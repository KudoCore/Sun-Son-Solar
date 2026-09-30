/**
 * Sun Son Solar — small server for the two HTML pages.
 * Run with Node.js 22 or newer: node server.mjs
 * The Supabase service-role key stays inside the deployed Edge Function.
 */
import http from "node:http";
import { readFile } from "node:fs/promises";
import { createHash, randomBytes, timingSafeEqual } from "node:crypto";
import { fileURLToPath } from "node:url";
import path from "node:path";

const ROOT = path.dirname(fileURLToPath(import.meta.url));
const PORT = Number(process.env.PORT || 3000);
const ORIGIN = process.env.APP_ORIGIN || `http://localhost:${PORT}`;
const originURL = new URL(ORIGIN);
if (originURL.origin !== ORIGIN)
  throw new Error("APP_ORIGIN must be an origin without a trailing slash.");
if (
  originURL.protocol !== "https:" &&
  !["localhost", "127.0.0.1", "[::1]"].includes(originURL.hostname)
) {
  throw new Error("Public hosting requires an HTTPS APP_ORIGIN.");
}
const HTTPS = originURL.protocol === "https:";
const CSRF_COOKIE = HTTPS ? "__Host-solar-csrf" : "solar-csrf";
const API = "https://mtfunrbkcbcibumovwls.supabase.co/functions/v1/sunson-api/";

// Only these files can be served. SQL and server source cannot be downloaded.
const FILES = {
  "/": ["index.html", "text/html; charset=utf-8"],
  "/index.html": ["index.html", "text/html; charset=utf-8"],
  "/registration.html": ["registration.html", "text/html; charset=utf-8"],
  "/register/": ["registration.html", "text/html; charset=utf-8"],
  "/assets/solar-hero.jpg": ["assets/solar-hero.jpg", "image/jpeg"],
  "/assets/social-card.jpg": ["assets/social-card.jpg", "image/jpeg"],
  "/assets/favicon.svg": ["assets/favicon.svg", "image/svg+xml"],
};

function send(response, status, value, extraHeaders = {}) {
  response.writeHead(status, {
    "Content-Type": "application/json; charset=utf-8",
    "Cache-Control": "no-store",
    "X-Content-Type-Options": "nosniff",
    ...extraHeaders,
  });
  response.end(JSON.stringify(value));
}

function getCookie(request, name) {
  const item = (request.headers.cookie || "")
    .split(";")
    .map((item) => item.trim())
    .find((item) => item.startsWith(name + "="));
  return item ? item.slice(name.length + 1) : "";
}

function sameToken(a, b) {
  if (typeof a !== "string" || typeof b !== "string") return false;
  const first = Buffer.from(a);
  const second = Buffer.from(b);
  return (
    first.length > 0 &&
    first.length === second.length &&
    timingSafeEqual(first, second)
  );
}

// Allow our embedded CSS and JavaScript by their hashes, not unsafe-inline.
function contentPolicy(html) {
  const hash = (value) =>
    "'sha256-" + createHash("sha256").update(value).digest("base64") + "'";
  const scripts = [...html.matchAll(/<script>([\s\S]*?)<\/script>/g)].map(
    (match) => hash(match[1]),
  );
  const styles = [...html.matchAll(/<style>([\s\S]*?)<\/style>/g)].map(
    (match) => hash(match[1]),
  );
  return [
    "default-src 'self'",
    `script-src 'self' ${scripts.join(" ")} https://www.googletagmanager.com`,
    `style-src 'self' ${styles.join(" ")}`,
    "connect-src 'self' https://*.google-analytics.com https://*.analytics.google.com https://www.googletagmanager.com",
    "img-src 'self' data: https://*.google-analytics.com",
    "font-src 'self'",
    "object-src 'none'",
    "frame-src 'none'",
    "frame-ancestors 'self'",
    "base-uri 'self'",
    "form-action 'self'",
  ].join("; ");
}

async function handleApi(request, response, pathname) {
  if (pathname === "/api/session" && request.method === "GET") {
    let token = getCookie(request, CSRF_COOKIE);
    if (!/^[a-f0-9]{64}$/.test(token)) token = randomBytes(32).toString("hex");
    return send(
      response,
      200,
      { csrf: token, user: null },
      {
        "Set-Cookie": `${CSRF_COOKIE}=${token}; Path=/; HttpOnly; SameSite=Lax; Max-Age=28800${HTTPS ? "; Secure" : ""}`,
      },
    );
  }

  if (pathname === "/api/public-config" && request.method === "GET") {
    const result = await fetch(API + "public-config", {
      signal: AbortSignal.timeout(20000),
    });
    if (!result.ok)
      return send(response, 503, {
        error: "Company settings are temporarily unavailable.",
      });
    const config = await result.json();
    config.base_url = ORIGIN;
    return send(response, 200, config);
  }

  if (pathname !== "/api/register")
    return send(response, 404, { error: "Endpoint not found." });
  if (request.method !== "POST")
    return send(response, 405, { error: "POST required." });
  if (request.headers.origin !== ORIGIN)
    return send(response, 403, { error: "Origin not allowed." });
  if (
    !sameToken(getCookie(request, CSRF_COOKIE), request.headers["x-csrf-token"])
  ) {
    return send(response, 403, { error: "Reload the page and try again." });
  }
  if (!request.headers["content-type"]?.startsWith("application/json")) {
    return send(response, 415, { error: "JSON request required." });
  }

  let total = 0;
  const chunks = [];
  for await (const chunk of request) {
    total += chunk.length;
    if (total > 12000)
      return send(response, 413, { error: "Request too large." });
    chunks.push(chunk);
  }
  let data;
  try {
    data = JSON.parse(Buffer.concat(chunks).toString("utf8"));
  } catch {
    return send(response, 400, { error: "Invalid request." });
  }
  if (!data || Array.isArray(data) || typeof data !== "object")
    return send(response, 400, { error: "Invalid form." });

  // The Edge Function validates fields, hashes passwords via Auth and sets
  // the account to pending approval. Client-supplied roles are never trusted.
  const result = await fetch(API + "register", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
    signal: AbortSignal.timeout(20000),
  });
  const reply = await result.json();
  return send(response, result.status, reply);
}

const server = http.createServer(async (request, response) => {
  try {
    const pathname = new URL(request.url, ORIGIN).pathname;
    if (pathname.startsWith("/api/"))
      return await handleApi(request, response, pathname);
    if (!["GET", "HEAD"].includes(request.method))
      return send(response, 405, { error: "Method not allowed." });
    const file = FILES[pathname];
    if (!file) {
      response.writeHead(404, {
        "Content-Type": "text/html; charset=utf-8",
        "X-Content-Type-Options": "nosniff",
      });
      return response.end(
        '<!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Page not found | Sun Son Solar</title><h1>Page not found</h1><p><a href="/">Return to Sun Son Solar</a></p></html>',
      );
    }
    const bytes = await readFile(path.join(ROOT, file[0]));
    const isHTML = file[1].startsWith("text/html");
    const headers = {
      "Content-Type": file[1],
      "X-Content-Type-Options": "nosniff",
      "Referrer-Policy": "strict-origin-when-cross-origin",
      "Permissions-Policy": "geolocation=(self), camera=(), microphone=()",
      "Cache-Control": isHTML ? "no-cache" : "public, max-age=3600",
    };
    if (isHTML)
      headers["Content-Security-Policy"] = contentPolicy(
        bytes.toString("utf8"),
      );
    response.writeHead(200, headers);
    response.end(request.method === "HEAD" ? undefined : bytes);
  } catch {
    if (!response.headersSent)
      send(response, 503, {
        error: "Service temporarily unavailable. Please try again.",
      });
    else response.end();
  }
});
server.requestTimeout = 30000;
server.headersTimeout = 15000;
server.listen(PORT, process.env.HOST || "127.0.0.1", () =>
  console.log(`Sun Son Solar: ${ORIGIN}`),
);
