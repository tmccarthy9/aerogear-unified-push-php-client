<?php
/*
 * JBoss, Home of Professional Open Source
 * Copyright Red Hat, Inc., and individual contributors.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class SenderClient {

  private $serverURL;
  private $pushApplicationID;
  private $masterSecret;
  private $variants = array();    //strings of targeted variants
  private $categories = array();
  private $alias = array();     //strings of aliases
  private $devices = array();     //strings of device types
  private $messages = array();    //array of key:val arrays
  private $simplePush;
  private $ttl;   //time to live in seconds
  private $responseCode;
  private $responseText;
  
  /*  Verifies URL and its structure   */
  public function setServerURL($url) {
    try {
      if($url == null) {
        throw new Exception("Server URL cannot be null");
      }
    } catch(Exception $e) {
      die($e->getMessage());
    }
  
    //adds / to end of URL if needed
    if($url[strlen($url)-1] != "/") {
      $this->serverURL .= $url."/";
    } else {
      $this->serverURL = $url;
    }
  
    $this->serverURL .= "rest/sender/";
  
  }
  
  /* Executes the curl command to send the message */
  public function sendMessage() {
  
    $credentials = base64_encode($this->pushApplicationID . ":" . $this->masterSecret);
    $con = curl_init($this->serverURL);
  
    curl_setopt($con, CURLOPT_HEADER, 0);
    curl_setopt($con, CURLOPT_SSLVERSION, 3);           //Allows https or http
    curl_setopt($con, CURLOPT_POST, 1);     //POST request
    curl_setopt($con, CURLOPT_RETURNTRANSFER, true);  //hides(t)/shows(f) response (as value of curl_exec)
    curl_setopt($con, CURLOPT_HTTPHEADER, array("Authorization: Basic " .  $credentials,
    'Content-Type: application/json',
    'Accept: application/json'));
    
    Bitlog::debug('Payload:' . json_encode($this->buildPayload()));
    curl_setopt($con, CURLOPT_POSTFIELDS, json_encode($this->buildPayload()));
    
    //try to connect to send the payload, throw exception upon failure
    try {
      if(!$result = curl_exec($con)) {
        throw new Exception("A connection could not be made to the server.");
      } else {
        $this->setResponseText($result);
        $this->setResponseCode(curl_getinfo($con, CURLINFO_HTTP_CODE));
        curl_close($con);
      }
    } catch (Exception $e) {
        die($e->getMessage());
    }
  
  }
  
  /*  Put values that have been set into JSON-encodable format (PHP array) for request  */
  public function buildPayload() {
    try {
      if(!empty($this->messages)) {
        return array(
            "variants"     =>   $this->variants,
            "categories"   =>   $this->categories,
            "alias"        =>   $this->alias,
            "deviceType"   =>   $this->devices,
            "ttl"      => $this->ttl,
            "message"      =>   $this->messages,
            "simple-push"  =>   $this->simplePush
        );
      } else {
          throw new Exception("At least one message must be submitted.");
      }
    } catch(Exception $e) {
        die($e->getMessage());
    }
  }
  
  
  /*  Allows variants to be added to an array   */
  public function addVariant($vid) {
    $this->variants[] = $vid;
  }
  
  /*  Adds key, value pairs to message payload array   */
  public function addMessage($k, $v) {
    $this->messages[$k] = $v;
  }
  
  /*  Adds key,value pairs to simple-push array   */
  public function addSimplePush($k, $v) {
    $this->simplePush[$k]  = $v;
  }
  
  /*  Allows aliases to be added to an array   */
  public function addAlias($aid) {
    $this->alias[]  = $aid;
  }
  
  /*  Allows devices to be added to an array   */
  public function addDevice($did) {
    $this->devices[]  = $did;
  }
  
  /*  Allows ttl to be added to an array   */
  public function addTtl($secs) {
    $this->ttl  = $secs;
  }
  
  /*  Tells which application to send to   */
  public function setPushApplicationID($id) {
    try {
      if($id != null) {
        $this->pushApplicationID = $id;
      } else {
        throw new Exception("Push Application ID must not be null.");
      }
    } catch (Exception $e) {
        die($e->getMessage());
    }
  }
  
  /*  Used for server authentication   */
  public function setMasterSecret($secret) {
    try {
      if($secret != null) {
        $this->masterSecret = $secret;
      } else {
        throw new Exception("Master secret must not be null.");
      }
    } catch (Exception $e) {
        die($e->getMessage());
    }
  }
  /*  Allows category to be set */
  public function setCategories($categories) {
    $this->categories = $categories;
  }
  
  /*  Sets the HTTP response */
  private function setResponseCode($http) {
    $this->responseCode = $http;
  }
  
  /*  Sets the HTTP body response */
  private function setResponseText($text) {
    $this->responseText = $text;
  }
  
  /*  Retrieves the HTTP response code from the request   */
  public function getResponseCode() {
    return $this->responseCode;
  }
  
  /*  Retrieves the HTTP response text from the request   */
  public function getResponseText() {
    return $this->responseText;
  }

}
