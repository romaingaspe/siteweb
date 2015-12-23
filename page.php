


<?php include 'inc/header.php' ?>
<main>
<?php
	$bdd =new PDO('mysql:host=localhost;dbname=siteweb;charset=utf8', 'root', '');
	$res = $bdd->prepare('SELECT * FROM articles  WHERE id = :idArticle');
	$res->bindValue(':idArticle',$_GET['id'],PDO::PARAM_INT);
	$res->execute();
	$article = $res->fetchAll(PDO::FETCH_ASSOC);
	foreach ($article as $value) {
		echo '<article> <img src="'.$value['img'].'">';
		echo '<h2>'. $value['titre'].'</h2>';
		echo '<p>'.$value['content'];
		echo '<br>';
		echo 'Posté le '.date('d/m/Y \à\ H:i',strtotime($value['date'])).'</p></article>';
	}


?>

<div>
	<h1>Commentaires :</h1>
</div>
<?php include 'inc/affichecom.php'; ?>
<a href="commentaire.php">Commenter l'article</a>

</main>