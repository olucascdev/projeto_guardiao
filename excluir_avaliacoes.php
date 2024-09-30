<?php 
session_start();
include_once 'Controller/conexao.php';

// Recebe o valor do parâmetro 'id' da URL e sanitiza
$avaliacaoId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($avaliacaoId > 0) {
    // Cria a consulta SQL para recuperar o nome da imagem associada ao usuário
    $sql = "SELECT * FROM avaliacao WHERE id = $avaliacaoId";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado && $linha = mysqli_fetch_assoc($resultado)) {
        $sqlDelete = "DELETE FROM avaliacao WHERE id = $avaliacaoId";
        if (mysqli_query($conn, $sqlDelete)) {
            // Verifica se o arquivo da imagem existe e exclui

            // Exibe mensagem de sucesso e redireciona
            echo "
                <script>
                    alert('Usuário excluído com sucesso');
                    window.location.href='../projeto_guardiao/avaliacoesHome.php';
                </script>
            ";
        } else {
            // Se a exclusão do banco falhar
            echo "
                <script>
                    alert('Erro ao excluir registro do banco de dados');
                    window.location.href='../projeto_guardiao/avaliacoesHome.php';
                </script>
            ";
        }
    } else {
        // Se não encontrar o usuário
        echo "
            <script>
                alert('Usuário não encontrado');
                window.location.href='../projeto_guardiao/avaliacoesHome.php';
            </script>
        ";
    }
} else {
    // Se o ID não for válido
    echo "
        <script>
            alert('ID inválido');
            window.location.href='../projeto_guardiao/avaliacoesHome.php';
        </script>
    ";
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
