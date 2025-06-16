<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>BurnNote | Your Message</title>
  <?php wp_head(); ?>
</head>
<body>
  <div class="burnnote-container">
    <h2>Your Message:</h2>
    <p><?php echo $message; ?></p>
    <p class="burnnote-subtext"><em>This note has now been deleted.</em></p>
    <a href="<?php echo esc_url(site_url()); ?>" style="display:inline-block;margin-top:1rem;color:#4FBFA2;text-decoration:underline;">← Make a new note</a>
  </div>
  <?php wp_footer(); ?>
</body>
</html>
