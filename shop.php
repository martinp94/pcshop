<?php

include_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
$shop = array();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if($user->hasShop()) {
	if(!$user->shop()->isAccepted()) {
		Redirect::to('index.php');
	}
} 

if(isset($_GET['shop']) && $_GET['shop'] != '') {
		
	$shop = $db->get('shops', array('url_name', '=', $_GET['shop']));

	if($shop->count()) {
		
		$shop = $shop->results()[0];
?>

<div class="container-fluid">
	<div class="row">
    	<div class="col-md-4">
    	  <?php
    	    if($shop->image !== null){

    	  ?>

         	 <img src="images/uploads/shop_images/<?php echo $shop->image; ?>" class="img-fluid img-thumbnail" alt="profilna slika" >

          <?php
            } else {
          ?>
              <img src="images/shop.png" class="img-fluid img-thumbnail " alt="profilna slika" >
          <?php
            }
          ?>
        </div>

        <div class="col-md-2">
          <h3><?php echo escape($shop->shop_name); ?></h3>
        </div>

        <div class="col-md-2 col-md-offset-3">
      		<a href="index.php?poruke&newmessage=<?php echo escape($shop->url_name); ?>"><img src="images/poruke.png" class="img-responsive" ></a>
    	</div>
    </div>

	<div class="row">
		<div class="col-md-12">
			<table class="table table-dark table-hover">
	  			<tbody>

					<tr>
					  <th scope="row">Naziv firme</th>
					  <td><?php echo escape($shop->shop_name); ?></td>
					</tr>

					<tr>
					  <th scope="row">Adresa</th>
					  <td><?php echo escape($shop->adress); ?></td>
					</tr>

					<tr>
					  <th scope="row">Pridružena</th>
					  <td colspan="2"><?php echo escape($shop->joined); ?></td>
					</tr>

					<tr>
					  <th scope="row">Telefoni</th>
					  <td colspan="2"></td>
					</tr>

				    <?php
				        $phones = $db->get('tel', array('shop_id', '=', $shop->id))->results();
				        if(!empty($phones)) {
				        	foreach ($phones as $telRow) {
				    ?>    
	          
			        <tr>
			              
			            <th scope="row"></th>
			            <td colspan="2"><?php echo $telRow->provider . '-' . $telRow->num; ?></td>
			              
			        </tr>
	    
	     			<?php         
	           				}
	        			}

	   			    ?>
	    		</tbody>
			</table>
		</div>
	</div>
</div>

<?php
	
	}

	die();
}

if($user->hasShop()){
	if($user->shop()->isAccepted()) {
		$shop = $user->shop();
?>


	<div class="container-fluid">
		<div class="row" >
			<div class="col-md-12">
				<div class="shopHead">
					<h1><?php echo $shop->data()->shop_name; ?></h1>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-3">
				
					<h4><strong><a href="?shop&info">Podaci</a> </strong></h4>
					<h4><strong><a href="?shop&products">Prikaži sve</a> </strong></h4>
					<?php
						if(Session::exists('productAddStep')) {
					?>
						<h4><strong><a href="?shop&addproduct" onclick="return false" style="color: grey;">Dodaj proizvod</a> </strong></h4>
					<?php
						} else {
					?>
						<h4><strong><a href="?shop&addproduct">Dodaj proizvod</a> </strong></h4>
					<?php
						}
					?>
						<h4><strong><a href="?shop&editproducts">Edituj proizvode</a> </strong></h4>
						<h4><strong><a href="?shop&orders"> Narudžbine </a> </strong></h4>
			</div>


		<div class="col-md-9">
			<?php
				if(isset($_GET['info'])) {
					include 'shopedit.php';

				} else if(isset($_GET['products'])) {
					include 'shopproducts.php';

				} else if(isset($_GET['addproduct'])) {
					include 'addproduct.php';

				}  else if(isset($_GET['editproducts'])) {
					include 'editproducts.php';
				} else if(isset($_GET['orders'])) {
					include 'orders.php';
				}

			?>
		</div>
		
		</div>
	
	</div>

<?php
	}
}
?>


