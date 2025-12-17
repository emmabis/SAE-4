<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../web/assets/css/create.css">
    <title>Formulaire Glassmorphism</title>

</head>
<body class='body-create'>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="post" action="../web/frontController.php?action=created&controller=utilisateur">
        <h3>Créer un compte</h3>

        <label for="login_id">Login</label>
        <input type="text" placeholder="Login" name="login" id="login_id" required />

        <label for="mdp_id">Mot de passe&#42;</label>
        <input class="letext" type="password" placeholder="Mot de passe" name="mdp" id="mdp_id" required />

        <label for="mdp2_id">Confirmer le mot de passe&#42;</label>
        <input class="letext" type="password" placeholder="Confirmer mot de passe" name="mdp2" id="mdp2_id" required />

        <?php
        use App\SAE3\Lib\ConnexionUtilisateur;
        if (ConnexionUtilisateur::estAdministrateur()) {
            echo "
            <div class='checkbox-group'>
                <input type='checkbox' name='estAdmin' id='estAdmin_id'>
                <label for='estAdmin_id'>Administrateur</label>
            </div>";
        }
        ?>

        <button type="submit">Envoyer</button>
    </form>
</body>
</html>
