<?php
include "header.php";
?>

<input id="bold" class="textButton" type="button" value="B" />
<input id="underline" class="textButton" type="button" value="S" />
<input id="italic" class="textButton" type="button" value="I" />
<select id="colors"></select>
<input type="button" value="Lien" id="link" />
<input type="button" value="Ajouter une vidéo" id="embedVideo" />
<input type="button" value="Ajouter une image" id="addImage" />
<div id="textAreaDiv" class="textArea" contenteditable="true"></div>
<form method="POST">
	<input id="textAreaContent" type="hidden" value="" />
</form>
<?php
include "footer.php";
?>