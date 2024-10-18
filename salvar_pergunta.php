<?php 
session_start();
include_once 'Controller/conexao.php'; // Inclui a conexão com o banco

// Recebendo os dados do formulário
$codigo_avaliacao = filter_input(INPUT_POST, 'codigo_avaliacao', FILTER_SANITIZE_NUMBER_INT);
$estabelecimento_id = filter_input(INPUT_POST, 'estabelecimento_id', FILTER_SANITIZE_NUMBER_INT);
$codigo_pergunta = filter_input(INPUT_POST, 'codigo_pergunta', FILTER_SANITIZE_NUMBER_INT);
$nome_pergunta = filter_input(INPUT_POST, 'nome_pergunta', FILTER_SANITIZE_SPECIAL_CHARS);
$tipo_pergunta = filter_input(INPUT_POST, 'tipo_pergunta', FILTER_SANITIZE_SPECIAL_CHARS);
$nome_avaliacao = filter_input(INPUT_POST, 'nome_avaliacao', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
$data_cadastro = filter_input(INPUT_POST, 'data_cadastro', FILTER_SANITIZE_SPECIAL_CHARS) ?? ''; 
$observacoes = filter_input(INPUT_POST, 'observacoes', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

// Inicializa as variáveis de resposta
$respostas = filter_input(INPUT_POST, 'objetiva_opcao', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) ?? [];

// Garante que o array de respostas tenha pelo menos 5 elementos
$respostas = array_pad($respostas, 5, ''); // Preenche com strings vazias até ter 5 elementos

// Verifica se todos os campos essenciais estão recebendo valores
if (!$codigo_avaliacao) {
    die('Erro: Código da avaliação não foi fornecido.');
}

if (!$nome_pergunta) {
    die('Erro: O nome da pergunta não foi fornecido.');
}

// Verifica se está editando ou criando uma nova pergunta
if (!empty($codigo_pergunta)) {
    // Atualizar pergunta existente
    $query = "UPDATE avaliacao_estabelecimento_questoes SET 
                questao = '$nome_pergunta', 
                questao_tipo = '$tipo_pergunta', 
                resposta1 = '{$respostas[0]}', 
                resposta2 = '{$respostas[1]}', 
                resposta3 = '{$respostas[2]}', 
                resposta4 = '{$respostas[3]}', 
                resposta5 = '{$respostas[4]}'
              WHERE id = '$codigo_pergunta'";
} else {
    // Inserir nova pergunta
    $query = "INSERT INTO avaliacao_estabelecimento_questoes (avaliacao_estabelecimento_id, questao_tipo, questao, resposta1, resposta2, resposta3, resposta4, resposta5) 
              VALUES ('$codigo_avaliacao', '$tipo_pergunta', '$nome_pergunta', '{$respostas[0]}', '{$respostas[1]}', '{$respostas[2]}', '{$respostas[3]}', '{$respostas[4]}')";
}

// Executar a consulta
if (mysqli_query($conn, $query)) {
    // Redireciona após sucesso
    header("Location: AddQuestionarios.php?codigo_avaliacao=$codigo_avaliacao&nome_avaliacao=" . urlencode($nome_avaliacao) . "&estabelecimento_id=$estabelecimento_id&data_cadastro=" . urlencode($data_cadastro) . "&observacoes=" . urlencode($observacoes));
    exit();
} else {
    echo "Erro ao executar a consulta: " . mysqli_error($conn);
}

// Fecha a conexão
mysqli_close($conn);
?>
