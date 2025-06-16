<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BurnNote | Create a Note</title>
    <?php wp_head(); ?>
</head>
<body>
    <div class="burnnote-container">
        <h2>BurnNote</h2>
        <form method="post" autocomplete="off">
           <textarea 
    		name="burnnote_message" 
    		rows="5" 
    		placeholder="Enter your private message..." 
    		required></textarea>

            
            <!-- Honeypot field to catch bots -->
            <input 
                type="text" 
                name="username" 
                autocomplete="off" 
                style="display:none" 
                aria-hidden="true"
            >
            
            <input 
                type="password" 
                name="new_burnnote_password" 
                placeholder="Optional password (leave blank for none)" 
                class="burnnote-password-field" 
                autocomplete="new-password"
            >
            
            <button type="submit" class="burnnote-submit-btn">
                Create Private Link
            </button>
        </form>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
