<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BurnNote | Your Message</title>
	<?php wp_head(); ?>
</head>
<body>
  <div class="burnnote-container">
    <div class="burnnote-header">
      <div class="burnnote-icon" role="img" aria-label="Secure lock icon"></div>
      <h2>Your Message</h2>
      <p class="burnnote-subtext"><em>This note has been burned and can no longer be accessed.</em></p>
    </div>

    <div class="burnnote-message-body">
      <p><?php echo nl2br(esc_html($message)); ?></p>
    </div>

    <div class="burnnote-newnote-link">
      <a href="<?php echo esc_url(site_url()); ?>">← Make a new note</a>
    </div>
  </div>
  <?php wp_footer(); ?>
</body>
</html>
