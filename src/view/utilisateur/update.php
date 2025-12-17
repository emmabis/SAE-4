<meta name="viewport" content="width=device-width, initial-scale=1.0">
<body class='update-body'>
<form class='leforumee' method="post" action="../web/frontController.php?action=updated&controller=utilisateur">
        <fieldset>
            <legend>Modification :</legend>
            <p>
                <?php
                    echo "<input type='text' value=$loginUpdate name='login' id='login_id' readonly required/>"
                ?>
            </p>
            <p class="InputAddOn">
                <label class="InputAddOn-itemee" for="mdp_id">Ancien mot de passe&#42;</label>
                <input class="InputAddOn-fieldee" type="password" value="" placeholder="" name="oldMdp" id="mdp_id" required>
            </p>
            <p class="InputAddOn">
                <label class="InputAddOn-itemee" for="mdp_id">Nouveau mot de passe&#42;</label>
                <input class="InputAddOn-fieldee" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>
            </p>
            <p class="InputAddOn">
                <label class="InputAddOn-itemee" for="mdp2_id">Vérification du nouveau mot de passe&#42;</label>
                <input class="InputAddOn-fieldee" type="password" value="" placeholder="" name="mdp2" id="mdp2_id" required>
            </p>
            <?php
            use App\SAE3\Lib\ConnexionUtilisateur;
                if (ConnexionUtilisateur::estAdministrateur()){
                    echo "<p class='InputAddOnee'>";
                    echo "<lab class='InputAddOn-itemee' for='estAdmin_id'>Administrateur</label>";
                    if(ConnexionUtilisateur::estUtilisateur($loginUpdate)){
                        echo "<input class='InputAddOn-fieldee' type='checkbox' placeholder='' name='estAdmin' id='estAdmin_id' checked>";
                    } else {
                        echo "<input class='InputAddOn-fieldee' type='checkbox' placeholder='' name='estAdmin' id='estAdmin_id'>";
                    }
                    echo "</p>";
                }
            ?>
            
            <p>
                <input class="submitee" type="submit" value="Envoyer" />
            </p>
        </fieldset>
    </form>
            </body>