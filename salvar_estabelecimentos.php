<?php
include_once 'Controller/conexao.php';

// Recebe os dados do formulário
$codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_NUMBER_INT);
$nome_estabelecimento = filter_input(INPUT_POST, 'estabelecimento', FILTER_SANITIZE_SPECIAL_CHARS);
$abrev = filter_input(INPUT_POST, 'abrev', FILTER_SANITIZE_SPECIAL_CHARS);
$ativo = filter_input(INPUT_POST, 'ativo', FILTER_SANITIZE_NUMBER_INT); // Assumindo que o ativo será 0 ou 1

// Verifica se é uma edição ou um novo registro
if (empty($codigo)) {
    // Inserir novo estabelecimento
    $insert_estabelecimento = "INSERT INTO estabelecimentos (estabelecimento, abrev, ativo) VALUES ('$nome_estabelecimento', '$abrev', '$ativo')";
    if (mysqli_query($conn, $insert_estabelecimento)) {
        echo "Estabelecimento registrado com sucesso!";
        header("Location: avaliacao_estabelecimento.php"); // Redireciona para a página de lista após o sucesso
    } else {
        echo "Erro ao registrar estabelecimento: " . mysqli_error($conn);
    }
} else {
    // Lógica para edição de estabelecimento
    $update_estabelecimento = "UPDATE estabelecimentos SET estabelecimento='$nome_estabelecimento', abrev='$abrev', ativo='$ativo' WHERE id='$codigo'";
    if (mysqli_query($conn, $update_estabelecimento)) {
        echo "Estabelecimento atualizado com sucesso!";
        header("Location: avaliacao_estabelecimento.php"); // Redireciona após a edição
    } else {
        echo "Erro ao atualizar estabelecimento: " . mysqli_error($conn);
    }
}

// Redireciona após a execução
// header("Location: avaliacoesHome.php");
// exit;
?>
