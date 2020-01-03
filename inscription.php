<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="css\style.css">

</head>
<body>
<header>
        <h1>Jeu du snake</h1>
        <img id="snakePicture" src="image\snake header.png" alt="snake">
        <nav>
            <ul id="menu">
                <li><a href="index.php">Snake</a></li>
            </ul>
    </nav>
   </header>




<div id="form">
    <h3> Inscription</h3>
<?php
if (isset($_GET['message'])) {
    echo $_GET['message'];
}

if (isset($_SESSION['id']) AND isset($_SESSION['pseudo']))
{
    echo 'Bonjour ' . $_SESSION['pseudo'];
}
else 
{
    echo'
    <form action="post\inscription_post.php" method="post">
        <label for="pseudo"> Votre pseudo : </label><input type="text" name="pseudo" id="pseudo">
        <br />
        <label for="email"> Votre mail : </label><input type="text" name="email" id="email">
        <br />
        <label for="password"> Votre mot de passe : </label><input type="password" id="password" name="password">
        <br />
        <label for="confirm_password"> Confirmation de votre mot de passe : </label><input type="password" id="confirm_password" name="confirm_password">
        <br />
        <input type="submit">
    </form>
    <p>Deja un compte ? : <a href="index.php">connexion</a></p>
'

;
}
?>
    
    
</body>
</html> 





