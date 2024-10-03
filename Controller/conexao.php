<?php 
    include_once 'config.php';
    

    if ($conn->connect_errno) {

        echo "Falha na conexão: " . $conn->connect_error;
        exit();
    }
    if ($conn1->connect_errno) {

        echo "Falha na conexão: " . $conn1->connect_error;
        exit();
    }
    

    
?>