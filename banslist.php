<?php 

include_once 'core/init.php';

$db = DB::getInstance();
$data = null;

if($user->isLoggedIn()){
	if(!$user->hasPermission('Admin')) {

		Redirect::to('index.php');
	} 

		$query = $db->leftJoin('users_bans','users','user_id');
    
    if($query){
      if($query->count()){
				$data = $query->results();
      } 
		} 

    if(isset($_GET['bans']) && $_GET['bans'] !== '') {
			$user = new User();

			$username = $_GET['bans'];
			if($user->find($username)) {
				$user->unban();
			}

		}
}

?>

<div class="userslist">
	
<table class="table table-hover table-dark">
  <thead>
    <tr>
      <th scope="col" class="text-center">Korisničko ime</th>
      <th scope="col" class="text-center">Banovan</th>
      <th scope="col" class="text-center">Istice</th>
      <th scope="col" class="text-center"> Opcije </th>

    </tr>
  </thead>

  <tbody>

  	<?php 
  	if($data !== null){
      foreach ($data as $value) {
  			if($value->expires == null && $value->permanent == 1){
    ?>

  	<tr>
      <td><?php echo $value->username; ?></td>
      <td><?php echo $value->ban_date; ?> </td>
      <td> trajno </td>	
      <td> <strong class="text-danger">TRAJNI BAN</strong> </td>	
    </tr>

  	<?php
  			} else {
    ?>
		<tr>
      <td><?php echo $value->username; ?></td>
      <td><?php echo $value->ban_date; ?> </td>
      <td> <?php echo $value->expires; ?> </td>	
      <td><button class="btn btn-info" onclick="window.location='index.php?administracija&bans=<?php echo $value->username; ?>'" style="text-decoration: none;"> Ukloni ban </button></td>
    </tr>
  	<?php
    			}
    		}

    	}
    ?>
  </tbody>

</table>

</div>
