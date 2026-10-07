<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <meta name="description" content="View your approved Sun Son Solar employee account.">
  <title>My account | Sun Son Solar Inc.</title>
  <link rel="icon" href="<?= esc(base_url('assets/favicon.svg'), 'attr') ?>" type="image/svg+xml">
  <style>
    :root{--green:#243e2e;--lime:#defa9e;--ink:#303030;--muted:#586259;--line:#dce3d9}
    *{box-sizing:border-box}body{margin:0;background:#f5f7f2;color:var(--ink);font:16px/1.6 system-ui,-apple-system,sans-serif}
    a{color:var(--green)}button,a{touch-action:manipulation}button{font:inherit;cursor:pointer}a:focus-visible,button:focus-visible{outline:3px solid #4c751b;outline-offset:5px}
    .skip{position:absolute;left:16px;top:-90px;background:white;padding:12px;z-index:2}.skip:focus{top:12px}
    header{background:white;border-bottom:1px solid var(--line);padding:22px max(24px,6vw);display:flex;gap:20px;align-items:center;justify-content:space-between}
    .brand{font-weight:750;letter-spacing:.02em;text-decoration:none}.brand span{color:#557c3b;font-size:24px;margin-right:10px}
    .shell{max-width:1140px;margin:64px auto;padding:0 24px;display:grid;grid-template-columns:300px 1fr;gap:28px}
    aside{background:var(--green);color:white;border-radius:24px;padding:36px;display:flex;flex-direction:column;align-items:start}
    .eyebrow{font-size:12px;letter-spacing:.15em;font-weight:700;margin:0 0 24px}.sun{font-size:76px;color:var(--lime);line-height:1.2}
    aside h2{font-size:28px;font-weight:500;line-height:1.2;margin:26px 0 16px}aside p{color:#e1e9dc}aside a{color:var(--lime);margin-top:auto}
    main{background:white;border:1px solid var(--line);border-radius:24px;padding:40px}.breadcrumb{font-size:14px;margin-bottom:32px}.breadcrumb span{color:var(--muted)}
    h1{font-size:clamp(28px,4vw,40px);line-height:1.2;letter-spacing:-.04em;font-weight:550;margin:0 0 12px;overflow-wrap:anywhere}
    .intro{color:var(--muted);margin:0 0 24px}.status{display:inline-flex;gap:8px;align-items:center;background:#edf6df;border:1px solid #d6e6c3;color:var(--green);border-radius:24px;padding:5px 13px;font-size:13px;font-weight:650}
    dl{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin:32px 0}dl div{border-top:1px solid var(--line);padding-top:16px;min-width:0}dt{color:var(--muted);font-size:13px}dd{margin:4px 0 0;font-weight:550;overflow-wrap:anywhere}
    .note{font-size:14px;color:var(--muted);border-top:1px solid var(--line);padding-top:22px}.actions{display:flex;gap:20px;align-items:center;margin-top:28px;flex-wrap:wrap}
    button{background:var(--green);color:white;border:0;border-radius:28px;padding:12px 24px;min-height:48px}button:disabled{opacity:.65;cursor:wait}.message{color:#9c2929;min-height:24px;font-size:14px}
    footer{text-align:center;color:var(--muted);padding:0 24px 32px;font-size:13px}
    @media(max-width:760px){.shell{grid-template-columns:1fr;margin:28px auto;gap:18px}aside{padding:28px}.sun{font-size:42px}aside h2{margin-top:14px}aside a{margin-top:12px}main{padding:28px}dl{grid-template-columns:1fr}header{padding:18px 24px}.eyebrow{margin-bottom:14px}}
  </style>
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
