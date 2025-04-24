<nav class="navbar navbar-expand-lg" style="background-color: #ffffff;">
    <div class="container">
      <a class="navbar-brand" href="index.php"><img alt="CES-Logo" src="https://www.delasantafe.cl/talentos/eva/assets/img/logo.png" width="52" height="58"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php"><i class="bi bi-file-earmark-person-fill" style="font-size: 1.5rem;"></i> Mi AutoEvaluación</a>
          </li>
          <?php
          if(esEvaluador($_SESSION['full_rut']) == 1){
          ?>
          <li class="nav-item">
          <a class="nav-link" href="evaluaciones.php"><i class="bi bi-file-earmark-check-fill" style="font-size: 1.5rem;"></i> Mis Evaluaciones</a>
          </li>
          <li class="nav-item">
          <a class="nav-link" href="circular_evaluadores.php"><i class="bi bi-arrow-repeat" style="font-size: 1.5rem;"></i> Mi Evaluación Circular</a>
          </li>
          <?php
          }

          if(esComite($_SESSION['full_rut']) >= 1){
            ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-check-fill" style="font-size: 1.5rem;"></i> Comité</a>
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="com_faltantes.php"><i class="bi bi-person-fill-x"></i> Reporte Faltantes</a></li>
                <li><a class="dropdown-item" href="com_res_evaluacion.php"><i class="bi bi-award"></i> Resultados Evaluación</a></li>
                <li><a class="dropdown-item" href="com_res_circular.php"><i class="bi bi-arrow-repeat"></i> Resultados Ev. Circular</a></li>
            </ul>
        </li>
          <?php }?>          
        </ul>
        <div class="d-flex nav-item">
          <a class="nav-link text-primary" href="/talentos/portal.php"><i class="bi bi-house-up" style="font-size: 1.5rem;"></i> <strong>Portal Talentos</strong></a>
        </div>
      </div>
    </div>
  </nav>