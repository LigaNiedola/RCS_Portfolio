<?php

header('Content-Type: application/json');

define('PRIVATE_DIR', __DIR__ . '/private/');

@$_GET['action_name']; // darbības veids
@$_POST['player_id']; // spelētāja identifikācijas kods
@$_POST['cell_id']; // gājiena koordināte


include(PRIVATE_DIR . 'classes/API/Actions.php');
use API\Actions;

$output = [];


$players = [
  'f2234iohfsdavk1312fds' => [
    'player_symbol' => 'x',
    'player_id' => 'f2234iohfsdavk1312fds'
  ],
  '123khld12ihiufh1i2hf1' => [
    'player_symbol' => 'o',
    'player_id' => '123khld12ihiufh1i2hf1'
  ]
];

try {
  checkQuery('action_name');

  $actions = new Actions($players);
  if ($_GET['action_name'] === 'move') {
    checkInput('player_id');
    checkInput('cell_id');

    $cell_id = $_POST['cell_id'];
    $current_player_id = $_POST['player_id'];
    
    $output['message'] = $actions->move($current_player_id, $cell_id);
  }
  elseif ($_GET['action_name'] === 'initialize') {
    $output = array_merge($output, $actions->initialize());
  }
  elseif ($_GET['action_name'] === 'get-moves') {
    $output['moves'] = $actions->getMoves();
  }
  elseif ($_GET['action_name'] === 'reset') {
    $actions->resetMoves();
    $output['message'] = 'game has reseted';
  }

  $output['status'] = true;
}
catch (Exception $e) {
  $output['status'] = false;
  $output['error'] = $e->getMessage();
}

echo json_encode($output, JSON_PRETTY_PRINT);


function checkQuery($name) {
  if (!isset($_GET[$name])) throw new Exception($name . ' not specified');
  if (!is_string($_GET[$name])) throw new Exception($name . ' is not of type string');
}
function checkInput($name) {
  if (!isset($_POST[$name])) throw new Exception($name . ' not specified');
  if (!is_string($_POST[$name])) throw new Exception($name . ' is not of type string');
}

/**
 Pārbaudīt
 1. (+) darbības veidu
 2. (+) Pārbaude: Vai lauks ir brīvs?
 3. (+) Spēlētājs var veikt gājienu.
  -- Šobrīd ja gājienu nevar veikt, tad tas nerada paziņojumu šobrīd.
 4. (+) Atcerēties gājienu (saglabāt to)
 5. (+) Pārbauda vai spēlētājs "x" var veikt gājienu (lai nav divi pēc kārtas)
 6. (+) Noteikt kurš klients ir kurš spēlētājs
  - pieviento spēlēs pievienošanās loģiku
  - reģistrēt nickname
  
 7. Jāļau otram spēlētājam veikt ģajienus

 *** Iespējot divu spēlētāju spēlēšanu.

 15. Lai apturas spēle. [Nešķirts, Ir uzvarētājs, Priekšlaicīga spēles apturēšana]
 
 . Vai ir trīs gājieni vienā līnijā



 **************
 */
