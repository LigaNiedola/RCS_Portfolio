const cartContainer = document.getElementById("cart-container");
const totalCountElement = document.getElementById("total-count");
const totalPriceElement = document.getElementById("total-price");

let cartItems = {};
let totalCount = 0;
let totalPrice = 0;

function renderCart() {
  cartContainer.innerHTML = "";

  for (const id in cartItems) {
    const item = cartItems[id];
    const itemElement = createItemElement(item, id);
    cartContainer.appendChild(itemElement);
  }

  totalCountElement.innerText = `Total Count: ${totalCount} Pcs`;
  totalPriceElement.innerText = `Total Price: ${totalPrice} USD`;
}

function createItemElement(item, id) {
  const itemElement = document.createElement("div");
  itemElement.classList.add("item");
  itemElement.dataset.id = id;

  const itemImage = document.createElement("img");
  itemImage.src = item.thumbnail;

  const itemName = document.createElement("h4");
  itemName.innerText = item.title;

  const increaseButton = createButton("+");
  increaseButton.addEventListener("click", () => {
    changeItemCount(id, 1);
  });

  const itemCount = document.createElement("span");
  itemCount.classList.add("count");
  itemCount.innerText = item.order_count;

  const decreaseButton = createButton("-");
  decreaseButton.addEventListener("click", () => {
    changeItemCount(id, -1);
  });

  const itemPrice = document.createElement("span");
  itemPrice.innerText = `${item.price * item.order_count} USD`;

  const deleteButton = createButton("Delete");
  deleteButton.addEventListener("click", () => {
    changeItemCount(id, 0);
  });

  itemElement.appendChild(itemImage);
  itemElement.appendChild(itemName);
  itemElement.appendChild(decreaseButton);
  itemElement.appendChild(itemCount);
  itemElement.appendChild(increaseButton);
  itemElement.appendChild(itemPrice);
  itemElement.appendChild(deleteButton);

  return itemElement;
}

function createButton(text) {
  const button = document.createElement("button");
  button.innerText = text;
  return button;
}

function changeItemCount(id, amount) {
  // amount == 0 - delete operation
  if (cartItems[id]) {
    if (amount !== 0) {
      cartItems[id].order_count += amount;

      totalCount += amount;
      totalPrice += amount * cartItems[id].price;

      if (cartItems[id].order_count < 1) {
        delete cartItems[id];
        deleteFromAPI(id);
      }
    }
    else {
      totalCount -= cartItems[id].order_count;
      totalPrice -= cartItems[id].order_count * cartItems[id].price;

      delete cartItems[id];
      deleteFromAPI(id);
    }

    // TODO:  Update to API
    /**
     * 1. Fetch pieprasījums
     * 2. Api un Crud loģīka
     */
    renderCart();
  }
}


function deleteFromAPI (id) {
  const data = new FormData();
  data.append('id', id);
  fetch("api.php?action_name=delete&from_javascript", {
    method: 'post',
    body: data
  });
}

document.addEventListener("DOMContentLoaded", function () {
  fetch("api.php?action_name=read&from_javascript")
    .then(function (response) {return response.json()})
    .then(function (result) {
      cartItems = result.data.entries;
      for (const id in cartItems) {
        const entry = cartItems[id]
        totalCount += entry.order_count;
        totalPrice += entry.order_count *  entry.price;
      }
      renderCart();
    });
});
