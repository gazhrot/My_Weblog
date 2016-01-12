<?php
include "header.php";

if (isset($_SESSION["connected"]))
{
	$_SESSION["connected"] = 0;
	session_destroy();
}

header("Location: index.php");

include "footer.php";
?>