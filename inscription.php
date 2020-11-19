<?php
require_once('inc/init.inc.php');

//si l'internaute est connecté cela veut dire que l'indice 'user' est bien defini dans la session, alors il n'a rien a faire sur la page inscription, on le redirige vers sa page profil
if(connect())
{
    header("location: profil.php");
}


// 2. Controle en PHP que l'on receptionne bien toute les données saisies dans le formulaire
// echo '<pre>'; print_r($_POST); echo '</pre>';

if($_POST)
{
    // bordure rouge en cas d'erreur dans le formulaire
    $border = "border border-danger";

    // 3. Controle la validité du pseudo, si le pseudo est existant en BDD alors on affiche un message d'erreur. Faites de meme pour le champ email

    // CONTROLE DISPONIBILITE PSEUDO
    // On selectionne tout en bdd à condition que champs pseudo soit egal au pseudo que l'internaute à saisi
    $verifPseudo = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo"); // :pseudo (marqueur nominatif ici vise)
    //$verifPseudo : objet PDOStatement
    $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR); // on transmet le pseudo saisi dans le formulaire dans le marqueur declaré :pseudo
    $verifPseudo->execute(); // execution de la requete prepare

    // si la requete de selection a retourné au moins 1 resultat, cela veut dire que le pseudo est connu en bdd, alors on entre dans le IF et on affiche un message d'erreur à l'internaute
    
    
    if(empty($_POST['pseudo']))
    {
        $errorPseudo = "<p class='text-danger font-italic'>Veuillez renseigner un pseudo</p>";

        $error = true;
    }
    elseif($verifPseudo->rowCount())
    {
        $errorPseudo = "<p class='text-danger font-italic'>Pseudo dejà existant</p>";

        $error = true;
    }

    

    // CONTROLE DISPONIBILITE EMAIL
    $verifEmail = $bdd->prepare("SELECT * FROM membre WHERE email = :email");
    $verifEmail->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $verifEmail->execute();

    
    // Si le champ email est laissé vide par l'internaute, alors on entre dans le IF
    if(empty($_POST['email']))
    {
        $errorEmail = "<p class='text-danger font-italic'>Veuillez renseigner un email</p>";

        $error = true;
    }
    elseif($verifEmail->rowCount())
    {
        // SI la condition renvoie TRUE, cela veut dire que rowCount() retourne un INT donc une ligne de la BDD, donc l'email est connu en BDD
        // SI la condition IF renvoie FALSE, cela veut dire que rowCount() retourne un boolean FALSE, donc l'email n'est pas connu de la BDD
        $errorEmail = "<p class='text-danger font-italic'>Email dejà existant</p>";

        $error =true;
    }

    // 4. Informer l'internaute si les mdp ne correspondent pas.
    // Si la valeur du champ 'mot de passe' est differente du champ 'confirmer votre mdp', alors on entre dans la condition IF

    if($_POST['mdp'] != $_POST['confirm_mdp'])
    {
        $errorMdp = "<p class='text-danger font-italic'>Les mots de passe doivent être identique</p>";

        $error = true;
    }

    // 5. Gerer les failles XSS
  

   
    
    if(!isset($error))
    {
        foreach($_POST as $key => $value)
        {
            $_POST[$key] = htmlspecialchars($value);
        } 
        // cryptage du mot de passe en BDD
        // les mots de passe ne sont jamais gardés en clair dans la BDD
        // paswword_hash() : fonction prédéfinie qui crée une clé de hachage pour le mot de passe dans la BDD
        $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_BCRYPT);


         // 6. Si l'internaute à correctement remplit le formulaire, realiser le traitement PHP+SQL permettant d'inserer le membre en BDD (requete preparée | prepare()+ bindvalue())

        $insert = $bdd->prepare("INSERT INTO membre (pseudo,mdp,nom,prenom,email,civilite,ville,code_postal,adresse) 
                                VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal,:adresse)");

        $insert->bindValue(':pseudo',$_POST['pseudo'], PDO::PARAM_STR);
        $insert->bindValue(':mdp',$_POST['mdp'], PDO::PARAM_STR);
        $insert->bindValue(':nom',$_POST['nom'], PDO::PARAM_STR);
        $insert->bindValue(':prenom',$_POST['prenom'], PDO::PARAM_STR);
        $insert->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
        $insert->bindValue(':civilite',$_POST['civilite'], PDO::PARAM_STR);
        $insert->bindValue(':ville',$_POST['ville'], PDO::PARAM_STR);
        $insert->bindValue(':code_postal',$_POST['code_postal'], PDO::PARAM_INT);
        $insert->bindValue(':adresse',$_POST['adresse'], PDO::PARAM_STR);
    
        $insert->execute();

        //Apres insertion du membre en BDD, on le redirige vers la page validation_inscription.php grâce à la fonction prédéfinie header()
        header("location: validation_inscription.php");
    }

}




require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>


<form method="post" class="col-md-6 mx-auto" action="">
    <div class="form-group">
        <label for="pseudo">Pseudo</label>

        <input type="text" class="form-control <?php if(isset($errorPseudo)) echo $border; ?>" id="pseudo" name="pseudo"  placeholder="ex : kkhuete" value="<?php if(isset($_POST['pseudo'])) echo $_POST['pseudo']?>">  

        <?php if(isset($errorPseudo)) echo $errorPseudo; // affichage message d'erreur si le pseudo est connu?>

    </div>
    <div class="form-group">
        <label for="mdp">Mot de passe</label>
        <input type="text" class="form-control <?php if(isset($errorMdp)) echo $border;  ?>" id="mdp" name="mdp">
    </div>
    <div class="form-group">
        <label for="confirm_mdp">Confirme Mot de passe</label>
        <input type="text" class="form-control <?php if(isset($errorMdp)) echo $border; ?>" id="confirm_mdp" name="confirm_mdp">

        <?php if(isset($errorMdp)) echo $errorMdp;?>

    </div>
    <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom">
    </div>
    <div class="form-group">
        <label for="prenom">Prenom</label>
        <input type="text" class="form-control" id="prenom" name="prenom">
    </div>
    <div class="form-group">
        <label for="email">Email</label>

        <input type="text" class="form-control <?php if(isset($errorEmail)) echo $border;?>" id="email" name="email" placeholder="ex : exemple@exemple.com" value="<?php if(isset($_POST['email'])) echo $_POST['email']?>">

        <?php if(isset($errorEmail)) echo $errorEmail;?>
    </div>
    <div class="form-group">
                <label for="civilite">Civilite</label>
                <select class="form-control" id="civilite" name="civilite">
                <option value="h">Monsieur</option>
                <option value="f">Madame</option>
                </select>
    </div>
    <div class="form-group">
        <label for="ville">Ville</label>
        <input type="text" class="form-control" id="ville" name="ville">
    </div>
    <div class="form-group">
        <label for="code_postal">Code postal</label>
        <input type="text" class="form-control" id="code_postal" name="code_postal">
    </div>
    <div class="form-group">
        <label for="adresse">Adresse</label>
        <textarea class="form-control" id="adresse" rows="3" name="adresse"></textarea >
    </div>
        <button type="submit" class="btn btn-success mb-2">Inscription</button> 
</form>




<?php
require_once('inc/footer.inc.php');