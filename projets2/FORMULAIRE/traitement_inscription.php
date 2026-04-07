<?php
// Démarrer la session
session_start();

// Fonction utilitaire pour rediriger avec un message d'erreur
function redirectWithError(string $message): void {
    $_SESSION['erreur'] = $message;
    header('Location: inscription.php');
    exit();
}

// Vérifier que la requête vient bien d'un formulaire POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inscription.php');
    exit();
}

// -----------------------------------------------
// 1. RÉCUPÉRATION ET NETTOYAGE DES DONNÉES
// -----------------------------------------------
$nom             = isset($_POST['nom'])              ? trim($_POST['nom'])              : '';
$prenom          = isset($_POST['prenom'])           ? trim($_POST['prenom'])           : '';
$email           = isset($_POST['email'])            ? trim($_POST['email'])            : '';
$date_naissance  = isset($_POST['date_naissance'])   ? trim($_POST['date_naissance'])   : '';
$telephone       = isset($_POST['telephone'])        ? trim($_POST['telephone'])        : '';
$password        = isset($_POST['password'])         ? $_POST['password']               : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password']      : '';

// -----------------------------------------------
// 2. VALIDATIONS
// -----------------------------------------------

// Champs obligatoires
if (empty($nom) || empty($prenom) || empty($email) || empty($date_naissance) || empty($password) || empty($confirm_password)) {
    redirectWithError("Tous les champs obligatoires doivent être remplis.");
}

// Longueur du nom / prénom
if (strlen($nom) > 100 || strlen($prenom) > 100) {
    redirectWithError("Le nom ou le prénom est trop long (100 caractères maximum).");
}

// Format de l'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithError("L'adresse email n'est pas valide.");
}

// Longueur de l'email
if (strlen($email) > 255) {
    redirectWithError("L'adresse email est trop longue.");
}

// Format de la date de naissance (YYYY-MM-DD)
$date = DateTime::createFromFormat('Y-m-d', $date_naissance);
if (!$date || $date->format('Y-m-d') !== $date_naissance) {
    redirectWithError("Le format de la date de naissance est invalide.");
}

// Vérifier que la date est dans le passé et âge minimum (13 ans)
$age = (new DateTime())->diff($date)->y;
if ($date > new DateTime()) {
    redirectWithError("La date de naissance ne peut pas être dans le futur.");
}
if ($age < 13) {
    redirectWithError("Vous devez avoir au moins 13 ans pour vous inscrire.");
}

// Téléphone (optionnel, mais validé si fourni)
if (!empty($telephone)) {
    // Accepte les formats : +33 6 12 34 56 78 / 0612345678 / +33612345678
    if (!preg_match('/^[+]?[\d\s\-().]{7,20}$/', $telephone)) {
        redirectWithError("Le numéro de téléphone n'est pas valide.");
    }
}

// Correspondance des mots de passe
if ($password !== $confirm_password) {
    redirectWithError("Les mots de passe ne correspondent pas.");
}

// Force du mot de passe (min. 8 caractères, 1 majuscule, 1 chiffre)
if (strlen($password) < 8) {
    redirectWithError("Le mot de passe doit contenir au moins 8 caractères.");
}
if (!preg_match('/[A-Z]/', $password)) {
    redirectWithError("Le mot de passe doit contenir au moins une lettre majuscule.");
}
if (!preg_match('/[0-9]/', $password)) {
    redirectWithError("Le mot de passe doit contenir au moins un chiffre.");
}

// -----------------------------------------------
// 3. HACHAGE DU MOT DE PASSE
// -----------------------------------------------
$password_hache = password_hash($password, PASSWORD_DEFAULT);

// -----------------------------------------------
// 4. ACCÈS BASE DE DONNÉES
// -----------------------------------------------
try {
    $connexion = new PDO(
        "mysql:host=localhost;dbname=nom_de_ta_base;charset=utf8mb4",
        "utilisateur",
        "mot_de_passe"
    );
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Vérifier si l'email est déjà utilisé
    $verif = $connexion->prepare("SELECT id FROM utilisateurs WHERE email = ? LIMIT 1");
    $verif->execute([$email]);

    if ($verif->rowCount() > 0) {
        redirectWithError("Cette adresse email est déjà associée à un compte.");
    }

    // Insérer le nouvel utilisateur
    $requete = $connexion->prepare(
        "INSERT INTO utilisateurs (nom, prenom, email, date_naissance, telephone, password)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $requete->execute([
        $nom,
        $prenom,
        $email,
        $date_naissance,
        $telephone ?: null,  // NULL si le téléphone n'est pas fourni
        $password_hache
    ]);

    // -----------------------------------------------
    // 5. SUCCÈS : redirection propre
    // -----------------------------------------------
    $_SESSION['inscription_success'] = "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.";
    header('Location: connexion.php');
    exit();

} catch (PDOException $e) {
    // On logge l'erreur réelle sans la montrer à l'utilisateur
    error_log("[INSCRIPTION] Erreur PDO : " . $e->getMessage());
    redirectWithError("Une erreur est survenue lors de l'inscription. Veuillez réessayer plus tard.");
}
?>