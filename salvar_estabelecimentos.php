<?php
include_once 'Controller/conexao.php';

// Recebe os dados do formulário
$codigo = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$nome_estabelecimento = filter_input(INPUT_GET, 'estabelecimento', FILTER_SANITIZE_SPECIAL_CHARS);
$sigla = filter_input(INPUT_GET, 'abrev', FILTER_SANITIZE_SPECIAL_CHARS);
$status = filter_input(INPUT_GET, 'ativo', FILTER_SANITIZE_SPECIAL_CHARS);


// Verifica se é uma edição ou um novo registro
if (empty($codigo)) {
    // Inserir nova avaliação
    $insert_estabelecimento = "INSERT INTO estabelecimentos (abrev, estabelecimento, ativo) VALUES ('$sigla', '$nome_avaliacao', '$nome_estabelecimento', '$status')";
    if (mysqli_query($conn, $insert_estabelecimento)) {
        // Captura o ID da nova avaliação
        $estabelecimento_id = mysqli_insert_id($conn);

        // Aqui, você deve inserir os colaboradores vinculados
        // Certifique-se de que a sessão de colaboradores vinculados exista e tenha dados
        if (isset($_SESSION['vinculados']) && !empty($_SESSION['vinculados'])) {
            foreach ($_SESSION['vinculados'] as $vinculado) {
                $colaborador_id = $vinculado['id'];
                $tel_movel = $vinculado['tel_movel'];
                $email = $vinculado['email'];

                // Inserir na tabela avaliacao_estabelecimento_colaborador
                $insert_colaborador = "INSERT INTO avaliacao_estabelecimento_colaborador (avaliacao_estabelecimento_id, colaborador_id, colaborador_telmovel, colaborador_email) VALUES ('$avaliacao_id', '$colaborador_id', '$tel_movel', '$email')";
                if (!mysqli_query($conn, $insert_colaborador)) {
                    echo "Erro ao vincular colaborador: " . mysqli_error($conn);
                }
            }
        } else {
            echo "Nenhum colaborador vinculado para inserir.";
        }

        echo "Avaliação registrada com sucesso!";
    } else {
        echo "Erro ao registrar avaliação: " . mysqli_error($conn);
    }
} else {
    // Lógica para edição de avaliações
    $update_avaliacao = "UPDATE avaliacao SET data_cadastro='$data_cadastro', nome_avaliacao='$nome_avaliacao', descricao_avaliacao='$observacoes' WHERE id='$codigo'";
    if (mysqli_query($conn, $update_avaliacao)) {
        echo "Avaliação atualizada com sucesso!";
        header("Location: avaliacoesHome.php");
    } else {
        echo "Erro ao atualizar avaliação: " . mysqli_error($conn);
    }
}

// Redirecionar ou fazer outra ação após o registro
// header("Location: avaliacoesHome.php");
// exit;
?>
