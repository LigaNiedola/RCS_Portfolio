<?php
function create() {
  // if (!isset($_POST['todo']))
  //   throw new Exception("todo is not specified");
  // if (!is_string($_POST['todo']))
  //   throw new Exception("todo should be of string type");

  $data = read();

  if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if ($id > $data['last_id']) {
      $data['last_id'] = $id;
    }
  }
  else {
    $id = ++$data['last_id'];
  }

  $entries = &$data['entries'];
  if (isset($entries[$id])) {
    $entries[$id]['order_count']++;
  }
  else {
    $entries[$id] = $_POST;
    $entries[$id]['order_count'] = 1;
  }

  file_put_contents('storage.json', json_encode($data, JSON_PRETTY_PRINT));
}

function update() {
  if (!isset($_POST['todo']))
    throw new Exception("todo is not specified");
  if (!is_string($_POST['todo']))
    throw new Exception("todo should be of string type");
  if (!isset($_POST['id']))
    throw new Exception("id is not specified");
  if (!is_string($_POST['id']))
    throw new Exception("id should be of string type");

  $data = read();

  $id = $_POST['id'];
  if (!isset($data['entries'][$id]))
    throw new Exception("invalid ID");

  $data['entries'][$id] = ["task" => $_POST['todo']];

  file_put_contents('storage.json', json_encode($data, JSON_PRETTY_PRINT));
}

function delete() {
  if (!isset($_POST['id']))
    throw new Exception("id is not specified");
  if (!is_string($_POST['id']))
    throw new Exception("id should be of string type");

  $data = read();

  $id = $_POST['id'];
  if (!isset($data['entries'][$id]))
    throw new Exception("invalid ID");

  unset($data['entries'][$id]);

  file_put_contents('storage.json', json_encode($data, JSON_PRETTY_PRINT));
}

function changeAmount() {
  if (!isset($_POST['id']))
    throw new Exception("id is not specified");
  if (!is_string($_POST['id']))
    throw new Exception("id should be of string type");
  if (!isset($_POST['amount']))
    throw new Exception("amount is not specified");
  if (!is_string($_POST['amount']))
    throw new Exception("amount should be of string type");

  $data = read();

  $id = $_POST['id'];
  $amount = (int) $_POST['amount'];
  if (!isset($data['entries'][$id]))
    throw new Exception("invalid ID");

  $data['entries'][$id]['order_count'] += $amount;

  file_put_contents('storage.json', json_encode($data, JSON_PRETTY_PRINT));
}



function read() {
  $template = [
    'last_id' => 0,
    'entries' => []
  ];

  if (!file_exists('storage.json')) {
    return $template;
  }

  $content = file_get_contents('storage.json');
  $data = json_decode($content, true);
  if (
    !isset($data['last_id']) || !isset($data['entries'])
  ) {
    return $template;
  }

  return $data;
}
