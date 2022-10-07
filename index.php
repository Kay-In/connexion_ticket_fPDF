<?php
require "database.class.php";
require "header.php";

$titre = "Liste des Articles";


session_start();
/********************************************************************************************************** */

//controle de ticket
// var_dump($_SESSION);

if (/*($_SESSION["autoriser"] = "oui") && */($_COOKIE['ticket'] == $_SESSION['ticket'])) {
   $ticket = session_id() . microtime() . rand(0, 999999999999);
   $ticket = hash('sha512', $ticket);
   $_COOKIE['ticket'] = $ticket;
   $_SESSION['ticket'] = $ticket;
} else {
   $_SESSION = array();
   session_destroy();
   header("location:login.php");
   exit();
}

/***************************************************************************************************************** */
//creation de tableau

// var_dump($_SESSION["role"]);
$pdo = Database::connect();

if ($_SESSION["role"] == 3) {

   $sql = "CALL qte_vendue()";
   $reponse = $pdo->prepare($sql);
   $reponse->execute();
?>
   <h1 class="text-center">Liste des Articles</h1>

   <table class="table table-dark table-striped">
      <thead>
         <tr>
            <th class="table-dark">ID Article</th>
            <th class="table-dark">Désignation</th>
            <th class="table-dark">Prix Unitaire(en &euro;)</th>
            <th class="table-dark">Catégorie</th>
            <th class="table-dark">Quantité Vendue</th>

         </tr>
      </thead>
      <?php while ($donnees = $reponse->fetch()) {
         // var_dump($donnees);
         echo "<tr><td>" . $donnees["id_article"] . "</td>";
         echo "<td>" . $donnees["designation"] . "</td>";
         echo "<td>" . $donnees["prix"] . "</td>";
         echo  "<td><a class='cat' href='#' name='" . $donnees["id_article"] . "' id='" . $donnees["categorie"] . "' title=''>" . $donnees["categorie"] . "</a></td>";
         echo "<td>" . $donnees["sum(quantite)"] . "</td>";
      } ?>
   </table>
   <a href="fin.php">deconnexion</a>

<?php
} else {

   $sql = "CALL qte_vendue()";
   $reponse = $pdo->prepare($sql);
   $reponse->execute();
?>

   <h1 class="text-center">Liste des Articles</h1>
   <a href="add.php">Ajouter un article</a>
   <a href="liste_clients.php">listing des clients</a>
   <table class="table table-dark table-striped">
      <thead>
         <tr>
            <th class="table-dark">ID Article</th>
            <th class="table-dark">Désignation</th>
            <th class="table-dark">Prix Unitaire(en &euro;)</th>
            <th class="table-dark">Catégorie</th>
            <th class="table-dark">Quantité Vendue</th>
            <th class="table-dark">Modifier</th>
            <th class="table-dark">Supprimer</th>
         </tr>
      </thead>
      <?php while ($donnees = $reponse->fetch()) {
         // var_dump($donnees);
         echo "<tr><td>" . $donnees["id_article"] . "</td>";
         echo "<td>" . $donnees["designation"] . "</td>";
         echo "<td>" . $donnees["prix"] . "</td>";
         echo "<td><a class='cat' href='#' name='" . $donnees["id_article"] . "' id='" . $donnees["categorie"] . "' title=''>" . $donnees["categorie"] . "</a></td>";
         echo "<td>" . $donnees["sum(quantite)"] . "</td>";
         echo "<td><a href='modif.php'>Modifier</a></td>";
         echo "<td><a href='modif.php'>Supprimer</a></td></tr>";
      } ?>
   </table>
   <a href='fin.php'>deconnexion</a>
<?php
}

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
   $(document).ready(function() {

      $(".cat").on("mouseover", function(e) {

         var name = $(this).attr('name');
         var id = e.target.id;

         $.ajax({

            url: 'infobulle.php',
            type: 'POST',
            data: {
               id: id
            },


            success: function(response) {
               console.log(response);
               $("a[name = " + name + "]").attr("title", "Gain pour la catégorie " + id + ": " + response);
            }
         });
      });

   });
</script>


</body>

</html>