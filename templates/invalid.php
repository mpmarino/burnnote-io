<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BurnNote | Invalid Link</title>
    <?php wp_head(); ?>
</head>
<body>
    <div class="burnnote-container">
        <h2>Invalid Link</h2>
        <p class="burnnote-subtext">This link is invalid or has expired.</p>
        <a href="<?php echo esc_url(site_url()); ?>" style="display:inline-block;margin-top:1rem;color:#4FBFA2;text-decoration:underline;">← Create a new note</a>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
