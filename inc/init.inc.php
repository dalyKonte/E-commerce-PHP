<?php

// CONNEXION BDD
$bdd = new PDO('mysql:host=localhost;dbname=boutique','root','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));


// $bdd = new PDO('mysql:host=sql200.epizy.com;dbname=epiz_27185178_boutique','epiz_27185178','vZGKRzjEZ8At', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// SESSION
session_start();

// CONSTANTE (chemin)
define("RACINE_SITE",$_SERVER['DOCUMENT_ROOT'] . '/PHP/09-boutique/');

// define("RACINE_SITE",$_SERVER['DOCUMENT_ROOT'] . '/');
//$_SERVER['DOCUMENT_ROOT'] --> c:/xampp/htdocs
// echo RACINE_SITE . '<hr>'; // c:/xampp/htdocs/PHP/9-boutique/

// Cette constante retourne le chemin physique du dossier 9-boutique sur le serveur local xampp.
//Lors de l'enregistrement d'une image/photo, nous aurons besoin du chemin physique complet vers le dossier photo sur le server pour enregistrer la photo dans le bon dossier
//On appel $_SERVER['DOCUMENT_ROOT'] parce que chaque server possede des chemins differents

define("URL","http://localhost/PHP/09-boutique/");

// define("URL","http://e-boutique.rf.gd/");
// Cette constante servira à enregistrer l'URL d'une photo/image dans la BDD

//INCLUSION
// En appelant init.inc sur chaque fichier nous incluons en meme tps les fonctions déclarées
require_once('fonctions.inc.php');