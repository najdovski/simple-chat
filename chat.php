<?php

$db = new mysqli("localhost", "root", "", "chat");

if ($db->connect_error) {
  die("Connection failed: " . $db->conect_error);
}

$result = array();
$message = isset($_POST["message"]) ? $_POST["message"] : null;
$name = isset($_POST["name"]) ? $_POST["name"] : null;

if ($message && $name) {
  $sql = "INSERT INTO `chat` (`message`, `name`) VALUES ('".$message."', '".$name."')";
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