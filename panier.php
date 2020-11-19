<?php 
require_once('inc/init.inc.php');

if(isset($_POST['ajout_panier']))
{
    // echo'<pre>';print_r($_POST);echo '</pre>';
    $r=$bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $r->bindValue(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
    $r->execute();

    $p=$r->fetch(PDO::FETCH_ASSOC);
    // echo'<pre>';print_r($p);echo '</pre>';

    // on ajoute dans la session un produit à la validation du formulaire dans le fichier fiche_produit.php
    ajoutPanier($p['id_produit'], $p['photo'], $p['reference'], $p['titre'], $_POST['quantite'], $p['prix']);
    
}
//SUPPRESSION PRODUIT DANS LE PANIER
if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{
    // on recupere l'indice auquel se trouve le produit que l'on souhaite supprimer du panier afin de personnaliser le essage de validation de suppression (ajout du titre et reference du produit)
    $positionProduit = array_search($_GET['id_produit'], $_SESSION['panier']['id_produit']); // [0]

    $vd = "<div class='bg-success col-md-3 mx-auto text-center text-white rounded p-2 mb-2'>Le produit titre <strong>" . $_SESSION['panier']['titre'][$positionProduit] . "</strong> référence <strong>" . $_SESSION['panier']['reference'][$positionProduit] . "</strong> a bien été supprimé du panier.</div>";

    suppProduit($_GET['id_produit']); // on transmet l'id_produit du produit a supprimer du panier a la fonction suppProduit(). C'est la methode array_splice qui supprime chaque ligne dans les tableau ARRAY de la session
}


// CONTROLE STOCK PRODUIT
// Si l'indice 'payer' est bien défini, cela veut dire que l'internaute a cliqué sur le bouton 'valiser le paiement' et par consequent que l'attribut naame 'payer' a été detecté
if(isset($_POST['payer']))
{
    //La boucle for tourne autant de fois qu'il y d'id_produit dans la session donc qu'il y a de produit dans le panier
    $error = '';
    for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
    {
        $r = $bdd->query("SELECT stock FROM produit WHERE id_produit = " . $_SESSION['panier']['id_produit'][$i]);
        $s= $r->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>'; print_r($s); echo '</pre>';

        //si la quantite du stock en bdd est < à la quantite dans la session, c'est a dire la qté commandé par l'internaute, alors on entre dans le if
        
        if($s['stock'] < $_SESSION['panier']['quantite'][$i])
        {
            $error .= "<div class='bg-danger col-md-8 mx-auto text-center rounded p-2 mb-2'>Stock restant du produit :  <strong>$s[stock]</strong></div>";

            $error .= "<div class='bg-success col-md-8 mx-auto text-center rounded p-2 mb-2'>Quantité demandée du produit :  <strong>" . $_SESSION['panier']['quantite'][$i] . "</strong></div>";

            // si le stock en BDD est superieur à 0 mais inferieur a la quantite demandé par l'internaute, alors on entre dans le if
            if($s['stock'] > 0)
            {
                $error .= "<div class='bg-danger col-md-3 mx-auto text-center text-white rounded p-2 mb-2'>La quantité du produit <strong>" . $_SESSION['panier']['titre'][$i] . "</strong> référence <strong>" . $_SESSION['panier']['reference'][$i] . "</strong> a été modifiée car notre stock est insuffisant, vérifiez vos achats. </div>";

                $_SESSION['panier']['quantite'][$i] = $s['stock'];
            }
            else // sinon le stock du produit est à 0, on entre dans la condition ELSE
            {
                $error .= "<div class='bg-danger col-md-5 mx-auto text-center text-white rounded p-2 mb-2'>Le produit <strong>" . $_SESSION['panier']['titre'][$i] . "</strong> référence <strong>" . $_SESSION['panier']['reference'][$i] . "</strong> a été supprimé car ce produit est en rupture de stock, vérifiez vos achats. </div>";

                suppProduit($_SESSION['panier']['id_produit'][$i]); // on supprime dans la session le produit en rupture de stock
                $i--;// on fait un tour de boucle en arriere, on décrémente, car array_splice() remonte les indices inférieurs vers les indices supérieurs, cela permet de ne pas oublier de controler un produit qui aurait remonté d'un indice dans le tableau ARRAY de la session
            }

            $e = true;
        }
    }
    // si la variable $e n'est as definit, cela veut dire que les stocks sont superieur à la quantité commandé par l'internaute, on entre dans le if
    if(!isset($e))
    {
        // ENREGISTREMENT DE LA COMMANDE
        $r = $bdd->exec("INSERT INTO commande (membre_id, montant, date_enregistrement) VALUES (" . $_SESSION['user']['id_membre'] . ", " . montantTotal() . ", NOW())");

        $idCommande = $bdd->lastInsertId();// permet de recuperer le dernier id_commande créé dans la bdd afin de l'enregistrer dans la table detail_commande, pour relier chaque produit à la bonne commande

        for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
        {   
            //pour chaque tour de boucle for, on execute une requete d'insertion dans la table details_commande pour chaque produit ajouté au panier
            //on recupere le dernier id_commande généré en BDD afin de relier chaque produit à la bonne commande dans la table details_commande
            $r = $bdd->exec("INSERT INTO details_commande (commande_id, produit_id, quantite, prix) VALUES ($idCommande, " . $_SESSION['panier']['id_produit'][$i] . ", " . $_SESSION['panier']['quantite'][$i] . ", " . $_SESSION['panier']['prix'][$i] . ")");

            //Depreciation des stocks
            //Modifie la table produit afin que le stock soit egal au stock de la BDD 
            $r = $bdd->exec("UPDATE produit SET stock = stock - " . $_SESSION['panier']['quantite'][$i] . " WHERE id_produit = " . $_SESSION['panier']['id_produit'][$i]);
        }
        unset($_SESSION['panier']); // on supprime les elements du panier de la session apres la validation du panier et l'insertion dans les tables 'commande' et 'details_commande'

        $_SESSION['num_cmd'] =$idCommande; // on stock l'id_commande dans la session apres validation du panier
        header('location: validation_cmd.php');// apres la validation du panier, on redirige l'internaute
    }
}

// echo'<pre>';print_r($_SESSION);echo '</pre>';

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>

<h1 class="display-4 text-center my-4">Mon panier</h1>

<?php 
if(isset($error)) echo $error;
if(isset($vd)) echo $vd;
?>


<table class="col-md-8 mx-auto table table-bordered text-center">
    <tr>
        <th>PHOTO</th>
        <th>REFERENCE</th>
        <th>TITRE</th>
        <th>QUANTITE</th>
        <th>PRIX unitaire</th>
        <th>PRIX total/produit</th>
        <th>SUPPRIMER</th>
    </tr>

    <?php if(empty($_SESSION['panier']['id_produit'])): //si l'indice 'id_produit' dans le panier de la session est vide ou non définie, on entre dans la condition if ?>
        <tr><td colspan="7" class="text-danger">Aucun produit dans le panier</td></tr>

</table>
    
    <?php else: // sinon des id_produits sont bien définit dans le panier de la session, on entre dans la condition ELSE et on affiche le contenu du panier ?>

        <!-- Ma boucle for tourne autour de fois que nous avons de produit dans le panier -->
        <?php for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++): ?>

            <tr>
                <!-- Pour chaque tour de boucle FOR, nous allons crocheter aux indices numériques des differents ARRAY dans la session afin d'afficher la photo, le titre,la reference etc... des produits ajoutés dans le panier -->
                <td><a href="fiche_produit.php?id_produit=<?=$_SESSION['panier']['id_produit'][$i]; ?>"><img src="<?=$_SESSION['panier']['photo'][$i]; ?>" alt="<?=$_SESSION['panier']['titre'][$i]; ?>" style="width : 100px;"></a></td>

                <td><?=$_SESSION['panier']['reference'][$i]; ?></td>

                <td><?=$_SESSION['panier']['titre'][$i]; ?></td>

                <td><?=$_SESSION['panier']['quantite'][$i]; ?></td>

                <td><?=$_SESSION['panier']['prix'][$i]; ?>€</td>

                <td><?=$_SESSION['panier']['quantite'][$i]*$_SESSION['panier']['prix'][$i]; ?>€</td>

                <td><a href="?action=suppression&id_produit=<?=$_SESSION['panier']['id_produit'][$i] ?>" class='btn btn-danger'><i class='far fa-trash-alt'></i></a></td>
            </tr>

        <?php endfor; ?>

        <tr>
            <th>MONTANT TOTAL</th>
            <td colspan="4"></td>
            <th><?= montantTotal(); ?>€</th>
            <td></td>
        </tr>   

</table>

<?php if(connect()): // si l'internaute est connecté, on lui propose de valider le paiement ?>

    <form action="" method="post" class="col-md-8 mx-auto pl-0">
        <input type="submit" name="payer" value="VALIDER LE PAIEMENT" class="btn btn-success">
    </form>

    <?php else: // sinon l'internaute n'est pas connecté, on le renvoi vers la page connexion ?>

    <a href="<?= URL ?>connexion.php" class="offset-md-2 btn btn-success mb-3">SE CONNECTER POUR POURSUIVRE LA COMMANDE</a>

<?php endif; ?>

<?php endif; ?>

<?php
require_once('inc/footer.inc.php');

