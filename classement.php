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
    echo '<form action="post\connexion_post.php?pageSource=classement.php" method="post">
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

<div id="listclassement">
    <h2>Classement</h2>
<div id="listclassementP">
    <?php  


    include("bdd.php");

    if (isset($_POST['page'])) {
        $nbrElmtPage= ($_POST['page']-1)*10;
    }
    else{
        $nbrElmtPage = 0;
    }

    

    $req = $bdd->prepare('SELECT * FROM membres ORDER BY score DESC LIMIT '.$nbrElmtPage.', 10');
    $req->execute();
    $i =$nbrElmtPage+1;
    while ($donnees = $req->fetch()) {
        if (!empty($donnees['score'])) {
            echo '<p><strong>',$i,'</strong>. ',ucwords($donnees['pseudo']),' : ',$donnees['score'],' points';
            $i++;
        }
        
        
    }
    $req->closeCursor();
  
   
    
    ?>

    

</div>
</div>
</div>
<form id="selectionPage"action="classement.php" method='post'>
<p>Selection de la page : </p>
<select name="page" >
<?php
$req= $bdd->query('SELECT COUNT(*) as total FROM membres WHERE score !=0');
$total = $req->fetchColumn();
$nbr_page =  $total /10;
$nbr_page = (int) $nbr_page;
if ($total % 10 != 0) {
    $nbr_page +=1;
}

for ($i=1; $i <= $nbr_page ; $i++) {
    if (isset($_POST['page']) && $i == $_POST['page']) {
        echo '<option value='.$i.' selected>'.$i.'</option>';
    }else{
        echo '<option value='.$i.'>'.$i.'</option>';

    }
}
?>


<select>
<br/>
<input type="submit" style="margin-top: 10px">
</form>

        

  






    
    
    
    
    
    
 
    
    







</body>
</html>