<?php

$db = new mysqli("localhost", "root", "", "chat");

if ($db->connect_error) {
  die("Connection failed: " . $db->conect_error);
}

$result = array();
$message = isset($_POST["message"]) ? $_POST["message"] : null;
$name = isset($_POST["name"]) ? $_POST["name"] : null;

if ($message && $name) {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
      $ip = $_SERVER['REMOTE_ADDR'];
  }

  // For local testing purposes only, for example when the clients IP is "::1"
  if (!filter_var($ip, FILTER_VALIDATE_IP) || $ip === '::1') {
    $ip = file_get_contents('http://ipecho.net/plain');
  }

  $sql = "INSERT INTO `chat` (`message`, `name`, `ip`) VALUES ('".$message."', '".$name."', '".$ip."')";
  $result["send_status"] = $db->query($sql);
}

$messages = $db->query("SELECT * FROM `chat` ORDER BY `timestamp` DESC");

while ($row = $messages->fetch_assoc()) {
  $result['messages'][] = $row;
}
$db->close();

header ("Access-Control-Allow-Origin: *");
header ("Content-Type: application/json");

echo json_encode($result);