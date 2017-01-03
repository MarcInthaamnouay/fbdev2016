<?php require 'header.php'; ?>

<div class="container">
	<header>
		<h1>Choisir une photo parmi vos albums</h1>	
		<nav class="codrops-demos" id ="albums-list"></nav>
	</header>
	Warning : Si vous participer déjà à ce concours, et que vous ajouter une nouvelle image, l'ancienne sera supprimer et votre compteur remis à 0.
	<ul class="grid effect-1" id="grid"></ul>
</div>
<?php 
	require '../entity/Db.php';
	$db = new Db();
	$result = $db->getCurrentContest();
	foreach($result as $key => $res){
		echo "Concours : ";
		echo $res['id_concours']." "; echo $res['titre']. "<br />";
	}

?>

<?php require 'footer.php'; ?>

	