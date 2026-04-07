</main>
    <script>
function addToCart(productId) {
    fetch('/ecommerce/cart/add.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('✅ Produit ajouté au panier !');
            // Recharger la page pour mettre à jour le badge
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        alert('Erreur lors de l\'ajout au panier');
    });
}
</script>
    <!-- Footer Bootstrap -->
    <footer class="bg-dark text-white mt-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="bi bi-cart-fill"></i> MonShop</h5>
                    <p>Votre boutique en ligne de confiance</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liens utiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Conditions générales</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Politique de confidentialité</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Suivez-nous</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> MonShop. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JS personnalisé -->
    <script src="/mon-ecommerce/assets/js/script.js"></script>
</body>
</html>