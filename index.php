<?php include_once 'inc/header.php';?>
	<main>
		<?php
			$bdd =new PDO('mysql:host=localhost;dbname=siteweb;charset=utf8', 'root', '');
			$res = $bdd->prepare('SELECT * FROM articles ORDER BY date DESC LIMIT 5');
			$res->execute();
			$article = $res->fetchAll(PDO::FETCH_ASSOC);
			foreach ($article as $value) {
				echo '<article> <img src="'.$value['img'].'">';
				echo '<h2>'. $value['titre'].'</h2>';
				echo '<p>'.$value['content'].'<br>'.'Posté le '.date('d/m/Y \à\ H:i',strtotime($value['date'])).'</p>';
				echo '<a href="page.php?id='.$value['id'].'">lire la suite</a></article>';
			}


		?>
		
		

	</main>
<?php include_once 'inc/footer.php';?>