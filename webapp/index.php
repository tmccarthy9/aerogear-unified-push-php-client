<!--   
 JBoss, Home of Professional Open Source
 Copyright Red Hat, Inc., and individual contributors.
 
 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at
 
  	http://www.apache.org/licenses/LICENSE-2.0
 
 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
-->

<!DOCTYPE html>
<head>
<title>AeroGear Android PHP Demo</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="js/ui.js"></script>
</head>

<body>
	<img src="img/aerogear_logo_200px.png" />
	<h1>AeroGear Push with PHP Backend</h1>
	<form action="processForm.php" method="POST">
		<table>
			<tr>
			<td>Send Type</td>
			<td>
				<label onClick="hideOptions();">
					<input type="radio" name="sendType" value="broadcast" checked="checked" />
					Broadcast
				</label>
				<label onClick="showOptions();">
					<input type="radio" name="sendType" value="selected" />
					Selected
				</label>
			<!-- these will show/hide certain fields -->
			</td>
			</tr>

			<tr>
			<td>Server URL</td>
			<td>
				<input type="text" name="serverURL" />
			</td>
			</tr>

			<tr>
			<td>Push Application ID</td>
			<td>
				<input type="text" name="pushApplicationID" />
			</td>
			</tr>

			<tr>
			<td>Master Secret</td>
			<td>
				<input type="text" name="masterSecret" />
			</td>
			</tr>

			<!-- TODO make work properly/meaningfully with key-val pairs -->
			<tr>
			<td valign="top">Messages [<a href="javascript:addMessages();">+</a>]</td>
			<td>
				<div id="messageHolder">
					<input type="text" name="messageKey[]" /> : 
					<input type="text" name="messageValue[]" /><br />
				</div>
			</td>
			</tr>
		</table>
		<!-- If selected send, display these options -->
		<div id="selectedSendOptions">
		<table>
			<tr>
			<td>Variants (one per line)</td>
			<td>
				<textarea name="variants" class="variants"></textarea>
			</td>
			</tr>
			<tr>
			<td>Category</td>
			<td>
				<input type="text" name="category" />
			</td>
			</tr>
			<tr>
			<td>Aliases (one per line)</td>
			<td>
				<textarea name="alias"></textarea>
			</td>
			</tr>
			<tr>
			<td>Device Types (one per line)</td>
			<td>
				<textarea name="deviceTypes"></textarea>
			</td>
			</tr>
			<tr>
			<td valign="top">Simple Push [<a href="javascript:addSimplePush();">+</a>]</td>
			<td>
				<div id="simplePushHolder">
				<input type="text" name="simplePushKey[]" /> : 
				<input type="text" name="simplePushValue[]" /><br />
				</div>

			</td>
			</tr>
		</table>
		</div>
		<input type="submit" value="Send Message(s)" />
	</form>
</body>
</html>
