<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Employee login | Sun Son Solar Inc.</title>
    <meta
      name="description"
      content="Sign in to your approved Sun Son Solar employee account."
    />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="theme-color" content="#243e2e" />
    <meta property="og:type" content="website" />
    <meta
      property="og:title"
      content="Employee login | Sun Son Solar Inc."
    />
    <meta
      property="og:description"
      content="Sign in to your approved Sun Son Solar employee account."
    />
    <meta
      property="og:image"
      content="https://sun-son-solar.plovindino1.chatgpt.site/assets/social-card.jpg"
    />
    <meta
      property="og:image:alt"
      content="Sun Son Solar Inc. — Good energy. Great possibilities."
    />
    <meta name="twitter:card" content="summary_large_image" />
    <link rel="icon" href="<?= esc(base_url('assets'), 'attr') ?>/favicon.svg" type="image/svg+xml" />
    <!-- Page styling: edit the CSS below to change the design. -->
    <style>
      /* SUN SON SOLAR: colors, typography, layout, forms, then mobile styles. */
      :root {
        --green: #243e2e;
        --lime: #defa9e;
        --ink: #303030;
        --muted: #626c64;
        --line: #dce2db;
        --paper: #f5f7f3;
        --white: #fff;
        --radius: 20px;
      }
      * {
        box-sizing: border-box;
      }
      html {
        scroll-behavior: smooth;
        scroll-padding-top: 100px;
      }
      body {
        margin: 0;
        background: white;
        color: var(--ink);
        font:
          16px/1.65 Arial,
          Helvetica,
          sans-serif;
      }
      h1,
      h2,
      h3,
      p {
        margin: 0;
      }
      h1,
      h2,
      h3 {
        color: var(--green);
        font-weight: 500;
        letter-spacing: -0.045em;
        line-height: 1.08;
      }
      h1 {
        font-size: clamp(44px, 5.3vw, 80px);
      }
      h2 {
        font-size: clamp(32px, 3.5vw, 52px);
      }
      h3 {
        font-size: 24px;
      }
      p {
        margin-bottom: 20px;
        color: var(--muted);
      }
      a {
        color: inherit;
        text-decoration: none;
      }
      a:hover {
        text-decoration: underline;
        text-underline-offset: 5px;
      }
      button,
      input,
      select,
      textarea {
        font: inherit;
      }
      button,
      a,
      input,
      select,
      textarea {
        touch-action: manipulation;
      }
      button {
        cursor: pointer;
      }
      img {
        display: block;
        width: 100%;
        object-fit: cover;
      }
      button:focus-visible,
      a:focus-visible,
      input:focus-visible,
      select:focus-visible,
      textarea:focus-visible,
      summary:focus-visible {
        outline: 3px solid #607b24;
        outline-offset: 5px;
      }
      .wrap {
        max-width: 1320px;
        width: calc(100% - 96px);
        margin: 0 auto;
      }
      .section {
        padding-top: 100px;
        padding-bottom: 100px;
      }
      .eyebrow {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.15em;
        line-height: 1.5;
        color: var(--green);
        margin-bottom: 22px;
      }
      .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 50px;
        padding: 12px 24px;
        background: var(--green);
        color: white;
        border: 1px solid var(--green);
        border-radius: 999px;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        text-decoration: none !important;
        transition:
          background 0.2s,
          transform 0.2s;
      }
      .button:hover {
        background: #35563f;
        transform: translateY(-1px);
      }
      .button.light {
        background: var(--lime);
        border-color: var(--lime);
        color: var(--green);
      }
      .button.outline {
        color: var(--green);
        background: transparent;
      }
      .button.outline:hover {
        background: #edf3e8;
      }
      .button.glass {
        background: #ffffff16;
        color: white;
        border-color: #ffffff85;
        backdrop-filter: blur(8px);
      }
      .button.small {
        min-height: 44px;
        padding: 10px 20px;
      }
      .full {
        width: 100%;
      }
      .button:disabled {
        opacity: 0.55;
        cursor: wait;
        transform: none;
      }
      .underlink {
        display: inline-block;
        border-bottom: 1px solid var(--green);
        padding-bottom: 4px;
        font-size: 14px;
        font-weight: 600;
        color: var(--green);
      }
      .skip {
        position: fixed;
        top: -100px;
        left: 20px;
        background: var(--lime);
        padding: 15px;
        z-index: 20;
      }
      .skip:focus {
        top: 15px;
      }
      header {
        background: white;
      }
      .nav {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
      }
      .brand {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.05;
        color: var(--green);
        letter-spacing: 0.06em;
        text-decoration: none !important;
      }
      .brand-icon {
        font-size: 44px;
        line-height: 1;
        font-weight: 400;
      }
      .brand-small {
        font-size: 9px;
        letter-spacing: 0.34em;
        display: block;
        margin-top: 6px;
      }
      .nav nav {
        display: flex;
        gap: 28px;
      }
      .nav nav a,
      .portal-link {
        font-size: 14px;
      }
      .nav-actions {
        display: flex;
        align-items: center;
        gap: 24px;
      }
      .menu-toggle {
        display: none;
      }
      .hero-scene {
        min-height: 650px;
        height: calc(100svh - 170px);
        max-height: 790px;
        position: relative;
        isolation: isolate;
        overflow: hidden;
        border-radius: 24px;
        display: flex;
        align-items: center;
      }
      .hero-image {
        position: absolute;
        inset: 0;
        height: 100%;
        z-index: -3;
        object-position: center 60%;
      }
      .hero-shade {
        position: absolute;
        inset: 0;
        background: linear-gradient(
          90deg,
          rgba(13, 34, 23, 0.84),
          rgba(13, 34, 23, 0.3) 65%,
          rgba(13, 34, 23, 0.1)
        );
        z-index: -2;
      }
      .hero-copy {
        padding: 65px 60px;
      }
      .hero h1 {
        color: white;
        margin-bottom: 24px;
        max-width: 780px;
      }
      .hero .eyebrow {
        color: var(--lime);
        display: flex;
        align-items: center;
        gap: 10px;
      }
      .sun-mini {
        font-size: 23px;
      }
      .hero-copy > p:not(.eyebrow) {
        color: #fff;
        font-size: 18px;
        line-height: 1.65;
      }
      .hero-buttons {
        display: flex;
        gap: 12px;
        margin-top: 36px;
      }
      .hero-foot {
        position: absolute;
        bottom: 26px;
        left: 60px;
        right: 60px;
        display: flex;
        justify-content: space-between;
        border-top: 1px solid #ffffff55;
        padding-top: 20px;
        color: white;
        font-size: 11px;
        letter-spacing: 0.1em;
      }
      .hero-foot span:last-child {
        letter-spacing: 0;
        font-size: 13px;
      }
      .value-strip {
        display: flex;
        justify-content: space-around;
        gap: 15px;
        padding: 27px 0;
        border-bottom: 1px solid var(--line);
        font-size: 14px;
        color: var(--green);
      }
      .value-strip span:before {
        content: "✓";
        margin-right: 12px;
      }
      .about-grid {
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 120px;
      }
      .about-grid p {
        max-width: 500px;
      }
      .large-copy {
        font-size: 23px;
        color: var(--green);
        line-height: 1.5;
      }
      .section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 40px;
        margin-bottom: 44px;
      }
      .products-section,
      .faq-section {
        background: var(--paper);
      }
      .product-feature {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        background: white;
        border-radius: 20px;
        overflow: hidden;
      }
      .product-photo {
        position: relative;
        min-height: 430px;
      }
      .product-photo img {
        height: 100%;
      }
      .image-label {
        position: absolute;
        left: 25px;
        bottom: 25px;
        padding: 8px 16px;
        border-radius: 30px;
        background: white;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.1em;
      }
      .product-detail {
        padding: 45px 50px;
      }
      .index {
        font-size: 12px;
        letter-spacing: 0.1em;
        color: var(--muted);
      }
      .product-detail h3 {
        font-size: 44px;
        margin: 30px 0 22px;
      }
      .product-detail p {
        max-width: 370px;
      }
      .product-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        border-top: 1px solid var(--line);
        margin-top: 35px;
        padding-top: 25px;
      }
      .product-tags span {
        padding: 6px 10px;
        border: 1px solid var(--line);
        border-radius: 20px;
        font-size: 12px;
      }
      .heading-note {
        max-width: 355px;
        margin-bottom: 0;
      }
      .steps {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
      }
      .step {
        border-top: 1px solid var(--line);
        padding: 23px 8px 0 0;
      }
      .step h3 {
        margin: 34px 0 18px;
      }
      .step p {
        font-size: 14px;
      }
      .step:hover {
        text-decoration: none;
      }
      .step:hover h3 {
        text-decoration: underline;
      }
      .support-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid var(--line);
        padding-top: 28px;
        margin-top: 20px;
        font-size: 14px;
        gap: 24px;
      }
      .faq-grid {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 80px;
      }
      .faq-grid h2 {
        margin-bottom: 22px;
      }
      .faq-list details {
        border-bottom: 1px solid #cbd5c9;
      }
      .faq-list summary {
        cursor: pointer;
        list-style: none;
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 24px 0;
        font-size: 17px;
        color: var(--green);
        line-height: 1.5;
      }
      .faq-list summary::-webkit-details-marker {
        display: none;
      }
      .faq-list summary span {
        font-size: 24px;
      }
      .faq-list details[open] summary span {
        transform: rotate(45deg);
      }
      .faq-list details p {
        font-size: 15px;
        max-width: 560px;
        padding-right: 20px;
      }
      .cta-panel {
        margin: 80px 0;
        padding: 70px;
        text-align: center;
        background: var(--lime);
        border-radius: 24px;
      }
      .cta-panel h2 {
        font-size: clamp(38px, 4vw, 62px);
        margin-bottom: 30px;
      }
      .cta-panel .button {
        background: var(--green);
        color: white;
        border-color: var(--green);
      }
      footer {
        background: var(--green);
        color: #fff;
        padding-top: 65px;
      }
      footer .brand,
      footer h3 {
        color: white;
      }
      footer .brand-icon {
        color: var(--lime);
      }
      .footer-top {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.3fr;
        gap: 40px;
        padding-bottom: 60px;
      }
      footer p {
        color: #d1ddcf;
        margin-top: 24px;
        font-size: 18px;
      }
      footer h3 {
        font-size: 14px;
        letter-spacing: 0;
        font-weight: 600;
        margin: 0 0 22px;
      }
      footer a:not(.brand),
      footer .text-button {
        display: block;
        font-size: 13px;
        margin-bottom: 10px;
        color: #e3eae0;
      }
      .footer-bottom {
        border-top: 1px solid #ffffff30;
        display: flex;
        justify-content: space-between;
        padding: 22px 0;
        font-size: 12px;
        color: #d1ddcf;
      }
      .text-button {
        padding: 0;
        background: none;
        color: inherit;
        border: 0;
        text-align: left;
      }
      .text-button:hover {
        text-decoration: underline;
      }
      .mobile-cta {
        display: none;
      }
      .breadcrumbs {
        display: flex;
        gap: 12px;
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 55px;
      }
      .breadcrumbs a {
        text-decoration: underline;
      }
      .page-intro {
        padding-top: 25px;
        padding-bottom: 65px;
      }
      .page-intro h1 {
        margin-bottom: 26px;
      }
      .lead {
        font-size: 19px;
        max-width: 650px;
        margin-bottom: 0;
      }
      .catalog {
        padding-bottom: 65px;
      }
      .catalog-row {
        display: grid;
        grid-template-columns: 80px 1fr 1fr;
        gap: 30px;
        border-top: 1px solid var(--line);
        padding: 50px 0;
      }
      .catalog-row h2 {
        font-size: 38px;
      }
      .catalog-row h3 {
        font-size: 23px;
        margin-bottom: 20px;
      }
      .catalog-row .eyebrow {
        font-size: 11px;
      }
      .service-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
        padding-bottom: 40px;
      }
      .service-card {
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 32px;
        scroll-margin-top: 30px;
      }
      .service-card h2 {
        font-size: 30px;
        margin: 35px 0 15px;
      }
      .service-card h3 {
        font-size: 17px;
        letter-spacing: 0;
        line-height: 1.5;
        margin-bottom: 15px;
      }
      .service-card p {
        font-size: 15px;
      }
      .approach-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 70px;
        background: var(--paper);
        padding: 40px;
        border-radius: 20px;
      }
      .project-image img {
        height: 350px;
        border-radius: 15px;
      }
      .project-image small {
        font-size: 12px;
        color: var(--muted);
        display: block;
        margin-top: 12px;
      }
      .clean-list {
        list-style: none;
        padding: 0;
        margin-top: 30px;
      }
      .clean-list li {
        border-bottom: 1px solid var(--line);
        padding: 14px 0;
        font-size: 15px;
      }
      .team-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 40px;
        max-width: 780px;
      }
      .initials {
        height: 200px;
        display: grid;
        place-items: center;
        background: var(--paper);
        border-radius: 15px;
        font-size: 55px;
        color: var(--green);
        margin-bottom: 25px;
      }
      .team-grid h3 {
        font-size: 25px;
        margin-bottom: 8px;
      }
      .narrow {
        max-width: 800px;
      }
      .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1.15fr;
        gap: 100px;
        padding-bottom: 100px;
      }
      .contact-info {
        padding-top: 30px;
      }
      .contact-info h2 {
        margin-bottom: 25px;
      }
      .info-block {
        border-top: 1px solid var(--line);
        padding-top: 25px;
        margin-top: 30px;
      }
      .info-block h3 {
        font-size: 20px;
        margin-bottom: 15px;
      }
      .form-card {
        background: var(--paper);
        border: 1px solid var(--line);
        padding: 38px;
        border-radius: 20px;
      }
      .form-card h2 {
        font-size: 34px;
        margin-bottom: 16px;
      }
      .form-note {
        font-size: 13px;
        line-height: 1.6;
        margin-top: 14px;
      }
      .form-note a,
      .checkbox a,
      .legal a {
        text-decoration: underline;
      }
      label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--green);
        margin-bottom: 19px;
      }
      input,
      select,
      textarea {
        display: block;
        width: 100%;
        border: 1px solid #aeb9af;
        border-radius: 9px;
        background: white;
        padding: 12px 14px;
        font-size: 16px;
        margin-top: 7px;
        color: var(--ink);
        min-width: 0;
      }
      textarea {
        resize: vertical;
      }
      input::placeholder,
      textarea::placeholder {
        font-size: 14px;
        color: #697369;
      }
      input[type="checkbox"] {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        margin-top: 3px;
        accent-color: var(--green);
      }
      .checkbox {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-weight: 400;
        line-height: 1.6;
      }
      .honeypot {
        display: none;
      }
      .form-status {
        font-size: 14px;
        color: #a02424;
        white-space: pre-line;
      }
      .form-status:empty {
        display: none;
      }
      .portal-shell {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: 16px;
        margin: 20px auto 70px;
        gap: 30px;
        max-width: 1250px;
      }
      .portal-art {
        min-height: 680px;
        background: radial-gradient(
          ellipse at 60% 35%,
          #44654b,
          var(--green) 65%
        );
        padding: 40px;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }
      .portal-art .eyebrow,
      .portal-art p {
        color: #e0efdf;
      }
      .portal-art h1 {
        font-size: 45px;
        color: white;
        margin-bottom: 20px;
      }
      .portal-emblem {
        color: var(--lime);
        font-size: 160px;
        line-height: 1;
        text-align: center;
        margin: 20px 0;
        opacity: 0.85;
      }
      .art-bottom {
        font-size: 10px;
        color: #c3d5c1;
        letter-spacing: 0.12em;
      }
      .portal-form {
        align-self: center;
        padding: 40px 42px 40px 12px;
      }
      .portal-form h2 {
        font-size: 32px;
        margin-bottom: 15px;
      }
      .portal-form form {
        margin-top: 32px;
      }
      .password-wrap {
        display: flex;
        position: relative;
      }
      .password-wrap input {
        padding-right: 70px;
      }
      .password-toggle {
        position: absolute;
        right: 8px;
        top: 14px;
        border: 0;
        background: white;
        color: var(--green);
        font-size: 13px;
        padding: 9px;
      }
      .login-help {
        padding-top: 25px;
        border-top: 1px solid var(--line);
        margin-top: 30px;
        font-size: 14px;
      }
      .login-help p {
        margin-top: 8px;
      }
      .legal {
        max-width: 850px;
        padding-bottom: 90px;
      }
      .legal section {
        margin: 35px 0;
      }
      .legal h2 {
        font-size: 26px;
        margin-bottom: 18px;
      }
      .notice {
        background: #f1f5e9;
        border: 1px solid #c6d3b9;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        font-size: 14px;
      }
      .cookie-banner {
        position: fixed;
        bottom: 20px;
        left: 24px;
        right: 24px;
        max-width: 1250px;
        margin: auto;
        background: white;
        border: 1px solid var(--line);
        box-shadow: 0 10px 50px #0002;
        padding: 22px 28px;
        border-radius: 16px;
        z-index: 30;
        display: flex;
        align-items: center;
        gap: 35px;
      }
      .cookie-banner[hidden] {
        display: none;
      }
      .cookie-banner p {
        font-size: 13px;
        margin: 4px 0 0;
        max-width: 700px;
      }
      .cookie-banner a {
        text-decoration: underline;
      }
      .cookie-actions {
        display: flex;
        gap: 10px;
        flex-shrink: 0;
      }
      .cookie-actions .button {
        font-size: 13px;
        padding: 12px 18px;
      }
      .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
      }
      .dash-card {
        padding: 30px;
        border: 1px solid var(--line);
        border-radius: 18px;
        margin-bottom: 25px;
      }
      .dash-card h2 {
        font-size: 28px;
        margin-bottom: 20px;
      }
      .dash-card h3 {
        font-size: 22px;
        margin-bottom: 15px;
      }
      .dash-toolbar {
        display: flex;
        gap: 20px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
      }
      .fields-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 18px;
      }
      .table-scroll {
        overflow-x: auto;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
      }
      th,
      td {
        text-align: left;
        padding: 14px;
        border-bottom: 1px solid var(--line);
      }
      th {
        color: var(--green);
        font-weight: 600;
      }
      .check-time {
        font-size: 42px;
        color: var(--green);
        margin: 22px 0;
      }
      .muted {
        color: var(--muted);
      }
      .pill {
        font-size: 12px;
        padding: 6px 12px;
        background: var(--lime);
        border-radius: 20px;
        display: inline-block;
      }
      .dashboard-grid .full-span {
        grid-column: 1/-1;
      }
      @media (min-width: 1600px) {
        .hero-scene {
          min-height: 700px;
        }
      }
      @media (max-width: 1100px) {
        .wrap {
          width: calc(100% - 48px);
        }
        .nav nav {
          gap: 18px;
        }
        .portal-link {
          display: none;
        }
        .about-grid {
          gap: 50px;
        }
        .hero-copy {
          padding: 45px;
        }
        .hero-foot {
          left: 45px;
          right: 45px;
        }
        .contact-grid {
          gap: 50px;
        }
        .portal-art {
          padding: 30px;
        }
        .portal-form {
          padding-right: 20px;
        }
        .faq-grid {
          gap: 45px;
        }
        .service-grid {
          grid-template-columns: repeat(2, 1fr);
        }
        .cookie-banner {
          gap: 20px;
        }
        .cookie-actions {
          flex-direction: column;
        }
        .footer-top {
          gap: 25px;
        }
      }
      @media (max-width: 760px) {
        .wrap {
          width: calc(100% - 32px);
        }
        .nav {
          height: 80px;
        }
        .brand {
          font-size: 17px;
        }
        .brand-icon {
          font-size: 36px;
        }
        .nav-actions {
          margin-left: auto;
        }
        .nav-actions .button {
          font-size: 12px;
          min-height: 40px;
          padding: 9px 14px;
        }
        .menu-toggle {
          display: block;
          background: white;
          border: 1px solid var(--line);
          border-radius: 20px;
          padding: 7px 12px;
          font-size: 13px;
          order: 3;
        }
        .nav nav {
          display: none;
          position: absolute;
          top: 80px;
          left: 0;
          right: 0;
          background: white;
          padding: 25px;
          z-index: 10;
          box-shadow: 0 15px 20px #0001;
        }
        .nav nav.open {
          display: flex;
          flex-direction: column;
        }
        .hero-scene {
          min-height: 590px;
          max-height: 720px;
          height: calc(100svh - 115px);
          border-radius: 18px;
        }
        .hero-copy {
          padding: 30px 25px 70px;
        }
        .hero h1 {
          font-size: clamp(42px, 10vw, 65px);
          line-height: 1.06;
        }
        .hero-shade {
          background: linear-gradient(90deg, #132c20de, #132c2070);
        }
        .hero .eyebrow {
          font-size: 10px;
          letter-spacing: 0.09em;
        }
        .hero-copy > p:not(.eyebrow) {
          font-size: 16px;
        }
        .hero-buttons {
          flex-direction: column;
          align-items: flex-start;
          margin-top: 25px;
        }
        .hero-buttons .button {
          font-size: 13px;
          min-height: 46px;
        }
        .hero-foot {
          left: 25px;
          right: 25px;
          bottom: 20px;
        }
        .hero-foot span:last-child {
          display: none;
        }
        .value-strip {
          font-size: 12px;
          text-align: center;
          gap: 14px;
          line-height: 1.5;
        }
        .value-strip span:before {
          display: block;
          margin: 0 0 5px;
        }
        .section {
          padding-top: 65px;
          padding-bottom: 65px;
        }
        .about-grid,
        .faq-grid,
        .contact-grid,
        .approach-grid {
          grid-template-columns: 1fr;
          gap: 32px;
        }
        .section-heading {
          align-items: flex-start;
          gap: 25px;
          flex-direction: column;
          margin-bottom: 30px;
        }
        .product-feature {
          grid-template-columns: 1fr;
        }
        .product-photo {
          min-height: 250px;
          height: 250px;
        }
        .product-detail {
          padding: 28px;
        }
        .product-detail h3 {
          font-size: 36px;
          margin-top: 20px;
        }
        .product-tags {
          margin-top: 25px;
        }
        .steps {
          grid-template-columns: 1fr 1fr;
          gap: 25px;
        }
        .step h3 {
          font-size: 22px;
          margin-top: 25px;
        }
        .support-line {
          align-items: flex-start;
          flex-direction: column;
        }
        .cta-panel {
          padding: 50px 20px;
          margin: 45px 0;
        }
        .footer-top {
          grid-template-columns: 1fr 1fr;
          gap: 35px;
        }
        .footer-top > div:first-child {
          grid-column: 1/-1;
        }
        .footer-bottom {
          flex-direction: column;
          gap: 10px;
          padding-bottom: 90px;
        }
        .mobile-cta {
          display: flex;
          position: fixed;
          bottom: 12px;
          left: 16px;
          right: 16px;
          z-index: 12;
          box-shadow: 0 4px 20px #0003;
        }
        .page-intro {
          padding-top: 20px;
          padding-bottom: 40px;
        }
        .breadcrumbs {
          margin-bottom: 35px;
        }
        .page-intro h1 {
          font-size: 43px;
        }
        .lead {
          font-size: 17px;
        }
        .catalog-row {
          grid-template-columns: 35px 1fr;
          gap: 20px;
          padding: 32px 0;
        }
        .catalog-row > div:last-child {
          grid-column: 2;
        }
        .catalog-row h2 {
          font-size: 29px;
        }
        .catalog-row h3 {
          font-size: 20px;
        }
        .service-grid {
          grid-template-columns: 1fr;
        }
        .approach-grid {
          padding: 25px;
        }
        .project-image img {
          height: 230px;
        }
        .team-grid {
          gap: 20px;
        }
        .initials {
          height: 150px;
          font-size: 45px;
        }
        .team-grid h3 {
          font-size: 22px;
        }
        .contact-grid {
          padding-bottom: 60px;
        }
        .form-card {
          padding: 25px;
        }
        .portal-shell {
          grid-template-columns: 1fr;
          gap: 0;
          padding: 10px;
          margin-top: 5px;
        }
        .portal-art {
          min-height: 230px;
          padding: 25px;
        }
        .portal-art .eyebrow {
          margin-bottom: 20px;
        }
        .portal-art h1 {
          font-size: 34px;
        }
        .portal-art p {
          font-size: 14px;
          margin-bottom: 0;
        }
        .portal-emblem,
        .art-bottom {
          display: none;
        }
        .portal-form {
          padding: 35px 15px;
        }
        .portal-form h2 {
          font-size: 29px;
        }
        .cookie-banner {
          left: 12px;
          right: 12px;
          bottom: 12px;
          padding: 20px;
          flex-direction: column;
          align-items: stretch;
          gap: 15px;
        }
        .cookie-actions {
          flex-direction: row;
        }
        .cookie-actions .button {
          flex: 1;
        }
        .dashboard-grid,
        .fields-grid {
          grid-template-columns: 1fr;
        }
        .dashboard-grid .full-span {
          grid-column: auto;
        }
        .dash-toolbar {
          align-items: flex-start;
        }
        .dash-card {
          padding: 22px;
        }
        .legal h2 {
          font-size: 24px;
        }
        .eyebrow {
          font-size: 11px;
        }
        .desktop {
          display: none;
        }
      }
      @media (prefers-reduced-motion: reduce) {
        html {
          scroll-behavior: auto;
        }
        * {
          transition: none !important;
          animation: none !important;
        }
      }

      /* Account access remains visible at every screen size. */
      .mobile-account-link {
        display: none;
      }
      .register-link {
        font-weight: 600;
      }
      .optional-profile {
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 22px;
      }
      .optional-profile summary {
        cursor: pointer;
        color: var(--green);
        font-size: 14px;
      }
      .register-shell .portal-art {
        align-self: stretch;
      }
      .register-shell .portal-form {
        padding-top: 35px;
        padding-bottom: 35px;
      }
      .register-shell .fields-grid {
        gap: 0 14px;
      }
      .approval-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        border-top: 1px solid var(--line);
        padding: 16px 0;
      }
      .approval-row p {
        margin: 4px 0 0;
        font-size: 14px;
      }
      @media (max-width: 1100px) {
        .nav-actions .portal-link {
          display: inline-block;
        }
        .nav-actions {
          gap: 14px;
        }
        .nav nav {
          gap: 15px;
        }
      }
      @media (max-width: 950px) and (min-width: 761px) {
        .nav-actions .small {
          display: none;
        }
      }
      @media (max-width: 760px) {
        .nav-actions .small {
          display: none;
        }
        .nav-actions {
          gap: 13px;
        }
        .nav-actions .portal-link {
          font-size: 13px;
        }
        .mobile-account-link {
          display: block;
        }
        .register-shell .portal-art {
          min-height: 230px;
        }
        .register-shell .fields-grid {
          grid-template-columns: 1fr;
        }
        .brand {
          font-size: 15px;
        }
        .brand-icon {
          font-size: 30px;
        }
        .nav {
          gap: 12px;
        }
        .menu-toggle {
          padding: 7px 9px;
        }
      }
      @media (max-width: 370px) {
        .brand-small {
          font-size: 8px;
        }
        .nav {
          gap: 8px;
        }
        .nav-actions {
          gap: 9px;
        }
        .nav-actions .portal-link {
          font-size: 12px;
        }
        .brand {
          gap: 4px;
          font-size: 13px;
        }
      }
    </style>
    <link
      rel="canonical"
      href="<?= esc(site_url('login'), 'attr') ?>"
    />
  </head>
  <body data-page="login">
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
          <a href="https://sun-son-solar.plovindino1.chatgpt.site/products/"
            >Products</a
          ><a href="https://sun-son-solar.plovindino1.chatgpt.site/services/"
            >Services</a
          ><a href="https://sun-son-solar.plovindino1.chatgpt.site/projects/"
            >Our approach</a
          ><a href="https://sun-son-solar.plovindino1.chatgpt.site/about/"
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
            href="https://sun-son-solar.plovindino1.chatgpt.site/contact/"
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
          <div class="portal-emblem" aria-hidden="true">☀</div>
          <div>
            <h1>Your workday.<br />A brighter start.</h1>
            <p>
              Sign in to your employee account.<br />Stay connected with Sun Son Solar.
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
            Your account must be approved by IT before you can sign in.
          </p>
          <form id="login-form">
            <label>Username <input type="text" name="username" required minlength="3" maxlength="80" autocomplete="username" autocapitalize="none" spellcheck="false" /></label>
            <label>Password <span class="password-wrap"><input type="password" name="password" required maxlength="72" autocomplete="current-password" /><button type="button" class="password-toggle" aria-label="Show password">Show</button></span></label>
            <p class="form-status" role="status" aria-live="polite" tabindex="-1"></p>
            <button type="submit" class="button full">Sign in</button>
            <p class="form-note">New to the team? <a class="underlink" href="<?= esc(site_url('register'), 'attr') ?>">Create an account</a></p>
          </form>
          <div class="login-help">
            <strong>Need help signing in?</strong>
            <p>
              Contact your IT administrator if your account is awaiting approval or you need help with your password.
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
          <a href="https://sun-son-solar.plovindino1.chatgpt.site/products/"
            >Solar products</a
          ><a href="https://sun-son-solar.plovindino1.chatgpt.site/services/"
            >Our services</a
          ><a href="https://sun-son-solar.plovindino1.chatgpt.site/projects/"
            >Project approach</a
          ><a href="https://sun-son-solar.plovindino1.chatgpt.site/about/"
            >Our company</a
          >
        </div>
        <div>
          <h3>Connect</h3>
          <a href="https://sun-son-solar.plovindino1.chatgpt.site/contact/"
            >Request a consultation</a
          ><a href="<?= esc(site_url('login'), 'attr') ?>"
            >Employee sign in</a
          ><a href="<?= esc(site_url('register'), 'attr') ?>">Employee registration</a
          ><a href="https://sun-son-solar.plovindino1.chatgpt.site/faq/"
            >Common questions</a
          >
        </div>
        <div>
          <h3>Information</h3>
          <a href="https://sun-son-solar.plovindino1.chatgpt.site/privacy/"
            >Privacy policy</a
          ><a href="https://sun-son-solar.plovindino1.chatgpt.site/terms/"
            >Terms & conditions</a
          ><a href="https://sun-son-solar.plovindino1.chatgpt.site/cookies/"
            >Cookie policy</a
          ><a href="https://sun-son-solar.plovindino1.chatgpt.site/refunds/"
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
      href="https://sun-son-solar.plovindino1.chatgpt.site/contact/"
      >Request a solar consultation</a
    >
    <section class="cookie-banner" aria-label="Cookie preferences" hidden>
      <div>
        <strong>Your privacy, your choice.</strong>
        <p>
          Essential storage keeps the portal secure. Optional analytics helps us
          understand website use.
          <a href="https://sun-son-solar.plovindino1.chatgpt.site/cookies/"
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
      bindForm("#login-form", "login", () => {
        window.location.assign(<?= json_encode(site_url('account'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
      });
    </script>
  </body>
</html>
