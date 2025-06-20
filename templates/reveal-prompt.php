<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BurnNote | Your Message</title>
    <?php wp_head(); ?>
</head>
<body>
  <div class="burnnote-container">
    <div class="burnnote-header">
      <div class="burnnote-icon" role="img" aria-label="Secure lock icon"></div>
      <h2>Your secure message is ready.</h2>
      <p class="burnnote-subtext">Be careful — this note can only be viewed once.</p>
    </div>

    <div class="burnnote-reveal-section">
      <button type="button" id="burnnote-reveal-btn" class="burnnote-reveal-btn">
        Reveal Note
      </button>
    </div>

    <div class="burnnote-newnote-link">
      <a href="<?php echo esc_url(site_url()); ?>">← Make a new note</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const revealBtn = document.getElementById('burnnote-reveal-btn');
      const container = document.querySelector('.burnnote-container');
      
      if (revealBtn) {
        revealBtn.addEventListener('click', function () {
          // Show loading state
          revealBtn.innerHTML = '<span class="loading-spinner"></span> Revealing...';
          revealBtn.disabled = true;
          
          // Get token from current URL
          const urlParams = new URLSearchParams(window.location.search);
          const token = urlParams.get('burnnote_view');
          
          // Redirect to the actual message view
          window.location.href = '<?php echo esc_url(site_url()); ?>?burnnote_reveal=' + encodeURIComponent(token);
        });
      }
    });
  </script>
  <?php wp_footer(); ?>
</body>
</html> 
