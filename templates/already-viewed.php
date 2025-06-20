<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BurnNote | Note Already Viewed</title>
    <?php wp_head(); ?>
</head>
<body>
    <div class="burnnote-container">
      <div class="burnnote-header">
        <div class="burnnote-icon" role="img" aria-label="Secure lock icon"></div>
        <h2>Note Already Viewed</h2>
        <p class="burnnote-subtext">This note has already been viewed and is no longer available.</p>
      </div>
      
      <div class="burnnote-newnote-link">
        <a href="<?php echo site_url(); ?>">← Create a new note</a>
      </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
