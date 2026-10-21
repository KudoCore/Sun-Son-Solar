<!doctype html>
<html lang="en"><head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Let’s talk solar | Sun Son Solar Inc.</title>
  <link rel="icon" href="<?= esc(base_url('assets/favicon.svg'), 'attr') ?>" type="image/svg+xml">
  <link rel="stylesheet" href="<?= esc(base_url('assets/site.css') . '?v=customer-request', 'attr') ?>">
</head>
<body data-api-url="<?= esc(site_url('api'), 'attr') ?>" data-home-url="<?= esc(site_url(''), 'attr') ?>" data-csrf="<?= esc($csrf, 'attr') ?>">
<a class="skip" href="#main">Skip to content</a>
<header><div class="nav wrap">
  <a class="brand" href="<?= esc(site_url(), 'attr') ?>"><span class="brand-icon">☀</span><span>SUN SON<span class="brand-small">SOLAR INC.</span></span></a>
  <button class="menu-toggle" aria-expanded="false" aria-controls="navigation">Menu</button>
  <nav id="navigation" aria-label="Main navigation"><a href="<?= esc(site_url('products'), 'attr') ?>">Products</a><a href="<?= esc(site_url('services'), 'attr') ?>">Services</a><a href="<?= esc(site_url('lets-talk-solar'), 'attr') ?>" aria-current="page">Let’s talk solar</a></nav>
  <div class="nav-actions"><a class="portal-link" href="<?= esc(site_url('profile'), 'attr') ?>">Profile</a><button class="portal-link text-button" type="button" data-signout>Sign out</button></div>
</div></header>
<main id="main" class="wrap section">
  <p class="eyebrow">LET’S TALK SOLAR</p><h1>Tell us about your solar plans.</h1>
  <p>Choose a service and tell us about your property. Our team will follow up to confirm timing and project details.</p>
  <div class="contact-grid">
    <div class="form-card"><h2>What happens next?</h2><p>Your request goes to our team for review. You can track its status from your Profile page.</p><a class="underlink" href="<?= esc(site_url('profile'), 'attr') ?>">View your profile and requests</a></div>
    <form id="request-form" class="form-card" data-default-address="<?= esc($user['address'] ?? '', 'attr') ?>">
      <h2>Request a service</h2>
      <label>Service<select name="category_id" required><option value="">Choose a service</option><?php foreach ($customerServices as $service): ?><option value="<?= (int) $service['id'] ?>"><?= esc($service['name']) ?><?= $service['base_price'] !== null ? ' · ₱' . esc($service['base_price']) : ' · Quote required' ?></option><?php endforeach; ?></select></label>
      <label>Preferred date and time<input type="datetime-local" name="requested_at"></label>
      <label>Service address<input name="service_address" required maxlength="300" value="<?= esc($user['address'] ?? '', 'attr') ?>"></label>
      <label>Project details<textarea name="customer_notes" maxlength="3000" rows="5" placeholder="Tell us about your property and solar goals."></textarea></label>
      <p class="form-status" role="status" aria-live="polite"></p><button class="button" type="submit">Send service request</button>
    </form>
  </div>
</main>
<script>document.querySelector('.menu-toggle')?.addEventListener('click',e=>{const b=e.currentTarget,open=b.getAttribute('aria-expanded')!=='true';b.setAttribute('aria-expanded',String(open));document.querySelector('#navigation')?.classList.toggle('open',open)});</script>
<script src="<?= esc(base_url('assets/customer-pages.js') . '?v=1', 'attr') ?>" defer></script>
</body></html>
