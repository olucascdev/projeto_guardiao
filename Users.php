<?php 
    session_start();
    include_once 'Controller/conexao.php';
    

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <style>
        .box-search{
            display: flex;
            justify-content: end;
            gap: 1%;
        }
    </style>
</head>
<body>

<!-- Seção de lista de Users cadastrados -->
<div class="container2">
        <div class="row">
            <div class="col m-5">
                <h2>Users</h2>
            </div>
        </div>
            <!-- Seção de Pesquisa -->
            <div class="box-search">
                <input type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
                <button class="btn btn-primary" onclick="searchData()"><i class="bi bi-search"></i></button>
            </div>
        <div class="row">
            <div class="col">
                <!-- Tabela para exibir Users cadastrados -->
                <table class="table table-light table-hover m-5" border = 1 cellspacing = 0 cellpadding = 10>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome do usuário</th>
                            <th>Acesso</th>
                            <th>E-mail</th>
                            <th>Status</th>
                            <th>Ações</th>
                           
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        if(!empty($_GET['search']))
                        {
                            $data = $_GET['search'];
                            $rows = mysqli_query($conn, "SELECT * FROM usuarios WHERE id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' ORDER BY id asc");
                            
                        }else{
                         $i = 1;
                         $rows = mysqli_query($conn, "SELECT * FROM usuarios ORDER BY id asc");
                        }            

                        
                    
                        ?>
                        <?php foreach($rows as $row) : ?>
                        <tr>
                           
                           <td><?php echo $row['id']; ?></td> <!-- Mudar o nome para name caso der erro-->
                           <td><?php echo $row['nome']; ?></td>
                           <td><?php echo $row['acesso']; ?></td>
                           <td><?php echo $row['email']; ?></td>
                           <td><?php echo $row['ativo']; ?></td>
                          <!-- <td><img src="foto/<?php echo $row['foto']; ?>"  width="64px" title="<?php echo $row['foto']; ?>"> </td>     -->

                            <td>
                                    <!-- Botão de Editar -->
                                    <a href="?
                                    &id=<?php echo $row['id']; ?>
                                    &nome=<?php echo $row['nome']; ?>
                                    &acesso=<?php echo $row['acesso']; ?>
                                    &email=<?php echo $row['email']; ?>

                                    
                                    ">
                                    <img src="imagem/editar.png" alt="Editar" width="24px">
                                </a>
                                
                            <td>
                                <a href="Controller/excluir.php?id=<?php echo $row['id']; ?>"><img src="imagem/excluir.png" alt="" width="24px">
                            </a>
                            </td>
                        </tr>
                        <?php endforeach;?>                              

                           
                    </tbody>
                </table>
            </div>
        </div>

    </div>








<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js">
</script>
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
</body>
</html>