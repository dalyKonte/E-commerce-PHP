<?php
require_once('inc/init.inc.php');

//si l'internaute n'est pas connecté cela veut dire que l'indice 'user' n'est pas defini dans la session, alors il n'a rien a faire sur la page profil, on le redirige vers sa page connexion
if(!connect())
{
    header("location:connexion.php");
}

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');

// echo '<pre>'; print_r($_SESSION); echo '</pre>';
?>


<h1 class='display-4 text-center my-4'>Bonjour <span class="text-info"><?=$_SESSION['user']['pseudo']?></span></h1>

<!-- exo : afficher les infos de l'internaute contenu en session sur la page profil avec de la mise en forme -->
<!-- <div class="row justify-content-center">
<ul class=" col-md-3 mb-2 text-center list-group">
  <li class="list-group-item"><?=$_SESSION['user']['nom']?></li>
  <li class="list-group-item"><?=$_SESSION['user']['prenom']?></li>
  <li class="list-group-item"><?=$_SESSION['user']['email']?></li>
  <li class="list-group-item"><?=$_SESSION['user']['civilite']?></li>
  <li class="list-group-item"><?=$_SESSION['user']['ville']?></li>
  <li class="list-group-item"><?=$_SESSION['user']['code_postal']?></li>
  <li class="list-group-item"><?=$_SESSION['user']['adresse']?></li>
</ul>
</div> -->

<div class="col-md-3 mx-auto card mb-3 shadow-lg">
    <div class="card-body">

        <h5 class="card-title text-center">Vos informations personnelle</h5><hr>

        <?php foreach($_SESSION['user'] as $key => $value): // on passe en revue le tableau ARRAY à l'indice 'user' dans la session ?>

            <?php if($key != 'id_membre' && $key != 'statut'): /// on exclu à l'affichage le statut et l'id membre de l'utilisateur ?>

                <p class="card-text"><strong><?= $key ?></strong> : <?= $value ?></p>
            
            <?php endif; ?>

        <?php endforeach; ?>

        <a href="#" class="card-link">Modifier</a>

    </div>
</div>




<?php
require_once('inc/footer.inc.php');
?>