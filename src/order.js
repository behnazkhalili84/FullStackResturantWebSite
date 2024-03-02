var cartItems = [];
function addToCart(itemName, itemPrice) {
    // Add the item to the cartItems array or object
    var item = {
        name: itemName,
        price: itemPrice
    };
    cartItems.push(item);
    // Increment the cart item count
    var cartItemCount = document.getElementById("cartItemCount");
    cartItemCount.innerText = parseInt(cartItemCount.innerText) + 1;
}

function displayCart() {
    // Open a new window
    var cartWindow = window.open("", "_blank", "width=400,height=400");
    // Write the cart contents to the new window
    cartWindow.document.write("<h2>Cart Contents</h2>");
    cartWindow.document.write("<ul>");
    cartItems.forEach(function(item) {
        cartWindow.document.write("<li>" + item.name + " - $" + item.price + "</li>");
    });
    cartWindow.document.write("</ul>");
}
