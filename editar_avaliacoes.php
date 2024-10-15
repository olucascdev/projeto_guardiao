<?php 
// Inclui a conexão com o banco de dados
include_once 'Controller/conexao.php';
session_start();
// Verifica se o estabelecimento_id foi fornecido na URL, sanitizando a entrada
$estabelecimento_id = filter_input(INPUT_GET, 'estabelecimento_id', FILTER_SANITIZE_NUMBER_INT);

// Recebe os dados do formulário via GEsT (valores passados pela URL), sanitizando as entradas
if (isset($_GET['codigo_avaliacao'])) {
    $codigo_avaliacao = $_GET['codigo_avaliacao']; // Captura o valor de 'codigo'
}
$nome_avaliacao = filter_input(INPUT_GET, 'nome_avaliacao', FILTER_SANITIZE_SPECIAL_CHARS) ?? ''; 
$data_cadastro = filter_input(INPUT_GET, 'data_cadastro', FILTER_SANITIZE_SPECIAL_CHARS) ?? ''; 
$observacoes = filter_input(INPUT_GET, 'observacoes', FILTER_SANITIZE_SPECIAL_CHARS) ?? ''; 

// Converte a data para o formato Y-m-d caso não esteja vazia
if (!empty($data_cadastro)) {
    $data_cadastro = date('Y-m-d', strtotime($data_cadastro)); 
}

// Define o destino do formulário para o script que processará os dados
$action = "salvar_avaliacoes.php"; 

// Consulta para obter a abreviação da unidade utilizando prepared statements
$query_estabelecimento = "SELECT abrev FROM estabelecimentos WHERE id = ?";
$stmt = $conn->prepare($query_estabelecimento); // Prepara a consulta
$stmt->bind_param("i", $estabelecimento_id); // Faz o binding do parâmetro
$stmt->execute(); // Executa a consulta
$result_estabelecimento = $stmt->get_result(); // Obtém o resultado

// Verifica se o estabelecimento foi encontrado e captura a abreviação
if ($result_estabelecimento->num_rows > 0) {
    $unidade = $result_estabelecimento->fetch_assoc(); // Obtém os dados da unidade
    $abrev = $unidade['abrev'] ?? ''; // Atribui a abreviação, se encontrada
} else {
    $abrev = ''; // Valor padrão se não encontrado
}

$stmt->close(); // Fecha a declaração preparada
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configurações de responsividade -->
    <title>Editar Avaliação</title>
    <!-- Inclusão do Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"> <!-- Ícones do Bootstrap -->
    <link rel="stylesheet" href="styles/CadastroUser.css"> <!-- Estilo customizado -->
</head>
<body>
<div class="container mt-5"> <!-- Container Bootstrap -->
    <h1 class="text-left mb-4"><?php echo "Avaliação: " . htmlspecialchars($nome_avaliacao, ENT_QUOTES) . " - " . htmlspecialchars($abrev, ENT_QUOTES); ?></h1> <!-- Título da avaliação -->
    <div>
        <!-- Botões para adicionar questionário e colaborador -->
        <a href="AddQuestionarios.php?codigo_avaliacao=<?php echo $codigo_avaliacao; ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>&nome_avaliacao=<?php echo urlencode($nome_avaliacao); ?>&data_cadastro=<?php echo htmlspecialchars($data_cadastro, ENT_QUOTES); ?>&observacoes=<?php echo htmlspecialchars($observacoes, ENT_QUOTES); ?>">
            <button class="btn btn-success"><i class="bi bi-clipboard2-plus-fill"></i> Adicionar Questionário</button>
        </a>
        <a href="Colaborador.php?codigo_avaliacao=<?php echo $codigo_avaliacao; ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>&nome_avaliacao=<?php echo urlencode($nome_avaliacao); ?>&data_cadastro=<?php echo htmlspecialchars($data_cadastro, ENT_QUOTES); ?>&observacoes=<?php echo htmlspecialchars($observacoes, ENT_QUOTES); ?>">
            <button class="btn btn-success"><i class="bi bi-person-plus-fill"></i>  Adicionar Colaborador</button>
        </a>
    </div>
    <!-- Formulário para editar avaliação -->
    <form action="<?php echo $action; ?>" method="post">
        <input type="hidden" name="codigo_avaliacao" value="<?php echo $codigo_avaliacao; ?>"> <!-- Campo oculto para código -->
        <input type="hidden" name="estabelecimento_id" value="<?php echo $estabelecimento_id; ?>"> <!-- Campo oculto para ID do estabelecimento -->

        <div class="row"> <!-- Início da linha do formulário -->
            <div class="col-md-12 mb-4">
                <label for="nome_avaliacao" class="form-label">Nome da Avaliação</label>
                <input type="text" class="form-control" id="nome_avaliacao" name="nome_avaliacao" placeholder="Informe o nome da Avaliação" value="<?php echo htmlspecialchars($nome_avaliacao, ENT_QUOTES); ?>" required> <!-- Campo de texto para nome da avaliação -->
                <div class="invalid-feedback">Informe o Nome da Avaliação.</div> <!-- Mensagem de feedback para validação -->
            </div>
            <div class="col-md-12 mb-4">
                <label for="data_cadastro" class="form-label">Data Cadastro Avaliação</label>
                <input type="date" class="form-control" id="data_cadastro" name="data_cadastro" value="<?php echo htmlspecialchars($data_cadastro, ENT_QUOTES); ?>" required> <!-- Campo de data para cadastro -->
            </div>
        </div>

        <div class="row"> <!-- Início da linha para observações -->
            <div class="col-md-12 mb-4">
                <label for="observacoes" class="form-label">Observações sobre a Avaliação</label>
                <textarea class="form-control" id="observacoes" name="observacoes" rows="3" placeholder="Informe observações se necessário"><?php echo htmlspecialchars($observacoes, ENT_QUOTES); ?></textarea> <!-- Campo de texto para observações -->
            </div>
        </div>

        <div class="row"> <!-- Início da linha para botões de ação -->
            <div class="col-md-12 d-flex justify-content-start">
                <button class="btn btn-success p-3 m-2" type="submit">Salvar</button> <!-- Botão para salvar -->
                <button class="btn btn-warning p-3 m-2" type="reset">Limpar</button> <!-- Botão para limpar o formulário -->
                <a href="avaliacoesHome.php?estabelecimento_id=<?php echo $estabelecimento_id; ?>" class="btn btn-danger p-3 m-2">Cancelar</a> <!-- Botão para cancelar, redirecionando para a página de avaliações -->
            </div>
        </div>
    </form>
</div>

<!-- Inclusão do Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
