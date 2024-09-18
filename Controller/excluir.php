<?php 
session_start();
include_once 'conexao.php';

// Recebe o valor do parâmetro 'codigo' da URL e o sanitiza para evitar XSS (Cross-Site Scripting)

$userId = $_SESSION['id'];
if ($userId) {
    // Cria a consulta SQL para recuperar o nome da imagem associada ao produto
    $sql = "SELECT foto FROM usuarios WHERE id = $userId";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado && $linha = mysqli_fetch_assoc($resultado)) {
        // Obtém o nome da imagem
        $imagem = $linha['foto'];

        // Define o caminho completo para a imagem
        $caminhoImagem = '../img/' . $imagem;

        // Exclui o registro do banco de dados
        $sqlDelete = "DELETE FROM usuarios WHERE id=$userId";
        if (mysqli_query($conn, $sqlDelete)) {
            // Verifica se o arquivo da imagem existe e exclui
            if (file_exists($caminhoImagem)) {
                unlink($caminhoImagem);
            }

            // Exibe mensagem de sucesso e redireciona
            echo "
                <script>
                    alert('Registro excluído com sucesso');
                    window.location.href='../Users.php';
                </script>
            ";
        } else {
            // Se a exclusão do banco falhar
            echo "
                <script>
                    alert('Erro ao excluir registro do banco de dados');
                    window.location.href='../index.php';
                </script>
            ";
        }
    } else {
        // Se não encontrar o produto
        echo "
            <script>
                alert('Produto não encontrado');
                window.location.href='../index.php';
            </script>
        ";
    }
} else {
    // Se o ID não for válido
    echo "
        <script>
            alert('ID inválido');
            window.location.href='../index.php';
        </script>
    ";
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>