<?php
include("inc/init.inc.php");





include("inc/header.inc.php");
include("inc/nav.inc.php");
//echo '<pre>'; print_r($_SESSION); echo'</pre>';
?>

	<div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-user"></span> Profil</h1>
		<?php echo $message; // affichage des messages utilisateur  ?>
      </div>
	  
	  <div class="row">
		<div class="col-sm-8">
			<div class="list-group">
			    <p class="list-group-item active">Bonjour <b><?php echo ucfirst($_SESSION['membre']['pseudo']); ?></b></p>
			    <p class="list-group-item"><b>Nom:</b> <?php echo ucfirst($_SESSION['membre']['nom']); ?></p>
			    <p class="list-group-item"><b>Prénom:</b> <?php echo ucfirst($_SESSION['membre']['prenom']); ?></p>
			    <p class="list-group-item"><b>Email:</b> <?php echo ucfirst($_SESSION['membre']['email']); ?></p>
			    <p class="list-group-item"><b>Sexe:</b> <?php echo ucfirst($_SESSION['membre']['sexe']); ?></p>
			   
		</div>
		<hr>
		<?php
              if (utilisateur_est_admin()) {
               echo "<h2>Vous etes Administrateur </h2>";
              }else
              {
              	echo "<h2>Vous etes Membre </h2>";
              }

		 ?>
		<div class="col-sm-4">
			<img src="img/profil.jpg" class="img-thumbnail" alt="image de profil">
		</div>
	  </div>
	  

    </div><!-- /.container -->
























<?php
include("inc/footer.inc.php");  