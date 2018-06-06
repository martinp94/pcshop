<?php

include_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
$errors = array();
$data = null;

if($user->isLoggedIn()) {
	if(!$user->hasPermission('Admin')) {
		Redirect::to('index.php');
	}
		
	$query = $db->leftJoin('shops','users','user_id');
	
	if($query){
		if($query->count()){
			
			$data = $query->results();

		} else {
			$errors[] = 'Nema rezultata';
			$_SESSION['errors'] = $errors;
		}
	} else{
		$errors[] = 'Nema rezultata';
		$_SESSION['errors'] = $errors;
	}

	if(isset($_GET['accept'])) {
		if($_GET['accept'] != ''){
			
			$id = 0;
			$query = $db->get('shops', array('mbp','=',$_GET['accept']));

			if($query->count()) {

				$id = $query->results()[0]->id;

				$fields = array(
					'accepted' => true
				);

				if($db->update('shops',$id, $fields)) {
					Redirect::to('index.php?administracija&shops');
				} else {
					Redirect::to('index.php');
				}

			} else {
				Redirect::to('index.php');
			}


				
				
		}
	}

	if(isset($_GET['deactivate'])) {
		if($_GET['deactivate'] != ''){
			
			$id = 0;
			$query = $db->get('shops', array('mbp','=',$_GET['deactivate']));

			if($query->count()) {
				$id = $query->results()[0]->id;

				$fields = array(
					'accepted' => false
				);

				if($db->update('shops',$id, $fields)) {
					Redirect::to('index.php?administracija&shops');
				} else {
					Redirect::to('index.php');
				}
			} else {
				Redirect::to('index.php');
			}


			
			
		}
	}
}

?>



<div class="userslist">
	<table class="table table-hover table-dark">
		<thead>
			<tr>
			  <th scope="col" class="text-center">Naziv firme</th>
			  <th scope="col" class="text-center">Korisnik</th>
			  <th scope="col" class="text-center">Matični broj</th>
			  <th scope="col" class="text-center">PIB</th>
			  <th scope="col" class="text-center"> Prijavljena </th>

			</tr>
		</thead>
  		
  		<tbody>

		  	<?php 
		  	if($data !== null){

		  		foreach ($data as $value) {
		  			if($value->accepted == false){


		  	?>

			<tr>

			<td><?php echo $value->shop_name; ?></td>
			<td><?php echo $value->username; ?></td>
			<td><?php echo $value->mbp; ?></td>
			<td><?php echo $value->pib; ?></td>
			<td><?php echo $value->joined; ?></td>
			<td><button class="btn btn-success" onclick="window.location='index.php?administracija&shops&accept=<?php echo $value->mbp; ?>'"> Prihvati </button></td>

			</tr>

		  	<?php
		  			} else {
		  	?>

		  	<tr>
		  	  <td><?php echo $value->shop_name; ?></td>
		      <td><?php echo $value->username; ?></td>
		      <td><?php echo $value->mbp; ?></td>
		      <td><?php echo $value->pib; ?></td>
		      <td><?php echo $value->joined; ?></td>
		      <td><button class="btn btn-danger" onclick="window.location='index.php?administracija&shops&deactivate=<?php echo $value->mbp; ?>'"> Deaktiviraj </button></td>
		    </tr>
		  	<?php
		  			}
		  		}

		  	}
		  	?>

		</tbody>
	</table>

</div>
