<?php 
include_once 'Controller/conexao.php';

// Recebe os dados do formulário
$codigo = filter_input(INPUT_POST, "codigo", FILTER_SANITIZE_NUMBER_INT);
$nome = filter_input(INPUT_POST, "nome_avaliacao", FILTER_SANITIZE_SPECIAL_CHARS);
$data = filter_input(INPUT_POST, "data_cadastro", FILTER_SANITIZE_SPECIAL_CHARS);
$observacao = filter_input(INPUT_POST, "observacoes", FILTER_SANITIZE_SPECIAL_CHARS);

// Verifica se os campos obrigatórios estão preenchidos
if (!empty($nome) && !empty($data)) {
    // Verifica se é uma atualização
    if (!empty($codigo)) {
        // Atualiza a avaliação existente
        $query = "UPDATE avaliacao SET nome_avaliacao='$nome', data_cadastro='$data', descricao_avaliacao='$observacao' WHERE id=$codigo";
    } else {
        // Insere uma nova avaliação
        $query = "INSERT INTO avaliacao (id, nome_avaliacao, data_cadastro, descricao_avaliacao) VALUES (NULL,'$nome', '$data', '$observacao')";
    }
    
    $resultado = mysqli_query($conn, $query);

    // Verifica se a operação foi bem-sucedida
    if ($resultado) {
        echo "<script>alert('Avaliação salva com sucesso'); window.location.href='avaliacoesHome.php';</script>";
    } else {
        echo "<script>alert('Erro ao salvar a avaliação'); window.location.href='CadastroAvaliacao.php';</script>";
    }
} else {
    echo "<script>alert('Preencha todos os campos obrigatórios'); window.location.href='CadastroAvaliacao.php';</script>";
}

mysqli_close($conn);
?>
