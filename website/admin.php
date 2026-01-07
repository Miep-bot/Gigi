<?php
session_start();
require_once __DIR__ . '/../classes/config/Auth.php';

Auth::admin();
?>

<h1>Admin dashboard</h1>
<p>Welkom admin 👑</p>
<a href="index.php">Naar de webshop</a>