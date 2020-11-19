<?php 
require_once('inc/init.inc.php');
//Si l'indice 'id_produit' est bien definie dans l'URL et que sa valeur n'est pas vide, cela veut dire que l'internaute a cliquer sur un lien 'voir le detail' et par consequent transmis les parametres ex:'id_produit'
if(isset($_GET['id_produit']) && !empty($_GET['id_produit']))
{   
    //on selectionne tout en bdd par rapport à l'id_produit transmis dans l'URL,afin d'afficher le detail d'un produit
    $r = $bdd ->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $r->bindValue(':id_produit',$_GET['id_produit'], PDO::PARAM_INT);
    $r->execute();

    // si la requete de selection retourne 1 resultat de la bdd, cela veut dire que l'id_produit transmis dans l'URL est connu en BDD, alors on entre dans la condition IF
    if($r->rowCount())
    {
        //retourne un array avec les données du produit à afficher sur la page fiche_produit en fonction de l'id_produit dans l'URL
        $p = $r->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>'; print_r($p); echo '</pre>';
    }
    else// sinon l'id_produit transmis dans l'URl n'est pas connu en bdd, on redirige l'internaute vers la page boutique
    {
        header('location: boutique.php');
    }
}
else
{
    header('location: boutique.php');
}


   // 1. realiser le traitement SQL + PHP permettant de selectionner les données du produit par rapport à l'id_produit transmis dans l'URL
       
    // 2.Faites en sorte que si l'id_produit n'est pas definit ou sa valeur est vide, de re-diriger vers la page boutique
    // 3.si la requete de selection ne retourne aucun produit de la BDD, faites en sorte de re-diriger vers la page boutique
    // 4.afficher les details du produit dans l'affichage html, dans les div ci dessous




require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Exo : afficher la liste des categories stockées en BDD, chaque lien de categorie renvoie vers la page boutique à la bonne categorie -->
        
        <div class="col-lg-3">
        <?php
         $d= $bdd->query("SELECT DISTINCT categorie FROM produit")
         ?>
    
            <h1 class="my-4 text-center">Nouvelle collection</h1>
            <div class="list-group text-center">
                <li class="list-group-item bg-dark text-white">CATEGORIES</li>
                <?php while($c = $d->fetch(PDO::FETCH_ASSOC)): 
                // echo '<pre>'; print_r($c); echo '</pre>';
                // FETCH() retourne un ARRAY par tour de boucle WHILE contenant les données d'une categorie
                ?>

                <a href="boutique.php?categorie=<?= $c['categorie'] ?>" class="list-group-item text-dark"><?= $c['categorie'] ?></a> <!-- la boucle crée un lien par categorie pour chaque tour de boucle -->
                
            <?php endwhile; ?>
            </div>
        </div>
        <!-- /.col-lg-3 -->

        <div class="col-lg-9">

            <div class="card mt-4">
                <img class="card-img-top img-fluid" src="<?= $p['photo'] ?>" alt="<?= $p['titre'] ?>">
                <div class="card-body">
                    <h3 class="card-title">Product Name</h3>

                    <h4><?= $p['prix'] ?>€</h4>

                    <p class="card-text"><?= $p['description'] ?></p>

                    <p class="card-text">Catégorie : <a href="boutique.php?categorie=<?= $p['categorie'] ?>"><?= $p['categorie'] ?></a></p>

                    <p class="card-text"><?= $p['reference'] ?></p>

                    <p class="card-text"><?= $p['couleur'] ?></p>

                    <p class="card-text"><?= $p['taille'] ?></p>

                    <p class="card-text"><?= $p['public'] ?></p>

                    <?php if($p['stock'] <= 10 && $p['stock'] != 0): ?> <!-- si le stock du produit est inferieur ou egale a 10 et que le stock est different de 0 on entre dans la condition IF-->

                        <p class="card-text font-italic text-danger">Attention ! Il ne reste plus que <?= $p['stock'] ?> exemplaire(s) en stock. </p>

                    <?php elseif($p['stock'] > 10): ?> <!-- sinon si > 10 on entre dans le elseif-->

                        <p class="card-text font-italic text-success"><strong>En stock !</strong></p>

                    <?php endif; ?>

                    <hr>

                    <?php if($p['stock'] > 0): ?> <!-- si le stock du produit est superieur , on entre dans le IF et l'internaute peut choisir et ajouter le produit dans le panier-->

                    <form method="post" action="panier.php" class="form-inline">
                        <input type="hidden" id="id_produit" name="id_produit" value="<?= $p['id_produit'] ?>">
                        <div>
                            <select class="form-group" id="quantite" name="quantite"> 
                            <?php for($i = 1; $i <= $p['stock'] && $i <= 30; $i++): ?> 
                                
                                <option value="<?= $i ?>"><?= $i ?></option>

                            <?php endfor; ?>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-dark ml-2" name="ajout_panier" value="AJOUTER AU PANIER">
                    </form>


                    <?php else: ?> <!-- sinon le stock est a 0, on entre dans le ELSE, on affiche un message d'erreur-->

                        <p class="card-text font-italic text-danger"><strong>En rupture de stock !</strong></p>

                    <?php endif; ?>



                </div>
            </div>
            <!-- /.card -->

            <div class="card card-outline-secondary my-4">
                <div class="card-header">
                    Derniers avis...
                </div>
                <div class="card-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                    <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                    <hr>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                    <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                    <hr>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                    <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                    <hr>
                    <a href="#" class="btn btn-success">Leave a Review</a>
                </div>
            </div>
            <!-- /.card -->

        </div>
        <!-- /.col-lg-9 -->

    </div>

    </div>
    <!-- /.container -->

    <!-- Footer -->
    <footer class="py-5 bg-dark">
    <div class="container">
    <p class="m-0 text-center text-white">Copyright &copy; Your Website 2020</p>
</div>
<!-- /.container -->

<?php
require_once('inc/footer.inc.php');