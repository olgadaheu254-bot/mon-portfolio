<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Mon E-commerce'; ?></title>
    
    <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/litera/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- CSS personnalisé (optionnel) -->
    <link rel="stylesheet" href="/ecommerce/assets/css/style.css">
</head>
<body>
    <!-- Navigation Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/ecommerce/index.php">
                <i class="bi bi-cart-fill"></i> MonShop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav ms-auto">
    <li class="nav-item">
        <a class="nav-link" href="/ecommerce/index.php">
            <i class="bi bi-house-fill"></i> Accueil
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" onclick="alert('Page Produits en construction'); return false;">
            <i class="bi bi-grid-fill"></i> Produits
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link position-relative" href="#" onclick="alert('Panier en construction'); return false;">
            <i class="bi bi-cart3"></i> Panier
            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo count($_SESSION['cart']); ?>
                </span>
            <?php endif; ?>
        </a>
    </li>
    
    <?php if(isset($_SESSION['user_id'])): ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i> <?php echo $_SESSION['first_name']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/ecommerce/user/profile.php">
                    <i class="bi bi-person"></i> Mon Profil
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="/ecommerce/user/logout.php">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a></li>
            </ul>
        </li>
    <?php else: ?>
        <li class="nav-item">
            <a class="nav-link" href="/ecommerce/user/login.php">
                <i class="bi bi-box-arrow-in-right"></i> Connexion
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-light text-primary ms-2 px-3" href="/ecommerce/user/register.php">
                <i class="bi bi-person-plus"></i> Inscription
            </a>
        </li>
    <?php endif; ?>
</ul>
        </div>
    </nav>
    
    <div class="container mt-4">
<?php

