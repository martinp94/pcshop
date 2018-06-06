<?php
require_once 'core/init.php';

$user = new User();


?>
	
<div id="pageInclude" class="container-fluid">

		<?php

			if(isset($_SESSION['banned'])){
				if($_SESSION['banned'] === true){
					if($user->isBan()){
			
						

						if($user->ban()[0]->permanent == 1) {

		?>
							<strong style="color: red;">Vaš nalog je trajno banovan!</strong>
		<?php		
						} else {

							$datetime1 = new DateTime(date('Y-m-d H:i:s'));
							$datetime2 = new DateTime($user->ban()[0]->expires);
							$interval = $datetime1->diff($datetime2);
						
							$i = $interval->format('%d');

							if($i > 0) {
								echo '<div class="text-center well"><strong style="color: red;">Banovani ste do: ' . substr($user->ban()[0]->expires, 0, 10) . '</strong></div>';
							} else {
								unset($_SESSION['banned']);
								$user->unban();
							}
						}
					}
				}
			} else {

				if(isset($_GET['profile'])) {

					include 'profile.php';
				}

				if(isset($_GET['registracija'])) {

					include 'signup.php';
				}

				if(isset($_GET['edit'])) {

					include 'update.php';
				}

				if(isset($_GET['changepassword'])) {

					include 'changepassword.php';
				}

				if(isset($_GET['activate'])) {
					include 'activation.php';
				}

				if(isset($_GET['administracija'])) {
					include 'administration.php';
				}

				if(isset($_GET['podrska'])) {
					include 'usersupport.php';
				}

				if(isset($_GET['prodavnica'])) {
					include 'shopform.php';
				}

				if(isset($_GET['shop'])) {
					include 'shop.php';
				}

				if(isset($_GET['poruke'])) {
					include 'messages.php';
				}

				if(isset($_GET['login'])) {
					include 'login.php';
				}

				if(isset($_GET['proizvodi'])) {
					include 'listproducts.php';
				}

				if(isset($_GET['proizvod'])) {
					include 'product.php';
				}

				if(isset($_GET['kupovnakorpa'])) {
					include 'checkout.php';
				}




				
				if(Session::exists('accountCreated')) {
					Session::flash('accountCreated');

			  	 include 'activation.php';

				}

			}

			
	?>


</div>
