<?php
  include("../../assets/includes/validacao.php");
  include("../validar_sessao.php");
 
  $end_point = "http://localhost/api_jornal/noticia/";
 
  // inicializando o CURL
  $ch = curl_init();
 
  // Configurações od CURL (options)
  curl_setopt_array($ch, [
    CURLOPT_URL => $end_point,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
      'Authorization:' .$token
    ),
]);
 
  // recebe o request da API - formato JSON
  $resultado = curl_exec($ch);
 
  // finaliza o CURL
  curl_close($ch);
 
  // JSON decode, transforma o JSON em array ou objeto
  $dados = json_decode($resultado, TRUE);
 
  // echo "<pre>";
  // var_dump($dados);
  // echo "</pre>";
 
  $status = "success";
  if ($dados["status"]=="success"){
    $registros = $dados["noticia"];
  }else{
    echo $status = "fail";
    echo $msg = $dados["error"];
  };
 
  // echo "<pre>";
  // var_dump($registros);
  // echo "</pre>";
 
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Noticias</title>
 
  <?php include("../../assets/includes/head.php"); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
 
  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../../vendor/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>
 
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo $path."/".$home_interno; ?>" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>
 
    <!-- Right navbar links -->
    <?php include("../../assets/includes/right_menu.php"); ?>
  </nav>
  <!-- /.navbar -->
 
  <!-- Main Sidebar Container -->
  <?php include("../../assets/includes/menu.php"); ?>
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
   
    <!-- Main content -->

    <?php
    $explode = explode("/",$_SERVER["REQUEST_URI"]);
 
    $local = $explode[2];

  ?>
   
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Noticia</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Noticia</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
 
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
           
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Noticia</h3>
                <?php 
                if($_SESSION['cargo'] == "REDACAO" || $_SESSION['cargo'] == "ADM" || $_SESSION['cargo'] == "REVISOR") {
                ?>
                  <a href="form.php" class="btn btn-primary"><i class="fas fa-plus"></i> NOVO</a>
              <?php
                  } 
              ?>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Titulo</th>
                      <th>Sub-Titulo</th>
                      <th>Genero</th>
                      <th>imagem</th>
                      <th>Ação</th>
                    </tr>
                  </thead>
                  <tbody>
 
                    <?php
                    // laço de repetição para que possa listar todos os
                    // registro trazidos pela API
                      foreach($registros as $registro){
                    ?>
 
                    <tr>
                      <th><?php echo $registro["pk_id"]; ?></th>
                      <td><?php echo $registro["titulo"]; ?></td>
                      <td><?php echo $registro["sub_titulo"]; ?></td>
                      <td><?php echo $registro["tipo_genero"]; ?></td>
                      <td><?php echo $registro["imagem"]; ?></td>
                      <td>
                        <a href="form.php?id=<?php echo $registro["pk_id"]; ?>" class="btn btn-secondary"><i class="fas fa-pen"></i> ALTERAR</a>
                        <a href="delete.php?id=<?php echo $registro["pk_id"]; ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i> EXCLUIR</a>
                      </td>
                    </tr>
 
                    <?php
                      }
                    ?>
 
                  </tbody>
                  <tfoot>
                  <tr>
                  <th>ID</th>
                      <th>Titulo</th>
                      <th>Sub-Titulo</th>
                      <th>Genero</th>
                      <th>Imagem</th> 
                      <th>Ação</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
   
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
  <strong>Jornal JS <a href="../noticia/index.php">Tela Principal</a>.</strong>
    <!-- <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div> -->
  </footer>
 
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<?php include("../../assets/includes/scripts.php"); ?>
 
</body>
</html>