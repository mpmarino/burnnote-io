<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>BurnNote | Your Private Link</title>
  <?php wp_head(); ?>
</head>
<body>
  <div class="burnnote-container">
    <h2>Your Private Link</h2>
    <div class="burnnote-link-box">
      <label for="burnnote-generated-link">Copy and share this link:</label>
      <div class="burnnote-link-row">
        <input id="burnnote-generated-link" type="text" value="<?php echo esc_url($link); ?>" readonly>
        <button type="button" id="burnnote-copy-btn">Copy</button>
      </div>
    </div>
    <div style="text-align: center; margin-top: 2rem;">
      <a href="<?php echo esc_url(get_permalink()); ?>" style="color:#4FBFA2; text-decoration: underline;">← Make a new note</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const copyBtn = document.getElementById('burnnote-copy-btn');
      const input = document.getElementById('burnnote-generated-link');
      if (copyBtn && input) {
        copyBtn.addEventListener('click', function () {
          input.select();
          document.execCommand('copy');
          copyBtn.innerText = 'Copied!';
          setTimeout(() => copyBtn.innerText = 'Copy', 4000);
        });
      }
    });
  </script>
  <?php wp_footer(); ?>
</body>
</html>
