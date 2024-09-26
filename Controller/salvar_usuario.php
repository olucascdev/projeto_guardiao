<?php
include_once 'conexao.php';

// Verifica se o ID do usuário foi enviado
if (isset($_POST['id'])) {
    // Coleta os dados do formulário
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $uf = $_POST['uf'];
    $cidade = $_POST['cidade'];
    $telefoneFixo = $_POST['telefoneFixo'];
    $telefoneMovel = $_POST['telefoneMovel'];
    $observacoes = $_POST['observacoes'];
    $notificacoes = $_POST['notificacoes'];
    $dataExpiracao = $_POST['dataExpiracao'];

    // Prepara a query para atualizar os dados do usuário
    $stmt = $conn->prepare("UPDATE usuarios SET nome=?, usuario=?, email=?, regiao=?, cidade=?, tel_fixo=?, tel_movel=?, observacao=?, notificacao=?, expira=? WHERE id=?");
    $stmt->bind_param("ssssssssssi", $nome, $usuario, $email, $uf, $cidade, $telefoneFixo, $telefoneMovel, $observacoes, $notificacoes, $dataExpiracao, $id);

    // Executa a query
    if ($stmt->execute()) {
        echo "Usuário atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar usuário: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "ID do usuário não fornecido.";
}

// Fecha a conexão
$conn->close();
?>