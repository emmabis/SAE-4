<?php

namespace App\SAE3\Controller;

use App\SAE3\Lib\MotDePasse;
use App\SAE3\Lib\ConnexionUtilisateur;
use App\SAE3\Model\Repository\UtilisateurRepository;
use App\SAE3\Model\DataObject\Utilisateur;
use App\SAE3\Controller\GenericController;
use App\SAE3\Model\HTTP\Session;
use App\SAE3\model\DataObject\Meteotheque;
use App\SAE3\model\Repository\MeteothequeRepository;

class ControllerUtilisateur extends GenericController{
    
    public static function read() {
        $login = $_GET['login'] ?? null;
    
        if ($login) {
            $utilisateur = (new UtilisateurRepository())->select($login);
            if (is_object($utilisateur)) {
                $MeteothequeUser = (new MeteothequeRepository())->selectAllWhere("utilisateur",$login) ;
                self::afficheVue('utilisateur/detail.php', ['utilisateur' => $utilisateur,'MeteothequeUser'=>$MeteothequeUser], 'Détail utilisateur');
            } else {
                self::rediriger("../web/frontController.php?action=readAll&controller=station", "warning", "L'utilisateur n'existe pas !");
            }
        } else {
            self::rediriger("../web/frontController.php?action=readAll&controller=station", "warning", "Veuillez mettre un login.");
        }
    }

    public static function create() {
        self::afficheVue('utilisateur/create.php', [], 'Créer un utilisateur');
    }

    public static function created() {
        $admin = $_POST["estAdmin"];
        if ($_POST["mdp"]==$_POST["mdp2"]){
            if ($admin){
                if (ConnexionUtilisateur::estAdministrateur()){
                    $nvUtilisateur = Utilisateur::construireDepuisFormulaire($_POST);
                    (new UtilisateurRepository())->sauvegarder($nvUtilisateur);
    
                    self::rediriger("../web/frontController.php?action=readAll&controller=station", "success", "L'utilisateur a été créée avec succès !");
                } else {
                    self::rediriger("../web/frontController.php?action=readAll&controller=station", "danger", "Vous n'avez pas les permissions.");
                }
            } else {
                $nvUtilisateur = Utilisateur::construireDepuisFormulaire($_POST);
                (new UtilisateurRepository())->sauvegarder($nvUtilisateur);
    
                self::rediriger("../web/frontController.php?action=readAll&controller=station", "success", "L'utilisateur a été créée avec succès !");
            }
        } else {
            self::rediriger("../web/frontController.php?action=create&controller=utilisateur", "warning", "Le mot de passe doit être le même !");
        }
    }

    public static function update(){
        $login = $_GET['login'] ?? null;
        if ($login){
            if (ConnexionUtilisateur::estUtilisateur($login)||ConnexionUtilisateur::estAdministrateur()){
                self::afficheVue('utilisateur/update.php', ['loginUpdate'=>$login], 'Mettre à jour un utilisateur');
            } else {
                self::rediriger("../web/frontController.php?action=readAll&controller=station", "danger", "Ce compte ne vous appartient pas !");
            }
        }
    }

    public static function updated(){
        $login = $_POST['login'] ?? null;
        $ancienMdp = $_POST['oldMdp'] ?? null;
        $nouveauMdp = $_POST['mdp'] ?? null;
        $confirmationMdp = $_POST['mdp2'] ?? null;
        $estAdmin = isset($_POST['estAdmin']) ? 1 : 0;
        if (!empty($login) || !empty($ancienMdp) || !empty($nouveauMdp) || !empty($confirmationMdp)) {
            if (ConnexionUtilisateur::estUtilisateur($login)||ConnexionUtilisateur::estAdministrateur()){   
                if (!ConnexionUtilisateur::estAdministrateur()){
                    $estAdmin = 0;
                }
                if ($login=="Admin"){
                    self::rediriger("../web/frontController.php?action=update&controller=utilisateur&login=$login", "warning", "Vous ne pouvez pas modifier ce compte.");
                }
                $utilisateur = (new UtilisateurRepository())->select($login);
                if ($utilisateur instanceof Utilisateur) {
                    if (MotDePasse::verifier($_POST["oldMdp"],$utilisateur->getMdpHache())){
                        if ($_POST["mdp"]==$_POST["mdp2"]){
                            $majUtilisateur = Utilisateur::construireDepuisFormulaire($_POST);
                            (new UtilisateurRepository())->update($majUtilisateur);
                            self::rediriger("../web/frontController.php?action=readAll&controller=station", "success", "L'utilisateur a été mis à jour avec succès !");
                        } else{
                            self::rediriger("../web/frontController.php?action=update&controller=utilisateur&login=$login", "warning", "Le mot de passe doit être le même !");
                        }
                    } else{
                        self::rediriger("../web/frontController.php?action=update&controller=utilisateur&login=$login", "warning", "L'ancien mot de passe est faux !");
                    }
                }
            }
        } else {
            self::rediriger("../web/frontController.php?action=readAll&controller=utilisateur", "warning", "Veuillez remplir le formulaire correctement");
        }
    }

    public static function delete(){
        $login = $_GET['login'] ?? null;
        if ($login){
            if (ConnexionUtilisateur::estUtilisateur($login)){
                (new UtilisateurRepository())->Supprimer($login);
                self::rediriger("../web/frontController.php?action=readAll&controller=station", "success", "L'utilisateur a été supprimer avec succès !");
            } else {
                self::rediriger("../web/frontController.php?action=readAll&controller=station", "danger", "Ce compte ne vous appartient pas !");
            }
        }
    }

    public static function formulaireConnexion(){
        self::afficheVue('utilisateur/formulaireConnexion.php', [], 'Connexion utilisateur');
    }

    public static function connecter(){
        $login = $_POST['login'] ?? null;
        $mdp = $_POST['mdp'] ?? null;

        if (empty($login) || empty($mdp)) {
            self::rediriger("../web/frontController.php?action=formulaireConnexion&controller=utilisateur","danger","Le login et le mot de passe sont requis !"); 
        }else {
            $utilisateur = (new UtilisateurRepository())->select($login);
            if ($utilisateur){
                if ($utilisateur instanceof Utilisateur) {
                    if (MotDePasse::verifier($_POST["mdp"],$utilisateur->getMdpHache())){
                        ConnexionUtilisateur::connecter($login);
                        self::rediriger("../web/frontController.php?action=read&controller=utilisateur&login=$login", "success", "L'utilisateur est connecté !");
                    } else{
                        self::rediriger("../web/frontController.php?action=formulaireConnexion&controller=utilisateur", "warning", "Mot de passe erroné");
                    }
                }
            } else {
                self::rediriger("../web/frontController.php?action=formulaireConnexion&controller=utilisateur", "warning", "L'utilisateur existe pas");
            }
        }
    }

    public static function deconnecter(){
        if (ConnexionUtilisateur::estConnecte()){
            ConnexionUtilisateur::deconnecter();
            self::rediriger("../web/frontController.php?action=readAll&controller=station", "success", "L'utilisateur a été déconnecter !");
        }
    }

    public static function test(){
        $mdp = MotDePasse::hacher("test");
        self::afficheVue('utilisateur/test.php', ['mdp' => $mdp], 'Détail utilisateur');
    }

    public static function support(){
        //if (ConnexionUtilisateur::estConnecte()){
            self::afficheVue('utilisateur/support.php', [], 'Aide utilisateur');
        //}
    }

}


