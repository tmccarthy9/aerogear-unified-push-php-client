<?php
/**
 * JBoss, Home of Professional Open Source
 * Copyright Red Hat, Inc., and individual contributors.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * 	http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

//path to the PHP class SenderClient
define("PATH_TO_SRC", "../src/");


require(PATH_TO_SRC . "SenderClient.php");
require("toArray.php");

//gather our variables from the POST data
$type = $_POST['sendType'];

$serverURL = $_POST['serverURL'];
$pushApplicationID = $_POST['pushApplicationID'];
$masterSecret = $_POST['masterSecret'];

$messageKey = $_POST['messageKey'];
$messageValue = $_POST['messageValue'];
$variants = $_POST['variants'];
$category = $_POST['category'];
$alias = $_POST['alias'];
$deviceTypes = $_POST['deviceTypes'];
$simplePush = $_POST['simplePush'];

//now initiate our SenderClient and give it the form data
$send = new SenderClient($type);
$send->setServerURL($serverURL);
$send->setMasterSecret($masterSecret);
$send->setPushApplicationID($pushApplicationID);

if($type == "broadcast") {

	for($i = 0; $i < count($messageKey); $i++) {
		if(!empty($messageKey[$i])) {
			$send->addMessage($messageKey[$i], $messageValue[$i]);
		}
	}

} else {

	for($i = 0; $i < count($messageKey); $i++) {
		if(!empty($messageKey[$i])) {
			$send->addMessage($messageKey[$i], $messageValue[$i]);
		}
	}

	$send->setCategory($category);

	for($i = 0; $i < count($simplePushKey); $i++) {
		if(!empty($simplePushKey[$i])) {
			$send->addSimplePush($simplePushKey[$i], $simplePushValue[$i]);
		}
	}

	/* Some of our fields can have multiple values, so we need to
	   parse them into a usable form for the script
	   Items: variants, alias, deviceTypes, simplePush */
	foreach(toArray($variants) as $indVariant) {
		$send->addVariant($indVariant);
	}

	foreach(toArray($alias) as $indAlias) {
		$send->addAlias($indAlias);
	}

	foreach(toArray($deviceTypes) as $indDeviceType) {
		$send->addDevice($indDeviceType);
	}

}

//send the message
$send->sendMessage();

//capture the response
$response = $send->getResponseCode();
$responseText = $send->getResponseText();

//output to user!
echo "The server returned HTTP $response, <br/>";
echo "and a body of: &quot;$responseText&quot;";
echo "<br/><a href=\"index.php\">Back</a>";



