<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Secure Link Created | BurnNote</title>
   <?php wp_head(); ?>
</head>
<body>
  <div class="burnnote-container">
    <div class="burnnote-header">
      <div class="burnnote-icon" role="img" aria-label="Secure lock icon"></div>
      <h2>Your Private Link</h2>
      <p class="burnnote-subtext" id="note-description">Send this secure note before it disappears.</p>
    </div>

    <div class="burnnote-link-box">
      <label for="burnnote-generated-link">Copy and share this link:</label>
      <div class="burnnote-link-row">
        <input id="burnnote-generated-link" type="text" value="<?php echo esc_url($link); ?>" readonly>
        <button type="button" id="burnnote-copy-btn">Copy</button>
        <button type="button" id="burnnote-open-btn" title="Open in new tab">↗</button>
      </div>
    </div>

    <div class="burnnote-newnote-link">
      <a href="<?php echo esc_url(get_permalink()); ?>">← Make a new note</a>
    </div>
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const copyBtn = document.getElementById('burnnote-copy-btn');
      const openBtn = document.getElementById('burnnote-open-btn');
      const input = document.getElementById('burnnote-generated-link');
      
      if (copyBtn && input) {
        copyBtn.addEventListener('click', function () {
          input.select();
          document.execCommand('copy');
          copyBtn.innerText = 'Copied!';
          setTimeout(() => copyBtn.innerText = 'Copy', 4000);
        });
      }
      
      if (openBtn && input) {
        openBtn.addEventListener('click', function () {
          window.open(input.value, '_blank');
        });
      }
    });
  </script>
  <?php wp_footer(); ?>
</body>
</html>
