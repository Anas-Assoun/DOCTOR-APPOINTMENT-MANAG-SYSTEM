	<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  		<!-- Brand -->
  		<a class="navbar-brand" href="#"><?php echo $_SESSION['patient_name']; ?></a>

  		<!-- Links -->
	  	<ul class="navbar-nav">
	    	<li class="nav-item">
	      		<a class="nav-link" href="profil.php">Profil</a>
	    	</li>
	    	<li class="nav-item">
	      		<a class="nav-link" href="tbl_bord.php">Prendre rendez-vous</a>
	    	</li>
	    	<li class="nav-item">
	      		<a class="nav-link" href="rdv.php">Mes rendez-vous</a>
	    	</li>
	    	<li class="nav-item">
	      		<a class="nav-link" href="logout.php">Se déconnecter</a>
	    	</li>
	  	</ul>
	</nav>