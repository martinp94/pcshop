<?php
	
	include_once 'core/init.php';

	$user = new User();
	$messenger = null;

	if($user->isLoggedIn()){
		$messenger = new Messenger($user->data()->id);

	} else {
		Redirect::to('index.php');
	}
?>

<div class="container-fluid">
	<div class="row text-center">
		<div class="col-md-3">

			<button class="btn btn-secondary btn-lg btn-block"  onclick="window.location.href='index.php?poruke&newmessage'">Nova poruka</button>

			<button class="btn btn-warning btn-lg btn-block" onclick="window.location.href='index.php?poruke&prijem'">Primljene</button>
			
			<button class="btn btn-primary btn-lg btn-block"  onclick="window.location.href='index.php?poruke&poslate'">Poslate</button>


		</div>


		<div class="col-md-9">
			<?php
				if(isset($_GET['prijem'])){
					include 'inbox.php';
				} else if (isset($_GET['poslate'])){
					include 'outbox.php';
				} else if (isset($_GET['newmessage'])){
					include 'newmessage.php';
				}
			?>
		</div>

	</div>


</div>









