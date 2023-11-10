

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



if ($("#minus-button").length > 0) {
  // Event listener for the <span> elements
  document.getElementById("minus-button").addEventListener("click", decreaseQty);
  document.getElementById("plus-button").addEventListener("click", increaseQty);
}