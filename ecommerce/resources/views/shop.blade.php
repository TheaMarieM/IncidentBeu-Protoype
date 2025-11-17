<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-commerce Shop</title>
<style>
body { 
    font-family: Arial, sans-serif; 
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
}

h1 { 
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

h2 { 
    color: #555;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
    margin: 30px 0 20px 0;
}

.products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.product { 
    background: white;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.product h3 {
    margin: 0 0 10px 0;
    color: #333;
}

.product .price {
    font-size: 18px;
    font-weight: bold;
    color: #e74c3c;
    margin: 10px 0;
}

.product .description {
    color: #666;
    margin: 10px 0;
    font-size: 14px;
}

.product .stock {
    color: #27ae60;
    font-size: 14px;
    margin: 10px 0;
}

.btn { 
    background: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
}

.btn:hover { 
    background: #0056b3;
}

.btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.cart {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.cart-item { 
    padding: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-item:last-child {
    border-bottom: none;
}

.remove-btn {
    background: #dc3545;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}

.remove-btn:hover {
    background: #c82333;
}

.order-btn {
    background: #28a745;
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 20px;
}

.order-btn:hover {
    background: #218838;
}

.message {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 4px;
    color: white;
    font-weight: bold;
    z-index: 1000;
    display: none;
}

.message.success {
    background: #28a745;
}

.message.error {
    background: #dc3545;
}

.empty {
    text-align: center;
    color: #999;
    font-style: italic;
    padding: 20px;
}
</style>
</head>
<body>

<h1>Online Shop</h1>

<h2>Products</h2>
<div class="products" id="products">
    <div class="empty">Loading products...</div>
</div>

<h2>Shopping Cart</h2>
<div class="cart">
    <div id="cart">
        <div class="empty">Cart is empty</div>
    </div>
    <button class="order-btn" onclick="placeOrder()">Place Order</button>
</div>

<div id="message" class="message"></div>

<script>
const API = "http://127.0.0.1:8000/api";
let cart = [];

async function loadProducts() {
    try {
        let res = await fetch(`${API}/products`);
        let products = await res.json();

        if(products.length === 0){
            document.getElementById("products").innerHTML = "<div class='empty'>No products available</div>";
            return;
        }

        let html = "";
        products.forEach(p => {
            html += `<div class="product">
                <h3>${p.name}</h3>
                <div class="price">₱${p.price}</div>
                <div class="description">${p.description}</div>
                <div class="stock">Stock: ${p.stock}</div>
                <button class="btn" onclick="addToCart(${p.id},'${p.name}',${p.price})" 
                        ${p.stock <= 0 ? 'disabled' : ''}>
                    ${p.stock <= 0 ? 'Out of Stock' : 'Add to Cart'}
                </button>
            </div>`;
        });
        document.getElementById("products").innerHTML = html;
    } catch (err) {
        document.getElementById("products").innerHTML = "<div class='empty'>Failed to load products</div>";
    }
}

async function addToCart(id, name, price){
    try {
        let existing = cart.find(i => i.product_id === id);
        if(existing){
            existing.quantity++;
        } else {
            cart.push({product_id: id, name: name, price: parseFloat(price), quantity: 1});
        }

        await fetch(`${API}/cart`, {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({product_id: id, quantity: 1})
        });

        showMessage(`${name} added to cart`);
        renderCart();
    } catch (err) {
        showMessage(`Failed to add ${name}`, 'error');
    }
}

function renderCart(){
    if(cart.length === 0){
        document.getElementById("cart").innerHTML = "<div class='empty'>Cart is empty</div>";
        return;
    }

    let html = "";
    cart.forEach(item => {
        html += `<div class="cart-item">
            <div>
                <strong>${item.name}</strong><br>
                Quantity: ${item.quantity} × ₱${item.price} = ₱${(item.price * item.quantity).toFixed(2)}
            </div>
            <button class="remove-btn" onclick="removeFromCart(${item.product_id})">Remove</button>
        </div>`;
    });

    document.getElementById("cart").innerHTML = html;
}

async function removeFromCart(id){
    try {
        cart = cart.filter(i => i.product_id !== id);
        await fetch(`${API}/cart/${id}`, {method: "DELETE"});
        renderCart();
        showMessage("Item removed");
    } catch (err) {
        showMessage("Failed to remove item", 'error');
    }
}

async function placeOrder(){
    if(cart.length === 0){
        showMessage("Cart is empty", 'error');
        return;
    }

    try {
        await fetch(`${API}/orders`, {method: "POST"});
        cart = [];
        renderCart();
        showMessage("Order placed successfully");
    } catch(err){
        showMessage("Failed to place order", 'error');
    }
}

function showMessage(msg, type = 'success'){
    const messageEl = document.getElementById("message");
    messageEl.textContent = msg;
    messageEl.className = `message ${type}`;
    messageEl.style.display = 'block';
    
    setTimeout(() => {
        messageEl.style.display = 'none';
    }, 3000);
}

loadProducts();
renderCart();
</script>

</body>
</html>
