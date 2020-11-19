<nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Ma boutique</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

    <div class="collapse navbar-collapse" id="navbarsExample04">
        <ul class="navbar-nav mr-auto">

        <?php
        // si l'indice 'panier' dans la session est bien défini, alors on calcul la somme de toute les quantités demandé grace à la fonction prédéfinie array_sum()
        if(isset($_SESSION['panier']))
        {
            $badge= "<span class='badge badge-primary'>" . array_sum($_SESSION['panier']['quantite']) ."</span>";
        }
        else // sinon, si l'indice 'panier' n'est pas définit dans la session, donc que l'internaute n'a pas ajouté de produit dans le panier
        {
            $badge= "<span class='badge badge-primary'>0</span>";
        }
        ?>

        <?php if(connect()): // acces membre connecté mais NON ADMIN ?>


        <li class="nav-item active">
            <a class="nav-link" href="<?= URL ?>profil.php">Votre compte </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link " href="<?= URL ?>boutique.php">Accès à la boutique</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link " href="<?= URL ?>panier.php">Mon panier <?= $badge ?> </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link " href="<?= URL ?>connexion.php? action=deconnexion">Deconnexion</a>
        </li>

        <?php else: // acces visiteur lambda NON connecté ?>

            <li class="nav-item active">
            <a class="nav-link" href="<?= URL ?>inscription.php">Creer votre compte </a>
        </li>
        <li class="nav-item active">
            <a class="nav-link " href="<?= URL ?>connexion.php">Identifiez-vous</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="<?= URL ?>boutique.php">Acces à la boutique</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link " href="<?= URL ?>panier.php">Mon panier <?= $badge ?> </a>
        </li>


        
        <?php endif; ?>

        <?php if(adminConnect()): // si l'utilisateur a pour valeur 1 pour le statut dans la session, alors il est administrateur du site et nous lui donnons acces aux liens du backOffice ?>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">BACK OFFICE</a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
            
                <a class="dropdown-item" href="<?= URL ?>admin/gestion_boutique.php">Gestion boutique</a>

                <a class="dropdown-item" href="<?= URL ?>admin/gestion_commande.php">Gestion commande</a>

                <a class="dropdown-item" href="<?= URL ?>admin/gestion_membre.php">Gestion membre</a>

            </div>
        </li>

        <?php endif; ?>


        </ul>
        <form class="form-inline my-2 my-md-0">
        <input class="form-control" type="text" placeholder="Search">
        </form>
    </div>
    </nav>

    <main class="container-fluid" style="min-height : 90vh;">