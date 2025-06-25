<div class="burnnote-hero">
  <div class="burnnote-container">
    <div class="burnnote-header">
      <div class="burnnote-icon" role="img" aria-label="Secure lock icon"></div>
      <h2>Password Required</h2>
      <p class="burnnote-subtext">This note is protected with a password.</p>
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
          Unlock Note
      </button>
    </form>

    <div class="burnnote-newnote-link">
      <a href="<?php echo esc_url(site_url()); ?>">← Make a new note</a>
    </div>
  </div>
</div>
