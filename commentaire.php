<?php
$err=array();
$erreurForm = false;
$formValide = false;

if (!empty($_POST)) {
	foreach ($_POST as $key => $value) {
		$post[$key]= trim(strip_tags($value));
	}
	if (empty($post['pseudo'])) {
		$err[]= 'Le pseudo ne peut pas être vide';
	}
	if (empty($post['commentaire'])) {
		$err[] = 'Le champ commentaire ne peut pas être vide';
	}
	if (count($err) > 0) {
		$erreurForm = true;
	}
	else {
		$formValide = true;
	}

	$bdd = new PDO('mysql:host=localhost;dbname=siteweb;charset=utf8', 'root', '');

	$res = $bdd->prepare('INSERT INTO comments(comment, date, id_user, id_article) VALUES (:comment, NOW(), :id_user, :id_article)'); 
	$res->bindValue(':comment', $post['commentaire'],PDO::PARAM_STR);
	$res->bindValue(':id_article', $post['id_article'],PDO::PARAM_INT);
	$res->bindValue(':id_user', $post['id_user'].PDO::PARAM_INT);

	$res->execute();

	$reponse = $bdd->prepare('INSERT INTO user(pseudo) VALUES (:pseudo) ');
	$reponse->bindValue(':pseudo', $post['pseudo'],PDO::PARAM_STR);
	$reponse->execute();



}		
	
	

?>
<?php include 'inc/header.php'; ?>
<main>
<h2>Poster un commentaire :</h2>
<?php 
if ($formValide){
	echo '<p style="color:green;">Le commentaire a bien été envoyé </p>';
}
if ($erreurForm) {
	echo '<p style="color:red;">'.implode('<br>', $err).'</p>';
}


?>

	<form method="POST">
		<input type="hidden" name="id_user" value="<?php echo $_GET['id']; ?>">
		<input type="hidden" name="id_article" value="<?php echo $_GET['id']; ?>">
		<label for="pseudo">Pseudo</label>	
		<input type="text" name="pseudo">
		<br>
		<label for="commentaire">Commentaire</label>
		<textarea type="text" name="commentaire"></textarea>
		<br>
		<input type="submit" value="Envoyer">

	</form>
</main>
<?php include 'inc/footer.php'; ?>