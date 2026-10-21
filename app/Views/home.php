<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Solar solutions for your everyday | Sun Son Solar Inc.</title>
    <meta
      name="description"
      content="Explore solar panels, batteries, inverters and end-to-end solar project services from Sun Son Solar Inc."
    />
    <meta name="robots" content="index, follow" />
    <meta name="theme-color" content="#243e2e" />
    <meta property="og:type" content="website" />
    <meta
      property="og:title"
      content="Solar solutions for your everyday | Sun Son Solar Inc."
    />
    <meta
      property="og:description"
      content="Explore solar panels, batteries, inverters and end-to-end solar project services from Sun Son Solar Inc."
    />
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
    <link rel="stylesheet" href="<?= esc(base_url('assets/site.css') . '?v=customer-home', 'attr') ?>" />
    <link
      rel="canonical"
      href="<?= esc(site_url(''), 'attr') ?>"
    />
  </head>
  <?php $isCustomer = isset($user) && ($user['role'] ?? '') === 'customer'; ?>
  <body data-page="home" data-api-url="<?= esc(site_url('api'), 'attr') ?>" data-home-url="<?= esc(site_url(''), 'attr') ?>" data-login-url="<?= esc(site_url('login'), 'attr') ?>" data-csrf="<?= esc($csrf ?? '', 'attr') ?>">
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
          ><?php if ($isCustomer): ?>
          <a class="mobile-account-link" href="<?= esc(site_url('profile'), 'attr') ?>">Profile</a>
          <a class="mobile-account-link" href="<?= esc(site_url('lets-talk-solar'), 'attr') ?>">Request a service</a>
          <?php elseif (isset($user)): ?>
          <a class="mobile-account-link" href="<?= esc(site_url('portal'), 'attr') ?>">Staff portal</a>
          <?php else: ?>
          <a class="mobile-account-link" href="<?= esc(site_url('login'), 'attr') ?>">Log in</a>
          <a class="mobile-account-link" href="<?= esc(site_url('register'), 'attr') ?>">Register</a>
          <?php endif; ?>
        </nav>
        <div class="nav-actions">
          <?php if ($isCustomer): ?>
          <a class="portal-link" href="<?= esc(site_url('profile'), 'attr') ?>">Profile</a>
          <button class="portal-link text-button" type="button" data-home-signout>Sign out</button>
          <?php elseif (isset($user)): ?>
          <a class="portal-link" href="<?= esc(site_url('portal'), 'attr') ?>">Staff portal</a>
          <button class="portal-link text-button" type="button" data-home-signout>Sign out</button>
          <?php else: ?>
          <a
            class="portal-link"
            href="<?= esc(site_url('login'), 'attr') ?>"
            >Log in</a
          ><a class="portal-link register-link" href="<?= esc(site_url('register'), 'attr') ?>"
            >Register</a>
          <?php endif; ?>
          <a class="button small" href="<?= esc(site_url('lets-talk-solar'), 'attr') ?>">Let’s talk solar</a>
        </div>
      </div>
    </header>
    <!-- Main page content -->
    <main id="main">
      <section class="hero wrap">
        <div class="hero-scene">
          <img
            class="hero-image"
            src="<?= esc(base_url('assets'), 'attr') ?>/solar-hero.jpg"
            alt="Rows of photovoltaic solar panels beneath an open sky"
            fetchpriority="high"
          />
          <div class="hero-shade"></div>
          <div class="hero-copy">
            <p class="eyebrow">
              <span class="sun-mini">☀</span> A BRIGHTER WAY FORWARD
            </p>
            <h1>Power Your Future<br />with Solar.</h1>
            <p>
              Solar solutions built around your space,<br class="desktop" />
              your needs, and what comes next.
            </p>
            <div class="hero-buttons">
              <a
                class="button light"
                href="<?= esc(site_url('lets-talk-solar'), 'attr') ?>"
                >Start your solar journey</a
              ><a
                class="button glass"
                href="<?= esc(site_url('services'), 'attr') ?>"
                >Explore our services</a
              >
            </div>
          </div>
          <div class="hero-foot">
            <span>SUN SON SOLAR INC.</span
            ><span>From the first conversation to everyday energy.</span>
          </div>
        </div>
      </section>
      <div class="wrap value-strip">
        <span>Designed around you</span><span>Complete system planning</span
        ><span>Support beyond installation</span>
      </div>
      <section class="wrap section about-grid">
        <div>
          <p class="eyebrow">A LITTLE SUN. A LOT OF POTENTIAL.</p>
          <h2>A smarter way to<br />power your everyday.</h2>
        </div>
        <div>
          <p class="large-copy">Your roof can do more. Your energy can too.</p>
          <p>
            At Sun Son Solar, we bring the parts of your solar journey
            together—from choosing equipment to designing, installing and caring
            for your system.
          </p>
          <a
            class="underlink"
            href="<?= esc(site_url('about'), 'attr') ?>"
            >Meet Sun Son Solar</a
          >
        </div>
      </section>
      <section class="products-section">
        <div class="wrap section">
          <div class="section-heading">
            <div>
              <p class="eyebrow">THE BUILDING BLOCKS</p>
              <h2>One system.<br />Every part considered.</h2>
            </div>
            <a
              class="button outline"
              href="<?= esc(site_url('products'), 'attr') ?>"
              >Explore all products</a
            >
          </div>
          <div class="product-feature">
            <div class="product-photo">
              <img
                src="<?= esc(base_url('assets'), 'attr') ?>/solar-hero.jpg"
                alt="Photovoltaic panel surfaces arranged to capture sunlight"
                loading="lazy"
              /><span class="image-label">SOLAR PANELS</span>
            </div>
            <div class="product-detail">
              <span class="index">01 / 05</span>
              <h3>It starts<br />with sunlight.</h3>
              <p>
                Find the right combination of panels, inverters and storage for
                the way you use energy.
              </p>
              <a
                class="underlink"
                href="<?= esc(site_url('products'), 'attr') ?>"
                >Find your system components</a
              >
              <div class="product-tags">
                <span>Panels</span><span>Inverters</span><span>Batteries</span
                ><span>Mounting</span><span>Wires</span>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="wrap section">
        <div class="section-heading">
          <div>
            <p class="eyebrow">WITH YOU, EVERY STEP</p>
            <h2>From possibility<br />to power.</h2>
          </div>
          <p class="heading-note">
            A connected approach to your solar project, with room for the
            details that make it yours.
          </p>
        </div>
        <div class="steps">
          <a
            href="<?= esc(site_url('services'), 'attr') ?>#consultation"
            class="step"
            ><span class="index">01</span>
            <h3>Consultation</h3>
            <p>
              Discuss your energy use, priorities, property and project budget.
            </p></a
          ><a
            href="<?= esc(site_url('services'), 'attr') ?>#designing"
            class="step"
            ><span class="index">02</span>
            <h3>Designing</h3>
            <p>
              Evaluate roof space, electrical loads and component compatibility.
            </p></a
          ><a
            href="<?= esc(site_url('services'), 'attr') ?>#permitting"
            class="step"
            ><span class="index">03</span>
            <h3>Permitting</h3>
            <p>
              Coordinate project requirements with the relevant utility and
              authorities.
            </p></a
          ><a
            href="<?= esc(site_url('services'), 'attr') ?>#installation"
            class="step"
            ><span class="index">04</span>
            <h3>Installation</h3>
            <p>
              Install and commission the agreed system with appropriate safety
              checks.
            </p></a
          >
        </div>
        <div class="support-line">
          <span>Already have a system? Let’s keep it working for you.</span
          ><a
            class="underlink"
            href="<?= esc(site_url('services'), 'attr') ?>#maintenance"
            >Maintenance, repair & monitoring</a
          >
        </div>
      </section>
      <section class="wrap section stories-section">
        <div class="section-heading"><div><p class="eyebrow">A CLEARER SOLAR PLAN</p><h2>Energy that works<br />for your everyday.</h2></div><a class="button outline" href="<?= esc(site_url('lets-talk-solar'), 'attr') ?>">Request a quote</a></div>
        <div class="service-grid">
          <article class="service-card"><span class="index">01 / SAVINGS</span><h3>Plan around your energy costs.</h3><p>Start with your electricity use and a project assessment to understand the system options and costs for your property.</p></article>
          <article class="service-card"><span class="index">02 / SUSTAINABILITY</span><h3>Make room for cleaner energy.</h3><p>Explore solar equipment and installation choices that can help reduce reliance on grid electricity.</p></article>
          <article class="service-card"><span class="index">03 / INDEPENDENCE</span><h3>Choose the right level of backup.</h3><p>Discuss storage and backup needs early; system design and equipment determine what can operate during an outage.</p></article>
        </div>
      </section>
      <section class="wrap section stories-section" aria-labelledby="stories-title">
        <div class="section-heading"><div><p class="eyebrow">CUSTOMER STORIES</p><h2 id="stories-title">Good energy,<br />shared honestly.</h2></div></div>
        <?php if (!empty($testimonials)): ?>
          <div class="service-grid">
            <?php foreach ($testimonials as $story): ?>
              <article class="service-card testimonial-card"><span class="index">CUSTOMER STORY</span><blockquote>“<?= esc($story['quote_text']) ?>”</blockquote><p class="testimonial-name">— <?= esc($story['display_name']) ?></p></article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="story-note"><p>We’re gathering customer feedback and will publish stories here only after each customer gives permission.</p><a class="underlink" href="<?= esc(site_url('contact'), 'attr') ?>">Share your experience with our team</a></div>
        <?php endif; ?>
      </section>
      <section class="wrap section stories-section" aria-labelledby="solar-notes-title">
        <div class="section-heading"><div><p class="eyebrow">SOLAR NOTES</p><h2 id="solar-notes-title">Ideas for your<br />next conversation.</h2></div><a class="underlink" href="<?= esc(site_url('faq'), 'attr') ?>">Browse common questions</a></div>
        <div class="service-grid">
          <article class="service-card"><span class="index">PROJECT GUIDE</span><h3>Is your property ready for solar?</h3><p>Roof condition, shade, space and your electricity use all shape a useful site assessment.</p><a class="underlink" href="<?= esc(site_url('faq'), 'attr') ?>">Read the FAQ</a></article>
          <article class="service-card"><span class="index">EQUIPMENT</span><h3>Panels, inverters and storage.</h3><p>Learn how the main parts of a solar system fit together before choosing an option.</p><a class="underlink" href="<?= esc(site_url('products'), 'attr') ?>">Explore products</a></article>
          <article class="service-card"><span class="index">AFTER INSTALLATION</span><h3>Plan for ongoing care.</h3><p>Ask about maintenance and support as part of your project scope and written quotation.</p><a class="underlink" href="<?= esc(site_url('services'), 'attr') ?>">View services</a></article>
        </div>
      </section>
      <section class="faq-section">
        <div class="wrap section faq-grid">
          <div>
            <p class="eyebrow">A LITTLE CLARITY</p>
            <h2>Solar, without<br />the guesswork.</h2>
            <p>Good decisions start with good questions.</p>
            <a
              class="underlink"
              href="<?= esc(site_url('lets-talk-solar'), 'attr') ?>"
              >Talk through your project</a
            >
          </div>
          <div class="faq-list">
            <details>
              <summary>
                Is my property suitable for solar?<span aria-hidden="true"
                  >+</span
                >
              </summary>
              <p>
                Roof condition, available space, shading and your electricity
                use all matter. A consultation and site assessment help
                determine what is practical.
              </p>
            </details>
            <details>
              <summary>
                Will solar work during a power outage?<span aria-hidden="true"
                  >+</span
                >
              </summary>
              <p>
                A standard grid-tied system normally shuts down during an
                outage. Backup power requires compatible equipment, suitable
                batteries and a correctly designed backup circuit.
              </p>
            </details>
            <details>
              <summary>
                How much does a solar system cost?<span aria-hidden="true"
                  >+</span
                >
              </summary>
              <p>
                Pricing depends on system size, equipment, roof conditions,
                permits and installation scope. Request a tailored quotation; no
                universal price or savings figure applies.
              </p>
            </details>
            <details>
              <summary>
                Can I add battery storage later?<span aria-hidden="true"
                  >+</span
                >
              </summary>
              <p>
                Sometimes. It depends on inverter compatibility, available space
                and the electrical design. Tell us early if future storage is
                part of your plan.
              </p>
            </details>
            <details>
              <summary>
                What support is available after installation?<span
                  aria-hidden="true"
                  >+</span
                >
              </summary>
              <p>
                Maintenance, repair and monitoring services can be discussed as
                part of your project. Specific warranty coverage and service
                terms must be confirmed in your written quotation.
              </p>
            </details>
          </div>
        </div>
      </section>
      <section class="wrap section home-service-request" aria-labelledby="lets-talk-title">
        <div class="cta-panel">
          <p class="eyebrow">LET’S TALK SOLAR</p>
          <h2 id="lets-talk-title">Let’s put the sun<br />to work for you.</h2>
          <p>Tell us about your property and the service you need. Sign in to send and track your request.</p>
          <a class="button light" href="<?= esc(site_url($isCustomer ? 'lets-talk-solar' : 'login'), 'attr') ?>"><?= $isCustomer ? 'Request a service' : 'Sign in to request service' ?></a>
          <?php if (!$isCustomer): ?><a class="button glass" href="<?= esc(site_url('register'), 'attr') ?>">Create a customer account</a><?php endif; ?>
        </div>
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
          <a href="<?= esc(site_url('lets-talk-solar'), 'attr') ?>"
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
      href="<?= esc(site_url('lets-talk-solar'), 'attr') ?>"
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
    </script>
    <?php if (isset($user)): ?><script src="<?= esc(base_url('assets/home-customer.js'), 'attr') ?>" defer></script><?php endif; ?>
  </body>
</html>
