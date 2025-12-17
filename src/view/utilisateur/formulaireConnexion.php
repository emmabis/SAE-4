<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Glassmorphism</title>
    <link rel="stylesheet" href="../web/assets/css/connex.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body class='body-connexion'>
    <div id="connexion">
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="post" action="../web/frontController.php?action=connecter&controller=utilisateur" id="formulaireConnexion">
        <h3>Se connecter</h3>

        <label for="login_id">Login</label>
        <input type="text" placeholder="" name="login" id="login_id" required/>

        <label for="mdp_id">Mot de passe</label>
        <input type="password" placeholder="" name="mdp" id="mdp_id" required />

        <button type="submit">Se connecter</button>
        <br>
        <br>
        <p>Pas de compte ? <a href="../web/frontController.php?action=create&controller=utilisateur">S'inscrire</a></p>
    </form>
</div>
</body>
</html>
