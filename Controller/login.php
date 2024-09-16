<?php 
    session_start();
    include_once 'conexao.php';

    if(isset($_POST['entrar']) && !empty($_POST['user']) && !empty($_POST['senha'])){

        $user = filter_input(INPUT_POST,'user',FILTER_SANITIZE_SPECIAL_CHARS );
        $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_SPECIAL_CHARS);

        if(filter_var($user,FILTER_VALIDATE_EMAIL)) {
            // Se for um email, busque pelo campo 'email'
            $sql = "SELECT * FROM usuarios WHERE email='$user' and senha='$senha'";
        }else{
            // Caso contrário, busque pelo 'usuario'
            $sql = "SELECT * FROM usuarios Where usuario='$user' and senha='$senha'";
        
        }

        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die('Erro na consulta: ' . mysqli_error($conn));
        }

        if(mysqli_num_rows($result) < 1 )
        { //caso nao exista
            unset($_SESSION['user']);
            unset($_SESSION['senha']);
            header('Location: TelaLogin.php');
            exit(); 
            

        }else{ //caso exista
            $_SESSION['user'] = $user;
            $_SESSION['senha'] = $senha;
            header('Location: ../sistema.php');
            exit(); 
        }
    }    
    
?>