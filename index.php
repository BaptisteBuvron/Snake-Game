<?php
session_start();
if (isset($_COOKIE['id']) && isset($_COOKIE['pseudo']) && isset($_COOKIE['score'])) {
    $_SESSION['id'] = $_COOKIE['id'];
    $_SESSION['pseudo'] = $_COOKIE['pseudo'];
    $_SESSION['score'] = $_COOKIE['score'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Jeu du serpent</title>
    <link rel="stylesheet" href="css\style.css">
    <script type="text/javascript" src="script.js"></script>
</head>
<body>
   <header>
        <h1>Jeu du snake</h1>
        <img id="snakePicture" src="image\snake header.png" alt="snake">
        <nav>
            <ul id="menu">
                <li><a href="personnalisation.php">Personnalisation</a></li>
                <li><a href="classement.php">Classement</a></li>
            </ul>
    </nav>
   </header>
    <div id="conteneur">
    <div id="snake"></div>
    
    <div id="form">
<?php
if (isset($_GET['message'])) {
    echo $_GET['message'];
    ?>
    <br />
<?php
}
if (isset($_SESSION['id']) AND isset($_SESSION['pseudo']))
{
    echo 'Bonjour ' . $_SESSION['pseudo'].'
    <br />
    <p>Deconnexion : <a href="deconnexion.php">deconnexion</a></p>';
}else
{
    ?>
    <h3>Connexion</h3>
    <?php
    echo '<form action="post/connexion_post.php" method="POST">
    <label for="pseudo"> Votre pseudo : </label><input type="text" name="pseudo" id="pseudo">
    <br />
    <label for="password"> Votre mot de passe : </label><input type="password" id="password" name="password">
    <br />
    <label for="connexion_auto"> Connexion automatique : </label><input type="checkbox" id="connexion_auto" name="connexion_auto">
    <br />
    <input type="submit">
</form>
<p>Vous n\'avez pas de compte  ? : <a href="inscription.php">inscription</a></p>';
}
?>



</div>
<div id="classement">
    <h2>Classement</h1>
    <?php 

    include("bdd.php");

    

    $req = $bdd->prepare('SELECT * FROM membres ORDER BY score DESC');
    $req->execute();
    $pseudoDansClassement = false;
    $i =1;
    while ($i<=3 && $donnees = $req->fetch()) {
        if (!empty($donnees['score'])) {
            echo '<p><strong>',$i,'</strong>. ',ucwords($donnees['pseudo']),' : ',$donnees['score'],' points';
        
            if (isset($_SESSION['id']) && $donnees['id'] == $_SESSION['id']) {
                $pseudoDansClassement = true;
        }
            $i++;
        }
        
        
    }
    $req->closeCursor();
    $i = 1;
    $req = $bdd->prepare('SELECT * FROM membres ORDER BY score DESC');
    $req->execute();
    while ($donnees = $req->fetch()) {
        if(isset($_SESSION['pseudo']) && $donnees['pseudo'] == $_SESSION['pseudo']){
            break;
        }
        $i++;
    }
    if (isset($_SESSION['id']) && !$pseudoDansClassement && $_SESSION['score']!=0 ) {
        echo '<p><strong>',$i,'</strong>. ',ucwords($_SESSION['pseudo']),' : ',$_SESSION['score'],' points';
    }



    
    
    
    
    
    
    ?>
</div>
</div>
<input id="refresh" type="button" onclick='window.location.reload(false)' value="Recommencer"/>
    
    







</body>
</html>