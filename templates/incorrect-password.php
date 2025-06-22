<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BurnNote | Incorrect Password</title>
    <?php wp_head(); ?>
</head>
<body>
    <div class="burnnote-container">
      <div class="burnnote-header">
        <div class="burnnote-icon" role="img" aria-label="Secure lock icon"></div>
        <h2>Incorrect Password</h2>
        <p class="burnnote-subtext">The password you entered is incorrect. Please try again.</p>
      </div>
      
      <div class="burnnote-newnote-link">
        <a href="<?php echo esc_url(add_query_arg('burnnote_view', $token, site_url())); ?>">← Try again</a>
      </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
