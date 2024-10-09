<?php 
session_start();
include_once 'Controller/conexao.php';

// Configuração de paginação
$itens_por_pagina = 5;
$pagina_atual = $_GET['pagina'] ?? 1;

// Verifica se um estabelecimento foi selecionado e armazena o ID na sessão
if (isset($_GET['estabelecimento_id'])) {
    $estabelecimento_id = intval($_GET['estabelecimento_id']);
    // Aqui você pode adicionar a lógica para atualizar o banco de dados com o ID do estabelecimento, se necessário.
    // Exemplo:
    $query = "UPDATE avaliacao SET estabelecimento_id = $estabelecimento_id WHERE id = {id_da_avaliacao_aqui}"; // Adapte conforme necessário
    mysqli_query($conn, $query);
}

// Pesquisa
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $rows = mysqli_query($conn, "SELECT * FROM estabelecimentos WHERE id LIKE '%$data%' or abrev LIKE '%$data%' ORDER BY id asc");
} else {
    $rows = mysqli_query($conn, "SELECT * FROM estabelecimentos ORDER BY id asc");
}

$total_itens = mysqli_num_rows($rows);
$total_paginas = ceil($total_itens / $itens_por_pagina);
$inicio = ($pagina_atual - 1) * $itens_por_pagina;

// Filtra os dados para a página atual
if (!empty($_GET['search'])) {
    $rows = mysqli_query($conn, "SELECT * FROM estabelecimentos WHERE id LIKE '%$data%' or abrev LIKE '%$data%' ORDER BY id asc LIMIT $inicio, $itens_por_pagina");
} else {
    $rows = mysqli_query($conn, "SELECT * FROM estabelecimentos ORDER BY id asc LIMIT $inicio, $itens_por_pagina");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolher Estabelecimento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/Users.css">
</head>
<body>

<!-- Seção de lista de estabelecimentos -->
<div class="container2">
    <div class="row">
        <div class="col m-5">
            <h2>Escolher Estabelecimento</h2>
        </div>
    </div>
    <!-- Seção de Pesquisa -->
    <div class="box-search w-auto">
        <a href="Users.php" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Voltar</a>
        <button type="button" class="btn btn-info" onclick="atualizarPagina()">
            <i class="bi bi-arrow-clockwise"></i> Atualizar
        </button>
        <input type="search" class="form-control w-50" placeholder="Pesquisar por Cód / Sigla" id="pesquisar">
        <button class="btn btn-primary" onclick="searchData()"><i class="bi bi-search"></i></button>
    </div>

    <!-- Tabela para exibir estabelecimentos cadastrados -->
    <table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing=0 cellpadding=10>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome do Estabelecimento</th>
                <th>Sigla</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row) : ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['estabelecimento']; ?></td>
                <td><?php echo $row['abrev']; ?></td>
                <td>
                    <?php 
                        if ($row['ativo'] == 1) {
                            echo 'ATIVO';
                        } else {
                            echo 'DESATIVADO';
                        }
                    ?>
                </td>
                <td class="text-center d-flex justify-content-center">
                    <!-- Passa o ID do estabelecimento na URL -->
                    <a href="avaliacoesHome.php?estabelecimento_id=<?php echo $row['id']; ?>">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>                              
        </tbody>
    </table>

    <!-- Paginação -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($pagina_atual == 1) echo 'disabled'; ?>">
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $pagina_atual - 1; ?>">Anterior</a>
            </li>
            <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
            <li class="page-item <?php if ($pagina_atual == $i) echo 'active'; ?>">
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?php if ($pagina_atual == $total_paginas) echo 'disabled'; ?>">
                <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $pagina_atual + 1; ?>">Próximo</a>
            </li>
        </ul>
    </nav>             
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Script para pesquisa
var search = document.getElementById('pesquisar');
search.addEventListener("keydown", function(event){
    if (event.key === "Enter") {
        searchData();
    }
});                       

function searchData() {
    window.location = 'avaliacao_estabelecimento.php?search=' + search.value;
}

// Script para atualizar a página e resetar a pesquisa
function atualizarPagina() {
    document.getElementById('pesquisar').value = '';
    const urlSemParametros = window.location.href.split('?')[0];
    window.location.href = urlSemParametros;
}
</script>
</body>
</html>
