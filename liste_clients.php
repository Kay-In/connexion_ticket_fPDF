<?php

require "database.class.php";
require "header.php";

afficher_clients();



$reponse = afficher_clients();
?>

<h1 class="text-center">Listes des clients</h1>

<table class="table table-dark table-striped">
   <thead>
      <tr>
         <th class="table-dark">Civilités</th>
         <th class="table-dark">NOM</th>
         <th class="table-dark">Prénom</th>
         <th class="table-dark">Adresse</th>
         <th class="table-dark">Ville</th>
         <th class="table-dark">Mail</th>
      </tr>
   </thead>
   <?php while ($donnees = $reponse->fetch()) {
      // var_dump($donnees);
      echo "<tr><td>" . $donnees["civilite"] . "</td>";
      echo "<td><a href=facture_dynamique.php?id=" . $donnees["id_client"] . ">" . $donnees["nom"] . "</a></td>";
      echo "<td>" . $donnees["prenom"] . "</td>";
      echo  "<td>" . $donnees["adresse"] . "</td>";
      echo "<td>" . $donnees["code_postal"] . " " . $donnees["ville"] . "</td>";
      echo "<td>" . $donnees["mail"] . "</td>";
   } ?>
</table>
</body>

</html>