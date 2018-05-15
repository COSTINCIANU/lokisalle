<?php
include("../inc/init.inc.php");
if (!utilisateur_est_admin())// si l'utilisateur n'est pas admin
 {
 	header("location:../connexion.php");
 	exit(); // par securité , si on passe dans cette condition, cette ligne
 	//bloque l'exécution du code suivant.
}
//include_once("../inc/init.inc.php");
// le ou les fichiers joints via un formulaire seront dans la super globale $_FILES
//car ceux ne sont pas des saisies classiques (donc protocole http different)
// $_FILES est un tableau array multidimensionnel 


// SUPPRESSION PRODUIT
if (isset($_GET['action'])&& $_GET['action']== "suppression") 

	{$produit_a_supprimer = $_GET['id_produit'];
     $suppression = $pdo-> prepare("DELETE FROM produit WHERE id_produit = :id_produit");
     $suppression->bindParam(":id_produit", $produit_a_supprimer, PDO::PARAM_STR).
     $suppression->execute();
// on change la valeur de GET pour provoquer un affichage directement
     $_GET['action'] = 'voir';

}






$id_salle = "";
$date_arrivee = "";
$date_depart  = "";
$prix 	 = "";
$etat  = "";
// pour la modification uniquement
$id_produit ="";
// MODIFICATION PRODUIT
if (isset($_GET['action']) && $_GET['action'] == 'modification') 
{
   $produit_a_modifier = $_GET['id_produit'];
   $recup_info = $pdo->prepare ("SELECT *FROM produit WHERE id_produit = :id_produit");
   $recup_info->bindParam(":id_produit", $produit_a_modifier, PDO::PARAM_STR);
   $recup_info->execute();

   $produit_actuel = $recup_info->fetch(PDO::FETCH_ASSOC);
   	$id_produit = $produit_actuel['id_produit'];
	$id_salle = $produit_actuel['id_salle'];
	$date_arrivee = $produit_actuel['date_arrivee'];
	$date_depart = $produit_actuel['date_depart'];
	$prix = $produit_actuel['prix'];
	$etat = $produit_actuel['etat'];
	
}

//FIN MODIFICATION PRODUIT

if( isset($_POST['id_produit']) && 
	isset($_POST['id_salle']) && 
	isset($_POST['date_arrivee']) && 
	isset($_POST['date_depart']) && 
	isset($_POST['prix']) && 
	isset($_POST['etat']) 
{
	
    $id_produit=$_POST['id_produit'];
	$id_salle = $_POST['id_salle'];
	$date_arrivee = $_POST['date_arrivee'];
	$date_depart = $_POST['date_depart'];
	$prix = $_POST['prix'];
	$etat = $_POST['etat'];
	
// verification de la disponibilité de la reference
	$erreur = false;
	$verif_reference = $pdo->prepare("SELECT * FROM produit WHERE id_salle= :id_salle ");
	$verif_reference ->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);
	$verif_reference ->execute();
	// s'il ya une ligne dans verif_reference alors la reference existe deja!
	if($verif_id_salle->rowCount() >0 && empty($id_produit))
	{
		$erreur = true;
		$message .= '<div class="alert alert-danger" style="">La reference existe deja, Veuillez en choisir une autre</div>';
	}


  $photo_bdd ="";

// recuperation de la photo
	
		// verification s'il n'y a pas eu d'erreur sur les controles au dessus
		if (!$erreur) 

		{
		if (!empty($_FILES['photo']['name']))  
	    {
	    	// mise en place du src
	    	$photo_bdd = 'img/' . $reference . $_FILES['photo']['name'];
	    	$chemin = RACINE_SERVEUR . $photo_bdd;
	    	// copy() est une fonction predefinie permettant de copier un fichier
	    	// depuis un emplacement (1 er argument) vers un autre emplacement 
	    	// cible (2 eme argument)
           copy($_FILES['photo']['tmp_name'], $chemin);
		}
 // enregistrement en BDD du produit
		if(empty($id_produit))
			// si id_produit est vide c'est un ajout insert sinon c'est un update
		{$enregistrement = $pdo->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo,
			prix, stock) VALUES (:reference, :categorie,:titre, :description, :couleur, :taille, :public, '$photo_bdd',
			:prix, :stock)");
        } else {
        $enregistrement =$pdo->prepare("UPDATE produit SET reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, public = :public, photo = '$photo_bdd',
			prix = :prix, stock = :stock WHERE id_produit = :id_produit");
        $enregistrement->bindParam(":id_produit", $id_produit, PDO::PARAM_STR);
               }
        

        $enregistrement->bindParam(":reference", $reference, PDO::PARAM_STR);
        $enregistrement->bindParam(":categorie", $categorie, PDO::PARAM_STR);
        $enregistrement->bindParam(":titre", $titre, PDO::PARAM_STR);
        $enregistrement->bindParam(":description", $description, PDO::PARAM_STR);
        $enregistrement->bindParam(":couleur", $couleur, PDO::PARAM_STR);
        $enregistrement->bindParam(":taille", $taille, PDO::PARAM_STR);
        $enregistrement->bindParam(":public", $sexe, PDO::PARAM_STR);
        $enregistrement->bindParam(":prix", $prix, PDO::PARAM_STR);
        $enregistrement->bindParam(":stock", $stock, PDO::PARAM_STR);

        $enregistrement ->execute();


	    }

}



include("../inc/header.inc.php");
include("../inc/nav.inc.php");
?>
    <div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-th-list"></span> Gestion boutique</h1>
		<?php echo $message; // affichage des messages utilisateur  ?>
      </div>
	  
	  <div class="row">
		<div class="col-sm-12 text-center">
			<a href="?action=ajouter" class="btn btn-warning">Ajouter un produit</a>
			<a href="?action=voir" class="btn btn-primary">voir les produits</a>
			<hr>
		</div>
		
		<!-- FORMULAIRE AJOUT OU MODIFICATION PRODUIT -->
		<?php 
		if(isset($_GET['action']) && ($_GET['action'] == 'ajouter' || $_GET['action'] == 'modification'))
		{ 
		?>	
		
		<div class="col-sm-4 col-sm-offset-4">
			<form method="post" action="" enctype="multipart/form-data">
			<!-- enctype="multipart/form-data" est obligatoire s'il y a des pièces jointes dans le formulaire -->

           <!-- on rajoute un champ caché (type hidden) pour avoir l'id_produit lors d'une modification-->

			<input type="hidden" name="id_produit" value="<?php echo $id_produit;?>">
				<div class="form-group">				
					<label for="reference">Référence</label>
					<input type="text" class="form-control" id="reference" name="reference" placeholder="Référence..." value="<?php echo $reference; ?>" >
				</div>
				<div class="form-group">				
					<label for="titre">Titre</label>
					<input type="text" class="form-control" id="titre" name="titre" placeholder="Titre..." value="<?php echo $titre; ?>" >
				</div>
				<div class="form-group">				
					<label for="categorie">Catégorie</label>
					<input type="text" class="form-control" id="categorie" name="categorie" placeholder="Catégorie..." value="<?php echo $categorie; ?>" >
				</div>
				<div class="form-group">
					<label for="description">Description</label>
					<textarea id="description" name="description" class="form-control" rows="3"><?php echo $description; ?></textarea>
				</div>
				<div class="form-group">				
					<label for="couleur">Couleur</label>
					<input type="text" class="form-control" id="couleur" name="couleur" placeholder="Couleur..." value="<?php echo $couleur; ?>" >
				</div>
				<div class="form-group">				
					<label for="taille">Taille</label>
					<input type="text" class="form-control" id="taille" name="taille" placeholder="Taille..." value="<?php echo $taille; ?>" >
				</div>
				<div class="form-group">				
					<label for="sexe">Sexe</label>
					<select class="form-control" name="sexe" id="sexe">
						<option value="m" >Homme</option>
						<option <?php if($sexe == "f") { echo "selected"; } ?> value="f" >Femme</option>
					</select>
				</div>
				<div class="form-group">				
					<label for="photo">Photo</label>
					<input type="file" class="form-control" id="photo" name="photo" >
				</div>
				<div class="form-group">				
					<label for="prix">Prix</label>
					<input type="text" class="form-control" id="prix" name="prix" placeholder="Prix..." value="<?php echo $prix; ?>" >
				</div>
				<div class="form-group">				
					<label for="stock">Stock</label>
					<input type="text" class="form-control" id="stock" name="stock" placeholder="Stock..." value="<?php echo $stock; ?>" >
				</div>
				<hr>
				<button type="submit" class="btn btn-info col-sm-12"><span class="glyphicon glyphicon-ok" name="ajouter"></span> Ajouter</button>
				
			</form>
			
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
		</div>
		
		<?php
		} 
		?>
		<!-- / FIN FORMULAIRE AJOUT PRODUIT -->
		
		<!-- TABLEAU AFFICHAGE PRODUIT -->
		<?php 
		if(isset($_GET['action']) && $_GET['action'] == 'voir')
		{ 

			$les_produits = $pdo->query ("SELECT * FROM produit ORDER BY categorie");
           echo '<div class ="col-sm-12">';
           echo'<table class = "table table-bordered">';
           echo '<tr>';
           echo '<th>id_produit</th>';
           echo '<th>Référence</th>';
           echo '<th>Catégorie</th>';
           echo '<th>Titre</th>';
           echo '<th>Description</th>';
           echo '<th>Couleur</th>';
           echo '<th>Taille</th>';
           echo '<th>Public</th>';
           echo '<th>Photo</th>';
           echo '<th>Prix</th>';
           echo '<th>Stock</th>';
            echo '<th>Modifier</th>';
             echo '<th>Suprimer</th>';
           echo '</tr>';


while ($produit = $les_produits -> fetch (PDO::FETCH_ASSOC)) 
{
	echo '<tr>';
	echo '<td>' . $produit['id_produit'] . '</td>';
	echo '<td>' . $produit['reference'] . '</td>';
	echo '<td>' . $produit['categorie'] . '</td>';
	echo '<td>' . substr($produit['description'], 0, 14) . '...</td>';
	echo '<td>' . $produit['description'] . '</td>';
	echo '<td>' . $produit['couleur'] . '</td>';
	echo '<td>' . $produit['taille'] . '</td>';
	echo '<td>' . $produit['public'] . '</td>';
	echo '<td><img src="' . URL . $produit['photo'] . '" alt="image produit" class="img-responsive" width="100"></td>';
	echo '<td>' . $produit['prix'] . '</td>';
	echo '<td>' . $produit['stock'] . '</td>';
	echo '<td><a href ="?action=modification&id_produit=' . $produit['id_produit'] . '" class ="btn btn-warning"><span class = "glyphicon glyphicon-refresh"></span></a></td>';
	echo '<td><a href ="?action=suppression&id_produit=' . $produit['id_produit'] . '" class ="btn btn-warning" onclick="return(confirm(\'Etes vous sur ?\'))"><span class = "glyphicon glyphicon-trash"></span></a></td>';



	echo '</tr>';

}



           echo '</table>';
           echo '</div>';




		}
		?>	
				
		
		<!-- / FIN TABLEAU AFFICHAGE PRODUIT -->
	  </div>

    </div><!-- /.container -->


<?php
include("../inc/footer.inc.php");  




