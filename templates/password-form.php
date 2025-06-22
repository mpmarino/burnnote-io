<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BurnNote | Password Required</title>
    <?php wp_head(); ?>
</head>
<body>
<div class="burnnote-container">
  <div class="burnnote-header">
    <div class="burnnote-icon" role="img" aria-label="Secure lock icon"></div>
    <h2>Password Required</h2>
    <p class="burnnote-subtext">This note is protected. Please enter the password to continue.</p>
  </div>
  
  <form method="post" autocomplete="off">
    <input type="text" name="username" autocomplete="off" style="display:none" aria-hidden="true">
    <input type="password" name="burnnote_password" placeholder="Enter password" class="burnnote-password-field" required autocomplete="new-password">
    <button type="submit" class="burnnote-submit-btn">View Note</button>
  </form>
  
  <div class="burnnote-newnote-link">
    <a href="<?php echo esc_url(site_url()); ?>">← Make a new note</a>
  </div>
</div>
<?php wp_footer(); ?>
</body>
</html>
