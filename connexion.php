<?php
require_once('inc/init.inc.php');

//Lorsque l'internaute clique sur le lien 'deconnexion', il transmet dans le meme temps dans l'URL les parametres 'action=deconnexion'
//La condition IF permet de verifier si l'indice 'action' est bien defini dans 'URL' et qu'il a pour valeur 'deconnexion', on entre dans le IF seulement dans le cas où l'internaute clique sur 'deconnexion'
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
    //Pour que l'internaute soit deconnecté, il faut soit upprimer la session ou vider une partie afin que l'indice 'user' dans la session ne soit plus definit

    //session_destroy(); // suppression du fichier session
    unset($_SESSION['user']); // supprime le tableau ARRAY indice 'user' dans la session
}

//si l'internaute est connecté cela veut dire que l'indice 'user' est bien defini dans la session, alors il n'a rien a faire sur la page connexion, on le redirige vers sa page profil
if(connect())
{
    header("location: profil.php");
}

// echo '<pre>'; print_r($_POST); echo '</pre>';

if($_POST)
{   
    // on selectionne tout en BDD à condition que le champ pseudo ou email soit egal à la donnée saisie par l'internaute dans le formulaire dans le champ pseudo_email
    $data = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR email = :email");
    $data->bindValue(':pseudo',$_POST['pseudo_email'], PDO::PARAM_STR);
    $data->bindValue(':email',$_POST['pseudo_email'], PDO::PARAM_STR);
    $data->execute();

    // si la requete de selection retourne un resultat, cela veut dire que l'email ou le pseudo saisie par l'internaute est existant en BDD, alors on entre dans la condition IF
    if($data->rowCount())
    {
        // echo "pseudo ou email inexistant en BDD";

        $user = $data->fetch(PDO::FETCH_ASSOC);
        // echo '<pre>'; print_r($user); echo '</pre>';

        // controle mot de passe en clair en BDD
        // $_POST ['password'] == $user['mdp']

        //password_verify() permet de comparer une cle de hachage à une chaine de caracteres
        // arguments : password_verify('lachaine à comparer', 'la clé de hachage')
        if(password_verify($_POST['password'], $user['mdp']))
        {
            // echo "Mot de passe OK";
            // si nous entrons dans cette condition , cela veut dire que l'internaute à correctement rempli le formulaire de connexion

            // on passe en revue toute les donnes recuperes en BDD de l'internaute qui a correctement remplit le formulaire de connexion
            //              [mdp] => mdp
            foreach($user as $key => $value)
            { //    [mdp]
                if($key != 'mdp')// on exclut le mdp dans le fichier session
                { //               [pseudo]= dalylyda
                    $_SESSION['user'][$key] = $value;
                }
            }// on crée dans la session un indice 'user' contenant un tableau ARRAY avec toute les données de l'utilisateur
            // c'est ce qui permettra d'identifier l'utilisateur connecté sur le site et cela lui permettra de naviguer sur le site tout en restant connecté
            // echo '<pre>'; print_r($_SESSION); echo '</pre>';

            //Une fois que l'internaute s'est connecté, on le redirige vers sa page profil
            header('location: profil.php');
        }
        else
        {
            // echo "Erreur mot de passe";
            $error = "<p class=' col-md-4 mx-auto bg-danger text-white text-center p-3'> Identifiant ou mot de passe incorrect</p>";
        }
    }
    else // SINON le pseudo n'est pas connu en BDD, on entre dans la condition ELSE
    {
        // echo "erreur pseudo ou email";
        $error = "<p class=' col-md-4 mx-auto bg-danger text-white text-center p-3'> Identifiant ou mot de passe incorrect</p>";
    }
}

require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>

<h1 class="display-4 text-center my-4">Identifiez-vous</h1>

<?php if(isset($error)) echo $error ; // affichage de d'erreur en cas d'erreur d'identifiant ?> 

<form method="post" class="col-md-6 mx-auto" action="">
            <div class="form-group">
                <label for="email">Pseudo / Email</label>
                <input type="text" class="form-control" id="pseudo_email" name="pseudo_email" value="<?php if(isset($_POST['pseudo_email'])) echo $_POST['pseudo_email']; ?>" >  
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="text-center">
            <button type="submit" class="btn btn-dark">Connexion</button> 
            </div>
        </form>

<?php
require_once('inc/footer.inc.php');