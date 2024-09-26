<?php 
    session_start();
    include_once 'Controller/conexao.php';
    

    // Configuração de paginação
    $itens_por_pagina = 5;
    $pagina_atual = $_GET['pagina'] ?? 1;

    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $rows = mysqli_query($conn, "SELECT * FROM usuarios WHERE id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY id asc");
    }else{
        $rows = mysqli_query($conn, "SELECT * FROM usuarios ORDER BY id asc");
    }

    $total_itens = mysqli_num_rows($rows);
    $total_paginas = ceil($total_itens / $itens_por_pagina);
    $inicio = ($pagina_atual - 1) * $itens_por_pagina;

    // Filtra os dados para a página atual
    if(!empty($_GET['search']))
    {
        $rows = mysqli_query($conn, "SELECT * FROM usuarios WHERE id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY id asc LIMIT $inicio, $itens_por_pagina");
    }else{
        $rows = mysqli_query($conn, "SELECT * FROM usuarios ORDER BY id asc LIMIT $inicio, $itens_por_pagina");
    }

  

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/Users.css">
</head>
<body>

<!-- Seção de lista de Users cadastrados -->
<div class="container2">
        <div class="row">
            <div class="col m-5">
                <h2>Painel de Usuários</h2>
            </div>
        </div>
            <!-- Seção de Pesquisa -->
            <div class="box-search w-auto">
                    <a href="CadastroUsuario.php"><button class="btn btn-success"><i class="bi bi-person-plus-fill"></i> Novo Usuário</button></a>
                    <button class="btn btn-warning"><i class="bi bi-printer-fill"></i> Imprimir</button>
                    <button type="button" class="btn btn-info" onclick="atualizarPagina()">
                        <i class="bi bi-arrow-clockwise"></i> Atualizar
                    </button>   
                    <input type="search" class="form-control w-50" placeholder="Pesquisar por Cód / Email / Nome" id="pesquisar">
                    <button class="btn btn-primary" onclick="searchData()"><i class="bi bi-search"></i></button>
            </div>
        <div class="row">
            <div id='' class="col">
                <!-- Tabela para exibir Users cadastrados -->
                <table class="table table-light table-hover m-5" border="1" cellspacing = 0 cellpadding = 10>
                    <thead>
                        <tr>
                            <th style="width: 10%">Código</th>
                            <th style="width: 30%">Nome do usuário</th>
                            <th style="width: 15%">Acesso</th>
                            <th style="width: 30%">E-mail</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 15%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rows as $row) : ?>
                        <tr>
                           
                           <td><?php echo $row['id']; ?></td> <!-- Mudar o nome para name caso der erro-->
                           <td><?php echo $row['nome']; ?></td>
                           <td>
                            <?php 
                                switch ($row['acesso']) {
                                    case '0':
                                        echo 'Visitante';
                                        break;
                                    case '1':
                                        echo 'Usuário';
                                        break;
                                    case '2':
                                        echo 'Gestor';
                                        break;
                                    case '3':
                                        echo 'Administrador';
                                        break;
                                    case '4':
                                        echo 'Master';
                                        break;
                                }
                           
                           ?>
                           </td>
                           <td><?php echo $row['email']; ?></td>
                           <td class="text-center">
                            <?php 
                            switch ($row['ativo']) {
                                case '0':
                                    echo 'Desativado';
                                    break;
                                
                                default:
                                    echo 'Ativo';
                                    break;
                            }
                            
                            ?>
                        </td>
                          <!-- <td><img src="foto/<?php echo $row['foto']; ?>"  width="64px" title="<?php echo $row['foto']; ?>"> </td>     -->

                            <td class="text-center">
                                    <!-- Botão de Editar -->
                                    <a href="CadastroUsuarioEdit.php?
                                    &id=<?php echo $row['id']; ?>
                                    &nome=<?php echo $row['nome']; ?>
                                    &acesso=<?php echo $row['acesso']; ?>
                                    &email=<?php echo $row['email']; ?>
                                    &status=<?php echo $row['ativo']; ?>
                                    
                                    ">
                                    <i class="bi bi-pencil-square me-2"></i>
                                    </a>
                                
                            <td >
                                <a href="Controller/excluir.php?id=<?php echo $row['id']; ?>"><i class="bi bi-trash-fill me-2"></i>
                            </a>
                            </td>
                           <!-- <td class="text-center">
                                <i class="bi bi-camera-fill"></i>
                            </a>
                            </td> -->
                        </tr>
                        <?php endforeach;?>                              

                           
                    </tbody>
                    
                     
                </table>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php if($pagina_atual == 1) echo 'disabled'; ?>">
                            <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $pagina_atual - 1; ?>">Anterior</a>
                        </li>
                        <?php for($i = 1; $i <= $total_paginas; $i++) : ?>
                        <li class="page-item <?php if($pagina_atual == $i) echo 'active'; ?>">
                            <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        <li class="page-item <?php if($pagina_atual == $total_paginas) echo 'disabled'; ?>">
                            <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?pagina=<?php echo $pagina_atual + 1; ?>">Próximo</a>
                        </li>
                    </ul>
                </nav>             
                
            </div>
        </div>

</div>








<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js">
</script>
<!--script para pesquisa-->
<script>
    var search = document.getElementById('pesquisar');
    search.addEventListener("keydown", function(event){
        if(event.key === "Enter"){
            searchData();
        }
    });                       
                            
    function searchData()
    {
        window.location = 'Users.php?search='+search.value;
    }
</script>
<!--script para atualizar pagina e resetar a pesquisa-->
<script>
    function atualizarPagina() {
        // Limpa o campo de pesquisa
        document.getElementById('pesquisar').value = '';

        // Remove qualquer parâmetro de busca da URL e recarrega a página
        const urlSemParametros = window.location.href.split('?')[0];
        window.location.href = urlSemParametros;
    }

    // Função de pesquisa (se já estiver implementada)
    function searchData() {
        const query = document.getElementById('pesquisar').value;
        if (query) {
            // Redireciona para a página com o parâmetro de busca
            window.location.href = `?search=${query}`;
        }
    }
</script>
</body>
</html>