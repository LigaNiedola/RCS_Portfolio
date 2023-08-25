const board = document.querySelector('.board'),
      player_info = document.querySelector('.player_info'),
      reset_btn = document.querySelector('.reset'),
      popup_container = document.querySelector('.popup_container');
let moves = {},
    timeout = null,
    player_symbol,
    player_identifiaction_number;

Initialize_game: {
  player_identifiaction_number = sessionStorage.getItem('player_id');
  player_symbol = sessionStorage.getItem('player_symbol');
  if (player_identifiaction_number === null) {
    fetch('api.php?action_name=initialize')
    .then((response) => response.json())
    .then((result) => {
      player_identifiaction_number = result.player_id;
      player_symbol = result.player_symbol;

      if (result.status === false) return;
      setPlayerInfo(player_symbol);

      sessionStorage.setItem('player_id', player_identifiaction_number);
      sessionStorage.setItem('player_symbol', player_symbol);
    });
  }
  else {
    setPlayerInfo(player_symbol);
  }
}

board.onclick = function (event) {
  event.preventDefault();
  const cell = event.target;
  if (!cell.classList.contains('cell')) return;
  if (cell.textContent != '') return;


  let moves_count = Object.keys(moves).length;
  if (moves_count % 2 === 0) {
    if (player_symbol === 'o') return;
  }
  else {
    if (player_symbol === 'x') return;
  }

  cell.textContent = player_symbol;

  const data = new FormData();
  data.append('player_id', player_identifiaction_number);
  data.append('cell_id', cell.dataset.id);

  moves[cell.dataset.id] = {
    player_id: player_identifiaction_number,
    cell_id: cell.dataset.id
  };

  fetch('api.php?action_name=move', {
    method: "post",
    body: data
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.message.winner === null) return;
      if (result.message.winner !== player_identifiaction_number) return;

      popup_container.classList.toggle('open');
      popup_container.querySelector('.message').textContent = "You have won!";
    });
}

reset_btn.onclick = function (event) {
  fetch('api.php?action_name=reset')
  .then((response) => response.json())
  .then((result) => {
    for (const cell of board.children) {
      cell.textContent = '';
    }
  });
}

popup_container.onclick = function (event) {
  console.log('test');
}

function setPlayerInfo(player_symbol) {
  let player_nr = (player_symbol === 'x') ? 1 : 2;
  player_info.textContent = 'player ' + player_nr + " - " + player_symbol;

  refreshGameBoard();
}

function refreshGameBoard () {
  fetch('api.php?action_name=get-moves')
  .then((response) => response.json())
  .then((result) => {
    moves = result.moves;
    for (const cell of board.children) {
      const cell_id = cell.dataset.id;
      if (result.moves.hasOwnProperty(cell_id)) {
        const move = result.moves[cell_id];
        //{player_id: 'f2234iohfsdavk1312fds', cell_id: '0'}
        let symbol;
        if (move.player_id === player_identifiaction_number) {
          symbol = player_symbol;
        }
        else {
          symbol = (player_symbol === 'x') ? 'o' : 'x';
        }
        board.children[cell_id].textContent = symbol;
      }
      else {
        board.children[cell_id].textContent = '';
      }
    }

    if (timeout !== false) {
      timeout = setTimeout(() => {
        refreshGameBoard();
      }, 200);    
    }
  });
}

function stopRefreshing() {
  clearTimeout(timeout);
  timeout = false;
}

/*

*/



/*
Uzdevumu plāns:
  1. attēlojas spēlētājs
  2. uz klikšķa ievietot simbolu
  3. neļaut nederīgos gājienus (klienta pusē un servera pusē)
    3.1 klienta pusē
    3.2 nosūtīt informāciju par gājienu uz serveri


*/