<!doctype html>
<html lang="en"><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Your profile | Sun Son Solar Inc.</title>
  <link rel="icon" href="<?= esc(base_url('assets/favicon.svg'), 'attr') ?>" type="image/svg+xml">
  <link rel="stylesheet" href="<?= esc(base_url('assets/site.css') . '?v=customer-profile', 'attr') ?>">
</head>
<body data-api-url="<?= esc(site_url('api'), 'attr') ?>" data-home-url="<?= esc(site_url(''), 'attr') ?>" data-login-url="<?= esc(site_url('login'), 'attr') ?>" data-csrf="<?= esc($csrf, 'attr') ?>">
<a class="skip" href="#main">Skip to content</a>
<header><div class="nav wrap">
  <a class="brand" href="<?= esc(site_url(), 'attr') ?>"><span class="brand-icon">☀</span><span>SUN SON<span class="brand-small">SOLAR INC.</span></span></a>
  <button class="menu-toggle" aria-expanded="false" aria-controls="navigation">Menu</button>
  <nav id="navigation" aria-label="Main navigation"><a href="<?= esc(site_url('products'), 'attr') ?>">Products</a><a href="<?= esc(site_url('services'), 'attr') ?>">Services</a><a href="<?= esc(site_url('lets-talk-solar'), 'attr') ?>">Let’s talk solar</a></nav>
  <div class="nav-actions"><a class="portal-link" href="<?= esc(site_url('profile'), 'attr') ?>" aria-current="page">Profile</a><button class="portal-link text-button" type="button" data-signout>Sign out</button></div>
</div></header>
<main id="main" class="wrap section">
  <p class="eyebrow">YOUR ACCOUNT</p><h1>Your Sun Son Solar profile.</h1>
  <p>Update your contact details and preferences, and review your service requests.</p>
  <div class="contact-grid">
    <form id="profile-form" class="form-card">
      <h2>Personal information</h2>
      <label>First name<input name="first_name" required value="<?= esc($user['first_name'] ?? '', 'attr') ?>"></label>
      <label>Middle name<input name="middle_name" value="<?= esc($user['middle_name'] ?? '', 'attr') ?>"></label>
      <label>Last name<input name="last_name" required value="<?= esc($user['last_name'] ?? '', 'attr') ?>"></label>
      <label>Birthdate<input type="date" name="birthday" value="<?= esc($user['birthday'] ?? '', 'attr') ?>"></label>
      <label>Gender<select name="gender"><option value="">Choose an option</option><?php foreach (['Woman','Man','Non-binary','Self-described','Prefer not to say'] as $gender): ?><option value="<?= esc($gender, 'attr') ?>" <?= ($user['gender'] ?? '') === $gender ? 'selected' : '' ?>><?= esc($gender) ?></option><?php endforeach; ?></select></label>
      <label>Email<input type="email" name="email" required value="<?= esc($user['email'] ?? '', 'attr') ?>"></label>
      <label>Phone<input name="phone" required value="<?= esc($user['phone'] ?? '', 'attr') ?>"></label>
      <label>Address<input name="address" required value="<?= esc($user['address'] ?? '', 'attr') ?>"></label>
      <label class="checkbox"><input type="checkbox" name="promotions" <?= !empty($preferences['promotions']) ? 'checked' : '' ?>> Receive promotions</label>
      <label class="checkbox"><input type="checkbox" name="schedule_updates" <?= !empty($preferences['schedule_updates']) ? 'checked' : '' ?>> Receive schedule updates</label>
      <p class="form-status" role="status" aria-live="polite"></p><button class="button" type="submit">Save profile</button>
    </form>
    <div class="form-card"><h2>Service request history</h2><div id="profile-bookings" aria-live="polite">Loading your requests…</div>
      <h2>Change password</h2><form id="password-form"><label>Current password<input type="password" name="current_password" autocomplete="current-password" required></label><label>New password<input type="password" name="new_password" autocomplete="new-password" minlength="12" maxlength="72" required></label><p class="form-status" role="status" aria-live="polite"></p><button class="button outline" type="submit">Change password</button></form>
      <p><a class="underlink" href="<?= esc(site_url('lets-talk-solar'), 'attr') ?>">Request another service</a></p>
    </div>
  </div>
</main>
<script>document.querySelector('.menu-toggle')?.addEventListener('click',e=>{const b=e.currentTarget,open=b.getAttribute('aria-expanded')!=='true';b.setAttribute('aria-expanded',String(open));document.querySelector('#navigation')?.classList.toggle('open',open)});</script>
<script src="<?= esc(base_url('assets/customer-pages.js') . '?v=1', 'attr') ?>" defer></script>
</body></html>
