<?php 
    include_once "Controller/conexao.php";
    session_start();
   if(!isset($_SESSION['id'])) {
        header('Location: TelaLogin.php');
    }
    

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (for icons in sidebar) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/home.css">
    <title>Document</title>
</head>
<body>
   <!-- Sidebar -->
  <div class="fixed">
    <div class="sidebar d-flex flex-column p-3">
      <div class="logo text-center mb-4">
        <img src="imagem/logoperbras" alt="">
        <h3>Guardião</h3>
      </div>
      <nav>
        <div class="mb-2">
          <a class="parameter-item" target="frame" href="avaliacao_estabelecimento.php" role="button" aria-expanded="false" aria-controls="param1Subthemes">
              <i class="bi bi-newspaper"></i> Avaliações
          </a>
        </div>
        <div class="mb-2">
          <a class="parameter-item" href="Users.php" target="frame" role="button" aria-expanded="false" aria-controls="param2Subthemes">
            <i class="bi bi-people-fill"></i> Usuários
          </a>
        </div>
        <div class="mb-2">
          <a class="parameter-item"  href="avaliacao_unidades.php" target="frame" role="button" aria-expanded="false" aria-controls="param3Subthemes">
            <i class="bi bi-building"></i> Unidades
          </a>
        </div>
        <div class="mb-2">
          <a class="parameter-item" href="#param4Subthemes" role="button" aria-expanded="false" aria-controls="param4Subthemes">
            <i class="bi bi-gear-fill"></i> Parametro 4
          </a>
        </div>
        <div class="mb-2">
          <a class="parameter-item"  href="#param5Subthemes" role="button" aria-expanded="false" aria-controls="param5Subthemes">
            <i class="bi bi-gear-fill"></i> Parametro 5
          </a>
        </div>
      </nav>
      <div class="mt-auto">
        <a href="Controller/logout.php" class="text-center d-block">Logout</a>
      </div>
    </div>
    <!-- Main Content -->
    <div class="main-content">
      <div class="container-fluid">
        <div class="row mb-4">
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                Card 1 Content
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                Card 2 Content
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                Card 3 Content
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                Card 4 Content
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
          <iframe width="100%"  height="547px" frameborder="0" marginheight="0" marginwidth="0" name="frame" scrolling="yes" src="Users.php">

          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="javascript" src="script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>          
</body>
</html>