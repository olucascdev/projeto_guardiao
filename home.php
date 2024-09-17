<?php 
    include_once "Controller/conexao.php";
    session_start();
   if(!isset($_SESSION['id'])) {
        header('Location: TelaLogin.php');
    }
    

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/home.css">
    <title>Document</title>
</head>
<body>
    <h1>Deu certo</h1>
    <a href="Controller/logout.php"><button>Logout</button></a>
    
    <?php
    // esse php vai ficar dentro de uma navbar na lateral
        if($_SESSION['acesso'] < 1){
        echo "Visitante";
        
        } elseif($_SESSION['acesso'] == 1){
          echo "Usuário";
        
        } elseif($_SESSION['acesso'] == 2){
          echo "Gestor";
        
        } elseif($_SESSION['acesso'] == 3){
          echo "Administrador";
        
        } else {
          echo "Master";
    };
    ?>
            


    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>