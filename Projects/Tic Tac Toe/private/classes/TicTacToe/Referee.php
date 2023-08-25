<?php
namespace TicTacToe;
class Referee {
  public function checkWinner($moves) {
    $win_combinations = [
      [0, 1, 2],
      [3, 4, 5],
      [6, 7, 8],

      [0, 3, 6],
      [1, 4, 7],
      [2, 5, 8],

      [0, 4, 8],
      [2, 4, 6]
    ];

    foreach ($win_combinations as $combination) {
      if (
        $this->getMovePlayerId($moves, $combination[0]) !== false &&
        $this->getMovePlayerId($moves, $combination[0]) === $this->getMovePlayerId($moves, $combination[1]) &&
        $this->getMovePlayerId($moves, $combination[0]) === $this->getMovePlayerId($moves, $combination[2])
      ) {
          return $this->getMovePlayerId($moves, $combination[0]);
      }
    }

    return null;
  }

  private function getMovePlayerId($moves, $cell_id) {
    if (!isset($moves[$cell_id])) return false;
    return $moves[$cell_id]['player_id'];
  }
  
}