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
        <h2>This Burn Note Has Already Been Viewed</h2>
        <p class="burnnote-subtext">This message is no longer available. For your privacy, Burn Note does not store or archive messages after they’re viewed.</p>
      </div>
      
      <div class="burnnote-newnote-link">
        <a href="<?php echo site_url(); ?>">← Create a new note</a>
      </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>