<link rel="stylesheet" href="../web/assets/css/support.css">
<?php
    use App\SAE3\Lib\ConnexionUtilisateur;
?>
<div id="userData" data-client="<?php echo ConnexionUtilisateur::getLoginUtilisateurConnecte(); ?>"></div>
<div id="supportChatContainer" class="support-container">
    <div id="supportChatHeader" class="support-header">
        <h3>Chat</h3>
        <span id="supportStatus" class="support-status">Not Connected</span>
    </div>
    
    <div id="supportRoomSection" class="support-room-section">
        <form id="supportRoomChoice" class="support-room-form<?php if (ConnexionUtilisateur::estAdministrateur()){ echo " active";} ?>">
            <input type="text" id="supportRoomInput" class="support-input" placeholder="Choose a room..." />
            <button type="submit" class="support-button">Join</button>
        </form>
    </div>
        
    <div id="supportMessages" class="support-messages-container"></div>
        
    <form id="supportMessageForm" class="support-message-form">
        <input type="text" id="supportMessage" class="support-message-input" placeholder="Type a message..." />
        <button type="submit" class="support-submit-button">→</button>
    </form>
</div>
<script src="../web/assets/js/support.js"></script>