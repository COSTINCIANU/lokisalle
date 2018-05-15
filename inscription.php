<?php
include("inc/init.inc.php");

$pseudo= "";
$mdp= "";
$nom= "";
$prenom= "";
$email= "";
$sexe= "";

if(isset ($_POST['pseudo']) && 
   isset ($_POST['mdp']) && 
   isset ($_POST['nom']) && 
   isset ($_POST['prenom'])&&
   isset ($_POST['email']) && 
   isset ($_POST['sexe']) ) 
   
    {
         $pseudo= $_POST['pseudo'];
         $mdp= $_POST['mdp'];
         $nom= $_POST['nom'];
         $prenom= $_POST['prenom'];
          $email= $_POST['email'];
         $sexe= $_POST['sexe'];
         
		 
		 // variable de controle initialisée par defaut sur false
		 $erreur = false;
		 //controle sur la taille du pseudo
		 if(iconv_strlen($pseudo) < 4 || iconv_strlen ($pseudo)> 20)
		    {
			   $erreur = true;// un cas d'erreur
			   $message .= '<div class="alert alert-danger" style="">Attention,<br>Le pseudo doit avoir entre 4 et 20 caractères inclus</div>';

			}
			
		// vérification du pseudo selon des caractères autorisés via une expression régulière.
	// preg_match() renvoi true si les caractères correspondent à l'expression régulière fournie en 1er argument sinon false.
	$verif_pseudo = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);
	/*
		les # indiquent le début et la fin de l'expression.
		^ indique le début de la chaine sinon la chaine pourrait commencer par autre chose.
		$ indique la fin de la chaine sinon la chaine pourrait finir par autre chose.
		+ indique que l'on avoir plusieurs les mêmes caractères.
	*/
	if(!$verif_pseudo) // si $verif_pseudo == false (valeur implicite)
	{
		$erreur = true; // un cas d'erreur
		$message .= '<div class="alert alert-danger" style="">Attention,<br>Caractères autorisés pour le pseudo: A - Z et 0 - 9</div>';
	}
// verification du format du mail
      if (!filter_var($email, FILTER_VALIDATE_EMAIL))
{
	$erreur = true; // un cas d'erreur
	$message .= '<div class= "alert alert-danger" style ="">Attention,<br>le format du mail ets incorrect, veuillez verifier
	votre saisie</div>';

	// Vérification de la disponibilité du pseudo
	// requete en BDD pour vérifier l'existence
	$verif_dispo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
	$verif_dispo->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
	$verif_dispo->execute();
	
	if($verif_dispo->rowCount() > 0)
	{
		// s'il y a au moins une ligne alors le pseudo est déjà pris
		$erreur = true; // un cas d'erreur
		$message .= '<div class="alert alert-danger" >Attention,<br>Pseudo indisponible</div>';
	}
}
// verification s'il ya eu au moins un cas d'erreur sinon on enregistre en BDD
if(!$erreur) // si $erreur == false
{
     $mdp = password_hash($mdp, PASSWORD_DEFAULT); // cryptage du mot de passe (hashage)


	// $enregistrement = $pdo->prepare ("INSERT INTO membre (pseudo, mdp, nom, prenom, email, sexe, ville, code_postal, adresse, statut)
	 // VALUES (:pseudo, :mdp, :nom, :prenom, :email, :sexe, :ville, :code_postal, :adresse, 0)");
	 $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, sexe, statut) 
	 VALUES (:pseudo, :mdp, :nom, :prenom, :email, :sexe, 0)");
	 $enregistrement->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
	 $enregistrement->bindParam(":mdp", $mdp, PDO::PARAM_STR);
	 $enregistrement->bindParam(":nom", $nom, PDO::PARAM_STR);
	 $enregistrement->bindParam(":prenom", $prenom, PDO::PARAM_STR);
	 $enregistrement->bindParam(":email", $email, PDO::PARAM_STR);
	 $enregistrement->bindParam(":sexe", $sexe, PDO::PARAM_STR);
	 
	 
	 
 $enregistrement ->execute();

// une fois l'inscription terminee on redirige vers connexion.php
// attention cette connexion doit etre executé avant le moindre affichage html (comme set cookies( et session_start()))
    header("location:connexion.php");

}
}



include("inc/header.inc.php");
include("inc/nav.inc.php");
?>


 <div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-user"></span> Inscription</h1>
		<?php echo $message; // affichage des messages utilisateur  ?>
      </div>
	  
	  <div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			<form method="post" action="">
				<div class="form-group">				
					<label for="pseudo">Pseudo</label>
					<input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo..." value= "<?php //echo $pseudo;?>">
				</div>
				<div class="form-group">				
					<label for="mdp">Mot de passe</label>
					<input type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe..." value= "<?php //echo $mdp;?>">
				</div>
				<div class="form-group">				
					<label for="nom">Nom</label>
					<input type="text" class="form-control" id="nom" name="nom" placeholder="Nom..." value= "<?php //echo $nom;?>">
				</div>
				<div class="form-group">				
					<label for="prenom">Prénom</label>
					<input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom..." value= "<?php //echo $prenom;?>">
				</div>
				<div class="form-group">				
					<label for="email">Email</label>
					<input type="text" class="form-control" id="email" name="email" placeholder="Email..." value= "<?php //echo $email;?>">
				</div>
				<div class="form-group">				
					<label for="sexe">Sexe</label>
					<select class="form-control" name="sexe" id="sexe" value= "<?php echo $sexe;?>">
						<option value= "m">Homme</option>
						<option <?php if($sexe == "f"){echo "selected";}?> value= "f">Femme</option>
					</select>
				</div>
				
				<hr>
				<button type="submit" class="btn btn-success col-sm-12"><span class="glyphicon glyphicon-ok" name="inscription"></span> Inscription</button>
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

    </div><!-- /.container -->

























<?php
include("inc/footer.inc.php");  