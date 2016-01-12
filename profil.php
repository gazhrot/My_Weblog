<?php
include('header.php');
?>
<div class="interContent"></div>
<div id='profil'>
<?php
if (isset($_SESSION["whichRight"]) && !isset($_GET["billet"]))
{
    if (1 == 1)
    {
        if (!isset($_GET['modif']))
        {
            $_GET['modif'] = 99;
        }
        if (isset($_SESSION['id']))
        {
            $sql = "SELECT id, firstname, lastname, login, email, avatar, adress, age, phone, rights FROM users WHERE id = :id";
            $query = $bdd->prepare($sql);
            $query->bindParam(':id', $_SESSION['id']);
            $query->execute();
            $result = $query->fetch();
            
            if ($result['avatar'] != "")
            {
                ?>
                <img src=<?php echo "\"".$result['avatar']."\"";?>>
                <?php
            }
            ?>
            <a href="profil.php?modif=4">Modifier vôtre avatar</a>
            <?php
            if ($_GET['modif'] == 4) {
                ?>
                <form enctype="multipart/form-data" method="post" action="profil.php?modif=4">
                    <label>Par URL :</label>
                    <input type ="text" name="avatarUpdate" placeholder="http://liendevotreimage.exemple">
                    <label>Par upload (jpeg ou png) :</label>
                    <input type="file" name="avatarUpdate1">
                    <input type ="submit" name="submitAvatar">
                </form>
                <?php
                if (isset($_POST['avatarUpdate']) && $_POST['avatarUpdate'] != "") {
                    if (filter_var($_POST['avatarUpdate'], FILTER_VALIDATE_URL) != false) {
                        $sqlUpAvatar = "UPDATE users SET avatar = :newavatar WHERE id = ".$_SESSION['id'];
                        $queryUpAvatar = $bdd->prepare($sqlUpAvatar);
                        $queryUpAvatar->bindParam(':newavatar', $_POST['avatarUpdate']);
                        $queryUpAvatar->execute();
                        $queryUpAvatar->closeCursor();
                        echo "Avatar mis à jour";
                    }
                    else {
                        echo "Veuillez entrer une URL valide";
                    }
                }
                elseif (isset($_POST['avatarUpdate1']) || isset($_FILES['avatarUpdate1'])) {
                    $extension = str_replace("image/", ".", $_FILES['avatarUpdate1']['type']);
                    $target = "images/avatar/".$result['id'].$extension;
                    echo is_readable($_FILES["avatarUpdate1"]["tmp_name"]);
                    if (move_uploaded_file($_FILES["avatarUpdate1"]["tmp_name"], $target) && $_FILES['avatarUpdate1']['type'] === "image/jpeg" || $_FILES['avatarUpdate1']['type'] === "image/png")
                    {
                        $sqlUpAvatar = "UPDATE users SET avatar = :newavatar1 WHERE id = ".$_SESSION['id'];
                        $queryUpAvatar = $bdd->prepare($sqlUpAvatar);
                        $queryUpAvatar->bindParam(':newavatar1', $target);
                        $queryUpAvatar->execute();
                        $queryUpAvatar->closeCursor();
                        echo "L'avatar a bien ete upload";
                    }
                    else {
                        echo "Veuillez choisir une image au format jpeg ou png.";
                    }
                }
                elseif (!isset($_POST['avatarUpdate'])) {
                    echo "";
                }
            }
            ?>
            <ul>
                <li>Nom : 
                    <?php
                    echo $result['lastname'];
                    ?>
                </li>
                <li>Prénom : 
                    <?php
                    echo $result['firstname'];
                    ?>
                </li>
                <li>Login : 
                    <?php
                    echo $result['login'];
                    ?>
                </li>
                <li>
                    Rang :
                    <?php
                    switch (true) {
                        case $result['rights'] == 1:
                        $result['rights'] = "Lecteur";
                        break;
                        case $result['rights'] == 2:
                        $result['rights'] = "Auteur";
                        break;
                        case $result['rights'] == 3:
                        $result['rights'] = "Administrateur";
                        break;
                        default:
                        $result['rights'] = $result['rights'];
                        break;
                    }
                    echo $result['rights'];
                    ?>
                </li>
                <li>E-mail : 
                    <?php
                    echo $result['email'];
                    ?>
                    <a href="profil.php?modif=1">Modifier l'addresse e-mail</a>
                    <?php
                    if ($_GET['modif'] == 1) {
                        ?>
                        <form method="post" action="profil.php?modif=1">
                            <label> Nouvelle adresse e-mail :
                                <input type="email" name="mailUpdate" placeholder= <?php echo "\"".$result['email']."\"";?>>
                            </label>
                            <label> Confirmez nouvelle adresse e-mail :
                                <input type="email" name="mailCheckUpdate">
                            </label>
                            <input type="submit" name ="submitMail">
                        </form>
                        <?php
                        if (isset($_POST['mailUpdate']) && isset($_POST['mailCheckUpdate'])) {
                            if (filter_var($_POST['mailUpdate'], FILTER_VALIDATE_EMAIL) != false && filter_var($_POST['mailCheckUpdate'], FILTER_VALIDATE_EMAIL) != false) {
                                if ($_POST['mailUpdate'] == $_POST['mailCheckUpdate']) {
                                    $sqlUpMail = "UPDATE users SET email = :newmail WHERE id = ".$_SESSION['id'];
                                    $queryUpMail = $bdd->prepare($sqlUpMail);
                                    $queryUpMail->bindParam(':newmail', $_POST['mailUpdate']);
                                    $queryUpMail->execute();
                                    $queryUpMail->closeCursor();
                                    echo "Adresse mail changée avec succès";
                                }
                                else {
                                    echo "Veuillez indiquer deux adresses e-mail indentiques.";
                                }
                            }
                            else {
                                echo "Veuillez indiquer une adresse e-mail au format exemple@exemple.exemple";
                            }
                        }
                        elseif (!isset($_POST['mailUpdate']) && !isset($_POST['mailCheckUpdate'])) {
                            echo "";
                        }
                        else {
                            echo "Veuillez indiquer une nouvelle adresse e-mail";// affichage erreurs à faire ne JS//
                        }
                    }
                    ?>
                </li>
                <li>Mot de passe :
                    <a href="profil.php?modif=5">Modifier votre mot de passe</a>
                    <?php
                    if ($_GET['modif'] == 5) {
                        ?>
                        <form method="post" action="profil.php?modif=5">
                            <label>Mot de passe actuel :
                                <input type="password" name="actualPassword">
                            </label>
                            <label>Nouveau mot de passe :
                                <input type="password" name="newPassword">
                            </label>
                            <label>Confirmez le nouveau mot de passe :
                                <input type="password" name="newPasswordCheck">
                            </label>
                            <input type="submit">
                        </form>
                        <?php
                        if (isset($_POST['actualPassword']) && isset($_POST['newPassword']) && isset($_POST['newPasswordCheck'])) {
                            $sql = "SELECT password FROM users WHERE id = :id";
                            $query = $bdd->prepare($sql);
                            $query->bindParam(':id', $_SESSION['id']);
                            $query->execute();
                            $result = $query->fetch();
                            if ($result['password'] == md5($salt.$_POST['actualPassword'])) {
                                if (md5($salt.$_POST['newPassword']) == md5($salt.$_POST['newPasswordCheck'])) {
                                    $sqlPwd = "UPDATE users SET password = :password WHERE id = :id";
                                    $queryPwd = $bdd->prepare($sqlPwd);
                                    $queryPwd->bindParam(':id', $_SESSION['id']);
                                    $queryPwd->bindParam(':password', md5($salt.$_POST['newPasswordCheck']));
                                    $queryPwd->execute();
                                    echo "Le mot de passe à bien été modifié";
                                }
                                else {                                        
                                    echo "Veuillez entrer deux mots de passe identiques dans les champs \"Nouveau mot de passe\" et \"Confirmez nouveau le mot de passe\".";
                                }
                            }
                            else {                                    
                                echo "Les mot de passe ne correspond pas à votre mot de passe actuel dans le champs \"Mot de passe actuel\".";
                            }
                        }
                        else {                                
                            echo "Veuillez remplir les champs pour poursuivre la modification du mot de passe";
                        }
                    }
                    ?>
                </li>
                <li>Adresse : 
                    <?php
                    echo $result['adress'];
                    ?>
                    <a href="profil.php?modif=2">Modifier l'addresse</a>
                    <?php
                    if ($_GET['modif'] == 2) {
                        ?>
                        <form method="post" action="profil.php?modif=2">
                            <label> Nouvelle adresse :
                                <input type="text" name="adressUpdate" placeholder= <?php echo "\"".$result['adress']."\""?>>
                            </label>
                            <input type="submit" name="submitAdress">
                        </form>
                        <?php
                        if (isset($_POST['adressUpdate']) && $_POST['adressUpdate'] != "") {
                            $sqlUpAdress = "UPDATE users SET adress = :newadress WHERE id = ".$_SESSION['id'];
                            $queryUpAdress = $bdd->prepare($sqlUpAdress);
                            $queryUpAdress->bindParam(':newadress', $_POST['adressUpdate']);
                            $queryUpAdress->execute();
                            $queryUpAdress->closeCursor();
                            echo "Adresse mise à jour.";
                        }
                        elseif (!isset($_POST['adressUpdate'])) {
                            echo "";
                        }
                        else {
                            echo "Veuillez renseigner une nouvelle adresse.";
                        }
                    }
                    ?>
                </li>
                <li>Age : 
                    <?php
                    echo $result['age'];
                    ?>
                </li>
                <li>Téléphone : 
                    <?php
                    echo $result['phone'];
                    ?>
                    <a href="profil.php?modif=3">Modifier le numéro</a>
                    <?php
                    if ($_GET['modif'] == 3) {
                        ?>
                        <form method="post" action="profil.php?modif=3">
                            <label> Nouveaux numéro :
                                <input type="number" name="phoneUpdate" placeholder=<?php echo "\"".$result['phone']."\""?>>
                            </label>
                            <input type="submit" name="submitPhone">
                        </form>
                        <?php
                        if (isset($_POST['phoneUpdate']) && $_POST['phoneUpdate'] != "") {
                            $sqlUpPhone = "UPDATE users SET phone = :newphone WHERE id = ".$_SESSION['id'];
                            $queryUpPhone = $bdd->prepare($sqlUpPhone);
                            $queryUpPhone->bindParam(':newphone', $_POST['phoneUpdate']);
                            $queryUpPhone->execute();
                            $queryUpPhone->closeCursor();
                            echo "Numéro de telephone mis à jour";
                        }
                        elseif (!isset($_POST['phoneUpdate'])) {
                            echo "";
                        }
                        else {
                            echo "Veuillez renseigner un nouveau numéro de téléphone";
                        }
                    }
                }
                ?>
            </li>
        </ul>
    </form>
    </div>
    <?php
    if ($_SESSION["whichRight"] == "Author")
    {
        echo "<a href='profil.php?billet=1'>Voir/modifier mes billets</a>";
        echo "<p><a title='Modifier un billet' href='billets.php'>Ajouter un billet</a></p>";
    }
}
if ($_SESSION["whichRight"] == "Admin")
{
    echo "<div id='panelAdmin'>";
    echo "<p><a title='Modifier un utilisateur' href='allUsers.php'>Modifier/Ajouter/Supprimer un utilisateur</a></p>";
    echo "<p><a title='Modifier un billet' href='allTickets.php'>Modifier/Ajouter/Supprimer un billet</a></p>";
    echo "<p><a title='Modifier un commentaire' href='allComments.php'>Modifier/Ajouter/Supprimer un commentaire</a></p>";
    echo "</div>";
}
}

else if (isset($_SESSION["whichRight"]) && isset($_GET["billet"]) && !isset($_GET["id"]))
{
    if ($_SESSION["whichRight"] == "Author")
    {
        $req = $bdd->prepare("SELECT * FROM tickets WHERE login = :login");
        $req->bindParam(":login", $_SESSION["login"]);
        $req->execute();

        foreach ($req->fetchAll() as $key => $value)
        {
            echo "<div class='preview'>\n";
            echo "<a href='billets.php?idTicket=". $value["id_ticket"] ."'>Modifier</a>";
            echo "<label>". $value['title'] ."</label>\n";
            echo "<div>\n";
            echo "<label>". $value['login'] ."</label>\n";
            echo "</div>\n";
            echo "<div>\n";
            echo "<p>". $value['contents'] ."</p>\n";
            echo "</div>\n";
            echo "</div>\n";
        }
    }
}

else
{
    echo "Vous devez vous <a href='connexion.php'>connecter</a> avant de pouvoir accéder a cette page.";
}
?>
<?php
include('footer.php');
?>