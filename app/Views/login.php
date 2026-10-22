<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Account sign in | Sun Son Solar Inc.</title>
    <meta
      name="description"
      content="Sign in to your Sun Son Solar customer or employee account."
    />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="theme-color" content="#243e2e" />
    <meta property="og:type" content="website" />
    <meta
      property="og:title"
      content="Account sign in | Sun Son Solar Inc."
    />
    <meta
      property="og:description"
      content="Sign in to your Sun Son Solar customer or employee account."
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
    <link rel="stylesheet" href="<?= esc(base_url('assets/site.css') . '?v=solar-login', 'attr') ?>" />
    <link
      rel="canonical"
      href="<?= esc(site_url('login'), 'attr') ?>"
    />
  </head>
  <body data-page="login" data-api-url="<?= esc(site_url('api'), 'attr') ?>" data-home-url="<?= esc(site_url(''), 'attr') ?>" data-portal-url="<?= esc(site_url('portal'), 'attr') ?>">
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
      <section class="portal-shell wrap">
        <aside class="portal-art">
          <p class="eyebrow">GOOD TO SEE YOU AGAIN</p>
          <div>
            <h1>Your workday.<br />A brighter start.</h1>
            <p>
              Sign in to your customer or employee account.<br />Stay connected with Sun Son Solar.
            </p>
          </div>
          <span class="art-bottom"
            >SUN SON SOLAR INC. / EMPLOYEE PORTAL</span
          >
        </aside>
        <div class="portal-form">
          <p class="eyebrow">WELCOME BACK</p>
          <h2>Sign in to your account.</h2>
          <p class="form-note">
            Use the username and password you registered with.
            Customer accounts are ready after registration. Employee accounts are added by Admin or HR.
          </p>
          <form id="login-form" method="post" action="<?= esc(site_url('api/login'), 'attr') ?>">
            <input type="hidden" name="csrf_token" value="<?= esc($csrf ?? '', 'attr') ?>" />
            <label>Username <input type="text" name="username" required minlength="3" maxlength="80" autocomplete="username" autocapitalize="none" spellcheck="false" /></label>
            <label>Password <span class="password-wrap"><input type="password" name="password" required maxlength="72" autocomplete="current-password" /><button type="button" class="password-toggle" aria-label="Show password">Show</button></span></label>
            <p class="form-status" role="status" aria-live="polite" tabindex="-1"><?= esc($login_error ?? '') ?></p>
            <button type="submit" class="button full">Sign in</button>
            <p class="form-note">New customer? <a class="underlink" href="<?= esc(site_url('register'), 'attr') ?>">Create a customer account</a></p>
          </form>
          <div class="login-help">
            <strong>Need help signing in?</strong>
            <p>
              Contact Sun Son Solar support if you need help with your account or password.
            </p>
          </div>
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
    <script src="<?= esc(base_url('assets/login.js') . '?v=customer-home', 'attr') ?>" defer></script>
  </body>
</html>

