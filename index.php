<?php
include("header.php");
?>

<?php

if (isset($_GET["seeComments"]))
{
	if (isset($_GET["moderated"]))
	{
		if ($_GET["moderated"] == 1)
		{
			$req = $bdd->prepare("UPDATE comments SET moderated = 1 WHERE idTicket = :idTicket");
			$req->bindParam(":idTicket", $_GET["seeComments"]);
			$req->execute();
		}

		else
		{
			$req = $bdd->prepare("UPDATE comments SET moderated = 0 WHERE idTicket = :idTicket");
			$req->bindParam(":idTicket", $_GET["seeComments"]);
			$req->execute();	
		}

		header("Location: index.php?seeComments=". $_GET["seeComments"]. "");
		return 0;
	}
	$req = $bdd->prepare("SELECT * FROM tickets LEFT JOIN category ON tickets.id_category = category.id_category WHERE tickets.id_ticket = :idTicket AND category.id_category = tickets.id_category");
	$req->bindParam(":idTicket", $_GET["seeComments"]);
	$req->execute();

	$reqTag = $bdd->prepare("SELECT name FROM allTags WHERE idTicket = :idTicket");
	$reqTag->bindParam(":idTicket", $_GET["seeComments"]);
	$reqTag->execute();

	foreach ($req->fetchAll() as $key => $value)
	{
		echo "<div class='center'>";
		echo "<div class='interContent'></div>";
		echo "<div class='preview'>\n";
		echo "<div class='title'>". htmlspecialchars($value['title']) ."</div>\n";
		echo "<div class ='tag'>";
		echo "<p class ='tagTitle'>Tag</p>";
		while ($result = $reqTag->fetch()) {
			echo "<p class='tagName'><a href='index.php?tag=".$result['name']."'>".$result['name']."</a></p>";
		}
		echo"</div>";
		echo "<div class='ticketContent'>\n";
		echo "<p>". $value['contents'] ."</p>\n";
		echo "</div>\n";
		echo "<div class='comLinks'>";
		if (isset($_SESSION["connected"]))
		{			
			echo "<a href='index.php?comment=". $value["id_ticket"] ."'>Poster un commentaire</a>";
			echo "<a href='index.php?seeArticle=".$value['id_ticket']."'>Lire plus</a>";

		}
		echo "</div>\n";
		echo "</div>\n";
	}

	$req = $bdd->prepare("SELECT * FROM comments WHERE idTicket = :idTicket");
	$req->bindParam(":idTicket", $_GET["seeComments"]);
	$req->execute();
	
	echo "<div class='allComments'>";
	foreach ($req->fetchAll() as $key => $value)
	{
		if ($value["moderated"] == 0)
		{
			echo "<div class='eachComment'>";
			if (isset($_SESSION["whichRight"]))
			{
				if ($_SESSION["whichRight"] == "Admin")
				{
					echo "<p class='authorComment'>". htmlspecialchars($value["login"]) ."<span class='commentDate'><a href='index.php?seeComments=". $_GET["seeComments"] ."&moderated=1'>Modérer</a>". $value["createDate"] ."</span></p>";
				}
				else
				{
					echo "<p class='authorComment'>". htmlspecialchars($value["login"]) ."<span class='commentDate'>". $value["createDate"] ."</span></p>";
				}
			}

			else
			{
				echo "<p class='authorComment'>". htmlspecialchars($value["login"]) ."<span class='commentDate'>". $value["createDate"] ."</span></p>";
			}
			echo "<p>". htmlspecialchars($value["content"]) ."</p>";
			echo "</div>";
		}

		else
		{
			echo "<div class='eachComment'>";
			echo "<p class='authorComment'>Admin<span class='commentDate'><a href='index.php?seeComments=". $_GET["seeComments"] ."&moderated=2'>Démodérer</a>". $value["createDate"] ."</span></p>";
			echo "<p class='moderated'>Ce commentaire a été modéré par un Admin.</p>";
			echo "</div>";	
		}
	}
	echo "</div>";
	echo "</div>";
	return 0;
}

if (isset($_GET["commented"]))
{
	if (isset($_POST["comment"]) && isset($_POST["idTicket"]) && isset($_SESSION["login"]))
	{
		if ($_POST["comment"] != "" && $_POST["idTicket"] != "")
		{
			$theDate = date("Y-m-d H:i:s");
			$req = $bdd->prepare("INSERT INTO comments SET login = :login, content = :content, idTicket = :idTicket, createDate = :theDate");
			$req->bindParam(":login", $_SESSION["login"]);
			$req->bindParam(":content", $_POST["comment"]);
			$req->bindParam(":idTicket", $_POST["idTicket"]);
			$req->bindParam(":theDate", $theDate);
			$req->execute();

			echo "Votre commentaire a bien été posté.";
			return 0;
		}

		else
		{
			echo "Vous n'avez pas remplis tout les champs.";
			return 1;
		}
	}

	else
	{
		echo "Vous n'avez pas le droit d'accéder a cette page.";
		return 1;
	}
}

if (isset($_GET["comment"]))
{
	if (!isset($_SESSION["connected"]))
	{
		echo "Vous devez vous <a href=connexion.php>connecter</a> pour accéder a cette page";
		return 1;
	}
	$req = $bdd->prepare("SELECT * FROM tickets LEFT JOIN category ON tickets.id_category = category.id_category WHERE tickets.id_ticket = :idTicket AND category.id_category = tickets.id_category");
	$req->bindParam(":idTicket", $_GET["comment"]);
	$req->execute();

	$reqTag = $bdd->prepare("SELECT name FROM allTags WHERE idTicket = :idTicket");
	$reqTag->bindParam(":idTicket", $_GET["comment"]);
	$reqTag->execute();

	foreach ($req->fetchAll() as $key => $value)
	{
		echo "<div class='center'>";
		echo "<div class='interContent'></div>";
		echo "<div class='preview'>\n";
		echo "<div class='title'>". htmlspecialchars($value['title']) ."</div>\n";
		echo "<div class ='tag'>";
		echo "<p class ='tagTitle'>Tag</p>";
		while ($result = $reqTag->fetch()) {
			echo "<p class='tagName'><a href='index.php?tag=".$result['name']."'>".$result['name']."</a></p>";
		}
		echo"</div>";
		echo "<div class='ticketContent'>\n";
		echo "<p>". $value['contents'] ."</p>\n";
		echo "</div>\n";
		echo "<div class='comLinks'>";
		if (isset($_SESSION["connected"]))
		{			
			echo "<a href='index.php?comment=". $value["id_ticket"] ."'>Poster un commentaire</a>";
		}
		echo "<a href='index.php?seeComments=". $value["id_ticket"] ."'>Voir les commentaires</a>";
		echo "<a href='index.php?seeArticle=".$value['id_ticket']."'>Lire plus</a>";
		echo "</div>\n";
		echo "</div>\n";
	}

	echo "<form method='post' action='index.php?commented=1'>";
	echo "<textarea class='comment' name='comment'></textarea>";
	echo "<input class='submitComment' type='submit' value='Envoyer' />";
	echo "<input type='hidden' name='idTicket' value='". $_GET["comment"] ."' />";
	echo "</form>";
	echo "</div>";
	return 0;
}

if (!isset($_GET['page']) || $_GET["page"] <= 0)
{
	$_GET["page"] = 1;
}

$ticketsNbr = 0;
if ($_GET["page"] == 1)
{
	$fromWhere = 0;
}

else
{
	$fromWhere = ($_GET["page"] - 1) * 10;
}
$date = date("Y-m-d H:i:s");
if (!isset($_GET['search']) && !isset($_GET['tag']) && !isset($_GET['seeArticle'])) {
	$reqPagin = $bdd->prepare("SELECT COUNT(*) FROM tickets");
	$reqPagin->execute();

	foreach ($reqPagin->fetchAll() as $key => $value)
	{
		$ticketsNbr = $value["COUNT(*)"];
	}

	$req1 = $bdd->prepare("SELECT chapo, id_ticket, validation, contents, login, title, category.name 
		FROM tickets 
		LEFT JOIN category 
		ON category.id_category = tickets.id_category 
		WHERE publish_date < :publishDate 
		ORDER BY create_date 
		DESC LIMIT :fromWhere, 10");
	$req1->bindParam(":fromWhere", $fromWhere, PDO::PARAM_INT);
	$req1->bindParam(":publishDate", $date);
	$req1->execute();

}
elseif (isset($_GET['tag']) && $_GET['tag'] != "" && !isset($_GET['search'])) {
	$sqlPaginTag = "SELECT COUNT(*)
	FROM tickets
	LEFT JOIN category
	ON category.id_category = tickets.id_category
	LEFT JOIN allTags
	ON idTicket = id_ticket
	WHERE publish_date < :publishDate
	AND allTags.name = :tag";
	$queryPaginTag = $bdd->prepare($sqlPaginTag);
	$queryPaginTag->bindParam(':tag', $_GET['tag'], PDO::PARAM_STR);
	$queryPaginTag->bindParam(":publishDate", $date);
	$queryPaginTag->execute();

	foreach ($queryPaginTag->fetchAll() as $key => $value)
	{
		$ticketsNbr = $value["COUNT(*)"];
	}
	$sqlTag = "SELECT chapo, id_ticket, validation, contents, login, title, category.name, allTags.name
	FROM tickets
	LEFT JOIN category
	ON category.id_category = tickets.id_category
	LEFT JOIN allTags
	ON idTicket = id_ticket
	WHERE publish_date < :publishDate
	AND allTags.name = :tag
	ORDER BY create_date
	DESC LIMIT :fromWhere, 10";
	$req1 = $bdd->prepare($sqlTag);
	$req1->bindParam(":fromWhere", $fromWhere, PDO::PARAM_INT);
	$req1->bindParam(':tag', $_GET['tag'], PDO::PARAM_STR);
	$req1->bindParam(":publishDate", $date);
	$req1->execute();
}
elseif (isset($_GET['seeArticle']) && $_GET['seeArticle'] != "" && !isset($_GET['search']) && !isset($_GET['tag'])){
	$ticketsNbr = 1;
	$req1 = $bdd->prepare("SELECT chapo, id_ticket, validation, contents, login, title, category.name 
		FROM tickets 
		LEFT JOIN category 
		ON category.id_category = tickets.id_category 
		WHERE publish_date < :publishDate 
		AND id_ticket = :idticket");
	$req1->bindParam(":publishDate", $date);
	$req1->bindParam(':idticket', $_GET['seeArticle'], PDO::PARAM_INT);
	$req1->execute();
}
else {
	$_GET['search'] = "%".$_GET['search']."%";
	$reqPagin1 = $bdd->prepare("SELECT COUNT(*)
		FROM tickets
		LEFT JOIN category
		ON category.id_category = tickets.id_category
		LEFT JOIN allTags
		ON idTicket = id_ticket
		WHERE publish_date < :publishDate
		AND tickets.title LIKE :search
		AND validation = 1
		OR login LIKE :search
		OR contents LIKE :search
		OR category.name LIKE :search
		OR allTags.name LIKE :search
		");
	$reqPagin1->bindParam(':search', $_GET['search'], PDO::PARAM_STR);
	$reqPagin1->bindParam(':publishDate', $date);
	$reqPagin1->execute();

	foreach ($reqPagin1->fetchAll() as $key => $value)
	{
		$ticketsNbr = $value["COUNT(*)"];
	}
	$req1 = $bdd->prepare("SELECT chapo, id_ticket, validation, contents, login, title, category.name, allTags.name AS tagName
		FROM tickets 
		LEFT JOIN category 
		ON category.id_category = tickets.id_category 
		LEFT JOIN allTags 
		ON idTicket = id_ticket
		WHERE publish_date < :publishDate
		AND tickets.title LIKE :search
		OR login LIKE :search
		OR contents LIKE :search
		OR category.name LIKE :search
		OR allTags.name LIKE :search
		GROUP BY id_ticket
		ORDER BY create_date 
		DESC LIMIT :fromWhere, 10");
	$req1->bindParam(":fromWhere", $fromWhere, PDO::PARAM_INT);
	$req1->bindParam(":publishDate", $date);
	$req1->bindParam(':search', $_GET['search'], PDO::PARAM_STR);
	$req1->execute();
}

?>
<div class="center">
	<?php
	foreach ($req1->fetchAll() as $key => $value)
	{
		if ($value["validation"] == 1 && !isset($_GET['seeArticle']))
		{
			$reqTag = $bdd->prepare("SELECT name FROM allTags WHERE idTicket = :id");
			$reqTag->bindParam(":id", $value["id_ticket"]);
			$reqTag->execute();

			echo "<div class='interContent'></div>";
			echo "<div class='preview'>\n";
			echo "<div class='title'>". htmlspecialchars($value['title']) ." ecrit par ".$value['login']."</div>\n";
			echo "<div class ='tag'>";
			echo "<p class ='tagTitle'>Tag</p>";
			while ($result = $reqTag->fetch()) {
				echo "<p class='tagName'><a href='index.php?tag=".$result['name']."'>".$result['name']."</a></p>";
			}
			echo"</div>";
			echo "<div class='ticketContent'>\n";
			echo "<p>". $value['chapo'] ."</p>\n";
			echo "</div>\n";
			echo "<div class='comLinks'>";
			if (isset($_SESSION["connected"]))
			{			
				echo "<a href='index.php?comment=". $value["id_ticket"] ."'>Poster un commentaire</a>";
			}
			echo "<a href='index.php?seeComments=". $value["id_ticket"] ."'>Voir les commentaires</a>";
			echo "<a href='index.php?seeArticle=".$value['id_ticket']."'>Lire plus</a>";
			echo "</div>\n";
			echo "</div>\n";
		}
		elseif($value['validation'] == 1 && isset($_GET['seeArticle']) && $_GET['seeArticle'] != "") {
			$reqTag = $bdd->prepare("SELECT name FROM allTags WHERE idTicket = :id");
			$reqTag->bindParam(":id", $value["id_ticket"]);
			$reqTag->execute();
			echo "<div class='interContent'></div>";
			echo "<div class='preview'>\n";
			echo "<div class='title'>". htmlspecialchars($value['title']) ." ecrit par ".$value['login']."</div>\n";
			echo "<div class ='tag'>";
			echo "<p class ='tagTitle'>Tag</p>";
			while ($result = $reqTag->fetch()) {
				echo "<p class='tagName'><a href='index.php?tag=".$result['name']."'>".$result['name']."</a></p>";
			}
			echo"</div>";
			echo "<div class='ticketContent2'>\n";
			echo "<p>". $value['contents'] ."</p>\n";
			echo "</div>\n";
			echo "<div class='comLinks'>";
			if (isset($_SESSION["connected"]))
			{			
				echo "<a href='index.php?comment=". $value["id_ticket"] ."'>Poster un commentaire</a>";
			}
			echo "<a href='index.php?seeComments=". $value["id_ticket"] ."'>Voir les commentaires</a>";
			echo "</div>\n";
			echo "</div>\n";
		}
	}
	echo "<div class='interContent'></div>";


	?>
	<div class='textCenter'>
		<?php
		$i = 10;
		while (($i - 10) < $ticketsNbr)
		{
			echo "<a href='index.php?page=". $i / 10 ."'>". $i / 10 ."</a>";
			$i += 10;
		}
		?>
	</div>
</div>
<?php 
include ('footer.php');
?>