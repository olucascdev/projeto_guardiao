<?php 

    $server = 'localhost';
    $user = 'root';
    $password = '';
    $db = 'projeto_guardiao';

    $conn = mysqli_connect($server,$user,$password, $db);

    if ($conn->connect_errno) {

        echo "Falha na conexão: " . $conn->connect_error;
        exit();
    }

    
?>