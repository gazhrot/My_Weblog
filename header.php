<?php
try
{
	$bdd = new PDO("mysql:host=theradishparadise.tk; dbname=grivel_l_my_weblog; charset=utf8", "grivel_l", "7tm9TwhvA8OhGKwxQxD2", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $e)
{
	die("Erreur: ". $e->getMessage());
}
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>My weblog</title>
	<meta charset="UTF-8">
	<meta name="description" content="Projet web de trois super etudiants du samsung campus, site de bloging toutes catégories confondues">
	<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
</head>
<body>
    <header class="header">
    <?php
        if (isset($_SESSION["connected"]) && $_SESSION["connected"] == 1)
        {
          ?>
          <div class="connected">
             <?php
             if ($_SESSION["avatar"] != "")
             {
                echo "<img id='avatar' src=". $_SESSION['avatar'] ." alt='avatar' />";
             }
             if (!isset($_SESSION["whichRight"]))
             {
                $req = $bdd->prepare("SELECT whichRight FROM rights LEFT JOIN users ON users.rights = rights.id WHERE users.id = :id");
                $req->bindParam(":id", $_SESSION["id"]);
                $req->execute();

                foreach ($req->fetchAll() as $key => $value)
                {
                   $_SESSION["whichRight"] = $value["whichRight"];
                   echo $value["whichRight"];
               }
           }

           else
           {
            echo $_SESSION["whichRight"];
        }
        ?>
        <p>Connected</p>
        <p>Bonjour <?php echo $_SESSION["firstName"] ?></p>
        <a title="Deconnection" href="disconnect.php">Déconnection</a>
        <a title="Acces au profil" href="profil.php">Panel</a>
    </div>
    <?php
}
else {
?>
            <div id="connection">
                    <div>
                    </div>
                    <form id="connectForm" method="post" action="connexion.php">
                        <label>Login :</label>
                        <input type="text" name="login" placeholder="Login/email" />
                        <label>Mot de passe :</label>
                        <input type="password" name="password" placeholder="Mot de passe" />
                        <input type="submit" value="Se connecter" />
                    </form>
                </div>
        <?php
            }
        ?>
        <ul>
                <li><a href="index.php">Accueil</a></li>
                <?php 
                $salt = "kL,9T65fu{";
                if (!isset($_SESSION['connected'])) {

                    ?>
                    <li><a href="inscription.php">Inscription</a></li>
                    <!-- <div class="logo"><img src="images/css/logo.png"></div> -->
                    <?php
                }
                ?>
            </ul>
            <div id="searchBar">
                <form action="index.php">
                    <label>Recherche :
                    <input type="text" name="search" placeholder="titre, auteur, tag, catégorie"/>
                    </label>
                    <input type="submit" value="Rechercher" />
                </form>
            </div>
        </header>
        <section id="content">