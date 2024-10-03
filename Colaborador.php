<?php 
include_once 'Controller/conexao.php';

$query = "SELECT id, nome FROM colaboradores"; 
$result = mysqli_query($conn1, $query);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <title>Colaboradores</title>
</head>
<body>
    <div class="container">
        <h3>Vincular Colaborador no Questionário</h3>
        <table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing = 0 cellpadding = 10>
                    <thead>
                        <tr>
                            <th>Colaborador</th>
                            <th width="12%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select class="form-select" name="nome_colaborador" id="nome_colaborador">
                                    <option value="">Selecione o Colaborador</option>
                                <?php 
                                    // Verifica se houve resultados na consulta
                                    if(mysqli_num_rows($result) > 0) {
                                        // Itera sobre os colaboradores retornados e preenche o select
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>Nenhum colaborador encontrado</option>";
                                    }
                                    ?>

                                </select>
                            
                            
                            </td>

                           <td>
                            <button class="btn btn-success w-100"><i class="bi bi-plus-circle"></i> Adicionar</button>
                            </td>
                        </tr>
                                                     

                           
                    </tbody>
        </table>
        <h3>Colaboradores Vinculados ao Questionário</h3>
        <table class="table table-light table-bordered table-striped table-hover m-5" border="1" cellspacing = 0 cellpadding = 10>
                    <thead>
                        <tr>
                            <th>Colaborador</th>
                            <th width="12%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td></td>
                    <td><a href=""><button class="btn btn-danger w-100"><i class="bi bi-trash3"></i> Excluir</button></a></td>
                    </tbody>
        </table>

    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>