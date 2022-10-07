<?php
require 'database.class.php';

$pdo = Database::connect();

require("./fPDF/fpdf.php");
//création d'une classe PDF
class PDF extends FPDF
{
    //header
    function Header()
    {
        $this->Image("../logo_societe.png", 8, 2);
        $this->SetFont("HELVETICA", "I", 11);
        $this->text(150, 10, date("d/m/y"));
        $this->text(150, 30, "Mr Sacha RESTOUEIX");
        $this->text(150, 35, "5 Avenue Albert Einstein");
        $this->text(150, 40, "19240 Le Saillant");
        $this->text(150, 45, "sacha@gmail.com");
        //saut de ligne
        $this->Ln(50);
    }
    //footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont("HELVETICA", "I", 9);
        //numéro de page 
        $this->Cell(0, 10, "Page" . $this->PageNo() . "/{nb}", 0, 0, "C");
    }
}
$pdf = new PDF("P", "mm", "A4");

//ajout 'dune nouvelle page
$pdf->AddPage();
$pdf->AliasNbPages();
//Creation de la fonction titre
function titre_doc($position_titre)
{
    global $pdf;
    $pdf->SetDrawColor(183);
    //fond de couleur(valeur rgb)
    $pdf->SetFillColor(221);
    $pdf->SetTextColor(0);
 //positionnenment du coin superieur gauche par rapport a la marge
    $pdf->SetX(20);
 // Texte : 60 >largeur ligne, 8 >hauteur ligne. Premier 0 >pas de bordure, 1 >retour à la ligneensuite, C >centrer texte, 1> couleur de fond ok  
    $pdf->Cell(60, 8, "FACTURE", 0, 1, "C", 1);
    $pdf->Ln(10);
}
//affichage titre
$position_titre=60;
//titre gras police helvetica de taille 11
$pdf->SetFont("HELVETICA", "B", 11);
$pdf->SetTextColor(0);
titre_doc($position_titre);

// Creation de la fonction entete_tableau
function entete_table($position_entete){
    global $pdf;
    $pdf->SetDrawColor(150);
    $pdf->SetFillColor(221);
    $pdf->SetTextColor(0);
    $pdf->SetY($position_entete);
    $pdf->SetX(20);
    $pdf->Cell(60,8,"Designation Articles",1,0,"C",1);
    $pdf->SetX(80);
    $pdf->Cell(60,8,"Prix Unitaire",1,0,"C",1);
    $pdf->SetX(140);
    $pdf->Cell(30,8,"Quantite",1,0,"C",1);
    $pdf->SetX(170);
    $pdf->Cell(30,8,"Prix Total HT",1,0,"C",1);
    $pdf->Ln();
}
//Affichage entete
$position_entete=80;
$pdf->SetFont("helvetica","I",10);
$pdf->SetTextColor(0);
entete_table($position_entete);

//ajout du tableau récapitulatif
function table1($position_table_l1){
    global $pdf;
    $pdf->SetDrawColor(150);
    $pdf->SetFillColor(255);
    $pdf->SetTextColor(0);
    $pdf->SetY($position_table_l1);
    $pdf->SetX(20);
    $pdf->Cell(60,8,"Camescope Sony DCR-PC330",1,0,"C",1);
    $pdf->SetX(80);
    $pdf->Cell(60,8, '1629 euros',1,0,"C",1);
    $pdf->SetX(140);
    $pdf->Cell(30,8,"2",1,0,"C",1);
    $pdf->SetX(170);
    $pdf->Cell(30,8,"3528 euros",1,0,"C",1);
    $pdf->Ln();
}

function table2($position_table_l2){
    global $pdf;
    $pdf->SetDrawColor(150);
    $pdf->SetFillColor(255);
    $pdf->SetTextColor(0);
    $pdf->SetY($position_table_l2);
    $pdf->SetX(20);
    $pdf->Cell(60,8,"Nikon F80",1,0,"C",1);
    $pdf->SetX(80);
    $pdf->Cell(60,8, '479 euros' ,1,0,"C",1);
    $pdf->SetX(140);
    $pdf->Cell(30,8,"5",1,0,"C",1);
    $pdf->SetX(170);
    $pdf->Cell(30,8,"2395 euros",1,0,"C",1);
    $pdf->Ln();
}
//Affichage tableau
$position_table_l1=88;
$position_table_l2=96;
$pdf->SetFont("helvetica","I",10);
$pdf->SetTextColor(0);
table1($position_table_l1);
table2($position_table_l2);

//Partie Total de la commande
function entete_total($position_total){
    global $pdf;
    $pdf->SetDrawColor(150);
    $pdf->SetFillColor(221);
    $pdf->SetTextColor(0);
    $pdf->SetY($position_total);
    $pdf->SetX(170);
    $pdf->Cell(30,8,"Total",1,0,"C",1);
    $pdf->Ln();
}
function total1($position_total1){
    global $pdf;
    $pdf->SetDrawColor(150);
    $pdf->SetFillColor(255);
    $pdf->SetTextColor(0);
    $pdf->SetY($position_total1);
    $pdf->SetX(140);
    $pdf->Cell(30,8,"HT",1,0,"C",1);
    $pdf->SetX(170);
    $pdf->Cell(30,8,"5863 euros",1,0,"C",1);
    $pdf->Ln();
}

function total2($position_total2){
    global $pdf;
    $pdf->SetDrawColor(150);
    $pdf->SetFillColor(221);
    $pdf->SetTextColor(0);
    $pdf->SetY($position_total2);
    $pdf->SetX(140);
    $pdf->Cell(30,8,"TTC",1,0,"C",1);
    $pdf->SetX(170);
    $pdf->Cell(30,8,"6783 euros",1,0,"C",1);
    $pdf->Ln();
}

//Affichage total
$position_total=160;
$position_total1=168;
$position_total2=176;
$pdf->SetFont("helvetica","I",10);
$pdf->SetTextColor(0);
entete_total($position_total);
total1($position_total1);
total2($position_total2);





//permet de tester l'affichage de la page sur le navigateur 
$pdf->Output('test.pdf', "I");

//e,registrement de la page dans le dossier pdf
$pdf->Output("F","./pdf/1ere page non dynamique.pdf");
//Pour enregistrer dans le dossier,ouvrir la page facture.php via WAMP sur le navigateur
//ça n'affiche rien si le 1er output est desactivé,

?>
