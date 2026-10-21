<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create a customer account | Sun Son Solar Inc.</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="<?= esc(base_url('assets/site.css'), 'attr') ?>">
</head>
<body data-page="register">
  <a class="skip" href="#main">Skip to registration</a>
  <header><div class="nav wrap"><a class="brand" href="<?= esc(site_url(), 'attr') ?>"><span class="brand-icon">☀</span><span>SUN SON<span class="brand-small">SOLAR INC.</span></span></a><nav><a href="<?= esc(site_url('services'), 'attr') ?>">Services</a><a href="<?= esc(site_url('faq'), 'attr') ?>">FAQ</a><a href="<?= esc(site_url('login'), 'attr') ?>">Log in</a></nav></div></header>
  <main id="main" class="portal-shell register-shell wrap">
    <aside class="portal-art"><p class="eyebrow">YOUR SOLAR JOURNEY STARTS HERE</p><div class="portal-emblem" aria-hidden="true">☀</div><div><h1>Good energy.<br>Made personal.</h1><p>Create a customer account to manage bookings, service updates and support.</p></div><span class="art-bottom">SUN SON SOLAR INC. / CUSTOMER PORTAL</span></aside>
    <section class="portal-form"><p class="eyebrow">CUSTOMER REGISTRATION</p><h2>Create your account.</h2><p class="form-note">All fields are required except middle name. Employees must be added by an Admin or HR team member.</p>
      <form id="register-form">
        <div class="fields-grid"><label>First name *<input name="first_name" required maxlength="100" autocomplete="given-name"></label><label>Middle name<input name="middle_name" maxlength="100" autocomplete="additional-name"></label><label>Last name *<input name="last_name" required maxlength="100" autocomplete="family-name"></label><label>Birthdate *<input type="date" name="birthday" required autocomplete="bday"></label><label>Gender *<select name="gender" required><option value="">Choose an option</option><option>Woman</option><option>Man</option><option>Non-binary</option><option>Self-described</option><option>Prefer not to say</option></select></label><label>Email *<input type="email" name="email" required maxlength="254" autocomplete="email"></label><label>Phone number *<input type="tel" name="phone" required maxlength="30" autocomplete="tel"></label><label>Address *<input name="address" required maxlength="300" autocomplete="street-address"></label></div>
        <label>Username *<input name="username" required minlength="3" maxlength="80" pattern="[a-zA-Z0-9_.-]{3,80}" autocomplete="username"></label>
        <label>Password *<input type="password" name="password" required minlength="12" maxlength="72" autocomplete="new-password"></label>
        <label>Confirm password *<input type="password" name="password_confirmation" required minlength="12" maxlength="72" autocomplete="new-password"></label>
        <label class="checkline"><input type="checkbox" name="consent" required> I have read the <a href="<?= esc(site_url('privacy'), 'attr') ?>">Privacy Policy</a> and agree to the use of my information to manage my account.</label>
        <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" class="honeypot-field">
        <p class="form-status" role="status" aria-live="polite" tabindex="-1"></p><button class="button full" type="submit">Create customer account</button>
      </form>
    </section>
  </main>
  <footer>Sun Son Solar Inc. · <a href="<?= esc(site_url('terms'), 'attr') ?>">Terms</a> · <a href="<?= esc(site_url('privacy'), 'attr') ?>">Privacy</a></footer>
  <script>
    const form = document.querySelector('#register-form');
    const status = document.querySelector('.form-status');
    const api = <?= json_encode(site_url('api'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      if (!form.reportValidity()) return;
      const button = form.querySelector('button[type="submit"]');
      button.disabled = true; status.textContent = 'Creating your account…';
      try {
        const sessionResponse = await fetch(api + '/session', {credentials:'same-origin', cache:'no-store'});
        if (!sessionResponse.ok) throw new Error('Account service is unavailable. Please try again.');
        const session = await sessionResponse.json();
        const data = Object.fromEntries(new FormData(form));
        const response = await fetch(api + '/register', {method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/json','X-CSRF-Token':session.csrf}, body:JSON.stringify(data)});
        const result = await response.json();
        if (!response.ok) throw new Error(result.error || 'Could not create your account.');
        form.innerHTML = '<div class="notice" role="status" tabindex="-1"><h3>Welcome to Sun Son Solar.</h3><p>Your customer account is ready. Sign in to request and manage service.</p><a class="button" href="<?= esc(site_url('login'), 'attr') ?>">Sign in</a></div>';
        form.querySelector('[role="status"]').focus();
      } catch (error) { status.textContent = error.message; status.focus(); button.disabled = false; }
    });
  </script>
</body>
</html>
