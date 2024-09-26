<?php
include_once 'Controller/conexao.php'; // Conexão com o banco de dados

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['codigo'])) {  // Certifique-se de que está verificando o 'codigo'
    // Atualizar o usuário
    $stmt = $conn->prepare("UPDATE usuarios SET nome=?, usuario=?, email=?, uf=?, cidade=?, telefone_fixo=?, telefone_movel=?, observacoes=?, notificacoes=?, data_expiracao=? WHERE id=?");
    $stmt->bind_param("ssssssssssi", $data['nome'], $data['usuario'], $data['email'], $data['uf'], $data['cidade'], $data['telefone_fixo'], $data['telefone_movel'], $data['observacoes'], $data['notificacoes'], $data['data_expiracao'], $data['codigo']);
    
    if ($stmt->execute()) {
        echo "Usuário atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar usuário: " . $stmt->error;
    }
} else {
    echo "Código do usuário não fornecido.";
}

?>
