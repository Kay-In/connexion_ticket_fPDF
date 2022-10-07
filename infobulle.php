<?php 

session_start();

include "database.class.php";

$pdo=Database::connect();

$cat=$_POST["id"];
$sql="CALL vente_cat(:categorie,@total_vente);select @total_vente";
$reponse=$pdo->prepare($sql);
$reponse->bindParam(":categorie",$cat);
$reponse->execute();

$donnees=$reponse->fetchAll();

 echo $donnees[0]["@total_vente"] ." € ";



?>