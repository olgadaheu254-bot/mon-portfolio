<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inscription</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="container">
            <h1>Inscription</h1>
            <form method="POST" action="traitement_inscription.php">

                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" placeholder="Zennith" required>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" placeholder="Megumi" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" placeholder="megumizennith@gmail.com" required>

                <label for="date_naissance">Date de naissance :</label>
                <input type="date" id="date_naissance" name="date_naissance" required>

                <label for="telephone">Téléphone :</label>
                <input type="tel" id="telephone" name="telephone" placeholder="0000000000">

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit">S'inscrire</button>

            </form>

            <p class="link">Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
            <p class="link"><a href="index.php">Retour à l'accueil</a></p>
        </div>
    </body>
</html>