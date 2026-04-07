<?php
require_once '../config/database.php';
$page_title = 'Connexion - MonShop';

// Si déjà connecté, rediriger vers l'accueil
if(isset($_SESSION['user_id'])) {
    header('Location: /ecommerce/index.php');
    exit;
}

// Traitement du formulaire de connexion
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if(empty($username) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        // Rechercher l'utilisateur (par username ou email)
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            
            // Rediriger vers la page d'origine ou l'accueil
            $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '/ecommerce/index.php';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = "Identifiants incorrects.";
        }
    }
}

include '../includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg mt-5">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4"><i class="bi bi-box-arrow-in-right"></i> Connexion</h2>
                    <p class="text-center text-muted mb-4">Connectez-vous à votre compte</p>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_GET['registered'])): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill"></i> Inscription réussie ! Vous pouvez maintenant vous connecter.
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur ou Email *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                                       required autofocus>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Mot de passe *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me">
                                <label class="form-check-label" for="remember_me">
                                    Se souvenir de moi
                                </label>
                            </div>
                            <a href="#" class="text-decoration-none">Mot de passe oublié ?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <p class="text-center mb-0">
                        Vous n'avez pas de compte ? <a href="register.php" class="fw-bold">S'inscrire</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>