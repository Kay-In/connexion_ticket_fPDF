<?php

class Database {

    private static $dbName = 'base_magasin' ;
    private static $dbHost = 'localhost:3306' ;
    private static $dbUsername = 'root';
    private static $dbUserPassword = '';
    private static $connexion = null;
     
    public function __construct() { 
         die("Fonction d'initialisation pas permise !!!");        
    }
     
    
    public static function connect() { 
        if ( null == self::$connexion ) { 
            try { 
                self::$connexion = new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);  
            } 
            catch(PDOException $e) { 
                 die($e->getMessage()); 
        }
       } 
       return self::$connexion;
    }
     
    public static function disconnect()
    {
        self::$connexion = null;
    }
}

$donnees=null;

//liste clients
function afficher_clients(){
    $pdo=Database::connect();
    $sql="SELECT * FROM client";
    $reponse=$pdo->prepare($sql);
    $reponse->execute();
    

    return $reponse;
}

//appel données clients
function identite_utilisateurs($id_client){
    $pdo=Database::connect();
    $sql="SELECT * FROM client where id_client=:id";
    $data=$pdo->prepare($sql);
    $data->bindParam(":id",$id_client);
    $data->execute();
    $donnees=$data->fetch(PDO::FETCH_ASSOC);

    return $donnees;

}

//appel recap commande

function recap_commande($id_client){
    $pdo=Database::connect();
    $sql="SELECT article.designation, ligne.prix_unit, ligne.quantite 
    FROM article 
    INNER JOIN ligne 
    ON ligne.id_article = article.id_article 
    INNER JOIN commande 
    ON commande.id_comm = ligne.id_comm 
    where commande.id_client =:idclient";
    $reponse=$pdo->prepare($sql);
    $reponse->bindParam("idclient",$id_client);
    $reponse->execute();
    

    return $reponse;
}




?>