<?php
namespace API;

include(PRIVATE_DIR . 'classes/Storage/CRUD.php');
use Storage\CRUD;

include(PRIVATE_DIR . 'classes/TicTacToe/Referee.php');
use TicTacToe\Referee;
class Actions {

  private $players;

  public function __construct($players) {
    $this->players = $players;
  }

  public function move($current_player_id, $cell_id) {
    $storage = new CRUD('tictactoe', ['player_id', 'cell_id', 'time']);

    $last_entry = $storage->getLastEntry();

    if (!isset($this->players[$current_player_id]))  throw new \Exception('player ID is not exceptable');

    $current_player =  $this->players[$current_player_id];
    $first_player = current($this->players);
    $cell_id = (int) $cell_id;

    if ($storage->getEntry($cell_id) !== false) throw new \Exception('this cell is not empty');

    if (
      $last_entry === false &&
      $first_player['player_id'] !== $current_player_id
    ) throw new \Exception('first move for x');

    if (is_array($last_entry) && $last_entry['player_id'] === $current_player_id) throw new \Exception('please wait for your move');
    if (!in_array($cell_id, [0, 1, 2, 3, 4, 5, 6, 7, 8])) throw new \Exception('cell id is not in excepted range');

    $last_move = $storage->create($cell_id, [
      'cell_id' => $cell_id,
      'player_id' => $current_player_id
    ]);

    $referee = new Referee();

    $winner = $referee->checkWinner($storage->getAllEntries());

    return [
      'last_move' => $last_move,
      'winner' => $winner
    ];
  }

  public function initialize() {
    $players_storage = new CRUD('players', ['game_id', 'player_id', 'player_symbol', 'time']);
    $output = [];
    $output['message'] = "Nosakam kurš spēlētājs tas ir.";

    $players_ids = array_keys($this->players);
    $players_count = $players_storage->countEntries();

    if ($players_count === 1) {
      $player_id = next($players_ids); // otra spēlētājā ID
    }
    else if ($players_count === 0) {
      $player_id = current($players_ids); // pirmā spēlētājā ID
    }
    else {
      throw new \Exception('no new players accepted');
    }

    $players_storage->create($player_id, [
      'game_id' => 0,
      'player_id' => $player_id,
      'player_symbol' => $this->players[$player_id]['player_symbol'],
      'time' => time()
    ]);

    $output['player_id'] = $player_id;
    $output['player_symbol'] = $this->players[$player_id]['player_symbol'];

    return $output;
  }

  public function getMoves() {
    $storage = new CRUD('tictactoe', ['player_id', 'cell_id', 'time']);

    return $storage->getAllEntries();
  }

  public function resetMoves() {
    $storage = new CRUD('tictactoe', ['player_id', 'cell_id', 'time']);
    $storage->deleteAll();

    $players_storage = new CRUD('players', ['game_id', 'player_id', 'player_symbol', 'time']);
    $players_storage->deleteAll();
  }
}