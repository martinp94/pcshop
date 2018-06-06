<?php

include_once 'core/init.php';

$user = new User();

if($user->isLoggedIn()){

	if(!$user->hasPermission('Admin')) {

		Redirect::to('index.php');
	}

} else {
	Redirect::to('index.php');
}

?>

<div class="container-fluid">
	<div class="row" >
		<div class="col-md-12">
			<div class="administrationHead">
				<h1>ADMINISTRACIJA</h1>
			</div>
		</div>
	</div>
	
	<div class="row text-center">
		<div class="col-md-3">
			<br>
			<ul class="nav navbar-nav">
				<li class="dropdown active">

					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <h4> <strong> Korisnici</strong>  <span class="caret"></span></h4></a>

					<ul class="dropdown-menu">
						<li><h4><strong><a href="?administracija&users">Upravljanje</a> </strong></h4></li>
						<hr>
						<li><h4><strong><a href="?administracija&support">Korisnička podrška</a> </strong></h4></li>
						<hr>
						<li><h4><strong><a href="?administracija&bans">Banovani korisnici</a> </strong></h4></li>
						<hr>
						<li><h4><strong><a href="?administracija&reports"> Prijave korisnika</a> </strong></h4></li>
						

					</ul>
				</li>

			</ul>	
			
				
				
				<hr>
				<h4><strong><a href="?administracija&shops">Prodavnice </a> </strong></h4>
				<hr>
				<h4><strong><a href="?administracija&news"> Novosti </a> </strong></h4>
			
			
		</div>

		<div class="col-md-9">
			<?php
				if(isset($_GET['users']) && $_GET['users'] === '') {
					include 'userslist.php';
				
				} else if (isset($_GET['users']) && $_GET['users'] !== ''){
					include 'edituser.php';

				} else if(isset($_GET['bans'])) {
					include 'banslist.php';

				} else if(isset($_GET['support'])) {
					include 'supportmessages.php';
					
				} else if(isset($_GET['shops'])) {
					include 'shoplist.php';

				} else if(isset($_GET['news'])) {
					include 'newsadd.php';
				} 
			?>
		</div>
	</div>
</div>