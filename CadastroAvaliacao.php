<?php 
include_once 'Controller/conexao.php';

// Verifica se é edição ou novo cadastro
$codigo = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$nome_avaliacao = filter_input(INPUT_GET, 'nome_avaliacao', FILTER_SANITIZE_SPECIAL_CHARS);
$data_cadastro = filter_input(INPUT_GET, 'data_cadastro', FILTER_SANITIZE_SPECIAL_CHARS);
$observacoes = filter_input(INPUT_GET, 'observacoes', FILTER_SANITIZE_SPECIAL_CHARS);
$estabelecimento_id = filter_input(INPUT_GET, 'estabelecimento_id', FILTER_SANITIZE_NUMBER_INT);


// Define o destino do formulário (salvar)
$action = "salvar_avaliacoes.php"; // Ajuste para o arquivo de salvar
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($codigo) ? 'Editar Avaliação' : 'Cadastro de Nova Avaliação'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/CadastroUser.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-left mb-4"><?php echo !empty($codigo) ? 'Editar Avaliação' : 'Cadastro de Nova Avaliação'; ?></h1>

        <!-- Formulário -->
        <form action="<?php echo $action; ?>" method="post">
            <!-- Campo oculto para armazenar o ID da avaliação (caso seja edição) -->
            <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
            <input type="hidden" name="estabelecimento_id" value="<?php echo $estabelecimento_id; ?>">

            <div class="row">
                <div class="col-md-12 mb-4">
                    <label for="nome_avaliacao" class="form-label">Nome da Avaliação</label>
                    <input type="text" class="form-control" id="nome_avaliacao" name="nome_avaliacao" placeholder="Informe o nome da Avaliação" value="<?php echo $nome_avaliacao; ?>" required>
                    <div class="invalid-feedback">Informe o Nome da Avaliação.</div>
                </div>
                <div class="col-md-12 mb-4">
                    <label for="data_cadastro" class="form-label">Data Cadastro Avaliação</label>
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
                    <a href="avaliacoesHome.php?estabelecimento_id=<?php echo $estabelecimento_id; ?>" class="btn btn-danger p-3 m-2">Cancelar</a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
