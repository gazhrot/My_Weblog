<?php 

include ('header.php');
$chapo = substr($_POST['content'], 0, 150)."...";
if (isset($_POST["content"]) && isset($_POST["publish_date"]))
{
	$sql = "UPDATE tickets SET chapo = :chapo, contents = :content, title = :title, publish_date = :publish_date WHERE id_ticket = :idTicket";

	if (!$_POST['publish_date']) {
		$_POST['publish_date'] = "0000-00-00 00:00:00";
	}

	$query = $bdd->prepare($sql);
	$query->bindParam(':content', $_POST['content']);
	$query->bindParam(':title', $_POST['title']);
	$query->bindParam(':publish_date', $_POST['publish_date']);
	$query->bindParam(":idTicket", $_SESSION['id_ticket']);
	$query->bindParam(":chapo", $chapo, PDO::PARAM_STR);

	$query->execute();

	$allTags = explode(" ", $_POST["tags"]);

	$req = $bdd->prepare("DELETE FROM allTags WHERE idTicket = :idTicket");
	$req->bindParam(":idTicket", $_SESSION["id_ticket"]);
	$req->execute();

	foreach ($allTags as $key => $value)
	{
		if ($value != "")
		{
			$req = $bdd->prepare("INSERT INTO allTags SET name = :name, idTicket = :idTicket");
			$req->bindParam(":name", $value);
			$req->bindParam(":idTicket", $_SESSION["id_ticket"]);
			$req->execute();
		}
	}

	echo "Votre billet a bien été modifié.";
}

else
{
	echo "Vous ne pouvez pas accéder a cette page.";
}
?>