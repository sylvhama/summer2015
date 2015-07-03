<?php

//isXMLHTTPRequest() 	or die('Forbidden');
isset($_GET['r'])	or die('Forbidden');
//isValidToken()		or die('CSRF Attack detected');

session_start();

function resetPDO($dbh) {
  include("db.php");
  $dbh = null;
  try {
    $dbh = new PDO($dsn, $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
  } catch (PDOException $e) {
    $error = array("error" =>  'Error connection'); //$e->getMessage()
    echo json_encode($error);
    die();
  }
  return $dbh;
}

$dbh = null;
$dbh = resetPDO($dbh);

$method = $_GET['r'];

switch ($method) {
	case 'selectUser':
		echo selectUser();
	  break;
	case 'selectMates':
		echo selectMates();
	  break;
	case 'selectTop3':
		echo selectTop3();
	  break;
	case 'addUser':
		echo addUser();
	  break;
	case 'addParticipation':
		echo addParticipation();
	  break;
	case 'updateUser':
		echo updateUser();
	  break;
	case 'updateShare':
		echo updateShare();
	  break;
	default:
		$error = array("error" =>  "Undefined function.");
    echo json_encode($error);
	  break;
}

$dbh = null;

function isXMLHTTPRequest() {
	if(!sizeError($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return true;
	} else {
		return false;
	}
}

function isValidToken() {
	//TODO improve security with a real token
	$token = getallheaders();
	$token = $token['X-CSRF-Token'];
	if(!sizeError($token) && $token == 'SylvainIsTheBest') {
		return true;
	} else {
		return false;
	}
}

function isValidHash($hash) {
  if ($hash != "dvHUChKocyLhG6a5jzTjXXVYbBH7nBCMHDvxxd2KXXVYbBH7nBCMHDvxxd2K") {
    return false;
  } else {
    return true;
  }
}

function getClientIp() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'NA';
    return $ipaddress;
}

function selectUser() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "SELECT COUNT(`user_id`) AS count FROM `user` WHERE `facebook_id` LIKE :facebook_id;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':facebook_id' => $objData->data->user->facebook_id));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    if ($obj->count == 0) {
      $error = array("error" =>  "noUser");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT count user query error.");
    return json_encode($error);
  }

  $dbh = resetPDO($dbh);

  $sql = "SELECT * FROM `user` WHERE `facebook_id` LIKE :facebook_id;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':facebook_id' => $objData->data->user->facebook_id));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    return json_encode(array("user_id" =>  $obj->user_id, "facebook_id" =>  $obj->facebook_id, "device" =>  $obj->device, "first_name" =>  $obj->first_name, "gender" =>  $obj->gender, "last_name" =>  $obj->last_name, "name" =>  $obj->name, "phone" =>  $obj->phone, "shares" =>  $obj->shares));
  }else {
    $error = array("error" =>  "SELECT user query error.");
    return json_encode($error);
  }
}

function selectMates() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->friend)) {
  	$error = array("error" =>  "No friend value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "SELECT COUNT(`participation_id`) AS count FROM `participation` WHERE `facebook_id` LIKE :facebook_id AND `score` = :score;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':facebook_id' => $objData->data->friend->facebook_id, ':score' => intval($objData->data->score)));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    if ($obj->count == 0) {
      $error = array("error" =>  "noMate");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT count mates query error.");
    return json_encode($error);
  }

  $dbh = resetPDO($dbh);

  $sql = "SELECT * FROM `participation` WHERE `facebook_id` LIKE :facebook_id AND `score` = :score LIMIT 1;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':facebook_id' => $objData->data->friend->facebook_id, ':score' => $objData->data->score));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    return json_encode(array("facebook_id" =>  $obj->facebook_id));
  }else {
    $error = array("error" =>  "SELECT mates query error.");
    return json_encode($error);
  }
}

function selectTop3() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "SELECT score AS id, COUNT(*) AS count FROM `participation` GROUP BY id ORDER BY count DESC LIMIT 3;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute();
  if ($executed) {
    $obj = $stmt->fetchAll();
    return json_encode($obj);
  }else {
    $error = array("error" =>  "SELECT top3 query error.");
    return json_encode($error);
  }
}

function addUser() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];
  $id = -1;
  $alreadyFBID = false;

  $sql = "SELECT COUNT(`user_id`) AS count FROM `user` WHERE `facebook_id` LIKE :facebook_id;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':facebook_id' => $objData->data->user->facebook_id));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    if ($obj->count > 0) {
      $alreadyFBID = true;
      $error = array("error" =>  "alreadyFBID");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT user fb_id query error.");
    return json_encode($error);
  }

  if(!$alreadyFBID) {
    $dbh = resetPDO($dbh);
    $sql = "INSERT INTO `user`(`facebook_id`, `first_name`, `last_name`, `name`, `gender`, `link`, `device`) VALUES (:facebook_id, :first_name, :last_name, :name, :gender, :link, :device);";
    $stmt = $dbh->prepare($sql);
    $executed = $stmt->execute(array(':facebook_id' => $objData->data->user->facebook_id, ':first_name' => $objData->data->user->first_name, ':last_name' => $objData->data->user->last_name, ':name' => $objData->data->user->name, ':gender' => $objData->data->user->gender, ':link' => $objData->data->user->link, ':device' => $objData->data->user->device));
    if ($executed) {
      $id = $dbh->lastInsertId();
      return $id;
    }else {
      $error = array("error" =>  "INSERT user query error.");
      return json_encode($error);
    }
  }
}

function addParticipation() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];
  $id = -1;
  $alreadyID = true;

  $sql = "SELECT COUNT(`user_id`) AS count FROM `user` WHERE `facebook_id` LIKE :facebook_id;";
  $stmt = $dbh->prepare($sql);
  $executed = $stmt->execute(array(':facebook_id' => $objData->data->user->facebook_id));
  if ($executed) {
    $obj = $stmt->fetch(PDO::FETCH_LAZY);
    if ($obj->count == 0) {
      $alreadyID = false;
      $error = array("error" =>  "noUser");
      return json_encode($error);
    }
  }else {
    $error = array("error" =>  "SELECT user fb_id query error.");
    return json_encode($error);
  }

  if($alreadyID) {
    $dbh = resetPDO($dbh);
    $sql = "INSERT INTO `participation`(`facebook_id`, `score`) VALUES (:facebook_id, :score);";
    $stmt = $dbh->prepare($sql);
    $executed = $stmt->execute(array(':facebook_id' => $objData->data->user->facebook_id, ':score' => $objData->data->user->score));
    if ($executed) {
      $id = $dbh->lastInsertId();
      return $id;
    }else {
      $error = array("error" =>  "INSERT participation query error.");
      return json_encode($error);
    }
  }
}

function updateUser() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $phone = preg_replace('/[^0-9]+/', '', $objData->data->user->phone);
  if(strlen($phone) == 0){
    $error = array("error" =>  "sizeErrorPhone");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "UPDATE `user` SET `phone` = :phone WHERE `user_id` = :id;";
  $stmt = $dbh->prepare($sql);
  $unsafeUserId = intval($objData->data->user->user_id);
  $executed = $stmt->execute(array(':id' =>  $unsafeUserId, ':phone' =>  $phone));
  if ($executed) {
    return $unsafeUserId;
  }else {
    $error = array("error" =>  "UPDATE user query error.");
    return json_encode($error);
  }
}

function updateShare() {
	$data = file_get_contents("php://input");
	$objData = json_decode($data);

	if(!isset($objData->data->hash)) {
  	$error = array("error" =>  "No hash value.");
    return json_encode($error);
  }
	if(!isset($objData->data->user)) {
  	$error = array("error" =>  "No user value.");
    return json_encode($error);
  }
	if (!isValidHash($objData->data->hash)) {
    $error = array("error" =>  "Incorrect hash value.");
    return json_encode($error);
  }

  $dbh = $GLOBALS['dbh'];

  $sql = "UPDATE `user` SET `shares` = `shares`+1 WHERE `user_id` = :id;";
  $stmt = $dbh->prepare($sql);
  $unsafeUserId = intval($objData->data->user->user_id);
  $executed = $stmt->execute(array(':id' =>  $unsafeUserId));
  if ($executed) {
    return $unsafeUserId;
  }else {
    $error = array("error" =>  "UPDATE share query error.");
    return json_encode($error);
  }
}
?>