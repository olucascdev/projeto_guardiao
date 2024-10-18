<?php 
session_start(); 
include_once 'Controller/conexao.php'; 

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
    $stmt = $conn->prepare("SELECT * FROM avaliacao_estabelecimento_questoes WHERE id = ?");
    $stmt->bind_param("i", $codigo_pergunta);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nome_pergunta = $row['questao'];
        $tipo_pergunta = $row['questao_tipo'];
        $respostas = [$row['resposta1'], $row['resposta2'], $row['resposta3'], $row['resposta4'], $row['resposta5']];
    }
}
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

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pergunta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style/colaborador.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
</head>
<body class="p-4">

<div class="container">
<div class="botao">
            <!-- Botão "Voltar" com os parâmetros de avaliação e estabelecimento na URL -->
            <a href="editar_avaliacoes.php?codigo_avaliacao=<?= $codigo_avaliacao; ?>&estabelecimento_id=<?= $estabelecimento_id; ?>&nome_avaliacao=<?php echo urlencode($nome_avaliacao); ?>&data_cadastro=<?php echo htmlspecialchars($data_cadastro, ENT_QUOTES); ?>&observacoes=<?php echo htmlspecialchars($observacoes, ENT_QUOTES); ?>" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
    <h2>Cadastro de Pergunta <?php echo "- " . htmlspecialchars($nome_avaliacao, ENT_QUOTES) . " - " . htmlspecialchars($abrev, ENT_QUOTES); ?></h2>
    <form action="salvar_pergunta.php" method="POST">
        <input type="hidden" name="codigo_avaliacao" value="<?php echo $codigo_avaliacao; ?>"> 
        <input type="hidden" name="estabelecimento_id" value="<?php echo $estabelecimento_id; ?>">
        <input type="hidden" name="codigo_pergunta" value="<?php echo $codigo_pergunta; ?>"> 
        <input type="hidden" name="nome_avaliacao" value="<?php echo urlencode($nome_avaliacao); ?>">
        <input type="hidden" name="data_cadastro" value="<?php echo htmlspecialchars($data_cadastro, ENT_QUOTES); ?>">
        <input type="hidden" name="observacoes" value="<?php echo htmlspecialchars($observacoes, ENT_QUOTES); ?>">

        <div class="mb-3">
            <label for="nome_pergunta" class="form-label">Pergunta</label>
            <input type="text" class="form-control" id="nome_pergunta" name="nome_pergunta" value="<?php echo htmlspecialchars($nome_pergunta); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo da Pergunta</label>
            <div>
                <input type="radio" id="Discursiva" name="tipo_pergunta" value="1" onclick="toggleTipoPergunta()" <?php echo ($tipo_pergunta == '1') ? 'checked' : ''; ?> required>
                <label for="Discursiva">Discursiva</label>
            </div>
            <div>
                <input type="radio" id="Objetiva" name="tipo_pergunta" value="2" onclick="toggleTipoPergunta()" <?php echo ($tipo_pergunta == '2') ? 'checked' : ''; ?> required>
                <label for="Objetiva">Objetiva</label>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
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

<table class="table table-sm table-light table-bordered table-striped table-hover mt-3" border="1" cellspacing=0 cellpadding=5 id="perguntasTable">
    <thead>
        <tr>
            <th>Pergunta</th>
            <th style="width: 15%;">Tipo</th>
            <th style="width: 15%;">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $stmt = $conn->prepare("SELECT * FROM avaliacao_estabelecimento_questoes WHERE avaliacao_estabelecimento_id = ?");
        $stmt->bind_param("i", $codigo_avaliacao);
        $stmt->execute();
        $rows = $stmt->get_result();

        while ($row = $rows->fetch_assoc()): ?>
            <tr data-id="<?php echo $row['id']; ?>">
                <td><?php echo htmlspecialchars($row['questao']); ?></td>
                <td><?php echo $row['questao_tipo'] == '1' ? 'Discursiva' : 'Objetiva'; ?></td>
                <td class="text-center d-flex justify-content-center">
                    <a href="AddQuestionarios.php?codigo_avaliacao=<?php echo $codigo_avaliacao; ?>&codigo_pergunta=<?php echo $row['id']; ?>&nome_avaliacao=<?php echo urlencode($nome_avaliacao); ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>&data_cadastro=<?php echo urlencode($data_cadastro); ?>&observacoes=<?php echo urlencode($observacoes); ?>" class="btn btn-warning btn-sm me-2">
                        <i class="bi bi-pencil w-25"></i>
                    </a>
                    <a href="excluir_pergunta.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash-fill w-25"></i>
                    </a>
                </td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleTipoPergunta() {
        var tipoPergunta = document.querySelector('input[name="tipo_pergunta"]:checked').value;

        if (tipoPergunta === "2") {
            var modalObjetiva = new bootstrap.Modal(document.getElementById("modalObjetiva"));
            modalObjetiva.show();
        }
    }

    function mostrarOpcoes() {
        var quantidade = document.getElementById('quantidade_opcoes').value;
        var opcoes = document.getElementsByClassName('opcao');
        for (var i = 0; i < opcoes.length; i++) {
            opcoes[i].style.display = 'none';
        }
        for (var i = 1; i <= quantidade; i++) {
            document.getElementById('opcao' + i).style.display = 'block';
        }
    }

    function salvarOpcoes() {
        var quantidade = document.getElementById('quantidade_opcoes').value;
        for (var i = 1; i <= quantidade; i++) {
            var valorOpcao = document.getElementById('objetiva_opcao' + i).value;
            var inputOpcao = document.createElement("input");
            inputOpcao.type = "hidden";
            inputOpcao.name = "objetiva_opcao[]";
            inputOpcao.value = valorOpcao;
            document.querySelector('form').appendChild(inputOpcao);
        }
    }

    var sortable = Sortable.create(document.getElementById('perguntasTable').querySelector('tbody'), {
        animation: 150,
        onEnd: function (evt) {
            var ids = Array.from(evt.from.children).map(row => row.getAttribute('data-id'));
            fetch('salvar_ordem.php', {
                method: 'POST',
                body: JSON.stringify({ ordem: ids, codigo_avaliacao: '<?php echo $codigo_avaliacao; ?>' }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Ordem salva com sucesso!');
                } else {
                    alert('Erro ao salvar a ordem.');
                }
            })
            .catch(error => console.error('Erro:', error));
        }  
    });
</script>

</body>
</html>