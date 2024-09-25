<?php
// Conexão com o banco de dados
include_once 'Controller/conexao.php';

// Lê os dados JSON enviados
$dados = json_decode(file_get_contents('php://input'), true);

$nome = $dados['nome'];
$permissao = $dados['permissao'];
$estabelecimento = $dados['estabelecimento'];
$empresa = $dados['empresa'];
$login = $dados['login'];
$email = $dados['email'];
$uf = $dados['uf'];
$cidade = $dados['cidade'];
$telefoneFixo = $dados['telefone_fixo'];
$telefoneMovel = $dados['telefone_movel'];
$observacoes = $dados['observacoes'];
$notificacoes = $dados['notificacoes'];
$dataExpiracao = $dados['data_expiracao'];

// Preparar e executar a query de inserção
$stmt = $conn->prepare("INSERT INTO usuarios (nome, acesso, login, email, regiao, cidade, telefone_fixo, telefone_movel, observacoes, notificacoes, data_expiracao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssssss", $nome, $permissao, $estabelecimento, $empresa, $login, $email, $uf, $cidade, $telefoneFixo, $telefoneMovel, $observacoes, $notificacoes, $dataExpiracao);

if ($stmt->execute()) {
    echo "Usuário cadastrado com sucesso!";
} else {
    echo "Erro ao cadastrar o usuário: " . $stmt->error;
}
?>
