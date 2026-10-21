<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="description" content="View your approved Sun Son Solar employee account.">
  <title>My account | Sun Son Solar Inc.</title>
  <link rel="icon" href="<?= esc(base_url('assets/favicon.svg'), 'attr') ?>" type="image/svg+xml">
  <link rel="stylesheet" href="<?= esc(base_url('assets/account.css'), 'attr') ?>" />
</head>
<body>
  <a class="skip" href="#main">Skip to account details</a>
  <header><a class="brand" href="<?= esc(site_url(), 'attr') ?>"><span aria-hidden="true">☀</span>SUN SON SOLAR INC.</a><span>Employee portal</span></header>
  <div class="shell">
    <aside><p class="eyebrow">ONE TEAM. BRIGHTER TOGETHER.</p><span class="sun" aria-hidden="true">☀</span><h2>Welcome to your<br>Sun Son account.</h2><p>Your employee profile, in one place.</p><a href="<?= esc(site_url(), 'attr') ?>">Visit the main website →</a></aside>
    <main id="main">
      <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= esc(site_url(), 'attr') ?>">Home</a> / <span aria-current="page">My account</span></nav>
      <h1>Hello, <?= esc($user['first_name']) ?>.</h1>
      <p class="intro">You’re signed in to your employee account.</p>
      <span class="status">✓ Account approved</span>
      <dl>
        <div><dt>Full name</dt><dd><?= esc(trim($user['first_name'] . ' ' . ($user['middle_name'] ?? '') . ' ' . $user['last_name'])) ?></dd></div>
        <div><dt>Username</dt><dd><?= esc($user['username']) ?></dd></div>
        <div><dt>Email address</dt><dd><?= esc($user['email']) ?></dd></div>
        <div><dt>Department</dt><dd><?= esc($user['department']) ?></dd></div>
        <div><dt>Account role</dt><dd><?= esc(ucwords(str_replace('_', ' ', $user['role']))) ?></dd></div>
        <div><dt>Birthday</dt><dd><?= esc($user['birthday'] ?: 'Not provided') ?></dd></div>
        <div><dt>Gender</dt><dd><?= esc($user['gender'] ?: 'Not provided') ?></dd></div>
        <div><dt>Phone number (<?= esc($user['phone_type'] ?? 'unspecified') ?>)</dt><dd><?= esc($user['phone'] ?: 'Not provided') ?></dd></div>
        <div><dt>Address</dt><dd><?= esc($user['address'] ?: 'Not provided') ?></dd></div>
      </dl>
      <p class="note">Need to update your details? Contact your IT administrator.</p>
      <div class="actions"><button id="logout" type="button">Sign out</button><a href="<?= esc(site_url(), 'attr') ?>">Back to website</a></div>
      <p id="message" class="message" role="status" aria-live="polite" tabindex="-1"></p>
    </main>
  </div>
  <footer>Sun Son Solar Inc. · Employee account</footer>
  <script>
    const logoutButton = document.querySelector('#logout');
    const message = document.querySelector('#message');
    const apiUrl = <?= json_encode(site_url('api'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const loginUrl = <?= json_encode(site_url('login'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    // A restored browser-history page must check the current session again.
    window.addEventListener('pageshow', (event) => {
      if (event.persisted) window.location.reload();
    });

    logoutButton.addEventListener('click', async () => {
      logoutButton.disabled = true;
      message.textContent = 'Signing out…';
      try {
        const sessionResponse = await fetch(apiUrl + '/session', {credentials: 'same-origin', cache: 'no-store'});
        if (!sessionResponse.ok) throw new Error('Could not check your session. Please try again.');
        const session = await sessionResponse.json();
        const response = await fetch(apiUrl + '/logout', {
          method: 'POST', credentials: 'same-origin',
          headers: {'Content-Type': 'application/json', 'X-CSRF-Token': session.csrf},
          body: JSON.stringify({})
        });
        if (!response.ok) throw new Error('Could not sign out. Please try again.');
        window.location.replace(loginUrl);
      } catch (error) {
        message.textContent = error.message;
        message.focus();
        logoutButton.disabled = false;
      }
    });
  </script>
</body>
</html>
