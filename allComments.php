<?php
include "header.php";

if (!isset($_SESSION["whichRight"]))
{
	echo "Vous n'avez pas les droits d'accéder a cette page.";
	return 1;
}

if ($_SESSION["whichRight"] != "Admin")
{
	echo "Vous n'avez pas les droits d'accéder a cette page.";
	return 1;
}

if (isset($_GET["delete"]))
{
	$req = $bdd->prepare("DELETE FROM comments WHERE id = :id");
	$req->bindParam(":id", $_GET["delete"]);
	$req->execute();

	echo "Le commentaire a bien été supprimé";
	return 0;
}

if (isset($_GET["sended"]))
{
	if (count($_POST) == 4)
	{
		$req = $bdd->prepare("UPDATE comments SET login = :login, content = :content, createDate = :createDate WHERE id = :id");
		$req->bindParam(":login", $_POST["login"]);
		$req->bindParam(":content", $_POST["content"]);
		$req->bindParam(":createDate", $_POST["createDate"]);
		$req->bindParam(":id", $_POST["id"]);
		$req->execute();

		echo "Le commentaire a bien été mis a jour.";
	}

	else
	{
		echo "Vous ne pouvez pas accéder a cette page.";
	}
	return 0;
}

if (isset($_GET["modif"]))
{
	$req = $bdd->prepare("SELECT * FROM comments WHERE id = :id");
	$req->bindParam(":id", $_GET["modif"]);
	$req->execute();

	echo "<div class='aComment'>";
	echo "<form method='post' action='allComments.php?sended=1'>";
	foreach ($req->fetchAll() as $key => $value)
	{
		echo "<textarea name='content'>". $value["content"] ."</textarea>";
		echo "<label>Auteur</label>";
		echo "<input name='login' type='text' value='". $value["login"] ."' />";
		echo "<label>Date</label>";
		echo "<input name='createDate' type='text' value='". $value["createDate"] ."' />";
		echo "<input type='hidden' name='id' value='". $_GET["modif"] ."' />";
		echo "<input type='submit' value='Modifier' />";
	}
	echo "</form>";
	echo "</div>";
	return 0;
}

$req = $bdd->prepare("SELECT * FROM comments");
$req->execute();

echo "<div class='allAdminComments'>";
echo "<div class='firstEachCommentAdmin'><p><span class='commentContent'>Contenu</span>Supprimer<span class='commentDate'>Modifier</span></p></div>";
foreach ($req->fetchAll() as $key => $value)
{
	echo "<div class='eachCommentAdmin'><p><span class='commentContent'>". $value["content"] ."</span><a href='allComments.php?delete=". $value["id"] ."'>Supprimer</a><span class='commentDate'><a href='allComments.php?modif=". $value["id"] ."'>Modifier</a></span></p></div>";
}
echo "</div>";

include "footer.php";
?>