<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/f1daa21f42.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css\accueil.css">
</head>

<body>
<header>
    
    <nav class="navbar">
    <a href="#" class="logo">InstaMeme</a>
    <div class="recherche" >
       <li><input placeholder="Rechercher"></li>
       </div>
    
    
    <div class="nav-links">
      <ul>
        <li><a href="index.php">Accueil</a></li>
        <?php 
          if(!isset($_SESSION['pseudo'])){
          
        ?>
        <li><a href="connexion.php">Connexion/Inscription</a></li>  
        <?php }else{
          ?>
        <li><a href="Profil.php">Profil</a></li>
        <li><a href="">Créer</a></li>
        <li><a href="deconnexion.php">Déconnexion</a></li>
        <?php }?>
         
        
        <li><a href="#"></a></li>
        
               
           
      </ul>

    </div>
</header>

<br>
<br>
<br>
<br>
<br>