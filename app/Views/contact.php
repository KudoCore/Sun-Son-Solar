<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Request a solar consultation | Sun Son Solar Inc.</title>
    <meta name="description" content="Tell Sun Son Solar about your property and goals to begin a tailored solar conversation.">
    <meta name="robots" content="index, follow" />
    <meta name="theme-color" content="#243e2e" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Request a solar consultation | Sun Son Solar Inc.">
    <meta property="og:description" content="Tell Sun Son Solar about your property and goals to begin a tailored solar conversation.">
    <meta
      property="og:image"
      content="<?= esc(base_url('assets/social-card.jpg'), 'attr') ?>"
    />
    <meta
      property="og:image:alt"
      content="Sun Son Solar Inc. — Good energy. Great possibilities."
    />
    <meta name="twitter:card" content="summary_large_image" />
    <link rel="icon" href="<?= esc(base_url('assets'), 'attr') ?>/favicon.svg" type="image/svg+xml" />
    <!-- Page styling: edit the CSS below to change the design. -->
    <link rel="stylesheet" href="<?= esc(base_url('assets/site.css'), 'attr') ?>" />
    <link rel="canonical" href="<?= esc(site_url('contact'), 'attr') ?>">
  </head>
  <body data-page="contact">
    <a class="skip" href="#main">Skip to content</a>
    <!-- Main navigation -->
    <header>
      <div class="nav wrap">
        <a class="brand" href="<?= esc(site_url(), 'attr') ?>" aria-label="Sun Son Solar home"
          ><span class="brand-icon">☀</span
          ><span>SUN SON<span class="brand-small">SOLAR INC.</span></span></a
        ><button
          class="menu-toggle"
          aria-expanded="false"
          aria-controls="navigation"
        >
          Menu
        </button>
        <nav id="navigation" aria-label="Main navigation">
          <a href="<?= esc(site_url('products'), 'attr') ?>"
            >Products</a
          ><a href="<?= esc(site_url('services'), 'attr') ?>"
            >Services</a
          ><a href="<?= esc(site_url('projects'), 'attr') ?>"
            >Our approach</a
          ><a href="<?= esc(site_url('about'), 'attr') ?>"
            >About us</a
          ><a
            class="mobile-account-link"
            href="<?= esc(site_url('login'), 'attr') ?>"
            >Log in</a
          ><a class="mobile-account-link" href="<?= esc(site_url('register'), 'attr') ?>">Register</a>
        </nav>
        <div class="nav-actions">
          <a
            class="portal-link"
            href="<?= esc(site_url('login'), 'attr') ?>"
            >Log in</a
          ><a class="portal-link register-link" href="<?= esc(site_url('register'), 'attr') ?>"
            >Register</a
          ><a
            class="button small"
            href="<?= esc(site_url('contact/'), 'attr') ?>"
            >Let’s talk solar</a
          >
        </div>
      </div>
    </header>
    <!-- Main page content -->
    <main id="main">
<section class="page-intro wrap">
<nav class="breadcrumbs" aria-label="Breadcrumb">
<a href="<?= esc(site_url(''), 'attr') ?>">Home</a>
<span aria-hidden="true">/</span>
<span>Contact</span>
</nav>
<p class="eyebrow">Contact</p>
<h1>Let’s talk<br>about your solar plans.</h1>
<p class="lead">Tell us a little about your property and what you have in mind.</p>
</section>
<section class="wrap contact-grid">
<div class="contact-info">
<p class="eyebrow">START WITH A CONVERSATION</p>
<h2>Your next step<br>starts here.</h2>
<p>Share your goals and we’ll discuss the assessment, equipment and services your project may need.</p>
<div class="info-block">
<h3>What happens next?</h3>
<p>Your enquiry goes to our team for review. We’ll use your email to follow up about your project.</p>
<p id="response-promise">A response-time commitment will be confirmed by the team.</p>
</div>
<div class="info-block">
<h3>Visit us</h3>
<div id="business-location">
<p>Please arrange your visit with the team. A verified office address and directions will be published when available.</p>
</div>
</div>
</div>
<form id="enquiry-form" class="form-card" method="post" action="<?= esc(site_url('api/enquiry'), 'attr') ?>">
<p class="eyebrow">PROJECT ENQUIRY</p>
<h2>A little about you.</h2>
<p class="form-note">Fields marked * are required.</p>
<label>Your name *<input type="text" name="name" required autocomplete="name" maxlength="120">
</label>
<label>Email address *<input type="email" name="email" required autocomplete="email" maxlength="254">
</label>
<label>Phone number (optional)<input type="tel" name="phone"  autocomplete="tel" maxlength="30">
</label>
<label>Interested in *<select name="interest" required>
<option value="">Select a product or service</option>Complete solar system</option>Solar panels</option>Inverters</option>Batteries</option>Racking & mounting</option>Wires & connections</option>Consultation</option>Designing</option>Permitting</option>Installation</option>Maintenance</option>Repair</option>Monitoring</option>
</select>
</label>
<label>Tell us about your project *<textarea name="message" required minlength="10" maxlength="3000" rows="4" placeholder="Your property type, city and solar goals. Please do not include sensitive information.">
</textarea>
</label>
<label class="honeypot" aria-hidden="true">Leave blank<input name="website" tabindex="-1" autocomplete="off">
</label>
<label class="checkbox">
<input type="checkbox" name="consent" required>
<span>I have read the <a href="<?= esc(site_url('privacy/'), 'attr') ?>">Privacy Policy</a> and agree to be contacted about this enquiry.</span>
</label>
<p class="form-status" role="status" aria-live="polite">
</p>
<button class="button full" type="submit">Send my enquiry</button>
<p class="form-note">No payment is collected through this website.</p>
</form>
</section>
</main>
    <!-- Footer and company links -->
    <footer>
      <div class="wrap footer-top">
        <div>
          <a class="brand" href="<?= esc(site_url(), 'attr') ?>" aria-label="Sun Son Solar home"
            ><span class="brand-icon">☀</span
            ><span>SUN SON<span class="brand-small">SOLAR INC.</span></span></a
          >
          <p>More possibility.<br />Powered by the sun.</p>
        </div>
        <div>
          <h3>Explore</h3>
          <a href="<?= esc(site_url('products'), 'attr') ?>"
            >Solar products</a
          ><a href="<?= esc(site_url('services'), 'attr') ?>"
            >Our services</a
          ><a href="<?= esc(site_url('projects'), 'attr') ?>"
            >Project approach</a
          ><a href="<?= esc(site_url('about'), 'attr') ?>"
            >Our company</a
          >
        </div>
        <div>
          <h3>Connect</h3>
          <a href="<?= esc(site_url('contact/'), 'attr') ?>"
            >Request a consultation</a
          ><a href="<?= esc(site_url('login'), 'attr') ?>"
            >Customer &amp; staff sign in</a
          ><a href="<?= esc(site_url('register'), 'attr') ?>">Customer registration</a
          ><a href="<?= esc(site_url('faq/'), 'attr') ?>"
            >Common questions</a
          >
        </div>
        <div>
          <h3>Information</h3>
          <a href="<?= esc(site_url('privacy/'), 'attr') ?>"
            >Privacy policy</a
          ><a href="<?= esc(site_url('terms/'), 'attr') ?>"
            >Terms & conditions</a
          ><a href="<?= esc(site_url('cookies/'), 'attr') ?>"
            >Cookie policy</a
          ><a href="<?= esc(site_url('refunds/'), 'attr') ?>"
            >Refund policy</a
          ><button class="text-button" data-cookies>Cookie preferences</button>
        </div>
      </div>
      <div class="wrap footer-bottom">
        <span>© 2026 Sun Son Solar Inc.</span
        ><span>Thoughtful energy. Everyday possibilities.</span>
      </div>
    </footer>
    <a
      class="mobile-cta button"
      href="<?= esc(site_url('contact/'), 'attr') ?>"
      >Request a solar consultation</a
    >
    <section class="cookie-banner" aria-label="Cookie preferences" hidden>
      <div>
        <strong>Your privacy, your choice.</strong>
        <p>
          Essential storage keeps the portal secure. Optional analytics helps us
          understand website use.
          <a href="<?= esc(site_url('cookies/'), 'attr') ?>"
            >Read our cookie policy</a
          >.
        </p>
      </div>
      <div class="cookie-actions">
        <button class="button outline" data-consent="essential">
          Essential only</button
        ><button class="button" data-consent="analytics">
          Allow analytics
        </button>
      </div>
    </section>
    <!-- Page behavior: navigation, privacy choices and registration. -->
    <script>
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
          response = await fetch(<?= json_encode(site_url('api'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> + "/" + action, {
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
      const enquiryForm = findElement('#enquiry-form');
      const requestedInterest = new URLSearchParams(location.search).get('interest');
      if ([...enquiryForm.elements.interest.options].some(option => option.value === requestedInterest)) {
        enquiryForm.elements.interest.value = requestedInterest;
      }
      enquiryForm.addEventListener('submit', async event => {
        event.preventDefault();
        if (!enquiryForm.reportValidity()) return;
        const button = enquiryForm.querySelector('button[type="submit"]');
        const status = enquiryForm.querySelector('.form-status');
        button.disabled = true;
        status.textContent = 'Sending your enquiry…';
        try {
          await getSession();
          await sendRequest('enquiry', Object.fromEntries(new FormData(enquiryForm)));
          location.assign(<?= json_encode(site_url('thank-you'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
        } catch (error) {
          status.textContent = error.message;
          button.disabled = false;
        }
      });

    </script>
  </body>
</html>
