<?php
/*
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

class SenderClient {

  private $serverURL;
  private $type; //broadcast, selected
  private $pushApplicationID;
  private $masterSecret;
  private $variants = array(); //strings of targeted variants
  private $category;
  private $alias = array(); //strings of aliases
  private $devices = array(); //strings of device types
  private $messages = array(); //array of key:val arrays
  private $simplePush = array();
  private $response;

  /*  Determines whether it's a broadcast send or a selected send   */
  function __construct($type)
  {
    $this->type = $type;
  }

  /*  Verifies URL's structure   */
  public function setServerURL($url)
  {
    if($url == null)
    {
        throw new Exception("Server URL cannot be null");
    }

    if($url[strlen($url)-1] != "/")
    {
      $this->serverURL .= "/";
    }
    else
    {
      $this->serverURL = $url;
    }

    $this->serverURL .= "rest/sender/".$this->type;
  }

  public function submitPayload()
  {

  }  

  public function sendMessage()
  {

    $credentials = base64_encode($this->pushApplicationID . ":" . $this->masterSecret);
    $con = curl_init($this->serverURL);
 
    curl_setopt($con, CURLOPT_HEADER, 0);
    curl_setopt($con, CURLOPT_POST, 1); //POST request
    curl_setopt($con, CURLOPT_RETURNTRANSFER, true); //hides response (as value of curl_exec)
    curl_setopt($con, CURLOPT_HTTPHEADER, array("Authorization: Basic " .  $credentials,
                                                'Content-Type: application/json',
                                                'Accept: application/json'));    
    curl_setopt($con, CURLOPT_POSTFIELDS, json_encode($this->buildPayload()));  //send the message
    curl_exec($con);
    $this->setResponse(curl_getinfo($con, CURLINFO_HTTP_CODE));
    curl_close($con);
  }

  /*  Put values that have been set into JSON-encodable format for request  */
  public function buildPayload()
  {
    if($this->type == "selected")
    {
      return array(
                  "variants"     =>   $this->variants,
                  "category"     =>   $this->category,
                  "alias"        =>   $this->alias,
                  "deviceType"   =>   $this->devices,
                  "message"      =>   $this->messages,
                  "simple-push"  =>   $this->simplePush
                  );
    }
    else
    {
      //broadcast to all instances of the app
      //simply returns the array of key,val messages
      return $this->messages;
    }
  }


  /*  Allows variants to be added to an array   */
  public function addVariant($vid)
  {
    $this->variants[] = $vid;
  }

  /*  Adds key, value pairs to message payload array   */
  public function addMessage($k,$v)
  {
    $this->messages[$k] = $v;
  }

  /*  Adds key,value pairs to simple-push array   */
  public function addSimplePush($k, $v)
  {
    $this->simplePush[$k]  = $v;
  }

  /*  Allows aliases to be added to an array   */
  public function addAlias($aid)
  {
    $this->alias[]  = $aid;
  }

  /*  Allows devices to be added to an array   */
  public function addDevice($did)
  {
    $this->devices[]  = $did;
  }

  /*  Tells which application to send to   */
  public function setPushApplicationID($id)
  {
    $this->pushApplicationID = $id;
  }

  /*  Used for server authentication   */
  public function setMasterSecret($secret)
  {
    $this->masterSecret = $secret;
  }
  /*  Allows category to be set   */
  public function setCategory($cat)
  {
    $this->category = $cat;
  }

  /*  Sets the HTTP response for future use   */
  private function setResponse($http)
  {
    $this->response = $http;
  }

  /*  Retrieves the HTTP response code from the request   */
  public function getResponse()
  {
    return $this->response;
  }
}
