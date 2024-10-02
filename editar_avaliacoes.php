<?php 
include_once 'Controller/conexao.php';

// Recebe os dados do formulário via GET (valores passados pela URL)
$codigo = filter_input(INPUT_GET, 'codigo', FILTER_SANITIZE_NUMBER_INT);
$nome_avaliacao = filter_input(INPUT_GET, 'nome_avaliacao', FILTER_SANITIZE_SPECIAL_CHARS);
$data_cadastro = filter_input(INPUT_GET, 'data_cadastro', FILTER_SANITIZE_SPECIAL_CHARS);
$observacoes = filter_input(INPUT_GET, 'observacoes', FILTER_SANITIZE_SPECIAL_CHARS);

// Aqui NÃO convertemos a data para o formato dd/mm/yyyy, pois o input date requer o formato YYYY-MM-DD
// Verifica se a data_cadastro foi fornecida e está no formato correto
if (!empty($data_cadastro)) {
    $data_cadastro = date('Y-m-d', strtotime($data_cadastro)); // Converte a data caso necessário
}

// Define o destino do formulário
$action = "salvar_avaliacoes.php"; // Salvar na mesma página que você já tem
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Avaliação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/CadastroUser.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-left mb-4"><?php echo !empty($codigo) ? 'Editar Avaliação' : 'Cadastro de Nova Avaliação'; ?></h1>
    <div>
        <button class="btn btn-success"><i class="bi bi-clipboard2-plus-fill"></i> Adicionar Questionário</button>
        <a href=""><button class="btn btn-success"><i class="bi bi-person-plus-fill"></i>  Adicionar Colaborador</button></a>
    </div>
    <!-- Formulário -->
    <form action="<?php echo $action; ?>" method="post">
        <!-- Campo oculto para armazenar o ID da avaliação (caso seja edição) -->
        <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">

        <div class="row">
            <div class="col-md-12 mb-4">
                <label for="nome_avaliacao" class="form-label">Nome da Avaliação</label>
                <input type="text" class="form-control" id="nome_avaliacao" name="nome_avaliacao" placeholder="Informe o nome da Avaliação" value="<?php echo $nome_avaliacao; ?>" required>
                <div class="invalid-feedback">Informe o Nome da Avaliação.</div>
            </div>
            <div class="col-md-12 mb-4">
                <label for="data_cadastro" class="form-label">Data Cadastro Avaliação</label>
                <!-- Aqui a data precisa estar no formato YYYY-MM-DD -->
                <input type="date" class="form-control" id="data_cadastro" name="data_cadastro" value="<?php echo $data_cadastro; ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <label for="observacoes" class="form-label">Observações sobre a Avaliação</label>
                <textarea class="form-control" id="observacoes" name="observacoes" rows="3" placeholder="Informe observações se necessário"><?php echo $observacoes; ?></textarea>
            </div>
        </div>

        <!-- Botões alinhados com o formulário -->
        <div class="row">
            <div class="col-md-12 d-flex justify-content-start">
                <button class="btn btn-success p-3 m-2" type="submit">Salvar</button>
                <button class="btn btn-warning p-3 m-2" type="reset">Limpar</button>
                <a href="avaliacoesHome.php" class="btn btn-danger p-3 m-2">Cancelar</a>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
