<?php

// FONCTION INTERNAUTE CONNECTE
// Cette fonction permet de savoir si l'utilisateur est conecté ou non
function connect()
{
    // si l'indice 'user' dans la session n'est pas defini cela veut dire que l'internaute n'est pas passé par la page connexion (c'est dans cette page que est créé l'indice 'user' dans la session), cela veut dire que l'internaute n'est pas connecté et n'est peut etre pas inscrit sur le site
    if(!isset($_SESSION['user']))
    {
        return false;
    }
    else // SINON, l'indice 'user' est bien defini dans la session donc l'internaute est bien connecté
    {
        return true;
    }
}

// FONCTION INTERNAUTE ADMINISTRATEUR
function adminConnect()
{   // si l'internaute est connecté et que l'indice statut dans la session a pour valeur 1 cela veut dire que l'internaute est administrateur du site 
    if(connect() && $_SESSION['user']['statut']==1)
    {
        return true;
    }
    else // sinon, le statut de l'utilisateur dans la session n'a pas pour valeur 1, donc l'internaute n'est pas administrateur et peut-etre pas connecté
    {
        return false;
    }
}

// FONCTION CREATION DU PANIER DANS LA SESSION
//Les données du panier ne sont jamais conservés en BDD, beaucoup de panier n'aboutissent jamais
// donc nous allons stocker les information du panier directement dans le fichier session de l'utilisateur
//dans la session, nous definissons different tableaux array qui permettront de stocker par exemple toute les reference des produits ajoutés au panier dans un array
function creationPanier()
{
    //si l'indice panier n'est pas defini on le crée
    if(!isset($_SESSION['panier']))
    {
        $_SESSION['panier'] = array();// creation d'un tableau array dans la session à l'indice panier
        $_SESSION['panier']['id_produit'] = array();
        $_SESSION['panier']['photo'] = array();
        $_SESSION['panier']['reference'] = array();
        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['prix'] = array();
        
    }
}

// FONCTION AJOUTER PRODUIT DANS LA SESSION
// Les parametres définit dans la fonction permettront de receptionner les informations dans le panier afin de stocker chaque donnée dans les differents tableau ARRAY
function ajoutPanier($id_produit, $photo, $reference, $titre, $quantite, $prix)
{   
    creationPanier(); // On controle si le panier est créé dans la session ou non ($_SESSION['panier'])
    
    //array_search() permet de trouver a quel indice se trouve un element dans un tableau ARRAY
    //On demande à Array_search() de trouver à quel indice se trouve l'id_produit qui vient d'etre ajouté dans le panier
    //                                  4              ARRAY
    $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']); // false

    //si la variable $positionProduit est differente de false, cela veut dire que array_search a bien trouvé l'indice du produit dans la session
    //false
    if($positionProduit !== false)
    {
        //$_SESSION['panier']['quantite'][1] += 2;
        $_SESSION['panier']['quantite'][$positionProduit] += $quantite;
        //On modifie la quantite du produit à l'indice correspondant, retourné par array_search()
          // Chaque indice numérique dans les tableaux 'photo,reference, prix' etc... correspondent au même produit ajouté dans le panier 
    }
    else
    {
         // les crochets vide[] permettent de generer des indices numerique dans les tableaux array
    //ex: $_SESSION['panier']['id_produit'][0]=$29;
    $_SESSION['panier']['id_produit'][]=$id_produit;
    $_SESSION['panier']['photo'][]=$photo;
    $_SESSION['panier']['reference'][]=$reference;
    $_SESSION['panier']['titre'][]=$titre;
    $_SESSION['panier']['quantite'][]=$quantite;
    $_SESSION['panier']['prix'][]=$prix;
    }
   
}

// FONCTION MONTANT TOTAL PANIER
function montantTotal()
{
    $total = 0 ;
    // La boucle for tourne autant de fois qu'il y a d'id_produit dans la session, donc autant qu'il y a de produit dans le panier
    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
        
    }
    return round($total, 2); // on arrondi le total à 2 chiffres apres la virgule
}

// FUNCTION SUPRESSION PRODUIT DANS PANIER
function suppProduit($id_produit)
{
    //On transmet à la fonction prédéfinie array_search(), l'id_produit du produit en rupture de stock
    // array_search() retourne l'indice du tableau ARRAY auquel se trouve l'id_produit à supprimer
    $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    // si la valeur de $positionProduit est differente de false, cela veut dire que l'id_produit supprimé a bien été trouvé dans le panier de la session
    if($positionProduit !== false)
    {
        // array_splice() permet de supprimer des elements d'un tableau ARRAY
        // on supprime chaque ligne dans les tableaux du produit en rupture de stock
        // array_splice() ré-organise les tableaux ARRAY, c'est a dire que tout les elements aux indices inferieurs remonttent aux indices superieurs, le produit stocké à l'indice 3 du tableau ARRAY remonte à l'indice 2 du tableau ARRAY
        array_splice($_SESSION['panier']['id_produit'], $positionProduit, 1); // [1]
        array_splice($_SESSION['panier']['photo'], $positionProduit, 1);
        array_splice($_SESSION['panier']['reference'], $positionProduit, 1);
        array_splice($_SESSION['panier']['titre'], $positionProduit, 1);
        array_splice($_SESSION['panier']['quantite'], $positionProduit, 1);
        array_splice($_SESSION['panier']['prix'], $positionProduit, 1);
    }
}

/*
    array
    (
        [user] => ARRAY(infos de l'utilisateur connecté)

        [panier] => array(
                
                [id_produit] =>array(
                            0 => 15
                            1 => 40 
                        )

                [reference] => array(
                            0 => 12A45
                            1 => 46F56
                        )

                [photo] => array(
                            0 => http://localhost/PHP/09-boutique/photo/img.jpg
                            1 => http://localhost/PHP/09-boutique/photo/img3.jpg
                        )
        )
    )
*/
