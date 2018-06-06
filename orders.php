<?php 

include_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
$shop = array();
$orders = array();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if($user->hasShop()) {
	if(!$user->shop()->isAccepted()) {
		Redirect::to('index.php');
	} else {
		$shop = $user->shop();
	}
} 

$params = array(
		0 => $shop->data()->id
	);
$orders = $db->query('SELECT ord.id, ord.order_time, ord.total, us.username, us.adress, t.provider, t.num
FROM orders ord LEFT JOIN users us
ON ord.user_id = us.id
LEFT JOIN tel t
ON us.id = t.user_id
WHERE t.provider IS NOT null AND t.id = 
(SELECT MIN(id) FROM tel WHERE user_id = us.id) AND ord.shop_id = ?
ORDER BY ord.id'
, $params)->results();


	foreach ($orders as $order) {
		
		$orderLines = $db->query('SELECT ol.quantity, ol.price , p.brand, p.model FROM order_line ol
									LEFT JOIN products p
									ON ol.product_id = p.id
									WHERE ol.order_id = ?'
									, array(0 => $order->id))->results();


?>
		<br>		

		<div class="container-fluid">
	
			<div class="row text-center">
				<div class="cart-header col-md-4 col-md-offset-1">
					<a href="index.php?profile=<?php echo $order->username; ?>"> <?php echo $order->username; ?> </a> 
					<span class="pull-right"><img src="images/korpa.png" class="img-responsive"></span>
				</div>

			</div>

			<div class="cart-details row text-center">
				<div class="col-md-12 ">
					<table class="table table-hover">
						<thead>
					      <tr>
					        <th>Brend</th>
					        <th>Model</th>
					        <th>Cijena</th>
					        <th>Količina</th>
					        <th></th>
					      </tr>
					    </thead>

					    <tbody>

					  	<?php
					  		foreach ($orderLines as $line) {
					  		
					  	?>

					      <tr id="product<?php echo $key; ?>">
					        <td><?php echo $line->brand; ?></td>
					        <td><?php echo $line->model; ?></td>
					        <td><?php echo number_format((float)$line->price, 2, ',', '') . ' €'; ?></td>
					        <td><?php echo $line->quantity; ?></td>
					      </tr>

					    <?php
					    	}
					    ?>
					      <tr>
					      	<td></td>
					      	<td style="padding-top: 19px;"><span class="glyphicon glyphicon-home"></span> 
					      		<?php echo $order->adress; ?>
					      	</td>
					      	<td style="padding-top: 19px;"><span class="glyphicon glyphicon-earphone"></span> <?php echo $order->provider . '-' . $order->num; ?></td>
					        <td style="padding-top: 19px;">Ukupno: <?php echo number_format((float)$order->total, 2, ',', '') . ' €'; ?></td>
					      </tr>

					    </tbody>
					</table>
				</div>
			</div>

				</div>

				<br>
<?php

	}


?>
