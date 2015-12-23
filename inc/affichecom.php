<?php

$bdd = new PDO('mysql:host=localhost;dbname=siteweb;charset=utf8', 'root', '');

	$res = $bdd->prepare('SELECT pseudo FROM users WHERE id= :id_article');
	$res->bindValue(':id_article',$_GET['id'],PDO::PARAM_INT);
	$res->execute();
	$user = $res->fetch(PDO::FETCH_ASSOC);
	echo '<p>'.$user['pseudo'].'</p>';


	$resp = $bdd->prepare('SELECT * FROM comments WHERE id = :id_article');
	$resp->bindValue(':id_article',$_GET['id'],PDO::PARAM_INT);
	$resp->execute();
	$comment = $resp->fetch(PDO::FETCH_ASSOC);
	echo '<p>'.$comment['comment'].'</p>';
	echo '<p>'.$comment['date'].'<p>';

