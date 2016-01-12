<?php
    include('header.php');
    if (!isset($_SESSION['connected'])) {
?>
    <div id="registerForm">
        <form method="post" action="inscription.php">
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
                $pwd = md5($salt.$_POST['password']);
                $pwdCheck = md5($salt.$_POST['verifPassword']);
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
                                    $sql = "INSERT INTO users VALUES ('', :password, :firstname, :lastname, :login, :email, 'images/avatar/default.png', :adress, :age, :phone, '1')";
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
    else {
        ?>
            <p>Vous êtes déjà inscrit, vous allez être rediriger vers la page d'accueil dans 5 seconde</p>
        <?php
        header("Location: index.php");
    }
    include('footer.php');
?>