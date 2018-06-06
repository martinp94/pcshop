<?php

include_once 'core/init.php';

$user = new User();
$shop = array();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if($user->hasShop() && !$user->shop->isAccepted()) {
	Redirect::to('index.php');
} else if(!$user->hasShop()){
	Redirect::to('index.php');
} else if($user->hasShop() && $user->shop->isAccepted()){
	$shop = $user->shop()[0];
}

?>


<div class="container-fluid">
	<div class="row" >
		<div class="col-md-12">
			<div class="shopHead">
				<h1><?php echo $shop->shop_name; ?></h1>
			</div>
		</div>
	</div>
	
	<div class="row">
		
		<div class="col-md-3">
			<div class="shopHead" >
				<h4><strong><a href="?shop&info">Podaci</a> </strong></h4>
			</div>

			<div class="shopHead">
				<h4><strong><a href="?shop&products">Proizvodi</a> </strong></h4>
			</div>
		</div>

		<div class="col-md-9">
			<?php

				if(isset($_GET['info'])) {
					include 'shopinfo.php';

				} else if(isset($_GET['products'])) {
					include 'products.php';
				} 
			?>
		</div>
	</div>
	
</div>

