<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewpoert" content="width=device-width, initial-scale=1.0">
    <title>se connecter</title>
    <link rel="stylrheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>
        <form method="POST" action="traitement_connexion.php">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <p class="link">vous n'avez pas de compte ? <a href="inscription.php">S'inscrire</a></p>
        <p class="link"><a href="index.php">Retour à l'accueil</a></p>
    </div>
</body>

</html>