<?php
/**
 * CRUD - Create, Read, Update, Delete
 */

include("crud.php");

header("Content-Type: application/json");
$output = [];

try {
  if (!isset($_GET['action_name']))
    throw new Exception("action name not specified");
  if (!is_string($_GET['action_name']))
    throw new Exception("action name should be of string type");

  $action_name = $_GET['action_name'];
  if ($action_name === "create") {
    create();
  }
  else if ($action_name === "read") {
    $output['data'] = read();
  }
  else if ($action_name === "update") {
    update();
  }
  else if ($action_name === "change_amount") {
    changeAmount();
  }
  else if ($action_name === "delete") {
    delete();
  }
  else {
    throw new Exception("Unexpected action name");
  }
}
catch (Exception $e) {
  $output['error_message'] = $e->getMessage();
}

echo json_encode($output, JSON_PRETTY_PRINT);

if (!isset($_GET['from_javascript'])) {
  header("Location: http://localhost/CRUD_API/");
}
