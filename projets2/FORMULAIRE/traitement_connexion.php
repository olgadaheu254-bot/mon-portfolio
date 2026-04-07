<?php
session_start();

// -----------------------------------------------
// 1. VÉRIFICATION DE LA MÉTHODE
// -----------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: connexion.php');
    exit();
}

// -----------------------------------------------
// 2. RÉCUPÉRATION ET NETTOYAGE DES DONNÉES
// -----------------------------------------------
$email    = isset($_POST['email'])    ? trim($_POST['email'])    : '';
$password = isset($_POST['password']) ? $_POST['password']       : '';

// -----------------------------------------------
// 3. VALIDATIONS DE BASE
// -----------------------------------------------
if (empty($email) || empty($password)) {
    $_SESSION['erreur'] = "Veuillez remplir tous les champs.";
    header('Location: connexion.php');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erreur'] = "Format d'email invalide.";
    header('Location: connexion.php');
    exit();
}

// -----------------------------------------------
// 4. PROTECTION BRUTE FORCE (basique par session)
// -----------------------------------------------
// On limite à 5 tentatives, avec un délai de 15 minutes
if (!isset($_SESSION['tentatives'])) {
    $_SESSION['tentatives'] = 0;
    $_SESSION['premiere_tentative'] = time();
}

$delai_blocage = 15 * 60; // 15 minutes en secondes

if ($_SESSION['tentatives'] >= 5) {
    $temps_ecoule = time() - $_SESSION['premiere_tentative'];

    if ($temps_ecoule < $delai_blocage) {
        $minutes_restantes = ceil(($delai_blocage - $temps_ecoule) / 60);
        $_SESSION['erreur'] = "Trop de tentatives échouées. Réessayez dans {$minutes_restantes} minute(s).";
        header('Location: connexion.php');
        exit();
    } else {
        // Réinitialiser le compteur après le délai
        $_SESSION['tentatives'] = 0;
        $_SESSION['premiere_tentative'] = time();
    }
}

// -----------------------------------------------
// 5. ACCÈS BASE DE DONNÉES
// -----------------------------------------------
try {
    $connexion = new PDO(
        "mysql:host=localhost;dbname=nom_de_ta_base;charset=utf8mb4",
        "utilisateur",
        "mot_de_passe"
    );
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // On sélectionne uniquement les colonnes nécessaires (pas SELECT *)
    $requete = $connexion->prepare(
        "SELECT id, nom, prenom, email, password FROM utilisateurs WHERE email = ? LIMIT 1"
    );
    $requete->execute([$email]);
    $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

    // -----------------------------------------------
    // 6. VÉRIFICATION DU MOT DE PASSE
    // -----------------------------------------------
    // On vérifie toujours les deux conditions ensemble pour éviter
    // les attaques par timing (on ne révèle pas si l'email existe)
    if ($utilisateur && password_verify($password, $utilisateur['password'])) {

        // Réinitialiser le compteur de tentatives
        $_SESSION['tentatives'] = 0;

        // IMPORTANT : régénérer l'ID de session pour éviter la fixation de session
        session_regenerate_id(true);

        // Stocker les infos utiles en session (jamais le mot de passe !)
        $_SESSION['user_id'] = $utilisateur['id'];
        $_SESSION['nom']     = $utilisateur['nom'];
        $_SESSION['prenom']  = $utilisateur['prenom'];
        $_SESSION['email']   = $utilisateur['email'];

        // Redirection propre vers l'accueil
        header('Location: accueil.php');
        exit();

    } else {
        // Incrémenter le compteur de tentatives échouées
        $_SESSION['tentatives']++;

        // Message volontairement vague : on ne précise pas si c'est l'email ou le mdp
        $_SESSION['erreur'] = "Email ou mot de passe incorrect.";
        header('Location: connexion.php');
        exit();
    }

} catch (PDOException $e) {
    // On logge sans exposer l'erreur à l'utilisateur
    error_log("[CONNEXION] Erreur PDO : " . $e->getMessage());
    $_SESSION['erreur'] = "Une erreur est survenue. Veuillez réessayer plus tard.";
    header('Location: connexion.php');
    exit();
}