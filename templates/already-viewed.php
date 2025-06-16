<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BurnNote | Note Already Viewed</title>
    <?php wp_head(); ?>
</head>
<body>
    <div class="burnnote-container">
        <h2>Note Already Viewed</h2>
        <p class="burnnote-subtext">This note has already been viewed and is no longer available.</p>
        <a href="<?php echo site_url(); ?>" style="display:inline-block;margin-top:1rem;color:#4FBFA2;text-decoration:underline;">← Create a new note</a>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
