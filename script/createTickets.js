function beforeSubmit()
{
	var element = document.getElementById("createTicketForm");
	element.addEventListener("submit", function()
	{
		var element = document.getElementById("textAreaDiv");
		var hiddenElement = document.getElementById("textAreaContent");
		hiddenElement.value = element.innerHTML;
	});
}

function addEvent(style)
{
	var element = document.getElementById(style);

	element.addEventListener("click", function()
	{
		document.execCommand(style);
	});
}

function addColors()
{
	var colors = ["color", "red", "blue", "green", "white", "black", "purple", "yellow"];

	var i = 0;
	while (colors[i])
	{
		var theColor = colors[i].substring(0, 1).toUpperCase() + colors[i].substring(1);
		var element = document.getElementById("colors");
		var newElement = document.createElement("option");
		newElement.value = colors[i];
		newElement.innerHTML = theColor;
		element.appendChild(newElement);
		i += 1;
	}
	
	element.addEventListener("change", function()
	{
		if (element.value != "Color")
		{
			document.execCommand("forecolor", false, element.value);
		}
	});
}

function addLink()
{
	var element = document.getElementById("link");
	element.addEventListener("click", function()
	{
		var theUrl = prompt("Veuillez entrer une url", "http://www.");
		if (theUrl !== null)
		{
			document.execCommand("createLink", false, theUrl);
		}
	});
}

function addVideo()
{
	var element = document.getElementById("embedVideo");
	element.addEventListener("click", function()
	{
		var theUrl = prompt("Veuillez entrer l'url de votre vidéo");
		
		if (theUrl === null)
		{
			return;
		}

		if (theUrl.substring(12, 19) == "youtube")
		{
			theUrl = theUrl.replace("watch?v=", "embed/");

			var element = document.createElement("iframe");
			element.style.width = "420px";
			element.style.height = "315px";
			element.frameborder = "0";
			element.src = theUrl;

			var textAreaElement = document.getElementById("textAreaDiv");
			textAreaElement.appendChild(element);
		}

		else if (theUrl.substring(11, 22) == "dailymotion")
		{
			theUrl = theUrl.split("video/");
			theUrl = theUrl[1].substring(0, 6);
			var element = document.createElement("iframe");
			element.style.width = "480px";
			element.style.height = "270px";
			element.style.frameborder = 0;
			element.src = "//www.dailymotion.com/embed/video/" + theUrl;

			var textAreaElement = document.getElementById("textAreaDiv");
			textAreaElement.appendChild(element);	
		}


	});
}

function showHide(value)
{
	if (value === 0)
	{
		var element = document.getElementById("grayCache");
		element.style.display = "none";
		element = document.getElementById("uploadImage");
		element.style.display = "none";
	}

	else
	{
		element = document.getElementById("grayCache");
		element.style.display = "block";
    
		var element = document.getElementById("uploadImage");
		element.style.display = "block";
	}
}

function submitImage()
{
	var titleElement = document.getElementById("imageTitle");
	if (titleElement.value == "")
	{
		titleElement.style.border = "1px solid red";
		return 1;
	}

	showHide(0);
	var textAreaDiv = document.getElementById("textAreaDiv");
	var element = document.getElementById("imageUrl");
	if (element.value == "")
	{
		element.style.border = "1px solid red";
		return 1;
	}
	var value = element.value;
	element.value = "";
	if (value !== "")
	{
		var imgElement = document.createElement("img");
		imgElement.src = value;
		imgElement.alt = titleElement.value;
		textAreaDiv.appendChild(imgElement);
		imgElement.addEventListener("error", function()
		{
			alert("L'url que vous avez entré n'est pas une image");
			textAreaDiv.removeChild(imgElement);
		});
	}
}

function addImage(imageClicked)
{
	var element = document.getElementById("addImage");

	element.addEventListener("click", function()
	{
		if (imageClicked === 0)
		{
			var grayCache = document.createElement("div");
			grayCache.id = "grayCache";
			grayCache.style.position = "absolute";
			grayCache.style.width = "100%";
			grayCache.style.height = "100%";
			grayCache.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
			grayCache.style.top = "0px";
			grayCache.style.left = "0px";

			document.body.appendChild(grayCache);

			var element = document.createElement("div");
			element.style.width = "500px";
			element.style.height = "200px";
			element.style.backgroundColor = "white";
			element.style.margin = "-20% 35%";
			element.style.position = "absolute";
			element.style.borderRadius = "10px";
			element.style.padding = "10px";
			element.id = "uploadImage";

			var textElement = document.createElement("p");
			textElement.innerHTML = "Ajouter une image par url:";
			textElement.style.float = "left";
			textElement.style.margin = "10px";

			var inputElement = document.createElement("input");
			inputElement.type = "text";
			inputElement.placeholder = "url";
			inputElement.style.marginTop = "5px";
			inputElement.id = "imageUrl";

			var inputTitleElement = document.createElement("input");
			inputTitleElement.type = "text";
			inputTitleElement.placeholder = "Titre de l'image";
			inputTitleElement.style.marginTop = "5px";
			inputTitleElement.id = "imageTitle";

			var submitElement = document.createElement("input");
			submitElement.type = "button";
			submitElement.value = "Envoyer";
			submitElement.addEventListener("click", submitImage);

			element.appendChild(textElement);
			element.appendChild(inputElement);
			element.appendChild(inputTitleElement);
			element.appendChild(submitElement);

			document.body.appendChild(element);
			grayCache.addEventListener("click", function()
			{
				showHide(0);
			});
		}

		else
		{
			showHide(1);
		}

		imageClicked = 1;
	});
}

document.addEventListener("DOMContentLoaded", function()
{
	var imageClicked = 0;

	addEvent("bold");
	addEvent("underline");
	addEvent("italic");
	addColors();
	addLink();
	addVideo();
	addImage(imageClicked);
	beforeSubmit();
});