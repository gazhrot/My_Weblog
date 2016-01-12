<?php

include "header.php";

if(isset($_POST["login"]) && isset($_POST["password"]))
{
	$login = "";

	$req = $bdd->prepare("SELECT login, id, firstname, rights, lastname, email, password, avatar FROM users WHERE login = :login OR email = :email");
	$req->bindParam(":login", $_POST["login"]);
	$req->bindParam(":email", $_POST["login"]);
	$req->execute();

	foreach ($req->fetchAll() as $key => $value)
	{
		if ($value["login"] == $_POST["login"] || $value["email"] == $_POST["login"])
		{
			$login = $value["login"];
			if ($value["password"] != md5($salt.$_POST["password"]))
			{
				echo "Le password que vous avez entré est incorrect.";
			}

			else
			{
				$_SESSION["connected"] = 1;
				$_SESSION["firstName"] = $value["firstname"];
				$_SESSION["id"] = $value["id"];
				$_SESSION["right"] = $value["rights"];
				$_SESSION["login"] = $value["login"];
				$_SESSION['avatar'] =$value['avatar'];			}
		}
	}

	if ($login == "")
	{
		echo "Le login/mail que vous avez entré est incorrect.";
	}
}

if (isset($_SESSION["connected"]))
{
	if ($_SESSION["connected"] == 1)
	{
		header("Location: index.php");
	}
}
?>
<div class="verticalAlign">
</div>
<form id="connectForm" method="post" action="connexion.php">
	<input type="text" name="login" placeholder="Login/email" />
	<input type="password" name="password" placeholder="Mot de passe" />
	<input type="submit" value="Se connecter" />
</form>
<?php
include('footer.php');
?>