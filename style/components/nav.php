<nav class="navbar">
    <div class="container nav-container">
        <a href="index.php" class="logo">Gigi</a>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="cart.php">Winkelmandje</a></li>
            <li><a href="orders.php">Bestellingen</a></li>
            <?php if (!empty($_SESSION['user']) && $_SESSION['user']['is_admin']): ?>
                <li><a href="admin.php">Admin</a></li>
            <?php endif; ?>
            <?php if (!empty($_SESSION['user'])): ?>
                <li><a href="logout.php">Uitloggen</a></li>
            <?php else: ?>
                <li><a href="login.php">Inloggen</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>