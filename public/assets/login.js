"use strict";
      const findElement = (selector, parent = document) => parent.querySelector(selector);
      const findElements = (selector, parent = document) => [...parent.querySelectorAll(selector)];
      // Identify which page is open. Both pages use the same small set of helpers.
      const page = document.body.dataset.page;
      let csrf = "",
        settings = {};
      const escapeHTML = (v) =>
        String(v ?? "").replace(
          /[&<>"']/g,
          (c) =>
            ({
              "&": "&amp;",
              "<": "&lt;",
              ">": "&gt;",
              '"': "&quot;",
              "'": "&#39;",
            })[c],
        );
      // Send form data to our CodeIgniter controller. Database credentials stay in PHP.
      async function sendRequest(action, data) {
        let response;
        try {
          response = await fetch(document.body.dataset.apiUrl + "/" + action, {
            method: data ? "POST" : "GET",
            credentials: "same-origin",
            headers: data
              ? { "Content-Type": "application/json", "X-CSRF-Token": csrf }
              : {},
            body: data ? JSON.stringify(data) : undefined,
          });
        } catch {
          throw Error(
            "Unable to connect. Please try again when your connection is available.",
          );
        }
        let body;
        try {
          body = await response.json();
        } catch {
          throw Error(
            "The server is unavailable. If you opened this file directly, run php spark serve and open http://localhost:8080 instead.",
          );
        }
        if (!response.ok)
          throw Error(
            body.error ||
              "We could not complete your request. Please try again.",
          );
        return body;
      }
      async function getSession() {
        const sessionData = await sendRequest("session");
        csrf = sessionData.csrf;
        return sessionData;
      }
      findElement(".menu-toggle")?.addEventListener("click", (e) => {
        const open = e.currentTarget.getAttribute("aria-expanded") !== "true";
        e.currentTarget.setAttribute("aria-expanded", String(open));
        findElement("#navigation").classList.toggle("open", open);
      });
      findElements(".password-toggle").forEach((b) =>
        b.addEventListener("click", () => {
          const i = findElement("input", b.parentElement),
            show = i.type === "password";
          i.type = show ? "text" : "password";
          b.textContent = show ? "Hide" : "Show";
          b.setAttribute(
            "aria-label",
            show ? "Hide password" : "Show password",
          );
        }),
      );
      // Remember only the cookie preference on this device.
      function getCookiePreference() {
        try {
          return localStorage.getItem("sunson-consent-v1");
        } catch {
          return null;
        }
      }
      function clearAnalytics() {
        window["ga-disable-" + settings.ga_id] = true;
        document.cookie.split(";").forEach((x) => {
          const key = x.split("=")[0].trim();
          if (key === "_ga" || key.startsWith("_ga_")) {
            const parts = location.hostname.split(".");
            document.cookie = key + "=;Max-Age=0;path=/";
            for (let i = 0; i < parts.length - 1; i++)
              document.cookie =
                key + "=;Max-Age=0;path=/;domain=." + parts.slice(i).join(".");
          }
        });
      }
      function initializeAnalytics() {
        if (
          getCookiePreference() !== "analytics" ||
          !/^G-[A-Z0-9]+$/.test(settings.ga_id || "") ||
          ["login", "register", "portal", "thank-you"].includes(page) ||
          findElement("#ga-script")
        )
          return;
        window.dataLayer = window.dataLayer || [];
        window.gtag = function () {
          window.dataLayer.push(arguments);
        };
        window.gtag("js", new Date());
        window.gtag("config", settings.ga_id, {
          send_page_view: true,
          page_location: location.origin + location.pathname,
          page_title: document.title,
          allow_google_signals: false,
          allow_ad_personalization_signals: false,
        });
        const s = document.createElement("script");
        s.id = "ga-script";
        s.async = true;
        s.src = "https://www.googletagmanager.com/gtag/js?id=" + settings.ga_id;
        document.head.appendChild(s);
      }
      const banner = findElement(".cookie-banner");
      if (!getCookiePreference()) banner.hidden = false;
      findElements("[data-cookies]").forEach((b) =>
        b.addEventListener("click", () => {
          banner.hidden = false;
          findElement("[data-consent]", banner).focus();
        }),
      );
      findElements("[data-consent]").forEach((b) =>
        b.addEventListener("click", () => {
          try {
            localStorage.setItem("sunson-consent-v1", b.dataset.consent);
          } catch {}
          banner.hidden = true;
          if (b.dataset.consent === "analytics") initializeAnalytics();
          else {
            clearAnalytics();
            if (findElement("#ga-script")) location.reload();
          }
        }),
      );
      // Load approved company details and optional tracking settings.
      async function loadCompanySettings() {
        try {
          settings = await sendRequest("public-config");
          if (settings.base_url) {
            const c =
              findElement('link[rel="canonical"]') || document.createElement("link");
            c.rel = "canonical";
            c.href = settings.base_url + location.pathname;
            if (!c.parentElement) document.head.appendChild(c);
            findElement('meta[property="og:image"]').content =
              settings.base_url + "/assets/social-card.jpg";
          }
          if (
            settings.address &&
            settings.maps_url &&
            findElement("#business-location")
          ) {
            const p = document.createElement("p");
            p.textContent = settings.address;
            const a = document.createElement("a");
            a.className = "underlink";
            a.textContent = "Get directions";
            a.href = settings.maps_url;
            a.target = "_blank";
            a.rel = "noopener noreferrer";
            findElement("#business-location").replaceChildren(p, a);
          }
          if (settings.response_time && findElement("#response-promise"))
            findElement("#response-promise").textContent = settings.response_time;
          if (settings.address && settings.phone && settings.base_url) {
            const el = document.createElement("script");
            el.type = "application/ld+json";
            el.textContent = JSON.stringify({
              "@context": "https://schema.org",
              "@type": "LocalBusiness",
              name: "Sun Son Solar Inc.",
              url: settings.base_url,
              telephone: settings.phone,
              address: settings.address,
              image: settings.base_url + "/assets/social-card.jpg",
            });
            document.head.appendChild(el);
          }
          if (settings.hr_details_enabled) {
            findElements("[data-hr]").forEach((el) => (el.disabled = false));
            if (findElement("#hr-note"))
              findElement("#hr-note").textContent =
                "These details are optional and used for approved employment administration.";
          }
          initializeAnalytics();
        } catch {}
      }
      loadCompanySettings();
      // Validate the form, show progress, then display the server result.
      function bindForm(id, action, after) {
        const form = findElement(id);
        if (!form) return;
        form.addEventListener("submit", async (e) => {
          e.preventDefault();
          if (!form.reportValidity()) return;
          const button = findElement('button[type="submit"],button:not([type])', form),
            status = findElement(".form-status", form);
          button.disabled = true;
          status.textContent = "Please wait…";
          try {
            if (!csrf) await getSession();
            const data = Object.fromEntries(new FormData(form));
            if (
              id === "#register-form" &&
              data.password !== data.password_confirmation
            )
              throw Error(
                "Your passwords do not match. Please check both password fields.",
              );
            const result = await sendRequest(action, data);
            await after(result, form);
            status.textContent = "";
          } catch (err) {
            status.textContent = err.message;
            status.focus();
          } finally {
            button.disabled = false;
          }
        });
      }
      bindForm("#login-form", "login", (result) => {
        const destination = result.role === "customer"
          ? document.body.dataset.homeUrl
          : document.body.dataset.portalUrl;
        window.location.assign(destination);
      });
