<nav class="navbar">
    <div class="container nav-container">
        <div class="logo-wrapper">
            <a href="index.php" class="logo">
                <img src="../style/images/Gigi-logo-white.png" alt="Gigi" class="logo-image">
            </a>
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li>
                <a href="cart.php">
                    Cart <span id="cart-count-badge" class="cart-badge" style="display: none;"></span>
                </a>
            </li>
            <li><a href="orders.php">Orders</a></li>
            <?php if (!empty($_SESSION['user']) && $_SESSION['user']['is_admin']): ?>
                <li><a href="admin.php">Admin</a></li>
            <?php endif; ?>
            <?php if (!empty($_SESSION['user'])): ?>
                <li class="coins-display">💰 <?= (int)$_SESSION['user']['coins'] ?> coins</li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- Cart Popup -->
<div id="cart-popup" class="cart-popup" style="display: none;">
    <div class="cart-popup-content">
        <h3>✅ Item added to cart!</h3>
        <p>What would you like to do?</p>
        <div class="cart-popup-buttons">
            <a href="cart.php"><button class="btn-primary">View Cart</button></a>
            <button class="btn-secondary" onclick="closeCartPopup()">Continue Shopping</button>
        </div>
    </div>
</div>

<script src="../style/js/cart.js"></script>