<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
        <title><?php echo htmlspecialchars($pagetitle, ENT_QUOTES, 'UTF-8'); ?></title>
        <link rel="stylesheet" href="../web/assets/css/list.css">
        <link rel="stylesheet" href="../web/assets/css/ChatBot.css">
        <script src="../web/assets/js/carousel.js"></script>
        <script src="../web/assets/js/ChatBot.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="icon" href="../web/assets/img/logo.png" type="image/x-icon">
    </head>
    <body>
        <header>
            <nav>
                <ul class="lagrossenavbar">
                    <li><a class='nava' href="../web/frontController.php?action=readAll&controller=station">Accueil</a></li>
                    <li><a class='nava' href="../web/frontController.php?action=choix&controller=station">Recherche</a></li>
                    <li><a class='nava' href="../web/frontController.php?action=readAll&controller=meteotheque">Meteotheques</a></li>
                    <li><a class='nava' href="../web/frontController.php?action=support&controller=utilisateur">Aide</a></li>
                    <?php 
                        use App\SAE3\Lib\ConnexionUtilisateur;
                        if (!ConnexionUtilisateur::estConnecte()){
                            echo "<li><a href='../web/frontController.php?action=formulaireConnexion&controller=utilisateur'>Connexion</a></li>";
                        } else{
                            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
                            echo "<li><a href='../web/frontController.php?action=read&controller=utilisateur&login=$login'>
                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                <path stroke-linecap='round' stroke-linejoin='round' d='M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z' />
                            </svg>
                        </a></li>";
                        echo "<li><a href='../web/frontController.php?action=deconnecter&controller=utilisateur&login=$login'>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75' />
                        </svg>
                    </a></li>";
                    echo '<li><a href="../web/frontController.php?action=AllFavori&controller=meteotheque">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
</svg>

                </a></li>';
                                        }
                    ?>
                </ul>
            </nav>
        </header>
        <main>
            <?php
            use App\SAE3\Lib\MessageFlash;

            $messagesFlash = MessageFlash::lireTousMessages();;
            if (isset($messagesFlash)) {
                foreach ($messagesFlash as $type => $messages) {
                    foreach ($messages as $message) {
                        echo "<div class=$type>{$message}</div>";
                    }
                }
            }
            require __DIR__ . "/{$cheminVueBody}";
            
            ?>
            <button id="chatButton">💬</button>
    
    <div id="chatContainer">
        <div id="chatHeader">
            <h3>Chat</h3>
        </div>

        <div id="messages"></div>

            <form id="messageForm">
                <input type="text" id="message" placeholder="Type a message..." />
                <button type="submit">→</button>
            </form>
        </div>
        </main>
    </body>
</html>