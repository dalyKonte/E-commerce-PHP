<?php 
require_once('../inc/init.inc.php');

//Si l'internaute n'est pas (!) administrateur du site, il n'a rien a faire sur scette page, on le redirige vers la page de connexion
if(!adminConnect())
{
    header('location:' . URL . 'connexion.php');
}



//SUPPRESSION MEMBRE
//Exo : realiser le traitement SQL + PHP permettant de supprimer un membre de la BDD en fonction de l'id_membre transmis dans l'URL

if(isset($_GET['action']) && $_GET['action'] == "suppression")
{
    $d = $bdd->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
    $d->bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
    $d->execute();

    $_GET['action'] = 'affichage';

    $vd = "<p class='col-md-3 mx-auto bg-success text-center text-white p-3 rounded'>Le membre <strong>ID $_GET[id_membre]</strong> a bien été supprimé !</p>";
}

if(isset($_GET['action']) && $_GET['action'] == "modification")
{
    if(isset($_GET['id_membre']) && !empty($_GET['id_membre']))
    {
        $r = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
        $r->bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
        $r->execute();

        if($r->rowCount())
        {
            $m = $r->fetch(PDO::FETCH_ASSOC);
            // echo '<pre>'; print_r($m); echo '</pre>';
        }
        else
        {
            header('location: ' . URL . 'admin/gestion_membre.php');
        }
    }
    else
    {
        header('location:' . URL . 'admin/gestion_membre.php');
    }

    //La boucle FOREACH genere une variable par tour de boucle
    // On se sert de la variable $k qui receptionne un indice du Tableau ARRAY par tour de boucle pour creer une variable
    //          id_membre
    foreach($m as $k => $v)
    {
        // 1er tour :
        //  id_membre=(isset($m['id_membre'])) ? $m['id_membre] : '';
        // 2eme tour :
        //  id_membre=(isset($m['id_pseudo'])) ? $m['id_pseudo] : '';
        // 3eme tour...
        $$k = (isset($m[$k])) ? $m[$k] : '';
        //                   ?if       :else
    }

    //REQUETE UPDATE MODIFICATION MEMBRE
    if($_POST)
    {
        // echo '<pre>'; print_r($_POST); echo '</pre>';
        $up = $bdd->prepare("UPDATE membre SET civilite = :civilite, nom = :nom, prenom = :prenom, adresse = :adresse, ville = :ville, code_postal = :code_postal, statut = :statut WHERE id_membre = :id_membre");
        $up->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
        $up->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $up->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $up->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
        $up->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
        $up->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
        $up->bindValue(':statut', $_POST['statut'], PDO::PARAM_INT);
        $up->bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
        $up->execute();

        $vUpdate = "<p class='col-md-3 mx-auto bg-success text-center text-white p-3 rounded'>Le membre <strong>ID $_GET[id_membre]</strong> a bien été modifié !</p>";

        //On définit la superglobale $_GET afin d'etre redirigé vers la gestion_membre apres la modification du membre, donc apres la validation du formulaire
        $_GET = '';


    }
}

require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');


?>
<!-- SUPPRESSION MEMBRE -->
<?php
$m = $bdd->query("SELECT * FROM membre");
$membre = $m->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="display-4 text-center my-4">Liste des membres</h1>
<!-- AFFICHAGE MESSAGES UTILISATEURS -->
<?php if(isset($vd)) echo $vd; ?>
<?php if(isset($vUpdate)) echo $vUpdate; ?>


<!-- AFFICHAGE NOMBRE DE MEMBRE -->
<?php
if($m->rowCount()==1)
    $txtmembre = 'membre enregistré.';
else
    $txtmembre = 'membres enregistrés.';
?>

<p><h5><span class="badge badge-success"><?= $m->rowCount() ?></span> <?= $txtmembre ?></h5></p>


<!-- AFFICHAGE NOMBRE ADMIN -->
<?php
$admin=$bdd->query("SELECT statut FROM membre WHERE statut = 1");
if($admin->rowCount()==1)
    $txtadmin = 'administrateur.';
else
    $txtadmin = 'administrateurs'
?>
    <p><h5><span class="badge badge-success"><?= $admin->rowCount() ?></span> <?= $txtadmin ?></h5></p>




<!-- TABLEAU LISTE DES MEMBRES  -->
<?php

echo '<table class="table mt-5 table-bordered text-center"><tr>';

foreach($membre[0] as $key => $value)
{   
    if($key == 'mdp')
    {
        
    }
    else
    {
        echo "<th> $key </th>";
    }
}    
    echo"<th>EDIT</th>";
    echo"<th>SUPP</th>";
    echo'<tr>';
    foreach($membre as $key => $value)
    {
        echo'<tr>';
        foreach($value as $key2 => $value2)
        {
            if($key2!= 'mdp')
            {
                if($key2 == 'statut')
                {
                    if($value2 == 0)
                    {
                        echo "<td>MEMBRE</td>";
                    }
                    else
                    {
                        echo "<td class='bg-info text-white'>ADMIN</td>";
                    }
                }
                else
                {
                    echo "<td>$value2</td>";
                }
            }
        }
        echo "<td class='align-middle'><a href='?action=modification&id_membre=$value[id_membre]' class='btn btn-dark'><i class='far fa-edit'></i></a></td>";

        echo"<td class='align-middle'><a href='?action=suppression&id_membre=$value[id_membre]' class='btn btn-danger' onclick='return(confirm(\"En êtes vous certain\"));'><i class='far fa-trash-alt'></i></a></td>";
    }

echo '</table>';

?>

<!-- MODIFICATION MEMBRE -->
<?php if(isset($_GET['action']) && $_GET['action'] == 'modification'): ?>

<form method="post" class="col-md-6 mx-auto mt-5" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="civilite">Civilite</label>
                <select id="civilite" name="civilite" class="form-control">
                    <option value="homme" <?php if($civilite == 'homme') echo 'selected' ?>>Monsieur</option>
                    <option value="femme" <?php if($civilite == 'femme') echo 'selected' ?>>Femme</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="pseudo">Pseudo</label>
                <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="ex : toto" value="<?= $pseudo ?>" disabled>
            </div>
        </div>

        <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= $nom ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="prenom">Prenom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?= $prenom ?>">
                </div>
        </div>
        
        <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?= $email ?>" disabled>
                    </div>
        </div>

        <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="ville">Ville</label>
                        <input type="text" class="form-control" id="ville" name="ville" value="<?= $ville ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="code_postal">Code postal</label>
                        <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?= $code_postal ?>">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="adresse">Adresse</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" value="<?= $adresse ?>">
                    </div>
        </div>
        <div class="form-row">
        <div class="form-group col-md-12">
                <label for="statut">Rôle</label>
                <select id="statut" name="statut" class="form-control">
                    <option value="0" <?php if($statut == '0') echo 'selected' ?>>Membre</option>
                    <option value="1" <?php if($statut == '1') echo 'selected' ?>>Administrateur</option>
                </select>
            </div>

        </div>

        <button type="submit" class="btn btn-dark mb-3">MODIFICATION MEMBRE</button>
            
</form>


<?php endif; ?>
<?php
require_once('../inc/footer.inc.php');