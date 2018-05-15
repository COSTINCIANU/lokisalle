<?php
include("inc/init.inc.php");

// deconnexion de l'utilisateur
if (isset($_GET['action']) && $_GET['action']== 'deconnexion') 
{
  // unset($_SESSION['membre']);


  session_destroy(); //cette fonction est reconnue mais ne sera 
  //executé qu'a la fin de l'execusion du script
}


 if (utilisateur_est_connecte()) {

//si l'utilisateur est connecté on le redirige sur connexion.php

  header("location:profil.php");// avec la session_destroy(); cette
  //ligne est exécutée nous sommes redirigé vers profil, et c'est a ce moment la que la session sera detruite
  
  exit(); // securité de bloquer l'execution du code 
}

if (isset($_POST['pseudo']) && isset($_POST['mdp']))
{
  $pseudo = $_POST['pseudo'];
  $mdp = $_POST['mdp'];

// requete en BDD selon le pseudo 

$selection_membre= $pdo->prepare ("SELECT *FROM membre WHERE pseudo =:pseudo");
$selection_membre ->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
$selection_membre ->execute();

// on verifie s'il ya au moins une ligne (s'il ya une ligne alors le pseudo existe)
if($selection_membre ->rowCount() > 0)
{
   $infos_membre = $selection_membre -> fetch(PDO::FETCH_ASSOC);
   // verification du mot de passe avec la fonctionpredefinie password_verify() qui fonctionne
   // avec password_hash() (voir sur page inscription !)

   if(password_verify($mdp, $infos_membre['mdp']))
   {
     // si on rentre dans cette ondition, le pseudo et le mot de passe sont correct!
     // on enregistre les informations dans la session

     $_SESSION['membre'] = array();
     $_SESSION['membre']['id_membre'] = $infos_membre['id_membre'];
     $_SESSION['membre']['pseudo'] = $infos_membre['pseudo'];
     $_SESSION['membre']['nom'] = $infos_membre['nom'];
     $_SESSION['membre']['prenom'] = $infos_membre['prenom'];
     $_SESSION['membre']['email'] = $infos_membre['email'];
     $_SESSION['membre']['sexe'] = $infos_membre['sexe'];
     $_SESSION['membre']['statut'] = $infos_membre['statut'];
    header("location:profil.php");
   }else {
    $message .= '<div class="alert alert-danger" style="">Attention,<br>erreur sur le pseudo ou le mot de passe</div>';
   }

}else {
  $message .= '<div class="alert alert-danger" style="">Attention,<br>erreur sur le pseudo ou le mot de passe</div>';
 }

}


include("inc/header.inc.php");
include("inc/nav.inc.php");
//echo '<pre>'; print_r($_SESSION); echo'</pre>';
?>
    

    <div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-play"></span> Connexion</h1>
      <?php echo $message;// affichage des messages utilisateur ?>
      </div>

    </div><!-- /.container -->
    <div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<form method="post" action="">
				<div class="form-group">				
					<label for="pseudo">Pseudo</label>
					<input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo..." value="" >
				</div>
				<div class="form-group">				
					<label for="mdp">Mot de passe</label>
					<input value="" type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe...">
				</div>
				<hr>
				<button type="submit" class="btn btn-success col-sm-12"><span class="glyphicon glyphicon-ok" name="inscription"></span> Connexion</button>
			</form>
		</div>
	  </div>


<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>









<?php
include("inc/footer.inc.php");