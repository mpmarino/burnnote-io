<div class="burnnote-hero">
  <div class="burnnote-container">
    <div class="burnnote-header">
      <div class="burnnote-icon" role="img" aria-label="Secure lock icon"></div>
        <h2>Secure Notes That Self-Destruct</h2>
        <p class="burnnote-subtext">Create encrypted, one-time messages for sharing sensitive information safely.</p>
      </div>

      <form method="post" id="burnnote-form" autocomplete="off" novalidate>
        <textarea 
            name="burnnote_message" 
            rows="5" 
            placeholder="Enter your private message..." 
            required></textarea>

        <!-- Honeypot -->
        <input 
            type="text" 
            name="username" 
            autocomplete="off" 
            style="display:none" 
            aria-hidden="true"
        >

        <input 
            type="password" 
            name="new_burnnote_password" 
            placeholder="Optional password (leave blank for none)" 
            class="burnnote-password-field" 
            autocomplete="new-password"
        >

        <!-- Hidden input to hold token -->
        <input type="hidden" name="cf-turnstile-response" id="cf-turnstile-response">

        <!-- Invisible Turnstile widget with explicit ID -->
        <div 
            class="cf-turnstile" 
            id="cf-turnstile"
            data-sitekey="0x4AAAAAABg6XqsLoYvl5HNT" 
            data-callback="onTurnstileSuccess"
            data-theme="light"
            data-size="invisible">
        </div>

        <button type="submit" class="burnnote-submit-btn">
            Create Private Link
        </button>
      </form>
    </div>
  </div>

  <script data-cfasync="false">
      let formSubmittedByUser = false;

      function onTurnstileSuccess(token) {
          if (!formSubmittedByUser) return;
          document.getElementById('cf-turnstile-response').value = token;
          document.getElementById('burnnote-form').submit();
      }

      document.getElementById('burnnote-form').addEventListener('submit', function (e) {
          const token = document.getElementById('cf-turnstile-response').value;

          if (!token) {
              e.preventDefault();
              formSubmittedByUser = true;
              turnstile.execute(document.getElementById('cf-turnstile'));
          }
      });
  </script>
</div>
