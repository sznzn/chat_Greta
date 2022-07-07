<?php
include_once "header.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        button{
            width: 150px;
        }
    </style>
    <title>Page chat</title>
</head>

<body class="container">
    <header>
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item border border-primary">
                <button class="nav-link" aria-current="pseudo" name="personne" ><?php echo "Bienvenu! ".$_SESSION["pseudo"]; ?></button>
            </li>
            <li class="nav-item border border-warning">
                <a class="nav-link" href="chat.php?action=deconnexion&id=<?=$_SESSION['id'];?>">deconnexion</a>
            </li>
        </ul>
    </header>


    <main class="container" >
        <div class="row">
            <div class="col-4 bg-warning">
<?php

if(session_id()!= NULL){
//需要修改的 1get为什么未definie
//因为没有isset 有了它就会识别
    if ( isset($_GET['action'])&&$_GET['action']=="deconnexion") {
        // ... code déconnexion...
        $sql = "DELETE FROM chat WHERE id = ".$_SESSION['id'];
        $requete = $bdd->prepare($sql);
        $requete->execute();
        //需要修改的
        header("Location:deconnexion.php");
    }
    
    
    echo "Liste de l'entrée";
    echo "<br>";
    
    
    
    //offre des based des données dans le mySql
    //ouvrir un nouveau site chat.php
    // on parle ici
    
    
    $sql = "SELECT * FROM chat";
    $requete = $bdd->prepare($sql);
    $requete->execute();
    $pseudos = $requete->fetchAll();
    
    foreach($pseudos as $p){
        echo "<br><button class='btn btn-secondary mb-1'>".$p["pseudo"]."</button>";
        
    }
}
        
?>
        </div>
            <div class="col-8 bg-secondary">
                l'écran pour se parler
                <div class="w-100 h-75 bg-light mb-3 overflow-auto">
                    <?php
                    $sql = "SELECT * FROM mymessage";
                    $requete = $bdd->prepare($sql);
                    $allmsg=$requete->execute();
                    if($allmsg){
                        echo "on commence!";
                    $allmsg=$requete= $bdd->query($sql);   
                    foreach($allmsg as $chqmsg){
                        
                        echo "<p>".$chqmsg["nom"]." : ". $chqmsg["msg"]." : ".$chqmsg["time_message"]."</p> ";
                        
                    }
                }
                    ?>
                </div>
                <form action="" method="POST">
                    <div class="w-100 h-10 row justify-content-around">
                    
                        <input  class="col-8  bg-light" type="text" name="msg" placeholder="parler ici">
                    
                        <button type="submit" class="col-2 btn btn-warning" name="envoyer">
                        envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
<?php
if(isset($_POST["envoyer"])&&(!empty($_POST["msg"]))){
    
    $nom = $_SESSION["pseudo"];
    $msg = htmlspecialchars($_POST["msg"]);
    $sql = "INSERT INTO mymessage(nom, msg, time_message) VALUES(?, ?, NOW())";
    $requete = $bdd->prepare($sql);
    $notreMsg=$requete->execute(array($nom, $msg));
    
    if($notreMsg){
        echo "envoyé";  
        header("Location: chat.php");
        }else{
            echo "problème";
        }

}
?>




