

// Function to increase the number of articles
function increaseQty() {
  var effect = document.getElementById("qty");
  var qty = effect.value;
  if (!isNaN(qty)) effect.value++;
  return false;
}

// Function to reduce the number of articles
function decreaseQty() {
  var effect = document.getElementById("qty");
  var qty = effect.value;
  if (!isNaN(qty) && qty > 1) effect.value--;
  return false;
}

// Event listener for the <span> elements
document.querySelector(".qty-minus").addEventListener("click", decreaseQty);
document.querySelector(".qty-plus").addEventListener("click", increaseQty);
