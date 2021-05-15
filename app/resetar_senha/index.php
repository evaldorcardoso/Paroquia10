<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="theme-color" content="#eee">
	<title>Paroquia10</title>
	<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
	<link rel="icon" href="../favicon.ico" type="image/x-icon">

	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
</head>
<body class="">
  <div class="wrapper">
    <div class="main-panel" style="width: 90%;float: left;margin-left: 5%;">
      	<!-- Navbar -->
      	<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
	        <div class="container-fluid">
	          <div class="navbar-wrapper" onclick="location.replace('index.html')">
	          	<img src="../images/icone_transp.png" style="width:25px">
	            <a class="navbar-brand" href="#">Paróquia10</a>
	          </div>
	        </div>
      	</nav>
      	<!-- End Navbar -->
      	<div class="content" style="padding:0;margin-top: 90px">
	        <div class="container-fluid">
	        	<div class="alert alert-danger" id="notificacao_erro" style="display: none">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span id="mensagem_notificacao_erro">This is a notification with close button.</span>
            </div>
            <div class="alert alert-success" id="notificacao_ok" style="display: none">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
              </button>
              <span id="mensagem_notificacao_ok">This is a notification with close button.</span>
            </div>
	          	<div id="div_principal" class="row">
		            <div class="col-lg-12 col-md-12" style="padding: 0">
		              <div class="card">
		                <div class="card-header card-header-rose">
		                  <h4 class="card-title">Seu Informativo digital</h4>
                      <?php
                      //array que guarda os dados
                      $info = array();
                      $id=$_GET['id'];
                      include_once '../paroquiaws/crudClass.php';
                      $cClass = new crudClass();  
                      if(is_numeric($id))                      
                        $cClass -> pesquisarTabela('users','id',$id);
                      if(0==mysqli_num_rows($cClass->getconsulta()))
                        echo 'Nenhum cadastro encontrado com esse email...';
                      else
                      {
                        $array_=mysqli_fetch_assoc($cClass->getconsulta());
                      }
                      ?>
		                  <p class="card-category">Redefina a senha para o usuário <?php echo $array_['username'];?> abaixo:</p>
		                </div>
		                <div class="card-body table-responsive">
                      <input id="redefinir_senha1" type="password" class="form-control" placeholder="Informe a nova Senha...">
                      <input id="redefinir_senha2" type="password" class="form-control" placeholder="Repita a nova Senha...">
		                </div>
		                <div class="text-center">
		                	<button type="button" class="btn btn-rose btn-round" onclick="">REDEFINIR SENHA<div class="ripple-container"></div></button>
		                </div>
		              </div>
		            </div>
	          	</div>
	        </div>
      	</div>
      <footer class="footer">
        <div class="container-fluid">
          <div class="copyright float-center">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>, made with <i class="material-icons">favorite</i> by
            <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
  <!--<script src="assets/js/core/jquery_.min.js"></script>-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="../assets/js/material-dashboard.js?v=2.1.1.1" type="text/javascript"></script>
</body>

</html>
