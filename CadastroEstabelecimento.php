<?php 
include_once 'Controller/conexao.php';

// Verifica se é edição ou novo cadastro
$codigo = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$nome_estabelecimento = filter_input(INPUT_GET, 'estabelecimento', FILTER_SANITIZE_SPECIAL_CHARS);
$sigla = filter_input(INPUT_GET, 'abrev', FILTER_SANITIZE_SPECIAL_CHARS);
$status = filter_input(INPUT_GET, 'ativo', FILTER_SANITIZE_SPECIAL_CHARS);

// Define o destino do formulário (salvar)
$action = "salvar_estabelecimentos.php"; // Ajuste para o arquivo de salvar
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Novo Estabelecimento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/CadastroUser.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-left mb-4">Cadastro de Novo Estabelecimento</h1>

        <!-- Formulário -->
        <form action="<?php echo $action; ?>" method="post">
            <!-- Campo oculto para armazenar o ID da avaliação (caso seja edição) -->
            <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">

            <div class="row">
                <div class="col-md-12 mb-4">
                    <label for="estabelecimento" class="form-label">Nome do Estabelecimento</label>
                    <input type="text" class="form-control" id="estabelecimento" name="estabelecimento" placeholder="Informe o nome do Estabelecimento" value="<?php echo $nome_estabelecimento; ?>" required>
                    <div class="invalid-feedback">Informe o Nome do Estabelecimento.</div>
                </div>
                <div class="col-md-12 mb-4">
                    <label for="abrev" class="form-label">Sigla</label>
                    <input type="text"  placeholder="Informe a sigla do Estabelecimento" class="form-control" id="abrev" name="abrev" value="<?php echo $sigla; ?>" required>
                </div>
                <div class="col-md-12 mb-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="ativo" required>
                        <option value="1" <?php echo ($status == '1') ? 'selected' : ''; ?>>Ativo</option>
                        <option value="0" <?php echo ($status == '0') ? 'selected' : ''; ?>>Desativado</option>
                    </select>
                    <div class="invalid-feedback">Selecione o status do Estabelecimento.</div>
                </div>
            </div>
            
            <!-- Botões alinhados com o formulário -->
            <div class="row">
                <div class="col-md-12 d-flex justify-content-start">
                    <button class="btn btn-success p-3 m-2" type="submit">Salvar</button>
                    <button class="btn btn-warning p-3 m-2" type="submit">Editar</button>
                    <a href="avaliacoesHome.php" class="btn btn-danger p-3 m-2">Cancelar</a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
