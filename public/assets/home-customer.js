"use strict";

(() => {
  const api = document.body.dataset.apiUrl;
  let csrf = document.body.dataset.csrf || "";
  document.querySelectorAll("[data-home-signout]").forEach((button) => {
    button.addEventListener("click", async () => {
      button.disabled = true;
      try {
        if (!csrf) {
          const sessionResponse = await fetch(`${api}/session`, { credentials: "same-origin" });
          const session = await sessionResponse.json();
          if (!sessionResponse.ok || !session.csrf) throw new Error(session.error || "Please sign in again.");
          csrf = session.csrf;
        }
        const response = await fetch(`${api}/logout`, { method: "POST", credentials: "same-origin", headers: { "Content-Type": "application/json", "X-CSRF-Token": csrf }, body: "{}" });
        const result = await response.json();
        if (!response.ok) throw new Error(result.error || "Could not sign out.");
        location.assign(document.body.dataset.homeUrl);
      } catch (error) {
        button.disabled = false;
        window.alert(error.message);
      }
    });
  });
})();
