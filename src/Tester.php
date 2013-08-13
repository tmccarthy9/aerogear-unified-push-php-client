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

/*
* This file simply demonstrates the basic usage of SenderClient class.
* The data is only hard coded in. For a dynamic data entry, see
* /webapp/index.php.
*/

require("SenderClient.php");


$message = new SenderClient('broadcast');
$message->setServerURL("http://pushserver-tomccart.rhcloud.com/");
$message->setMasterSecret("539849a6-bbc3-4ead-8bd1-f8f1d712ebeb");
$message->setPushApplicationID("a915580f-8397-4149-b609-3aacc43c00be");

$message->addVariant("2951009d-505c-43ad-a983-683d93875947");
$message->setCategory("androidCat");

$message->addAlias("user@account.com");

$message->addDevice("AndroidTablet");

$message->addMessage("key","value");
$message->addSimplePush("skey", "sval");

$message->sendMessage();

$response = $message->getResponseCode();

if($response == 200)
{
	echo "The job has been submitted to the server!";
}
else
{
	echo "The server returned a response code of " . $response;
}
