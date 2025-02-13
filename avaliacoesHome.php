<?php 
session_start(); // Inicia a sessão para manter as informações do usuário
include_once 'Controller/conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Captura o ID do estabelecimento da URL e o sanitiza
$estabelecimento_id = filter_input(INPUT_GET, 'estabelecimento_id', FILTER_SANITIZE_NUMBER_INT);

// Recupera a abreviação do estabelecimento para exibição na página
$query_abreviacao = "SELECT abrev FROM estabelecimentos WHERE id = '$estabelecimento_id'";
$result_abreviacao = mysqli_query($conn, $query_abreviacao);
$abreviacao_row = mysqli_fetch_assoc($result_abreviacao);
$abreviacao = $abreviacao_row['abrev'] ?? ''; // Valor padrão se não houver abreviação

// Configuração de paginação
$itens_por_pagina = 5; // Define quantos itens serão exibidos por página
$pagina_atual = $_GET['pagina'] ?? 1; // Captura o número da página atual ou define como 1

// Se houver uma pesquisa, realiza uma consulta diferente
if(!empty($_GET['search'])) {
    $data = $_GET['search']; // Captura a string de pesquisa
    // Total de itens para a pesquisa
    $total_query = "SELECT COUNT(*) as total FROM avaliacao WHERE id LIKE '%$data%' OR nome_avaliacao LIKE '%$data%'";
    $total_result = mysqli_query($conn, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_itens = $total_row['total']; // Total de itens que correspondem à pesquisa
    
    // Filtra os dados para a página atual
    $inicio = ($pagina_atual - 1) * $itens_por_pagina; // Calcula o índice inicial para a consulta
    $rows = mysqli_query($conn, "SELECT * FROM avaliacao WHERE id LIKE '%$data%' OR nome_avaliacao LIKE '%$data%' ORDER BY id ASC LIMIT $inicio, $itens_por_pagina");
} else {
    // Total de itens para o estabelecimento
    $total_query = "SELECT COUNT(*) as total FROM avaliacao WHERE estabelecimento_id = '$estabelecimento_id'";
    $total_result = mysqli_query($conn, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_itens = $total_row['total']; // Total de avaliações do estabelecimento

    // Filtra os dados para o estabelecimento
    $inicio = ($pagina_atual - 1) * $itens_por_pagina; // Calcula o índice inicial
    $rows = mysqli_query($conn, "SELECT * FROM avaliacao WHERE estabelecimento_id = '$estabelecimento_id' ORDER BY id ASC LIMIT $inicio, $itens_por_pagina");
}

// Calcula o total de páginas com base no total de itens
$total_paginas = ceil($total_itens / $itens_por_pagina); // Arredonda para cima

// Recupera todas as avaliações para o dropdown
$query_avaliacoes = "SELECT id, nome_avaliacao FROM avaliacao WHERE estabelecimento_id = '$estabelecimento_id'";
$result_avaliacoes = mysqli_query($conn, $query_avaliacoes);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Avaliações</title>
    <!-- Inclusão do Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"> <!-- Ícones do Bootstrap -->
    <link rel="stylesheet" href="styles/Users.css"> <!-- Estilos personalizados -->
</head>
<body>

<!-- Seção de lista de avaliações cadastrados -->
<div class="container2">
    <div class="row">
        <div class="col m-5">
            <h2>Painel de Avaliações - <?php echo !empty($abreviacao) ? $abreviacao : ''; ?></h2> <!-- Exibe a abreviação do estabelecimento -->
        </div>
    </div>
    <!-- Seção de Pesquisa -->
    <div class="box-search w-auto">
        <a href="avaliacao_estabelecimento.php" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Voltar <!-- Botão para voltar à página anterior -->
        </a>
        <a href="CadastroAvaliacao.php?estabelecimento_id=<?php echo $estabelecimento_id; ?>">
            <button class="btn btn-success"><i class="bi bi-clipboard2-plus-fill"></i> Nova Avaliação</button> <!-- Botão para adicionar nova avaliação -->
        </a>
        <button class="btn btn-warning"><i class="bi bi-printer-fill"></i> Imprimir</button> <!-- Botão para imprimir -->
        <button type="button" class="btn btn-info" onclick="atualizarPagina()">
            <i class="bi bi-arrow-clockwise"></i> Atualizar <!-- Botão para atualizar a página -->
        </button>   
        <input type="search" class="form-control w-50" placeholder="Pesquisar por Cód / Nome" id="pesquisar"> <!-- Campo de pesquisa -->
        <button class="btn btn-primary" onclick="searchData()"><i class="bi bi-search"></i></button> <!-- Botão de pesquisa -->

        <!-- Dropdown para escolher a avaliação -->
    
    </div>

    <!-- Tabela para exibir avaliações cadastradas -->
    <table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th style="width: 5%;">Código</th>
                <th>Nome da Avaliação</th>
                <th style="width: 10%;">Data</th>
                <th>Descrição</th>
                <th style="width: 10%;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row) : ?> <!-- Loop para exibir cada avaliação -->
            <tr>
                <td><?php echo $row['id']; ?></td> <!-- Exibe o ID da avaliação -->
                <td><?php echo $row['nome_avaliacao']; ?></td> <!-- Exibe o nome da avaliação -->
                <td><?php echo date('d/m/Y', strtotime($row['data_cadastro'])); ?></td> <!-- Exibe a data da avaliação formatada -->
                <td><?php echo $row['descricao_avaliacao']; ?></td> <!-- Exibe a descrição da avaliação -->
                <td class="text-center d-flex justify-content-center">
                    <!-- Botão de Editar -->
                    <a class="btn btn-warning btn-sm me-2" href="editar_avaliacoes.php?codigo_avaliacao=<?php echo $row['id']; ?>&nome_avaliacao=<?php echo urlencode($row['nome_avaliacao']); ?>&data_cadastro=<?php echo $row['data_cadastro']; ?>&observacoes=<?php echo urlencode($row['descricao_avaliacao']); ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>" >
                        <i class="bi bi-pencil w-25"></i> <!-- Ícone de editar -->
                    </a>
                    <a class="btn btn-danger btn-sm me-2" href="excluir_avaliacoes.php?id=<?php echo $row['id']; ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?> ">
                        <i class="bi bi-trash-fill w-25"></i> <!-- Ícone de excluir -->
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Navegação da página -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if($pagina_atual == 1) echo 'disabled'; ?>"> <!-- Desabilita o botão "Anterior" se estiver na primeira página -->
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $pagina_atual - 1; ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>">Anterior</a>
            </li>
            <?php for($i = 1; $i <= $total_paginas; $i++) : ?> <!-- Loop para gerar os números das páginas -->
            <li class="page-item <?php if($pagina_atual == $i) echo 'active'; ?>"> <!-- Marca a página atual como ativa -->
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $i; ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?php if($pagina_atual == $total_paginas) echo 'disabled'; ?>"> <!-- Desabilita o botão "Próximo" se estiver na última página -->
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $pagina_atual + 1; ?>&estabelecimento_id=<?php echo $estabelecimento_id; ?>">Próximo</a>
            </li>
        </ul>
    </nav>             
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> <!-- Inclusão do Bootstrap JS -->
<script>
    // Função para atualizar a página
    function atualizarPagina() {
        window.location.reload(); // Recarrega a página atual
    }

    // Função para realizar a pesquisa
    function searchData() {
        var search = document.getElementById('pesquisar').value; // Captura o valor do campo de pesquisa
        window.location.href = "avaliacao_estabelecimento.php?search=" + search + "&estabelecimento_id=<?php echo $estabelecimento_id; ?>"; // Redireciona com o valor da pesquisa
    }
</script>
</body>
</html>
