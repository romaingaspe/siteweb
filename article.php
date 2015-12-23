<?php
// inclu mon fichier connexion
$bdd =new PDO('mysql:host=localhost;dbname=siteweb;charset=utf8', 'root', '');

$post = array();
$error = array();
$maxSize = 1024 * 1000; // 1Ko * 1000 = 1Mo
$dirUpload = 'upload/';
$mimeTypeAllowed = array('image/jpg', 'image/jpeg', 'image/png', 'image/gif');

if(!empty($_POST)){
	$finfo = new finfo();

	foreach($_POST as $key => $value){
		$post[$key] = trim(strip_tags($value));
	}

	if(empty($post['title'])){
		$error[] = 'Le titre ne peut être vide';
	}
	if(empty($_FILES['img']['size'])){ // On vérifie qu'il n'y a pas d'image 
		/*
		 * Par défaut, sans avoir envoyé de fichier
		 * $_FILES['img'] = array(
		 *		 				'name' => '', 
		 *		 				'type' => '', 
		 *		 				'tmp_name' => '', 
		 *		 				'error' => '', 
		 *		 				'size' => ''
		 *	 				);
	 	 */
		$error[] = 'L\'image ne peut être vide';
	}
	elseif($_FILES['img']['size'] > $maxSize) { // Je vérifie que l'image ne soit pas trop grosse
		$error[] = 'L\'image excède le poids autorisé';
	}
	// in array(valeur, tableau) : cherche valeur dans tableau :-)
	// Vérifiera que le mime type de l'image correspond a ceux qu'on a autorisé
	//if(!in_array($_FILES['img']['type'], $mimeTypeAllowed)){ // Ca fonctionne, mais c'est pas génial !
	$fileMimeType = $finfo->file($_FILES['img']['tmp_name'], FILEINFO_MIME_TYPE);
	if(!in_array($fileMimeType, $mimeTypeAllowed)){ // 
		$error[] = 'Le fichier n\'est pas une image';
	}
	if(empty($post['article'])){
		$error[] = 'L\'article ne peut être vide';
	}

	if(count($error) > 0){
		$errorShow = true;
	}
	else {
		// $monImgUpload = upload/monimage.jpg

		$search = array(' ', 'é', 'è', 'à', 'ù');
		$replace = array ('-', 'e', 'e', 'a', 'u');
		// $search => les caractères qu'on souhaite remplacer
		// $replace => les valeurs de remplacement( dans le même ordre que $ search)
		// time().'-'. $_FILES['img']['name'] => La chaine dans laquelle j'effectue les remplacements

		$newFileName = str_replace($search, $replace, time().'-'. $_FILES['img']['name'])
	
		$monImgUpload = $dirUpload.time().'-'.$_FILES['img']['name'];
		if(move_uploaded_file($_FILES['img']['tmp_name'], $monImgUpload)){
			// insertion des données de l'article seulement si le fichier a été uploadé
			$insertArt = $bdd->prepare('INSERT INTO articles (title, img, content, date) VALUES (:title, :img, :content, NOW())');
			$insertArt->bindValue(':title', $post['title'], PDO::PARAM_STR);
			$insertArt->bindValue(':img', $monImgUpload, PDO::PARAM_STR);
			$insertArt->bindValue(':content', $post['article'], PDO::PARAM_STR);

			if($insertArt->execute()){
				$formSuccess = true;
				$id_article = $bdd->lastInsertId();
			}
			else {
				$errorShow = true;
				$error[] = 'Une erreur est survenue lors de l\'insertion de votre article';
			}
		}
		else { // S'il y a une erreur lors de l'upload de l'image
			$errorShow = true;
			$error[] = 'Une erreur est survenue lors de l\'envoi de votre image';
		}
	}


}
?>
<?php include_once 'inc/header.php'; ?>
	<main>

		<?php if(isset($errorShow) && $errorShow): ?>
			<div class="errorContent"><?php echo implode('<br>', $error); ?></div>
		<?php endif; ?>
		<?php if(isset($formSuccess) && $formSuccess): ?>
			<div class="successContent">
				Votre article a été publié ! <a href="read.php?id=<?php echo $id_article; ?>" class="link">&raquo; Voir l'article</a>
			</div>
		<?php endif; ?>


		<!-- attribut enctype="multipart/form-data" pour accepter les fichiers -->
		<form method="POST" enctype="multipart/form-data">
			<!-- 
				Limitation de la taille du fichier par le navigateur
				/!\ Il faut tout de même faire une vérification en PHP :-)
			-->
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxSize; ?>">

			<label for="title">Titre</label>
			<input type="text" id="title" name="title" placeholder="Votre titre">

			<br>

			<label for="img">Image</label>
			<!-- champ de type file pour choisir un fichier -->
			<input type="file" id="img" name="img">

			<br>


			<label for="article">Contenu</label>
			<textarea name="article" cols="40" rows="10"></textarea>

			<br>


			<input type="submit" value="Envoyer">

		</form>

	</main>

<?php include_once 'inc/footer.php'; ?>