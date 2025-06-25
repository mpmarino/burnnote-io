<div class="burnnote-hero">
  <div class="burnnote-container">
    <div class="burnnote-header">
      <div class="burnnote-icon" role="img" aria-label="Secure lock icon"></div>
      <h2>Incorrect Password</h2>
      <p class="burnnote-subtext">The password you entered is incorrect.</p>
    </div>

    <div class="burnnote-error">
      <p>Please check your password and try again.</p>
    </div>

    <form method="post" id="burnnote-password-form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
      <input 
          type="password" 
          name="burnnote_password" 
          placeholder="Enter password" 
          class="burnnote-password-field" 
          required
          autocomplete="current-password"
      >
      <button type="submit" class="burnnote-submit-btn">
          Try Again
      </button>
    </form>

    <div class="burnnote-newnote-link">
      <a href="<?php echo esc_url(site_url()); ?>">← Make a new note</a>
    </div>
  </div>
</div>
