<?php 
session_start();
include_once 'Controller/conexao.php';

// Recebe o valor do parâmetro 'id' da URL e sanitiza
$perguntaId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($perguntaId > 0) {
    // Cria a consulta SQL para recuperar a pergunta, incluindo o id da avaliação
    $sql = "SELECT avaliacao_estabelecimento_id FROM avaliacao_estabelecimento_questoes WHERE id = $perguntaId";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado && $linha = mysqli_fetch_assoc($resultado)) {
        $avaliacaoId = $linha['avaliacao_estabelecimento_id']; // Armazena o id da avaliação

        $sqlDelete = "DELETE FROM avaliacao_estabelecimento_questoes WHERE id = $perguntaId";
        if (mysqli_query($conn, $sqlDelete)) {
            // Exibe mensagem de sucesso e redireciona
            echo "
                <script>
                    alert('Pergunta excluída com sucesso');
                    window.location.href='../projeto_guardiao/AddQuestionarios.php?codigo_avaliacao=$avaliacaoId';
                </script>
            ";
        } else {
            // Se a exclusão do banco falhar
            echo "
                <script>
                    alert('Erro ao excluir registro do banco de dados');
                    window.location.href='../projeto_guardiao/AddQuestionarios.php';
                </script>
            ";
        }
    } else {
        // Se não encontrar a pergunta
        echo "
            <script>
                alert('Pergunta não encontrada');
                window.location.href='../projeto_guardiao/AddQuestionarios.php';
            </script>
        ";
    }
} else {
    // Se o ID não for válido
    echo "
        <script>
            alert('ID inválido');
            window.location.href='../projeto_guardiao/AddQuestionarios.php';
        </script>
    ";
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
