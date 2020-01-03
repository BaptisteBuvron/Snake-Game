<?php
session_start();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Jeu du serpent</title>
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
   <div id="conteneur">
        <div id="form">

        
<?php

if (isset($_GET['message'])) {
    echo $_GET['message'];
}
?>
<br/>
<?php

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
    echo '<form action="post\connexion_post.php?pageSource=personnalisation.php" method="post">
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

<?php

if (isset($_SESSION['id'])) {
    include("bdd.php");
    $req = $bdd->prepare('SELECT * FROM membres WHERE pseudo = :pseudo AND id = :id ');
    $req->execute(array(
        'pseudo' => $_SESSION['pseudo'],
        'id' => $_SESSION['id']
    ));
    $donnnee = $req->fetch();
    if (!empty($donnnee['color'])) {
        $color = unserialize($donnnee['color']);
    }
    if(!empty($donnnee['keyboard'])){
        $keyboard = unserialize($donnnee['keyboard']);
    }
}


?>
        </div>
        <div id="color">
            <h2>Personnalisation Serpent</h1>
            <form action="post/personnalisation_post.php" method="POST">
                <label for="colorHeadSelect"> Choix de la couleur de la tête de votre serpent : </label><input id="colorHeadSelect" name="colorHeadSelect" type="color" value="<?php if (isset($color)){echo $color['colorHeadSelect'];}else{echo "#01fd00";}  ?>">
                <br/>
                <label for="colorSelect"> Choix de la couleur de votre serpent : </label><input id="colorSelect" name="colorSelect" type="color" value="<?php if (isset($color)){echo $color['colorSelect'];}else{echo "#ff0000";}  ?>">
                <br/>
                <label for="colorAppleSelect"> Choix de la couleur de la pomme : </label><input id="colorAppleSelect" name="colorAppleSelect" type="color" value="<?php if (isset($color)){echo $color['colorAppleSelect'];}else{echo "#33cc33";}  ?>">
                <br/>
                <label for="reset"> Renitialiser : </label><input type="checkbox" id="reset" name="resetColor">
                <br/>
                <input type="submit" >
            </form>

        </div>
        <div id="keyboard">
            <h2>Personnalisation touche</h2>
            <form action="post/personnalisation_post.php" method="POST">
                <label for="keyUp"> Haut :</label><input type="text" id="keyUp" name="keyUp" value="<?php if (isset($keyboard)){echo $keyboard['keyUp'];}?>">
                <br/>
                <label for="keyLeft"> Gauche :</label><input type="text" id="keyLeft" name="keyLeft" value="<?php if (isset($keyboard)){echo $keyboard['keyLeft'];}?>">
                <br/>
                <label for="keyDown"> Bas :</label><input type="text" id="keyDown" name="keyDown" value="<?php if (isset($keyboard)){echo $keyboard['keyDown'];}?>">
                <br/>
                <label for="keyRight"> Droite :</label><input type="text" id="keyRight" name="keyRight" value="<?php if (isset($keyboard)){echo $keyboard['keyRight'];}?>">
                <br/>
                <label for="keySpace"> Pause / Rejouer :</label><input type="text" id="keySpace" name="keySpace" value="<?php if (isset($keyboard)){echo $keyboard['keySpace'];}?>">
                <br/>
                <label for="reset"> Renitialiser : </label><input type="checkbox" id="reset" name="resetKeyboard">
                <br/>
                <input type="submit" >
            </form>
            <p>Afin de connaitre l'id d'une touche veuillez-vous referrer au site suivant : <a href="https://www.dcode.fr/code-touches-javascript">Dcode</a></p>

        </div>

    </div>






    
    
    
    
    
    
 
    
    







</body>
</html>