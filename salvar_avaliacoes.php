<?php
session_start(); // Inicie a sessão se ainda não estiver iniciada
include_once 'Controller/conexao.php';

// Recebe os dados do formulário
$codigo_avaliacao = filter_input(INPUT_POST, 'codigo_avaliacao', FILTER_SANITIZE_NUMBER_INT);
$nome_avaliacao = filter_input(INPUT_POST, 'nome_avaliacao', FILTER_SANITIZE_SPECIAL_CHARS);
$data_cadastro = filter_input(INPUT_POST, 'data_cadastro', FILTER_SANITIZE_SPECIAL_CHARS);
$observacoes = filter_input(INPUT_POST, 'observacoes', FILTER_SANITIZE_SPECIAL_CHARS);
$estabelecimento_id = filter_input(INPUT_POST, 'estabelecimento_id', FILTER_SANITIZE_NUMBER_INT);

// Verifica se a data_cadastro foi fornecida e está no formato correto
if (!empty($data_cadastro)) {
    $data_cadastro = date('Y-m-d', strtotime($data_cadastro)); // Converte a data caso necessário
}

// Verifica se é uma edição ou um novo registro
if (empty($codigo_avaliacao)) {
    // Inserir nova avaliação
    $insert_avaliacao = "INSERT INTO avaliacao (data_cadastro, nome_avaliacao, descricao_avaliacao, estabelecimento_id) VALUES ('$data_cadastro', '$nome_avaliacao', '$observacoes', '$estabelecimento_id')";
    if (mysqli_query($conn, $insert_avaliacao)) {
        // Captura o ID da nova avaliação
        $avaliacao_id = mysqli_insert_id($conn);

        // Aqui, você deve inserir os colaboradores vinculados
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
        header("Location: avaliacoesHome.php?estabelecimento_id=$estabelecimento_id");
        exit();
    } else {
        echo "Erro ao registrar avaliação: " . mysqli_error($conn);
    }
} else {
    // Lógica para edição de avaliações
    $update_avaliacao = "UPDATE avaliacao SET data_cadastro='$data_cadastro', nome_avaliacao='$nome_avaliacao', descricao_avaliacao='$observacoes', estabelecimento_id='$estabelecimento_id' WHERE id='$codigo_avaliacao'";
    
    if (mysqli_query($conn, $update_avaliacao)) {
        // Limpa a tabela de colaboradores vinculados antes de atualizar
        $delete_colaboradores = "DELETE FROM avaliacao_estabelecimento_colaborador WHERE avaliacao_estabelecimento_id='$codigo_avaliacao'";
        mysqli_query($conn, $delete_colaboradores);

        // Insere os colaboradores vinculados novamente
        if (isset($_SESSION['vinculados']) && !empty($_SESSION['vinculados'])) {
            foreach ($_SESSION['vinculados'] as $vinculado) {
                $colaborador_id = $vinculado['id'];
                $tel_movel = $vinculado['tel_movel'];
                $email = $vinculado['email'];

                // Inserir na tabela avaliacao_estabelecimento_colaborador
                $insert_colaborador = "INSERT INTO avaliacao_estabelecimento_colaborador (avaliacao_estabelecimento_id, colaborador_id, colaborador_telmovel, colaborador_email) VALUES ('$codigo_avaliacao', '$colaborador_id', '$tel_movel', '$email')";
                if (!mysqli_query($conn, $insert_colaborador)) {
                    echo "Erro ao vincular colaborador: " . mysqli_error($conn);
                }
            }
        } else {
            echo "Nenhum colaborador vinculado para inserir.";
        }

        echo "Avaliação atualizada com sucesso!";
        header("Location: avaliacoesHome.php?estabelecimento_id=$estabelecimento_id");
        exit();
    } else {
        echo "Erro ao atualizar avaliação: " . mysqli_error($conn);
    }
}
?>
