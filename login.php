<?php
include "database.class.php";
include "header.php";


$pdo = Database::connect();
session_start();

$titre="Connexion";

$login = isset($_POST["login"]) ? $_POST["login"] : null;
$mdp = isset($_POST["mdp"]) ? $_POST["mdp"] : null;
$valider = isset($_POST["submit"]) ? $_POST["submit"] : null;
$erreur = null;
// $session = '"location:index.php';
$role = null;


if (isset($valider)) {
    $sql = "select * from utilisateurs where login=:login and mdp=:mdp";
    $reponse = $pdo->prepare($sql);
    $reponse->bindParam(":login", $login);
    $reponse->bindParam(":mdp", $mdp);
    $reponse->execute();
    $donnees = $reponse->fetchALL();

// var_dump($donnees);

    if (count($donnees) > 0) {
        // $_SESSION["autoriser"] = "oui";
        $_SESSION["role"] = $donnees[0]["id_role"];


        /********************************************************************************************************* */

        //  creation du ticket //




        /*********************************************************************************************************** */
        $cookie_name = "ticket";
        // On génère quelque chose d'aléatoire
        $ticket = session_id() . microtime() . rand(0, 9999999999);
        // On hash pour avoir un ID qui aura toujours la même forme
        $ticket = hash('sha512', $ticket);
        // On vérifie que le Navigateur du Client accepte les cookies
        if (!isset($_COOKIE[$cookie_name])) {
            echo "<p>Le navigateur n'accepte pas les cookies</p>";
        }
        // On enregistre des deux cotés
        setcookie($cookie_name, $ticket, time() + (60 * 20)); // Expire au bout de 20 min

        $_SESSION['ticket'] = $ticket;
        /********************************************************************************************************** */
        header("location:index.php");
        // var_dump($donnees[0]);
        $erreur = "";
    } else {
        $erreur = "Le login ou le mot de passe ne correspondent pas";
    }
}
?>
<form action="<?= $_SERVER['SCRIPT_NAME']; ?>" method="POST">
    <h1>Connexion au Magasin</h1>
    <label for="login">Login utilisateur</label>
    <input type="text" name="login" value="">
    <label for="mdp">Mot de Passe</label>
    <input type="password" name="mdp" value="">
    <input type="submit" name="submit" value="LOGIN">
    <p id="erreur"><?php
                    echo $erreur;
                    ?></p>
</form>