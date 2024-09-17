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
  <div class="sidebar d-flex flex-column p-3">
    <div class="logo text-center mb-4">
      <img src="imagem/logo.png" alt="">
      <h3>Guardião</h3>
    </div>
    <nav>
      <div class="mb-2">
        <a class="parameter-item" data-bs-toggle="collapse" href="#param1Subthemes" role="button" aria-expanded="false" aria-controls="param1Subthemes">
            <i class="bi bi-newspaper"></i> Avaliações
        </a>
        <div class="collapse parameter-subthemes" id="param1Subthemes">
          <a href="#"><i class="bi bi-newspaper"></i> Relatório</a>
          <a href="#"><i class="bi bi-bar-chart-line-fill"></i> Histórico</a>
          <a href="#"><i class="bi bi-file-earmark-plus-fill"></i> Novo Projeto</a>
        </div>
      </div>
      <div class="mb-2">
        <a class="parameter-item" data-bs-toggle="collapse" href="#param2Subthemes" role="button" aria-expanded="false" aria-controls="param2Subthemes">
          <i class="bi bi-gear-fill"></i> Parametro 2
        </a>
        <div class="collapse parameter-subthemes" id="param2Subthemes">
          <a href="#">Subtema 2.1</a>
          <a href="#">Subtema 2.2</a>
          <a href="#">Subtema 2.3</a>
        </div>
      </div>
      <div class="mb-2">
        <a class="parameter-item" data-bs-toggle="collapse" href="#param3Subthemes" role="button" aria-expanded="false" aria-controls="param3Subthemes">
          <i class="bi bi-gear-fill"></i> Parametro 3
        </a>
        <div class="collapse parameter-subthemes" id="param3Subthemes">
          <a href="#">Subtema 3.1</a>
          <a href="#">Subtema 3.2</a>
          <a href="#">Subtema 3.3</a>
        </div>
      </div>
      <div class="mb-2">
        <a class="parameter-item" data-bs-toggle="collapse" href="#param4Subthemes" role="button" aria-expanded="false" aria-controls="param4Subthemes">
          <i class="bi bi-gear-fill"></i> Parametro 4
        </a>
        <div class="collapse parameter-subthemes" id="param4Subthemes">
          <a href="#">Subtema 4.1</a>
          <a href="#">Subtema 4.2</a>
          <a href="#">Subtema 4.3</a>
        </div>
      </div>
      <div class="mb-2">
        <a class="parameter-item" data-bs-toggle="collapse" href="#param5Subthemes" role="button" aria-expanded="false" aria-controls="param5Subthemes">
          <i class="bi bi-gear-fill"></i> Parametro 5
        </a>
        <div class="collapse parameter-subthemes" id="param5Subthemes">
          <a href="#">Subtema 5.1</a>
          <a href="#">Subtema 5.2</a>
          <a href="#">Subtema 5.3</a>
        </div>
      </div>
    </nav>
    <div class="mt-auto">
      <a href="#" class="text-center d-block">Logout</a>
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
          <iframe src="https://www.example.com" width="100%" height="400px" frameborder="0"></iframe>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>          
</body>
</html>