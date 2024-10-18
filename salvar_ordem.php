<?php
session_start(); // Inicia a sessão para manter as informações do usuário
include_once 'Controller/conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Define o cabeçalho para resposta em JSON
header('Content-Type: application/json');

// Recebe o conteúdo do POST (JSON)
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['ordem']) && isset($data['codigo_avaliacao'])) {
    $ordem = $data['ordem'];
    $codigo_avaliacao = $data['codigo_avaliacao'];

    // Inicia uma transação para garantir a atomicidade das alterações
    mysqli_begin_transaction($conn);

    try {
        // Atualiza a ordem de cada pergunta
        foreach ($ordem as $posicao => $id_pergunta) {
            $nova_ordem = $posicao + 1; // A ordem começa de 1
            $query = "UPDATE avaliacao_estabelecimento_questoes SET ordem = ? WHERE id = ? AND avaliacao_estabelecimento_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'iii', $nova_ordem, $id_pergunta, $codigo_avaliacao);
            mysqli_stmt_execute($stmt);
        }

        // Confirma a transação
        mysqli_commit($conn);
        
       

    } catch (Exception $e) {
        // Em caso de erro, desfaz a transação
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
}
?>
