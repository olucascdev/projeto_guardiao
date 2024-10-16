<?php 
session_start(); // Inicia a sessão para manter as informações do usuário
include_once 'Controller/conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Variáveis para armazenar os dados da avaliação e da pergunta
$codigo_avaliacao = $_GET['codigo_avaliacao'] ?? null;
$codigo_pergunta = $_GET['codigo_pergunta'] ?? null; // Captura o código da pergunta
$nome_avaliacao = filter_input(INPUT_GET, 'nome_avaliacao', FILTER_SANITIZE_SPECIAL_CHARS) ?? ''; 
$estabelecimento_id = filter_input(INPUT_GET, 'estabelecimento_id', FILTER_SANITIZE_NUMBER_INT); 
$data_cadastro = filter_input(INPUT_GET, 'data_cadastro', FILTER_SANITIZE_SPECIAL_CHARS) ?? ''; 
$observacoes = filter_input(INPUT_GET, 'observacoes', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

// Variáveis para preencher o formulário
$nome_pergunta = '';
$tipo_pergunta = '';
$respostas = [];

// Verifica se estamos editando uma pergunta
if (!empty($codigo_pergunta)) {
    $query = "SELECT * FROM avaliacao_estabelecimento_questoes WHERE id = '$codigo_pergunta'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nome_pergunta = $row['questao'];
        $tipo_pergunta = $row['questao_tipo'];
        $respostas = [$row['resposta1'], $row['resposta2'], $row['resposta3'], $row['resposta4'], $row['resposta5']];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pergunta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
</head>
<body class="p-4">

<div class="container">
    <h2>Cadastro de Pergunta</h2>
    <form action="salvar_pergunta.php" method="POST">
        <input type="hidden" name="codigo_avaliacao" value="<?php echo $codigo_avaliacao; ?>"> 
        <input type="hidden" name="estabelecimento_id" value="<?php echo $estabelecimento_id; ?>">
        <input type="hidden" name="codigo_pergunta" value="<?php echo $codigo_pergunta; ?>"> <!-- Campo oculto para o código da pergunta -->

        <div class="mb-3">
            <label for="nome_pergunta" class="form-label">Pergunta</label>
            <input type="text" class="form-control" id="nome_pergunta" name="nome_pergunta" value="<?php echo $nome_pergunta; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo da Pergunta</label>
            <div>
                <input type="radio" id="discursiva" name="tipo_pergunta" value="discursiva" onclick="toggleTipoPergunta()" <?php echo ($tipo_pergunta === 'discursiva') ? 'checked' : ''; ?> required>
                <label for="discursiva">Discursiva</label>
            </div>
            <div>
                <input type="radio" id="objetiva" name="tipo_pergunta" value="objetiva" onclick="toggleTipoPergunta()" <?php echo ($tipo_pergunta === 'objetiva') ? 'checked' : ''; ?> required>
                <label for="objetiva">Objetiva</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<!-- Modal para preenchimento de opções de respostas para Objetiva -->
<div class="modal fade" id="modalObjetiva" tabindex="-1" aria-labelledby="modalObjetivaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalObjetivaLabel">Selecione a quantidade de opções</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="quantidade_opcoes" class="form-label">Quantidade de Opções (2-5)</label>
                    <select id="quantidade_opcoes" class="form-select" onchange="mostrarOpcoes()">
                        <option value="0">Selecione a quantidade</option>
                        <option value="2">2 Opções</option>
                        <option value="3">3 Opções</option>
                        <option value="4">4 Opções</option>
                        <option value="5">5 Opções</option>
                    </select>
                </div>
                <div id="opcoes_container">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <div class="mb-3 opcao" id="opcao<?php echo $i; ?>" style="display: none;">
                            <label for="objetiva_opcao<?php echo $i; ?>" class="form-label">Opção <?php echo $i; ?></label>
                            <input type="text" class="form-control" id="objetiva_opcao<?php echo $i; ?>" name="objetiva_opcao[]" value="<?php echo htmlspecialchars($respostas[$i - 1] ?? ''); ?>">
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="salvarOpcoes()">Salvar opções</button>
            </div>
        </div>
    </div>
</div>

<table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing=0 cellpadding=10>
    <thead>
        <tr>
            <th>Pergunta</th>
            <th>Tipo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $query = "SELECT * FROM avaliacao_estabelecimento_questoes WHERE avaliacao_estabelecimento_id = '$codigo_avaliacao'";
        $rows = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($rows)): ?>
            <tr>
                <td><?php echo $row['questao']; ?></td>
                <td><?php echo $row['questao_tipo']; ?></td>
                <td class="text-center d-flex justify-content-center">
                    <a href="AddQuestionarios.php?codigo_avaliacao=<?php echo $codigo_avaliacao; ?>&codigo_pergunta=<?php echo $row['id']; ?>">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <!-- Adicione aqui o botão para deletar a pergunta, se necessário -->
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Função para alternar entre Discursiva e Objetiva
    function toggleTipoPergunta() {
        var tipoPergunta = document.querySelector('input[name="tipo_pergunta"]:checked').value;

        if (tipoPergunta === "objetiva") {
            // Exibe o modal para perguntas objetivas
            var modalObjetiva = new bootstrap.Modal(document.getElementById("modalObjetiva"));
            modalObjetiva.show();
        }
    }

    // Função para mostrar as opções conforme a quantidade selecionada
    function mostrarOpcoes() {
        var quantidade = document.getElementById('quantidade_opcoes').value;

        // Oculta todas as opções inicialmente
        var opcoes = document.getElementsByClassName('opcao');
        for (var i = 0; i < opcoes.length; i++) {
            opcoes[i].style.display = 'none';
        }

        // Mostra as opções de acordo com a quantidade selecionada
        for (var i = 1; i <= quantidade; i++) {
            document.getElementById('opcao' + i).style.display = 'block';
        }
    }

    // Função para salvar as opções no formulário principal
    function salvarOpcoes() {
        var quantidade = document.getElementById('quantidade_opcoes').value;

        // Atualiza os valores das opções no formulário principal
        for (var i = 1; i <= quantidade; i++) {
            var valorOpcao = document.getElementById('objetiva_opcao' + i).value;
            var inputOpcao = document.createElement("input");
            inputOpcao.type = "hidden";
            inputOpcao.name = "objetiva_opcao[]"; // Cria um array de respostas
            inputOpcao.value = valorOpcao;
            document.querySelector('form').appendChild(inputOpcao);
        }
    }
</script>

</body>
</html>
