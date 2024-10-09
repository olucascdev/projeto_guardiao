<?php 
session_start();
include_once 'Controller/conexao.php';

// Captura o ID do estabelecimento
$estabelecimento_id = filter_input(INPUT_GET, 'estabelecimento_id', FILTER_SANITIZE_NUMBER_INT);

// Recupera a abreviação do estabelecimento
$query_abreviacao = "SELECT abrev FROM estabelecimentos WHERE id = '$estabelecimento_id'";
$result_abreviacao = mysqli_query($conn, $query_abreviacao);
$abreviacao_row = mysqli_fetch_assoc($result_abreviacao);
$abreviacao = $abreviacao_row['abrev'] ?? ''; // Valor padrão se não houver abreviação

// Configuração de paginação
$itens_por_pagina = 5;
$pagina_atual = $_GET['pagina'] ?? 1;

// Se houver pesquisa, usamos uma consulta diferente
if(!empty($_GET['search'])) {
    $data = $_GET['search'];
    // Total de itens para a pesquisa
    $total_query = "SELECT COUNT(*) as total FROM avaliacao WHERE id LIKE '%$data%' OR nome_avaliacao LIKE '%$data%'";
    $total_result = mysqli_query($conn, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_itens = $total_row['total'];
    
    // Filtra os dados para a página atual
    $inicio = ($pagina_atual - 1) * $itens_por_pagina;
    $rows = mysqli_query($conn, "SELECT * FROM avaliacao WHERE id LIKE '%$data%' OR nome_avaliacao LIKE '%$data%' ORDER BY id ASC LIMIT $inicio, $itens_por_pagina");
} else {
    // Total de itens para o estabelecimento
    $total_query = "SELECT COUNT(*) as total FROM avaliacao WHERE estabelecimento_id = '$estabelecimento_id'";
    $total_result = mysqli_query($conn, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_itens = $total_row['total'];

    // Filtra os dados para o estabelecimento
    $inicio = ($pagina_atual - 1) * $itens_por_pagina;
    $rows = mysqli_query($conn, "SELECT * FROM avaliacao WHERE estabelecimento_id = '$estabelecimento_id' ORDER BY id ASC LIMIT $inicio, $itens_por_pagina");
}

// Calcula o total de páginas
$total_paginas = ceil($total_itens / $itens_por_pagina);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Avaliações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/Users.css">
</head>
<body>

<!-- Seção de lista de avaliações cadastrados -->
<div class="container2">
    <div class="row">
        <div class="col m-5">
            <h2>Painel de Avaliações - <?php echo !empty($abreviacao) ? $abreviacao : ''; ?></h2>
        </div>
    </div>
    <!-- Seção de Pesquisa -->
    <div class="box-search w-auto">
        <a href="avaliacao_estabelecimento.php" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        <a href="CadastroAvaliacao.php?estabelecimento_id=<?php echo $estabelecimento_id; ?>">
            <button class="btn btn-success"><i class="bi bi-clipboard2-plus-fill"></i> Nova Avaliação</button>
        </a>
        <button class="btn btn-warning"><i class="bi bi-printer-fill"></i> Imprimir</button>
        <button type="button" class="btn btn-info" onclick="atualizarPagina()">
            <i class="bi bi-arrow-clockwise"></i> Atualizar
        </button>   
        <input type="search" class="form-control w-50" placeholder="Pesquisar por Cód / Nome" id="pesquisar">
        <button class="btn btn-primary" onclick="searchData()"><i class="bi bi-search"></i></button>
    </div>

    <!-- Tabela para exibir avaliações cadastradas -->
    <table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome da Avaliação</th>
                <th>Data da Avaliação</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row) : ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nome_avaliacao']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($row['data_cadastro'])); ?></td>
                <td><?php echo $row['descricao_avaliacao']; ?></td>
                <td class="text-center d-flex justify-content-center">
                    <!-- Botão de Editar -->
                    <a href="editar_avaliacoes.php?codigo=<?php echo $row['id']; ?>&nome_avaliacao=<?php echo urlencode($row['nome_avaliacao']); ?>&data_cadastro=<?php echo $row['data_cadastro']; ?>&observacoes=<?php echo urlencode($row['descricao_avaliacao']); ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>">
                        <i class="bi bi-pencil-square me-2"></i>
                    </a>
                    <a href="excluir_avaliacoes.php?id=<?php echo $row['id']; ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>">
                        <i class="bi bi-trash-fill me-2"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if($pagina_atual == 1) echo 'disabled'; ?>">
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $pagina_atual - 1; ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>">Anterior</a>
            </li>
            <?php for($i = 1; $i <= $total_paginas; $i++) : ?>
            <li class="page-item <?php if($pagina_atual == $i) echo 'active'; ?>">
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $i; ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?php if($pagina_atual == $total_paginas) echo 'disabled'; ?>">
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $pagina_atual + 1; ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>">Próximo</a>
            </li>
        </ul>
    </nav>             
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para pesquisa -->
<script>
    var search = document.getElementById('pesquisar');
    search.addEventListener("keydown", function(event){
        if(event.key === "Enter"){
            searchData();
        }
    });                       

    function searchData() {
        const query = document.getElementById('pesquisar').value;
        if (query) {
            window.location.href = `?search=${query}&estabelecimento_id=<?php echo $estabelecimento_id; ?>`;
        }
    }

    function atualizarPagina() {
        // Limpa o campo de pesquisa
        document.getElementById('pesquisar').value = '';

        // Remove qualquer parâmetro de busca da URL e recarrega a página
        const urlSemParametros = window.location.href.split('?')[0];
        window.location.href = urlSemParametros + '?estabelecimento_id=<?php echo $estabelecimento_id; ?>';
    }
</script>
</body>
</html>
