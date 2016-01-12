<?php
include "header.php";
if (!isset($_SESSION["whichRight"]))
{
    echo "Vous n'avez pas les droits d'accéder a cette page";
    return 1;
}

if ($_SESSION["whichRight"] == "Admin")
{
    if ($_SESSION["whichRight"] != "Admin") {
        header("location : profil.php");
    }
    ?>
    <div class='interContent'></div>
    <form method="get" action="allTickets.php" class="adminForm">
        <label>Afficher la liste des billets :</label>
        <label>Type de billet :</label>
            <select name="validation">
                <option id ="valid0" value="0">Non validé</option>
                <option id ="valid1" value="1">Validé</option>
            </select>
        <label>Billet/page :</label>
            <select name="limit">
                <option id="limit5">5</option>
                <option id="limit10">10</option>
                <option id="limit15">15</option>
                <option id="limit20">20</option>
                <option id="limit25">25</option>
                <option id="limit30">30</option>
                <option id="limit35">35</option>
                <option id="limit40">40</option>
                <option id="limit45">45</option>
                <option id="limit50">50</option>
            </select>
        <input type="submit" value="Rechercher">
        <a href="billets.php">Créer un nouveau billet</a>
        <a href="billets.php?category=1">Ajouter/Supprimer/Modifier une catégorie</a>
        <a href="billets.php?tag=1">Ajouter/Supprimer/Modifier un tag</a>
    </form>
                <div class="center">
    <?php
    if ($_SESSION["whichRight"] != "Admin" || !isset($_SESSION['connected'])) {
        header("location : profil.php");
    }
    if (isset($_GET['limit'])) {
        $_GET['limit'] = (int)$_GET['limit'];
    }
    else {
        $_GET['limit'] = (int)5;
    }
    if(isset($_GET['validation'])){
        if (!isset($_GET['page']) || $_GET['page'] == 0) {
            $_GET['page'] = 1;
            $page = $_GET['page'];
        }
        elseif (isset($_GET['page']) || $_GET['page'] != 0) {
            $page = $_GET['page'];
        }
        if (isset($_GET['limit'])) {
            $_GET['limit'] = (int)$_GET['limit'];
        }
        else {
            $_GET['limit'] = (int)5;
        }
        if(isset($_GET['validation'])){
            if (!isset($_GET['page']) || $_GET['page'] == 0) {
                $_GET['page'] = 1;
                $page = $_GET['page'];
            }
            elseif (isset($_GET['page']) || $_GET['page'] != 0) {
                $page = $_GET['page'];
            }
            $sqlPagin = "SELECT COUNT(validation) FROM tickets WHERE validation = :valid";
            $queryPagin = $bdd->prepare($sqlPagin);
            $queryPagin->bindParam(':valid' , $_GET['validation']);
            $queryPagin->execute();
            $rows = $queryPagin->fetchAll();
            $nbPage = (int)$rows[0];
            $limit = ((int)$_GET['limit']);
            $nbPage = ( $nbPage / $limit ) +1;
            $countPage = ($page * $limit) - $limit;
            $countPage = abs($countPage);
            $req = $bdd->prepare("SELECT * FROM tickets WHERE validation = :validation ORDER BY create_date DESC LIMIT :countpage , :limite");
            $req->bindParam(':validation', $_GET['validation']);
            $req->bindParam(':limite', $limit, PDO::PARAM_INT);
            $req->bindParam(':countpage', $countPage, PDO::PARAM_INT);
            $req->execute();
            foreach ($req->fetchAll() as $key => $value)
            {
                ?>
                    <div class="interContent"></div>
                    <div class="preview">
                       <?php 
                            $reqTag = $bdd->prepare("SELECT name FROM allTags WHERE idTicket = :id");
                            $reqTag->bindParam(":id", $value["id_ticket"]);
                            $reqTag->execute();
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
                        ?>
                        Actions :
                        <form method="post" action="allTickets.php">
                            <label>
                                <select name="actionTicket" class="selectAdmin">
                                    <option value="1">Valider le billet</option>
                                    <option value="2">Supprimer le billet</option>
                                    <option value="3">Modifier le billet</option>
                                </select>
                            </label>
                            <input type="hidden" name=<?php echo "\""."ticket"."\"";?> value=<?php echo "\"".$value['id_ticket']."\"";?> />
                            <input type="submit" value="Envoyer" class='submitAdmin' />
                        </form>
                    </div>
                
                <?php
            }
            if ( $page > 1 && $nbPage > 1 && $rows[0][0] != 0) {
                echo "<a class='paginArrows' href=\"allTickets.php?validation=".$_GET['validation']."&amp;limit=".$_GET['limit']."&amp;page=".($page -1)."\">&larr;</a>";
            }
            if (isset($nbPage) && $page < $nbPage && $rows[0][0] != 0) {
                echo "<a class='paginArrows' href=\"allTickets.php?validation=".$_GET['validation']."&amp;limit=".$_GET['limit']."&amp;page=".($page +1)."\">&rarr;</a>";
            }
        }
    }
    if (isset($_POST['actionTicket']) && $_POST['actionTicket'] == 1)
    {
        $valided = 0;

        $req = $bdd->prepare("SELECT validation FROM tickets WHERE id_ticket = :idTicket");
        $req->bindParam(":idTicket", $_POST["ticket"]);
        $req->execute();

        foreach ($req->fetchAll() as $key => $value)
        {
            if ($value["validation"] == 1)
            {
                echo "Le billet était déja validé.";
                $valided = 1;
            }
        }

        if ($valided == 0)
        {
            $query = $bdd->prepare("UPDATE tickets SET validation = 1 WHERE id_ticket = :ticket");
            $query->bindParam(':ticket', $_POST['ticket']);
            $query->execute();
            echo "Le billet à été validé";
        }
    }
    if (isset($_POST['actionTicket']) && $_POST['actionTicket'] == 2)
    {
        $allTags = [];
        $_POST['ticket'] = (int)$_POST['ticket'];

        $req = $bdd->prepare("SELECT name FROM allTags WHERE idTicket = :idTicket");
        $req->bindParam("idTicket", $_POST["ticket"]);
        $req->execute();

        foreach ($req->fetchAll() as $key => $value)
        {
            array_push($allTags, $value["name"]);
        }

        $sql = "DELETE FROM tickets WHERE id_ticket = :id_ticket";
        $query = $bdd->prepare($sql);
        $query->bindParam(':id_ticket', $_POST['ticket'], PDO::PARAM_INT);
        $query->execute();

        $req = $bdd->prepare("DELETE FROM allTags WHERE idTicket = :idTicket");
        $req->bindParam(":idTicket", $_POST["ticket"]);
        $req->execute();
        
        $req = $bdd->prepare("SELECT name FROM allTags");
        $req->execute();

        foreach ($allTags as $key => $value)
        {
            $exist = 0;
            foreach ($req->fetchAll() as $key2 => $value2)
            {
                if ($value2["name"] == $value)
                {
                    $exist = 1;
                }
            }

            if ($exist == 0)
            {
                $req2 = $bdd->prepare("DELETE FROM tag WHERE name = :name");
                $req2->bindParam(":name", $value);
                $req2->execute();
            }
        }

        $req = $bdd->prepare("DELETE FROM comments WHERE idTicket = :idTicket");
        $req->bindParam(":idTicket", $_POST["ticket"]);
        $req->execute();

        echo "Le billet à bien été supprimé !";
    }
    if (isset($_POST['actionTicket']) && $_POST['actionTicket'] == 3) {
        $_SESSION['id_ticket'] = $_POST['ticket'];
        header("Location: billets.php?idTicket=". $_POST["ticket"]);
    }
    ?>
    <?php
}
else
{
    echo "Vous n'avez pas les droits d'accéder a cette page.";
    return 1;
}
?>
</div>
<?php
include "footer.php";
?>