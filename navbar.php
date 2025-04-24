<?php 

$rut = get_rut_from_funcionario($_SESSION["username"]);
//$esAdmin = get_admin_from_funcionario($_SESSION["username"]);


?>

<nav class="navbar is-fixed-top has-shadow" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="portal.php">
      <img alt="CES-Logo" src="assets/img/ces-logo.png" width="112" height="28">
    </a>

	<a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu" aria-expanded="false">
	  <span aria-hidden="true"></span>
	  <span aria-hidden="true"></span>
	  <span aria-hidden="true"></span>
	</a>
  </div>

  <div id="navMenu" class="navbar-menu">
    <div class="navbar-start">
      <a href="portal.php" class="navbar-item">
      <span class="icon"><i class="fas fa-home"></i></span>&nbsp;
        Principal
      </a>

      <a href="cumplimiento.php" class="navbar-item <?php esHabilitado();?>">
      <span class="icon"><i class="fas fa-person-circle-check"></i></span>&nbsp;
        Cumplimiento
      </a>
	  
      <a href="ranking.php" class="navbar-item <?php esHabilitado();?>">
      <span class="icon"><i class="fas fa-ranking-star"></i></span>&nbsp;
        Ranking
      </a>
          
      
       <?php
        $esAdmin = 'disabled' ;
        
        if ($rut == '13549300' or $rut == '9676348' or $rut == '14275395' or $rut == '17455075' or $rut == '15808815')
	        {
	    	$esAdmin = '';
	        } else {
		    $esAdmin = 'disabled';
	    }
        ;?>
        <div class="navbar-item has-dropdown is-hoverable <?php echo $esAdmin ;?>">
            <a class="navbar-link">
                <!--<figure class="image is-24x24">
                    <img class="is-rounded" src="https://api.dicebear.com/7.x/initials/svg?seed=<?php echo htmlspecialchars($_SESSION["username"]);?>">
                    <img class="is-rounded" src="https://avatars.dicebear.com/api/initials/<?php echo htmlspecialchars($_SESSION["username"]);?>.svg">
                </figure>-->
                Admin
            </a>

        <div class="navbar-dropdown is-boxed is-right">
            <label><strong>&nbsp;Talcahuano</strong></label>
            <a href="https://espiritusanto.cl/dev/bd-talentos-th/" class="navbar-item" target=”_blank”><span class="icon"> <i class="fa-solid fa-people-group"></i> </span>&nbsp;Funcionarios Gestion de Talentos&nbsp;</a>
            <a href="https://espiritusanto.cl/dev/bd-eva-th/" class="navbar-item" target=”_blank”><span class="icon"><i class="fa-solid fa-database"></i></span>&nbsp;BD Funcionarios Activos&nbsp;</a>
            <a href="https://espiritusanto.cl/dev/bd-preguntas/" class="navbar-item" target=”_blank”><span class="icon"><i class="fa-solid fa-database"></i></span>&nbsp;BD Preguntas EVA-2020&nbsp;</a>
            <hr class="navbar-divider">
            <label><strong>&nbsp;San Antonio</strong></label>
            <a href="https://espiritusanto.cl/dev/bd-talentos-sa/" class="navbar-item" target=”_blank”><span class="icon"> <i class="fa-solid fa-people-group"></i> </span>&nbsp;Funcionarios Gestion de Talentos&nbsp;</a>
            <a href="https://espiritusanto.cl/dev/bd-eva-sa/" class="navbar-item" target=”_blank”><span class="icon"><i class="fa-solid fa-database"></i></span>&nbsp;BD Funcionarios Activos&nbsp;</a>
        </div>
    
          </div> 
    </div>
    <div class="navbar-end">
    <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          <figure class="image is-24x24">
          <img class="is-rounded" src="https://api.dicebear.com/7.x/initials/svg?seed=<?php echo htmlspecialchars($_SESSION["username"]);?>">
          <!--<img class="is-rounded" src="https://avatars.dicebear.com/api/initials/<?php echo htmlspecialchars($_SESSION["username"]);?>.svg">-->
          </figure>
          <?php echo "&nbsp;".htmlspecialchars($_SESSION["username"]).""; ?>
        </a>

        <div class="navbar-dropdown is-boxed is-right">
          <a href="mi_avance.php" class="navbar-item"><span class="icon"><i class="fas fa-arrow-trend-up"></i></span>&nbsp;Mi Avance&nbsp;<span class="tag is-rounded is-success"><?php get_avance($rut);?></span></a>
          <a href="stats.php" class="navbar-item"><span class="icon"><i class="fas fa-chart-simple"></i></span>&nbsp;Mi Estadística&nbsp;<span class="tag is-rounded is-white"></span></a>
          <hr class="navbar-divider">
          <a href="logout.php" class="has-text-danger navbar-item"><span class="icon"><i class="fas fa-arrow-right-from-bracket"></i></span>&nbsp;Salir</a>
        </div>    
    
    
          </div>
      </div>
    </div>
  </div>
</nav>