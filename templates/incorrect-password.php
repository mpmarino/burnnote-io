<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BurnNote | Incorrect Password</title>
    <?php wp_head(); ?>
</head>
<body>
    <div class="burnnote-container">
        <h2>Incorrect Password</h2>
        <p class="burnnote-subtext">The password you entered is incorrect. Please try again.</p>
        <a href="<?php echo esc_url(add_query_arg('burnnote_view', $token, site_url())); ?>" style="display:inline-block;margin-top:1rem;color:#4FBFA2;text-decoration:underline;">← Try again</a>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
