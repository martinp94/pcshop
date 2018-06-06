<?php

include_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
$data = null;

if($user->isLoggedIn()){

  if(!$user->hasPermission('Admin')) {

  	Redirect::to('index.php');
  }

		$query = $db->get('users',array('username', '!=', 'Administrator'));
    
    if($query){
			if($query->count()){
				
				$data = $query->results();
      }
		} else {
      Redirect::to('index.php');
    } 
}

?>

<div class="userslist">
	
	<table class="table table-hover table-dark">
    <thead>
      <tr>
        <th scope="col" class="text-center">Korisničko ime</th>
        <th scope="col" class="text-center">E-mail</th>
        <th scope="col" class="text-center">Ime</th>
        <th scope="col" class="text-center">Prezime</th>
        <th scope="col" class="text-center">Registrovan</th>
        <th scope="col" class="text-center"> Opcije </th>

      </tr>
    </thead>
    
    <tbody>
    	<?php 
    	if($data !== null){

    		foreach ($data as $value) {
    			
    	?>

    	<tr>
        <td><?php echo $value->username; ?></td>
        <td><?php echo $value->email; ?></td>
        <td><?php echo $value->f_name; ?></td>
        <td><?php echo $value->l_name; ?></td>
        <td><?php echo $value->joined; ?></td>
        <td><button class="btn btn-info" onclick="window.location='index.php?administracija&users=<?php echo $value->username; ?>'" style="text-decoration: none;"> Upravljaj </button></td>
        
      </tr>

    	<?php
    		}

    	}
    	?>
    </tbody>
    
</table>

</div>
