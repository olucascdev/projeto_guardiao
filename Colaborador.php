<?php 
session_start();
include_once 'Controller/conexao.php'; 

if (isset($_GET['codigo_avaliacao'])) {
    $codigo_avaliacao = $_GET['codigo_avaliacao']; // Captura o valor de 'codigo_avaliacao'
}
$nome_avaliacao = filter_input(INPUT_GET, 'nome_avaliacao', FILTER_SANITIZE_SPECIAL_CHARS) ?? ''; 
$estabelecimento_id = filter_input(INPUT_GET, 'estabelecimento_id', FILTER_SANITIZE_NUMBER_INT); 
$data_cadastro = filter_input(INPUT_GET, 'data_cadastro', FILTER_SANITIZE_SPECIAL_CHARS) ?? ''; 
$observacoes = filter_input(INPUT_GET, 'observacoes', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';

// Inicializa a sessão de vinculados para a combinação atual de avaliação e estabelecimento
if (!isset($_SESSION['vinculados'][$codigo_avaliacao][$estabelecimento_id])) {
    $_SESSION['vinculados'][$codigo_avaliacao][$estabelecimento_id] = []; // Inicializa se ainda não existir
}

// Armazena o código e o ID do estabelecimento atuais na sessão
$_SESSION['codigo_atual'] = $codigo_avaliacao;
$_SESSION['estabelecimento_atual'] = $estabelecimento_id;

// Insere um novo colaborador
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['colaborador_id'])) {
        $colaborador_id = filter_input(INPUT_POST, 'colaborador_id', FILTER_SANITIZE_NUMBER_INT);
        
        if ($colaborador_id) {
            // Obtém os detalhes do colaborador
            $col_query = "SELECT nome, tel_movel, email FROM colaboradores WHERE id = $colaborador_id";
            $col_result = mysqli_query($conn1, $col_query);
            
            if ($colaborador = mysqli_fetch_assoc($col_result)) {
                // Verifica se o colaborador já está vinculado a essa avaliação e estabelecimento
                $is_vinculado = false;
                foreach ($_SESSION['vinculados'][$codigo_avaliacao][$estabelecimento_id] as $vinculado) {
                    if ($vinculado['id'] == $colaborador_id) {
                        $is_vinculado = true;
                        break;
                    }
                }

                if (!$is_vinculado) {
                    // Insere os dados na tabela de vinculação e na sessão
                    $insert_query = "INSERT INTO avaliacao_estabelecimento_colaborador (avaliacao_estabelecimento_id, colaborador_id, colaborador_telmovel, colaborador_email) 
                                     VALUES ('$codigo_avaliacao', '$colaborador_id', '{$colaborador['tel_movel']}', '{$colaborador['email']}')";
                    mysqli_query($conn, $insert_query);

                    // Adiciona o colaborador à lista de vinculados na sessão
                    $_SESSION['vinculados'][$codigo_avaliacao][$estabelecimento_id][] = [
                        'id' => $colaborador_id,
                        'nome' => $colaborador['nome'],
                        'tel_movel' => $colaborador['tel_movel'],
                        'email' => $colaborador['email']
                    ];
                } else {
                    echo "<script>alert('Colaborador já está vinculado.');</script>";
                }
            } else {
                echo "<script>alert('Colaborador não encontrado.');</script>";
            }
        } else {
            echo "<script>alert('Selecione um colaborador válido.');</script>";
        }
    }

    // Remover colaborador
    if (isset($_POST['excluir_id'])) {
        $excluir_id = filter_input(INPUT_POST, 'excluir_id', FILTER_SANITIZE_NUMBER_INT);

        // Remove da tabela e da sessão
        $delete_query = "DELETE FROM avaliacao_estabelecimento_colaborador WHERE colaborador_id = $excluir_id AND avaliacao_estabelecimento_id = $codigo_avaliacao";
        mysqli_query($conn, $delete_query);

        // Remove da sessão também
        foreach ($_SESSION['vinculados'][$codigo_avaliacao][$estabelecimento_id] as $key => $vinculado) {
            if ($vinculado['id'] == $excluir_id) {
                unset($_SESSION['vinculados'][$codigo_avaliacao][$estabelecimento_id][$key]);
                break;
            }
        }
    }
}

// Armazena os colaboradores vinculados
$vinculados = $_SESSION['vinculados'][$codigo_avaliacao][$estabelecimento_id] ?? [];

// Paginação
$total_vinculados = count($vinculados);
$por_pagina = 3;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $por_pagina;
$total_paginas = ceil($total_vinculados / $por_pagina);
$vinculados_pagina = array_slice($vinculados, $offset, $por_pagina);

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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Inclui o CSS do Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"> <!-- Inclui ícones do Bootstrap -->
    <link rel="stylesheet" href="styles/Colaborador.css"> <!-- Inclui CSS personalizado -->
    <title>Colaboradores</title> <!-- Título da página -->
</head>
<body>
    <div class="container"> <!-- Contêiner principal -->
        <div class="botao">
            <!-- Botão "Voltar" com os parâmetros de avaliação e estabelecimento na URL -->
            <a href="editar_avaliacoes.php?codigo_avaliacao=<?= $codigo_avaliacao; ?>&estabelecimento_id=<?= $estabelecimento_id; ?>&nome_avaliacao=<?php echo urlencode($nome_avaliacao); ?>&data_cadastro=<?php echo htmlspecialchars($data_cadastro, ENT_QUOTES); ?>&observacoes=<?php echo htmlspecialchars($observacoes, ENT_QUOTES); ?>" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
        <h3>Vincular Colaborador no Questionário <?php echo "- " . htmlspecialchars($nome_avaliacao, ENT_QUOTES) . " - " . htmlspecialchars($abrev, ENT_QUOTES); ?></h3>
        <form method="POST" action=""> <!-- Formulário para adicionar colaboradores -->
            <table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing="0" cellpadding="10">
                <thead>
                    <tr>
                        <th>Colaborador</th>
                        <th width="15%">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                        <select class="form-select" name="colaborador_id" id="nome_colaborador">
                            <option value="">Selecione o Colaborador</option>
                            <?php 
                            // Certifique-se de que $estabelecimento_id foi definido antes
                            if (isset($estabelecimento_id)) {
                                // Consulta para obter colaboradores do estabelecimento específico
                                $query = "SELECT id, nome FROM colaboradores WHERE unidade_id = $estabelecimento_id ORDER BY nome ASC"; 
                                $result = mysqli_query($conn1, $query);
                                
                                // Verifica se houve resultados na consulta
                                if(mysqli_num_rows($result) > 0) {
                                    // Itera sobre os colaboradores retornados e preenche o select
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Nenhum colaborador encontrado</option>"; // Mensagem se não houver colaboradores
                                }
                            } else {
                                echo "<option value=''>Estabelecimento não definido</option>";
                            }
                            ?>
                        </select>
                        </td>
                        <td>
                             <!-- Botão para adicionar colaborador -->
                            <button class="btn btn-success w-100" type="submit" onclick="adicionarParametrosNaUrl()">
                        <i class="bi bi-plus-circle"></i> Adicionar
                        </button>

                        </td>
                    </tr>
                </tbody>
            </table>
        </form>

        <h3>Colaboradores Vinculados ao Questionário</h3>
        <table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing="0" cellpadding="10"> <!-- Tabela para exibir colaboradores vinculados -->
            <thead>
                <tr>
                    <th>Nome</th>
                    <th width="15%">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vinculados_pagina as $vinculado): ?> <!-- Itera sobre os colaboradores vinculados para exibição -->
                    <tr>
                        <td><?= htmlspecialchars($vinculado['nome'], ENT_QUOTES); ?></td> <!-- Nome do colaborador -->
                        <td>
                            <form method="POST" action="" class="excluir-form"> <!-- Formulário para excluir colaborador -->
                                <input type="hidden" name="excluir_id" value="<?= $vinculado['id']; ?>"> <!-- ID do colaborador a ser excluído -->
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tem certeza que deseja excluir?');"><i class="bi bi-trash"></i> Excluir</button> <!-- Botão para excluir colaborador -->
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Navegação de paginação -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php if ($pagina > 1): ?> <!-- Se não estiver na primeira página -->
                    <li class="page-item">
                        <a class="page-link" href="?codigo_avaliacao=<?= $codigo_avaliacao; ?>&estabelecimento_id=<?= $estabelecimento_id; ?>&nome_avaliacao=<?php echo urlencode($nome_avaliacao); ?>&data_cadastro=<?php echo urlencode($data_cadastro); ?>&observacoes=<?php echo urlencode($observacoes); ?>&pagina=<?= $pagina - 1; ?>">Anterior</a> <!-- Botão "Anterior" -->
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?> <!-- Itera sobre o total de páginas -->
                    <li class="page-item <?= $i === $pagina ? 'active' : ''; ?>">
                        <a class="page-link" href="?codigo_avaliacao=<?= $codigo_avaliacao; ?>&estabelecimento_id=<?= $estabelecimento_id; ?>&nome_avaliacao=<?php echo urlencode($nome_avaliacao); ?>&data_cadastro=<?php echo urlencode($data_cadastro); ?>&observacoes=<?php echo urlencode($observacoes); ?>&pagina=<?= $i; ?>"><?= $i; ?></a> <!-- Botões de página -->
                    </li>
                <?php endfor; ?>

                <?php if ($pagina < $total_paginas): ?> <!-- Se não estiver na última página -->
                    <li class="page-item">
                        <a class="page-link" href="?codigo_avaliacao=<?= $codigo_avaliacao; ?>&estabelecimento_id=<?= $estabelecimento_id; ?>&nome_avaliacao=<?php echo urlencode($nome_avaliacao); ?>&data_cadastro=<?php echo urlencode($data_cadastro); ?>&observacoes=<?php echo urlencode($observacoes); ?>&pagina=<?= $pagina + 1; ?>">Próximo</a> <!-- Botão "Próximo" -->
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

    </div>
    <script>
        function adicionarParametrosNaUrl() {
            // Obtém o formulário
            var form = document.getElementById('form-adicionar');
            
            // Ajusta o atributo 'action' para incluir os parâmetros na URL
            form.action = form.action + '?codigo_avaliacao=<?= $codigo_avaliacao; ?>&estabelecimento_id=<?= $estabelecimento_id; ?>';
        }
    </script>
</body>
</html>