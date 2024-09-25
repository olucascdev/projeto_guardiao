<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'conexao.php'; // Conexão com o banco de dados

// Verifica se os dados foram enviados
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    // Captura os dados recebidos do formulário
    $nome = $data['nome'];
    $usuario = $data['usuario'];
    $email = $data['email'];
    $cidade = $data['cidade'];
    $regiao = $data['uf']; // Estado
    $telFixo = $data['telefone_fixo'];
    $telMovel = $data['telefone_movel'];
    $observacao = $data['observacoes'];
    $notificacao = $data['notificacoes'];
    $dataExpiracao = $data['data_expiracao'];

    // Define a senha padrão (guardiao123) para todos os usuários
    $senha = 'guardiao123'; // Armazena a senha criptografada

    // Escapar os dados para evitar injeção de SQL
    $nome = mysqli_real_escape_string($conn, $nome);
    $usuario = mysqli_real_escape_string($conn, $usuario);
    $email = mysqli_real_escape_string($conn, $email);
    $cidade = mysqli_real_escape_string($conn, $cidade);
    $regiao = mysqli_real_escape_string($conn, $regiao);
    $telFixo = mysqli_real_escape_string($conn, $telFixo);
    $telMovel = mysqli_real_escape_string($conn, $telMovel);
    $observacao = mysqli_real_escape_string($conn, $observacao);
    $notificacao = mysqli_real_escape_string($conn, $notificacao);
    $dataExpiracao = mysqli_real_escape_string($conn, $dataExpiracao);

    // Monta a query de inserção
    $sql = "INSERT INTO usuarios (nome, usuario, email, senha, regiao, cidade, tel_fixo, tel_movel, observacao, notificacao, expira, acesso, ativo) 
            VALUES ('$nome', '$usuario', '$email', '$senha', '$regiao', '$cidade', '$telFixo', '$telMovel', '$observacao', '$notificacao', '$dataExpiracao', 'full', 1)";

    // Executa a query e verifica se deu certo
    if (mysqli_query($conn, $sql)) {
        echo 'Usuário cadastrado com sucesso!';
    } else {
        echo 'Erro ao cadastrar o usuário: ' . mysqli_error($conn);
    }

    // Fecha a conexão
    mysqli_close($conn);
} else {
    echo 'Nenhum dado recebido.';
}
?>
