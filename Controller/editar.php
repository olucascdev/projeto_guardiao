<?php 
include_once 'conexao.php';

// Recebe os dados do formulário e sanitiza
$codigo = filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_NUMBER_INT);
$nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS);
$usuario = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$telefoneFixo = filter_input(INPUT_POST, "telefone_fixo", FILTER_SANITIZE_SPECIAL_CHARS);
$telefoneMovel = filter_input(INPUT_POST, "telefone_movel", FILTER_SANITIZE_SPECIAL_CHARS);

// Atualiza o usuário no banco de dados
$query = "UPDATE usuarios SET nome=?, usuario=?, email=?, telefone_fixo=?, telefone_movel=? WHERE id=$codigo";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssi", $nome, $usuario, $email, $telefoneFixo, $telefoneMovel, $codigo);

if ($stmt->execute()) {
    echo "Editado com Sucesso";
} else {
    echo "Erro ao editar: " . $stmt->error;
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>
