<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BurnNote | Invalid Link</title>
    <?php wp_head(); ?>
</head>
<body>
    <div class="burnnote-container">
      <div class="burnnote-header">
        <div class="burnnote-icon" role="img" aria-label="Secure lock icon"></div>
        <h2>Invalid Link</h2>
        <p class="burnnote-subtext">This link is invalid or has expired.</p>
      </div>
      
      <div class="burnnote-newnote-link">
        <a href="<?php echo esc_url(site_url()); ?>">← Create a new note</a>
      </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>