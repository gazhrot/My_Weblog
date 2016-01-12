<?php

include ('header.php');

if (!isset($_SESSION["connected"]))
{
	echo "Vous devez vous <a href='connexion.php'>connecter</a> avant de pouvoir accéder a cette page.";
	return 1;
}

if ($_SESSION["whichRight"] == "Reader")
{
	echo "Vous ne pouvez pas accéder a cette page en tant que Lecteur.";
	return 1;
}

?>

<?php
if (count($_POST) >= 3 && !isset($_GET["category"]) && !isset($_GET["tag"]))
{
	if ($_POST["title"] == "")
	{
		echo "Vous devez préciser un titre dans votre ticket.";
		return 1;
	}

	if ($_POST["publish_date"] == "" || strlen($_POST["publish_date"]) != 19)
	{
		$_POST["publish_date"] = "0000-00-00 00:00:00";
	}

	if ($_POST["category"] == "Catégorie")
	{
		echo "Vous devez préciser une catégorie";
		return 1;
	}

	$req = $bdd->prepare("SELECT id_category FROM category WHERE name = :category");
	$req->bindParam(":category", $_POST["category"]);
	$req->execute();

	foreach ($req->fetchAll() as $key => $value)
	{
		$idCategory = (int)$value["id_category"];
	}

	$allTags = explode(" ", $_POST["tags"]);
	$oldAllTags = explode(" ", $_POST["tags"]);
	$req = $bdd->prepare("SELECT name FROM tag");
	$req->execute();

	foreach ($req->fetchAll() as $key => $value)
	{
		foreach ($allTags as $key2 => $value2)
		{
			if ($value["name"] == $value2)
			{
				$allTags[$key2] = "";
			}
		}
	}

	foreach ($allTags as $key => $value)
	{
		if ($value != "")
		{
			$req = $bdd->prepare("INSERT INTO tag SET name = :name");
			$req->bindParam(":name", $value);
			$req->execute();
		}
	}

	$theDate = date("Y-m-d H:i:s");
	$chapo = substr($_POST['content'], 0, 150)."...";
	$req = $bdd->prepare("INSERT INTO tickets SET contents = :content, login = :login, create_date = :createDate, publish_date = :publishDate, title = :title, validation = 0, id_category = :idCategory, chapo = :chapo");
	$req->bindParam(":content", $_POST["content"]);
	$req->bindParam(":login", $_SESSION["login"]);
	$req->bindParam(":createDate", $theDate);
	$req->bindParam(":publishDate", $_POST["publish_date"]);
	$req->bindParam(":title", $_POST["title"]);
	$req->bindParam(":idCategory", $idCategory, PDO::PARAM_INT);
	$req->bindParam(":chapo", $chapo, PDO::PARAM_STR);
	$req->execute();

	$req = $bdd->prepare("SELECT id_ticket FROM tickets WHERE create_date = :theDate AND login = :login");
	$req->bindParam(":theDate", $theDate);
	$req->bindParam(":login", $_SESSION["login"]);
	$req->execute();

	$idTicket = "";

	foreach ($req->fetchAll() as $key => $value)
	{
		$idTicket = $value["id_ticket"];
	}

	foreach ($oldAllTags as $key => $value)
	{
		if ($value != "")
		{
			$req = $bdd->prepare("INSERT INTO allTags SET name = :name, idTicket = :idTicket");
			$req->bindParam(":name", $value);
			$req->bindParam(":idTicket", $idTicket);
			$req->execute();
		}
	}

	echo "Votre billet a bien été envoyé.";

}

if (!isset($_GET["idTicket"]))
{
	unset($_SESSION["id_ticket"]);
}

if (!isset($_SESSION['id_ticket']) && !isset($_GET["idTicket"]) && !isset($_GET["category"]) && !isset($_GET["tag"]))
	{ ?>
<script type="text/javascript" src="script/createTickets.js"></script>
<div class="center">
<div class="interContent"></div>
<h1>Poster un nouveau billet :</h1>
<form method="post" id="createTicketForm">
	<label>Titre : </label>
	<input type="text" name="title">
	<label>Catégorie: </label>
	<select name="category">
		<option>Catégorie</option>
		<?php
		$req = $bdd->prepare("SELECT name FROM category");
		$req->execute();
		foreach ($req->fetchAll() as $key => $value)
		{
			echo "<option>". $value["name"] ."</option>";
		}
		?>
	</select>
	<label>Tags :</label>
	<input type="text" name="tags">
	<label>Contenu :</label>


	<input id="bold" class="textButton" type="button" value="B" />
	<input id="underline" class="textButton" type="button" value="S" />
	<input id="italic" class="textButton" type="button" value="I" />
	<select id="colors"></select>
	<input class="textButton" type="button" value="Lien" id="link" />
	<input class="textButton" type="button" value="Ajouter une vidéo" id="embedVideo" />
	<input class="textButton" type="button" value="Ajouter une image" id="addImage" />
	<div id="textAreaDiv" class="textArea" contenteditable="true"></div>

	<input id="textAreaContent" type="hidden" name="content" value="" />

	<label>Date de Publication : </label>
	<input type="text" placeholder="YYYY-MM-DD HH:MM:SS" name="publish_date">
	<button type="submit">Publier</button>
</form>
</div>

<?php
}
elseif (isset($_SESSION['id_ticket']) || isset($_GET["idTicket"]) && !isset($_GET["category"]) && !isset($_GET["tag"]))
{
	?>
	<script type="text/javascript" src="script/createTickets.js"></script>
	<?php
	if (isset($_GET["idTicket"]))
	{
		$_SESSION["id_ticket"] = $_GET["idTicket"];
	}

	$query = $bdd->prepare("SELECT * FROM tickets WHERE id_ticket = :idTicket");
	$query->bindParam(":idTicket", $_SESSION['id_ticket']);
	$query->execute();

	while($result = $query->fetch())
	{
		?>
		<label>Edition :</label>
		<div class="preview">
			<form method="POST" action="updateBillets.php" id="createTicketForm" >
				<label>Titre :</label>
				<input type="text" value="<?php echo $result['title']; ?>" name="title"/>
				<label>Date de Publication : </label>
				<input type="date" value="<?php echo $result['publish_date']; ?>" name="publish_date"/>
				<label>Contenue :</label>

				<input id="bold" class="textButton" type="button" value="B" />
				<input id="underline" class="textButton" type="button" value="S" />
				<input id="italic" class="textButton" type="button" value="I" />
				<select id="colors"></select>
				<input class="textButton" type="button" value="Lien" id="link" />
				<input class="textButton" type="button" value="Ajouter une vidéo" id="embedVideo" />
				<input class="textButton" type="button" value="Ajouter une image" id="addImage" />
				<div id="textAreaDiv" class="modifiedTextArea" contenteditable="true"><p><?php echo $result["contents"]; ?></p></div>

				<input id="textAreaContent" type="hidden" name="content" value="" />

				<!-- <textarea name="contents"><?php #echo $result['contents']; ?></textarea> -->

				<label>Tags :</label>
				<?php
				$req = $bdd->prepare("SELECT name FROM allTags WHERE idTicket = :idTicket");
				$req->bindParam(":idTicket", $_GET["idTicket"], PDO::PARAM_INT);
				$req->execute();
				echo "<input type='text' value='";
				foreach ($req->fetchAll() as $key => $value)
				{
					echo "". $value['name'] ." ";
				}
				echo "' name='tags' />";
				?>
				<button type="submit">Mettre a Jour</button>
			</form>
		</div>
		<?php
	}
}

if (isset($_GET["category"]) && isset($_POST["modifiedCategory"]))
{
	$req = $bdd->prepare("UPDATE category SET name = :newName WHERE name = :oldName");
	$req->bindParam(":oldName", $_POST["oldCategory"]);
	$req->bindParam(":newName", $_POST["modifiedCategory"]);
	$req->execute();
}

if (isset($_GET["category"]) && isset($_POST["modifCategory"]))
{
	echo "<form method='post' action='billets.php?category=1'>";
	echo "<input type='text' name='modifiedCategory' value='". $_POST['modifCategory'] ."' />";
	echo "<input type='hidden' name='oldCategory' value='". $_POST['modifCategory'] ."' />";
	echo "<input type='submit' value='Modifier' />";
	echo "</form>";
	return 0;
}

if (isset($_GET["category"]) && isset($_POST["addCategory"]))
{
	$idCategory = 0;
	$req = $bdd->prepare("SELECT name, id_category FROM category");
	$req->execute();

	foreach ($req->fetchAll() as $key => $value)
	{
		if ($value["id_category"] > $idCategory)
		{
			$idCategory = (int)$value["id_category"];
		}

		if ($value["name"] == $_POST["addCategory"])
		{
			echo "Cette catégorie existe déja";
			return 1;
		}
	}

	$idCategory += 1;

	$req = $bdd->prepare("INSERT INTO category SET name = :categoryName, id_category = :idCategory");
	$req->bindParam(":categoryName", $_POST["addCategory"]);
	$req->bindParam(":idCategory", $idCategory, PDO::PARAM_INT);
	$req->execute();

	echo "La catégorie '". $_POST["addCategory"] ."' a bien été ajouté";
}

if (isset($_GET["category"]) && isset($_POST["delCategory"]))
{
	if ($_POST["delCategory"] != "Supprimer une catégorie")
	{
		$req = $bdd->prepare("UPDATE tickets LEFT JOIN category ON tickets.id_category = category.id_category SET tickets.id_category = 0 WHERE category.name = :name");
		$req->bindParam(":name", $_POST["delCategory"]);
		$req->execute();

		$req = $bdd->prepare("DELETE FROM category WHERE name = :categoryName");
		$req->bindParam(":categoryName", $_POST["delCategory"]);
		$req->execute();

		echo "La catégorie '". $_POST["delCategory"] ."' a bien été supprimé";
	}	
}

if (isset($_GET["category"]) && $_SESSION["whichRight"] == "Admin")
{
	echo "<form method='post' action='billets.php?category=1'>";
	echo "<p>Ajouter une catégorie: </p>";
	echo "<input type='text' name='addCategory' />";
	echo "<input type='submit' value='Ajouter' />";
	echo "</form>";

	$req = $bdd->prepare("SELECT name FROM category");
	$req->execute();

	echo "<form method='post' action='billets.php?category=1'>";
	echo "<select name='delCategory'>";
	echo "<option>Supprimer une catégorie</option>";
	foreach ($req->fetchAll() as $key => $value)
	{
		echo "<option>". $value["name"] ."</option>";
	}
	echo "</select>";
	echo "<input type='submit' value='Supprimer' />";
	echo "</form>";

	$req = $bdd->prepare("SELECT name FROM category");
	$req->execute();

	echo "<form method='post' action='billets.php?category=1'>";
	echo "<select name='modifCategory'>";
	echo "<option>Modifier une catégorie</option>";
	foreach ($req->fetchAll() as $key => $value)
	{
		echo "<option>". $value["name"] ."</option>";
	}
	echo "</select>";
	echo "<input type='submit' value='Modifier' />";
	echo "</form>";
}

if (isset($_GET["tag"]) && isset($_POST["modifiedTag"]))
{
	$req = $bdd->prepare("UPDATE tag SET name = :newName WHERE name = :oldName");
	$req->bindParam(":oldName", $_POST["oldTag"]);
	$req->bindParam(":newName", $_POST["modifiedTag"]);
	$req->execute();
}

if (isset($_GET["tag"]) && isset($_POST["modifTag"]))
{
	echo "<form method='post' action='billets.php?tag=1'>";
	echo "<input type='text' name='modifiedTag' value='". $_POST['modifTag'] ."' />";
	echo "<input type='hidden' name='oldTag' value='". $_POST['modifTag'] ."' />";
	echo "<input type='submit' value='Modifier' />";
	echo "</form>";
	return 0;
}

if (isset($_GET["tag"]) && !isset($_GET["category"]) && isset($_POST["delTag"]))
{
	if ($_POST["delTag"] != "Supprimer un tag")
	{
		$req = $bdd->prepare("DELETE FROM tag WHERE name = :tagName");
		$req->bindParam(":tagName", $_POST["delTag"]);
		$req->execute();

		echo "Le tag '". $_POST["delTag"] ."' a bien été supprimé.";
	}
}

if (isset($_GET["tag"]) && !isset($_GET["category"]) && isset($_POST["addTag"]))
{
	if ($_POST["addTag"] != "")
	{
		$req = $bdd->prepare("SELECT name FROM tag");
		$req->execute();

		foreach ($req->fetchAll() as $key => $value)
		{
			if (strtolower($value["name"]) == strtolower($_POST["addTag"]))
			{
				echo "Le tag existe déja.";
				return 1;
			}
		}

		$req = $bdd->prepare("INSERT INTO tag SET name = :tagName");
		$req->bindParam(":tagName", $_POST["addTag"]);
		$req->execute();

		echo "Le tag '". $_POST["addTag"] . "' a bien été ajouté.";
	}
}

if (!isset($_GET["category"]) && isset($_GET["tag"]) && $_SESSION["whichRight"] == "Admin")
{
	echo "<form method='post' action='billets.php?tag=1'>";
	echo "<p>Ajouter un tag: </p>";
	echo "<input type='text' name='addTag' />";
	echo "<input type='submit' value='Ajouter' />";
	echo "</form>";

	$req = $bdd->prepare("SELECT name FROM tag");
	$req->execute();

	echo "<form method='post' action='billets.php?tag=1'>";
	echo "<select name='delTag'>";
	echo "<option>Supprimer un tag</option>";
	foreach ($req->fetchAll() as $key => $value)
	{
		echo "<option>". $value["name"] ."</option>";
	}
	echo "</select>";
	echo "<input type='submit' value='Supprimer' />";
	echo "</form>";

	$req = $bdd->prepare("SELECT name FROM tag");
	$req->execute();

	echo "<form method='post' action='billets.php?tag=1'>";
	echo "<select name='modifTag'>";
	echo "<option>Modifier un tag</option>";
	foreach ($req->fetchAll() as $key => $value)
	{
		echo "<option>". $value["name"] ."</option>";
	}
	echo "</select>";
	echo "<input type='submit' value='Modifier' />";
	echo "</form>";
}

include('footer.php');
?>