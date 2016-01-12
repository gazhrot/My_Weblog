<?php
include "header.php";

function showInputs($bdd)
{
	$req = $bdd->prepare("SELECT * FROM users WHERE id = :id");
	$req->bindParam(":id", $_GET["id"]);
	$req->execute();
	?>
	<form method="POST" action="allUsers.php?id=<?php echo $_GET['id']; ?>"> 
		<?php
		foreach ($req->fetchAll() as $key => $value)
		{
			?>
			<p>
				<label>Prénom: </label>
				<?php
				echo "<input name='firstName' type='text' value='". $value["firstname"] ."' />";
				?>
			</p>
			<p>
				<label>Nom: </label>
				<?php
				echo "<input name='lastName' type='text' value='". $value["lastname"] ."' />";
				?>
			</p>
			<p>
				<label>Login: </label>
				<?php
				echo "<input name='login' type='text' value='". $value["login"] ."' />";
				?>
			</p>
			<p>
				<label>Email: </label>
				<?php
				echo "<input name='email' type='text' value='". $value["email"] ."' />";
				?>
			</p>
			<p>
				<label>Avatar: </label>
				<?php
				echo "<input name='avatar' type='text' value='". $value["avatar"] ."' />";
				?>
			</p>
			<p>
				<label>Adresse: </label>
				<?php
				echo "<input name='adress' type='text' value='". $value["adress"] ."' />";
				?>
			</p>
			<p>
				<label>Age: </label>
				<?php
				echo "<input name='age' type='text' value='". $value["age"] ."' />";
				?>
			</p>
			<p>
				<label>Numéro de téléphone: </label>
				<?php
				echo "<input name='phone' type='text' value='". $value["phone"] ."' />";
				?>
			</p>
			<p>
				<label>Droits: </label>
				<?php
				echo "<input name='rights' type='text' value='". $value["rights"] ."' />";
				?>
			</p>
			<p>
				<label>Mot de passe: </label>
				<?php
				echo "<input name='password' type='text' value='". $value["password"] ."' />";
				echo "<input name='oldPassword' type='hidden' value='". $value["password"] ."' />";
				?>
			</p>
			<?php
		}
		$req->closeCursor();
		?>
		<input type="submit" value="Envoyer" />
	</form>
	<?php
}

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

if (!isset($_GET["id"]) && !isset($_GET["delete"]) && !isset($_GET["createUser"]))
{
	echo "<a id='createUser' href='allUsers.php?createUser=1'>Créer un utilisateur</a>";
	if (isset($_GET["deleted"]))
	{
		if ($_GET["deleted"] == 1)
		{
			if (isset($_SESSION["deleteUser"]) && $_SESSION["deleteUser"] == 1)
			{
				$_SESSION["deleteUser"] = 0;

				$req = $bdd->prepare("SELECT login FROM users WHERE id = :id");
				$req->bindParam(":id", $_SESSION["deleteUserId"]);
				$req->execute();

				$login = "";

				foreach ($req->fetchAll() as $key => $value)
				{
					$login = $value["login"];
				}

				$req = $bdd->prepare("DELETE FROM tickets WHERE login = :login");
				$req->bindParam(":login", $login);
				$req->execute();

				$req = $bdd->prepare("DELETE FROM users WHERE id = :id");
				$req->bindParam(":id", $_SESSION["deleteUserId"]);
				$req->execute();

				$req->closeCursor();
			}
		}
	}

	if (!isset($_GET["sort"]))
	{
		$req = $bdd->prepare("SELECT whichRight, users.id, firstName, lastName, login FROM users LEFT JOIN rights ON users.rights = rights.id");
		$req->execute();
	}

	elseif ($_GET["sort"] == "rights")
	{
		$req = $bdd->prepare("SELECT whichRight, users.id, firstName, lastName, login FROM users LEFT JOIN rights ON users.rights = rights.id ORDER BY rights.id DESC");
		$req->execute();
	}

	elseif ($_GET["sort"] == "login")
	{
		$req = $bdd->prepare("SELECT whichRight, users.id, firstName, lastName, login FROM users LEFT JOIN rights ON users.rights = rights.id ORDER BY login");
		$req->execute();	
	}

	elseif ($_GET["sort"] == "firstName")
	{
		$req = $bdd->prepare("SELECT whichRight, users.id, firstName, lastName, login FROM users LEFT JOIN rights ON users.rights = rights.id ORDER BY firstName");
		$req->execute();	
	}

	elseif ($_GET["sort"] == "lastName")
	{
		$req = $bdd->prepare("SELECT whichRight, users.id, firstName, lastName, login FROM users LEFT JOIN rights ON users.rights = rights.id ORDER BY lastName");
		$req->execute();	
	}

	else
	{
		$req = $bdd->prepare("SELECT whichRight, users.id, firstName, lastName, login FROM users LEFT JOIN rights ON users.rights = rights.id");
		$req->execute();	
	}

	?>
	<div id='allUsers'>
		<p id="firstColumn"><span class='eachColumn'><a href="allUsers.php?sort=login">Login</a></span><span class='eachColumn'><a href="allUsers.php?sort=firstName">Prénom</a></span><span class='eachColumn'><a href="allUsers.php?sort=lastName">Nom</a></span><span class='eachColumn'><a href="allUsers.php?sort=rights">Droits</a></span><span class='eachColumn'>Modifier</span></p>
		<?php
		foreach ($req->fetchAll() as $key => $value)
		{
			echo "<p><span class='eachColumn'>". $value["login"] ."</span><span class='eachColumn'>". $value["firstName"] ."</span><span class='eachColumn'>". $value["lastName"] ."</span><span class='eachColumn'>". $value["whichRight"] ."</span><span class='eachColumn'><a href='allUsers.php?id=". $value["id"] ."'>Modifier</a></span></p>\n";
		}
		?>
	</div>
	<?php
	$req->closeCursor();
}

elseif (isset($_GET["delete"]))
{
	$req = $bdd->prepare("SELECT firstname, lastname FROM users WHERE id = :id");
	$req->bindParam(":id", $_GET["delete"]);
	$req->execute();

	foreach ($req->fetchAll() as $key => $value)
	{
		$_SESSION["deleteUser"] = 1;
		$_SESSION["deleteUserId"] = $_GET["delete"];
		echo "Etes vous sur de vouloir supprimer l'utilisateur ". $value["firstname"] ." ". $value["lastname"]." ?";
		echo "<a title=\"Confimer la supression de l'utilisateur\" href=\"allUsers.php?deleted=1\"><input type='button' value=\"Supprimer l'utilisateur\" /></a>";
	}

	$req->closeCursor();
}

elseif (isset($_GET["createUser"]))
{
	?>
	<div id="registerForm">
		<form method="post" action="allUsers.php?createUser=1">
			<span class ="textAreas">
				<label>Nom : *
					<input type="text" name="lastname" placeholder ="ex :Dupon">
				</label>
			</span>
			<span class ="textAreas">
				<label>Prenom : *
					<input type="text" name="firstname" placeholder="ex :Charles">
				</label>
			</span>
			<span class ="textAreas">
				<label>Login : *
					<input type="text" name="login" placeholder="ex : kevindu92x-X-x">
				</label>
			</span>
			<span class ="textAreas">
				<label>Mot de passe (minimum 8 caractères) : *
					<input type="password" name="password">
				</label>
			</span>
			<span class ="textAreas">
				<label>Vérification du mot de passe : *
					<input type="password" name="verifPassword">
				</label>
			</span>
			<span class ="textAreas">
				<label>Adresse e-mail : *
					<input type="email" name="email" placeholder="exemple@exemple.exemple">
				</label>
			</span>
			<span class ="textAreas">
				<label>Vérification adresse e-mail : *
					<input type="email" name="verifEmail" placeholder="exemple@exemple.exemple">
				</label>
			</span>
			<span class="textAreas">
				<label>Adresse :
					<input type="text" name="adress">
				</label>
			</span>
			<span class="textAreas">
				<label>Age :
					<input type="number" name="age">
				</label>
			</span>
			<span class="textAreas">
				<label>Téléphone :
					<input type="number" name="phone">
				</label>
			</span>
			<span>
				<input id ="submitRegister" type="submit" name="submitRegister">
			</span>
		</form>
	</div>
	<section>
		<?php
		if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['login']) && isset($_POST['password']) && isset($_POST['verifPassword']) && isset($_POST['email']) && isset($_POST['verifEmail'])) {
			$pwd = md5($_POST['password']);
			$pwdCheck = md5($_POST['verifPassword']);
			$sql = "SELECT email, login FROM users WHERE login = :login OR email = :email";
			$query = $bdd->prepare($sql);
			$query->bindParam(':login', $_POST['login']);
			$query->bindParam(':email', $_POST['email']);
			$query->execute();
			if ($query->fetch() == false) {
				$query->closeCursor();
				if ($pwd === $pwdCheck) {
					if ($_POST['email'] === $_POST['verifEmail']) {
						if (strlen($_POST['password']) >= 8) {
							if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) != false) {
								$sql = "INSERT INTO users VALUES ('', :password, :firstname, :lastname, :login, :email, '', :adress, :age, :phone, '1')";
								$query = $bdd->prepare($sql);
								$query->bindParam(':firstname', $_POST['firstname']);
								$query->bindParam(':lastname', $_POST['lastname']);
								$query->bindParam(':password', $pwd);
								$query->bindParam(':login', $_POST['login']);
								$query->bindParam(':email', $_POST['email']);
								$query->bindParam(':adress', $_POST['adress']);
								$query->bindParam(':age', $_POST['age']);
								$query->bindParam(':phone', $_POST['phone']);
								$query->execute();
								$query->closeCursor();
								$login = strip_tags($_POST['login']);
								echo "<p>Bonjour ".$login. " vous êtes bien inscrit sur myWebLog ! Amusez vous bien !</p>";
							}
							else {
								echo "<p>Veuillez entrer une adresse mail valide au format exemple@exemple.exemple</p>";
							}
						}
						else {
							echo "<p>Votre mot de passe doit contenir au minimum 8 caractères</p>";
						}
					}
					else {
						echo "<p>Veuillez entrer la même adresse mail dans les deux champs</p>";
					}
				}
				else {
					echo "<p>Veuillez entrer le même mot de passe dans les deux champs</p>";
				}
			}
			else {
				echo "<p>Ce compte utilisateur existe déjà</p>";
			}
		}
		elseif (!isset($_POST['firstname']) && !isset($_POST['lastname']) && !isset($_POST['login']) && !isset($_POST['password']) && !isset($_POST['verifPassword']) && !isset($_POST['email']) && !isset($_POST['verifEmail'])){
			echo "";
		}
		else {
                echo "<p>Veuillez remplire tout les champs présents pour valider votre inscription</p>"; // JS pour l'affichage des erreurs//
            }
            ?>
        </section>
        <?php
    }

    else
    {
    	if (count($_POST) > 0)
    	{
    		if ($_POST["password"] != $_POST["oldPassword"])
    		{
    			$password = md5($salt ."". $_POST["password"]);
    		}

    		else
    		{
    			$password = $_POST["password"];
    		}

    		$age = (int)$_POST["age"];
    		$rights = (int)$_POST["rights"];
    		$id = (int)$_GET["id"];

    		$req = $bdd->prepare("UPDATE users SET password = :password, firstname = :firstName, lastname = :lastName, login = :login, email = :email, avatar = :avatar, adress = :adress, age = :age, phone = :phone, rights = :rights WHERE id = :id");
    		$req->bindParam(":password", $password);
    		$req->bindParam(":firstName", $_POST["firstName"]);
    		$req->bindParam(":lastName", $_POST["lastName"]);
    		$req->bindParam(":login", $_POST["login"]);
    		$req->bindParam(":email", $_POST["email"]);
    		$req->bindParam(":avatar", $_POST["avatar"]);
    		$req->bindParam(":adress", $_POST["adress"]);
    		$req->bindParam(":age", $age, PDO::PARAM_INT);
    		$req->bindParam(":phone", $_POST["phone"]);
    		$req->bindParam(":rights", $rights, PDO::PARAM_INT);
    		$req->bindParam(":id", $id, PDO::PARAM_INT);
    		$req->execute();

    		showInputs($bdd);
    		echo "Vos modifications ont bien été modifiées.";
    	}

    	else
    	{
    		?>
    		<a title="Supprimer cet utilisateur" href="allUsers.php?delete=<?php echo $_GET['id'] ?>">Supprimer l'utilisateur</a>
    		<?php
    		showInputs($bdd);
    	}
    }


    include "footer.php";
    ?>