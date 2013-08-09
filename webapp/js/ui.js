function hideOptions()
{
	var selOptions = document.getElementById('selectedSendOptions');
	selOptions.style.display = 'none';
}

function showOptions()
{
	var selOptions = document.getElementById('selectedSendOptions');
	selOptions.style.display = 'block';
}

function addMessages()
{
	var container = document.getElementById('messageHolder');

	var input = document.createElement("input");
	input.type = "text";
	input.name = "messageKey[]";
	container.appendChild(input);
		container.appendChild(document.createTextNode(" : "));
	var input = document.createElement("input");
	input.type = "text";
	input.name = "messageValue[]";
	container.appendChild(input);
	// Append a line break 
	container.appendChild(document.createElement("br"));
}

function addSimplePush()
{
	var container = document.getElementById('simplePushHolder');

	var input = document.createElement("input");
	input.type = "text";
	input.name = "simplePushKey[]";
	container.appendChild(input);
		container.appendChild(document.createTextNode(" : "));
	var input = document.createElement("input");
	input.type = "text";
	input.name = "simplePushValue[]";
	container.appendChild(input);
	// Append a line break 
	container.appendChild(document.createElement("br"));
}
