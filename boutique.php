<?php 
require_once('inc/init.inc.php');
//Si l'indice categorie est bien definie dans l'URL et que sa valeur n'est pas vide, cela veut dire que l'internaute a cliquer sur un lien de categorie et par consequent transmis les parametres 'categorie=tee-shirt'
if(isset($_GET['categorie']) && !empty($_GET['categorie']))
{
    //on selectionne tout en BDD par rapport a la categorie transmise dans l'URL, afin d'afficher tout les produits liés à la categorie
    $r = $bdd->prepare("SELECT * FROM produit WHERE categorie = :categorie");
    $r->bindValue(':categorie', $_GET['categorie'], PDO::PARAM_STR);
    $r->execute();

    // si la requete de selection ne retourne pas de resultat, que rowCount retourne false, cela veut dire que la categorie dans l'URL n'est pas connu en BDD, on redirige l'internaute vers la boutique.
    if(!$r->rowCount())
    {
        header('location: boutique.php');
    }
}
else // sinon l'indice id_produit n'est pas défini dans l'URL ou sa valeur est vide, alors on entre dans la condition ELSE et on selectionne l'ensemble de la table produit
{
    $r= $bdd->query("SELECT * FROM produit");
}

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <div class="col-lg-3">

        <?php
        // On selectionne les categories DISTINCT (elimine les doublons) dans la BDD
        $d= $bdd->query("SELECT DISTINCT categorie FROM produit")

        ?>

            <h4 class="my-4 text-center">Nouvelle collection !</h4>
            <div class="list-group text-center">
            <li class="list-group-item bg-dark text-white">CATEGORIES</li>
            <?php while($c = $d->fetch(PDO::FETCH_ASSOC)): 
                // echo '<pre>'; print_r($c); echo '</pre>';
                // FETCH() retourne un ARRAY par tour de boucle WHILE contenant les données d'une categorie
                ?>

                <a href="?categorie=<?= $c['categorie'] ?>" class="list-group-item text-dark"><?= $c['categorie'] ?></a> <!-- la boucle crée un lien par categorie pour chaque tour de boucle -->
                

            <?php endwhile; ?>
            </div>
            
        </div>
        <!-- /.col-lg-3 -->

        <div class="col-lg-9">

            <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner" role="listbox">
                <div class="carousel-item active">
                <img class="d-block img-fluid" src="<?= URL ?>photo/slider1.jpg" alt="First slide">
                </div>
                <div class="carousel-item">
                <img class="d-block img-fluid" src="<?= URL ?>photo/slider2.jpg" alt="Second slide">
                </div>
                <div class="carousel-item">
                <img class="d-block img-fluid" src="<?= URL ?>photo/slider3.jpg" alt="Third slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            </div>

            <div class="row">

            <?php while($p = $r->fetch(PDO::FETCH_ASSOC)):
                // echo '<pre>'; print_r($p); echo '</pre>';
                ?>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <a href="fiche_produit.php?id_produit=<?= $p['id_produit'] ?>"><img class="card-img-top" src="<?= $p['photo'] ?>" alt="<?= $p['titre'] ?>"></a>
                        <div class="card-body">
                            <h4 class="card-title">
                            <a href="fiche_produit.php?id_produit=<?= $p['id_produit'] ?>"><?= $p['titre'] ?></a>
                            </h4>
                            <h5><?= $p['prix'] ?>€</h5>
                            <p class="card-text">
                                <?php
                                //Si la taille de la chaine de description est > à 80 caracteres
                                if(iconv_strlen($p['description']) > 80)
                                    echo substr($p['description'], 0, 80) . '...'; // on coupe la description a 80 caracteres
                                else
                                // sinon la taille de la description < 80, on affiche la description normalement
                                {
                                    echo $p['description'];
                                }
                                ?>
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="fiche_produit.php?id_produit=<?= $p['id_produit'] ?>" class="btn btn-info">Voir le détail &raquo;</a>
                        </div>
                    </div>
                </div>
                
            <?php endwhile; ?>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.col-lg-9 -->

    </div>
<!-- /.row -->

</div>
<!-- /.container -->

<?php
require_once('inc/footer.inc.php');