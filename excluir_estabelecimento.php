<?php 
session_start();
include_once 'Controller/conexao.php';

// Recebe o valor do parâmetro 'id' da URL e sanitiza
$estabelecimentoId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($estabelecimentoId > 0) {
    // Cria a consulta SQL para recuperar o nome do estabelecimento
    $sql = "SELECT * FROM estabelecimentos WHERE id = $estabelecimentoId";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado && $linha = mysqli_fetch_assoc($resultado)) {
        // Cria a consulta para excluir o estabelecimento
        $sqlDelete = "DELETE FROM estabelecimentos WHERE id = $estabelecimentoId";
        if (mysqli_query($conn, $sqlDelete)) {
            // Exibe mensagem de sucesso e redireciona
            echo "
                <script>
                    alert('Estabelecimento excluído com sucesso');
                    window.location.href='../projeto_guardiao/avaliacao_estabelecimento.php';
                </script>
            ";
        } else {
            // Se a exclusão do banco falhar
            echo "
                <script>
                    alert('Erro ao excluir registro do banco de dados');
                    window.location.href='../projeto_guardiao/avaliacao_estabelecimento.php';
                </script>
            ";
        }
    } else {
        // Se não encontrar o estabelecimento
        echo "
            <script>
                alert('Estabelecimento não encontrado');
                window.location.href='../projeto_guardiao/avaliacao_estabelecimento.php';
            </script>
        ";
    }
} else {
    // Se o ID não for válido
    echo "
        <script>
            alert('ID inválido');
            window.location.href='../projeto_guardiao/avaliacao_estabelecimento.php';
        </script>
    ";
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
