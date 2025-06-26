document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('burnnote-create-form');
  const output = document.getElementById('burnnote-link-output');

  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(form);
      formData.append('action', 'burnnote_create');
      formData.append('nonce', burnnote_ajax.nonce);

      fetch(burnnote_ajax.ajax_url, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success && data.data.link) {
          output.innerHTML = `
            <div class='burnnote-link-box'>
              <label for='burnnote-generated-link'>Your private link:</label>
              <div class='burnnote-link-row'>
                <input id='burnnote-generated-link' type='text' value='${data.data.link}' readonly>
                <button type='button' id='burnnote-copy-btn'>Copy</button>
              </div>
            </div>
          `;
          form.reset();

          const copyBtn = document.getElementById('burnnote-copy-btn');
          const input = document.getElementById('burnnote-generated-link');

          copyBtn?.addEventListener('click', function () {
            input.select();
            document.execCommand('copy');
            copyBtn.innerText = 'Copied!';
            setTimeout(() => copyBtn.innerText = 'Copy', 5000);
          });
        } else {
          output.innerHTML = '<p style="color: red;">There was an error creating the note. Please try again.</p>';
        }
      });
    });
  }
});
