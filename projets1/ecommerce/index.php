<?php
require_once 'config/database.php';
$page_title = 'Accueil - MonShop';
include 'includes/header.php';
?>

<div class="container">
    
    <!-- Section Hero avec Bootstrap -->
    <section class="hero bg-gradient bg-primary text-white rounded p-5 mb-5 text-center">
        <h1 class="display-4 fw-bold">Bienvenue sur MonShop</h1>
        <p class="lead fs-4">Découvrez nos produits de qualité à prix imbattables</p>
        <a href="products/index.php" class="btn btn-light btn-lg mt-3">
            <i class="bi bi-bag-check"></i> Voir nos produits
        </a>
    </section>

    <!-- Section Produits en vedette -->
    <section class="featured-products mb-5">
        <h2 class="text-center mb-4">Produits en vedette</h2>
        <div class="row g-4">
            <?php
            try {
                // Récupérer les produits
                $stmt = $pdo->query("SELECT * FROM products WHERE active = 1 ORDER BY created_at DESC LIMIT 6");
                $products = $stmt->fetchAll();

                if(count($products) > 0) {
                    foreach($products as $product) {
                        ?>
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm">
                                <img src="<?php echo !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/400x300?text=Produit'; ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     style="height: 250px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?php echo substr(htmlspecialchars($product['description']), 0, 100) . '...'; ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="fs-4 fw-bold text-success"><?php echo number_format($product['price'], 2); ?> €</span>
                                    <button class="btn btn-primary" onclick="addToCart(<?php echo $product['id']; ?>)">
                                                <i class="bi bi-cart-plus"></i> Ajouter
                                                </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12"><div class="alert alert-info">Aucun produit disponible pour le moment.</div></div>';
                }
            } catch(PDOException $e) {
                echo '<div class="col-12"><div class="alert alert-danger">Erreur lors du chargement des produits.</div></div>';
            }
            ?>
        </div>
    </section>

    <!-- Section Avantages -->
    <section class="advantages mb-5">
        <h2 class="text-center mb-4">Pourquoi nous choisir ?</h2>
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="display-1 text-primary mb-3">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h5 class="card-title">Livraison rapide</h5>
                        <p class="card-text">Livraison sous 48h partout en France</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="display-1 text-success mb-3">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h5 class="card-title">Paiement sécurisé</h5>
                        <p class="card-text">Vos transactions sont 100% sécurisées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="display-1 text-warning mb-3">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </div>
                        <h5 class="card-title">Retour gratuit</h5>
                        <p class="card-text">14 jours pour changer d'avis</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="display-1 text-info mb-3">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h5 class="card-title">Support client</h5>
                        <p class="card-text">Disponible 7j/7 pour vous aider</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<?php include 'includes/footer.php'; ?>