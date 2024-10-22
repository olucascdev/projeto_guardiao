<?php 
session_start();
include_once 'Controller/conexao.php';

// Recebe o valor do parâmetro 'id' da URL e sanitiza
$perguntaId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Recebe e sanitiza os outros parâmetros da URL para redirecionamento
$codigo_avaliacao = filter_input(INPUT_GET, 'codigo_avaliacao', FILTER_SANITIZE_NUMBER_INT) ?? null;
$nome_avaliacao = filter_input(INPUT_GET, 'nome_avaliacao', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
$estabelecimento_id = filter_input(INPUT_GET, 'estabelecimento_id', FILTER_SANITIZE_NUMBER_INT) ?? null;
$data_cadastro = filter_input(INPUT_GET, 'data_cadastro', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
$observacoes = filter_input(INPUT_GET, 'observacoes', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

if ($perguntaId > 0) {
    // Cria a consulta SQL para recuperar a pergunta, incluindo o id da avaliação
    $sql = "SELECT avaliacao_estabelecimento_id FROM avaliacao_estabelecimento_questoes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $perguntaId);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $linha = $resultado->fetch_assoc()) {
        $avaliacaoId = $linha['avaliacao_estabelecimento_id']; // Armazena o id da avaliação

        // Exclui a pergunta
        $sqlDelete = "DELETE FROM avaliacao_estabelecimento_questoes WHERE id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $perguntaId);

        if ($stmtDelete->execute()) {
            // Exibe mensagem de sucesso e redireciona, mantendo os parâmetros na URL
            echo "
                <script>
                    alert('Pergunta excluída com sucesso');
                    window.location.href='AddQuestionarios.php?codigo_avaliacao=$codigo_avaliacao&nome_avaliacao=" . urlencode($nome_avaliacao) . "&estabelecimento_id=$estabelecimento_id&data_cadastro=" . urlencode($data_cadastro) . "&observacoes=" . urlencode($observacoes) . "';
                </script>
            ";
        } else {
            // Se a exclusão do banco falhar
            echo "
                <script>
                    alert('Erro ao excluir registro do banco de dados');
                    window.location.href='AddQuestionarios.php?codigo_avaliacao=$codigo_avaliacao&nome_avaliacao=" . urlencode($nome_avaliacao) . "&estabelecimento_id=$estabelecimento_id&data_cadastro=" . urlencode($data_cadastro) . "&observacoes=" . urlencode($observacoes) . "';
                </script>
            ";
        }
    } else {
        // Se não encontrar a pergunta
        echo "
            <script>
                alert('Pergunta não encontrada');
                window.location.href='AddQuestionarios.php?codigo_avaliacao=$codigo_avaliacao&nome_avaliacao=" . urlencode($nome_avaliacao) . "&estabelecimento_id=$estabelecimento_id&data_cadastro=" . urlencode($data_cadastro) . "&observacoes=" . urlencode($observacoes) . "';
            </script>
        ";
    }
} else {
    // Se o ID não for válido
    echo "
        <script>
            alert('ID inválido');
            window.location.href='AddQuestionarios.php?codigo_avaliacao=$codigo_avaliacao&nome_avaliacao=" . urlencode($nome_avaliacao) . "&estabelecimento_id=$estabelecimento_id&data_cadastro=" . urlencode($data_cadastro) . "&observacoes=" . urlencode($observacoes) . "';
        </script>
    ";
}

// Fecha a conexão com o banco de dados
$stmt->close();
$stmtDelete->close();
$conn->close();
?>
