<?php require 'header.php'; ?>
<!----------------------- Test -->
<?php 
	require '../entity/Db.php';
	$db = new Db();
	$result = $db->getAllContest();
	$current = $db->getCurrentContest();
	echo "Tous les concours : <br />";
	foreach($result as $row){
		echo "Concours : ";
		echo $row['id']." "; echo $row['title']. "<br />";
	}
	 echo "<br />currentCOntest : ".$current["title"];
	 if( $db->haveCurrentContest())
	 	echo "<br />currentCOntest : True";
	 else
	 	echo "<br />currentCOntest : True";

	$db->addContest('title','text','lot','infos',"12-12-2016", "19-12-2016");
	 	/*foreach($result as $row){
		echo "Concours : ";
		echo $row['id']." "; echo $row['title']. "<br />";
	}*/


?>