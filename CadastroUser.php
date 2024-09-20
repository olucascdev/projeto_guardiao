<?php 
  include_once 'Controller/conexao.php';
  include('class/ClassEstados.php');
  $objEstados = new ClassEstados();
  session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Novo Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (for icons in sidebar) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/CadastroUser.css">
</head>
<body>
    <!-- Formulário de Cadastro -->
    <div class="container mt-5">
        <h1 class="text-left mb-4">Cadastro de Novo Usuário</h1>
        
        <form class="needs-validation" novalidate>
            
            <!-- Primeira linha com 4 colunas -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="nome" class="form-label">Nome do Usuário</label>
                    <input type="text" class="form-control" id="nome" placeholder="Informe o nome do usuário" required>
                    <div class="invalid-feedback">Informe o nome do usuário.</div>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="permissao" class="form-label">Permissão do Usuário</label>
                    <select class="form-select" id="permissao" required>
                        <option selected disabled value="">Selecione o nível de permissão</option>
                      
                    </select>
                    <div class="invalid-feedback">Selecione a permissão.</div>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="estabelecimento" class="form-label">Estabelecimento do Usuário</label>
                    <select class="form-select" id="estabelecimento">
                        <option selected>Não vincular estabelecimento ao usuário</option>
                      
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="empresa" class="form-label">Empresa do Usuário</label>
                    <select class="form-select" id="empresa">
                        <option selected>Não vincular empresa ao usuário</option>
                      
                    </select>
                </div>
            </div>

            <!-- Segunda linha com 4 colunas -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="login" class="form-label">Login do Usuário</label>
                    <input type="text" class="form-control" id="login" placeholder="Informe o login do usuário" required>
                    <div class="invalid-feedback">Informe o login do usuário.</div>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="email" class="form-label">E-mail do Usuário</label>
                    <input type="email" class="form-control" id="email" placeholder="email@usuario.com.br" required>
                    <div class="invalid-feedback">Informe um e-mail válido.</div>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="estado" class="form-label">Estado (UF)</label>
                    <select class="form-select" id="estado" required>
                      <!-- Logica para pegar os UF no banco e printar -->
                        <option selected disabled value="">Selecione o estado</option>
                        <?php foreach ($objEstados->getEstados() as $estado) {?>
                          <option value="<?php echo $estado->id; ?>"><?php echo $estado->nome; ?></option>
                          <?php }?>
                        
                    </select>
                    <div class="invalid-feedback">Selecione o estado.</div>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="cidade" class="form-label">Cidade do Usuário</label>
                    <select class="form-select" id="cidade" disabled="disable">
                        <option value=" ">Aguardando seleção do estado</option>

                    </select>
                </div>
            </div>

            <!-- Terceira linha com 3 colunas -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="telefone-fixo" class="form-label">Telefone Fixo</label>
                    <input type="tel" class="form-control" id="telefone-fixo" placeholder="(00) 0000-0000">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="telefone-movel" class="form-label">Telefone Móvel</label>
                    <input type="tel" class="form-control" id="telefone-movel" placeholder="(00) 00000-0000">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="observacoes" class="form-label">Observações sobre o Usuário</label>
                    <textarea class="form-control" id="observacoes" rows="2" placeholder="Informe observações se necessário"></textarea>
                </div>
            </div>

            <!-- Quarta linha com 2 colunas -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="notificacoes" class="form-label">Enviar Notificações por E-mail</label>
                    <select class="form-select" id="notificacoes">
                        <option value="sim" selected>Sim, enviar e-mails de notificações</option>
                        <option value="nao">Não enviar e-mails de notificações</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="data-expiracao" class="form-label">Acesso do Usuário Expira em</label>
                    <input type="date" class="form-control" id="data-expiracao">
                </div>
            </div>

            <!-- Botões -->
            <div class="d-flex w-auto">
                <button class="btn btn-success w-75 p-2 m-3" type="submit">Salvar</button>
                <button class="btn btn-warning w-75 p-2 m-3" type="reset">Limpar</button>
                <button class="btn btn-danger  w-75 p-2 m-3" type="button">Cancelar</button>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Exemplo de JavaScript para desabilitar o envio do formulário se houver campos inválidos
      (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms)
          .forEach(function (form) {
            form.addEventListener('submit', function (event) {
              if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
              }
              form.classList.add('was-validated')
            }, false)
          })
      })()
    </script>
    <script> src="script.js"</script> 
</body>
</html>
