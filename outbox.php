<?php

include_once 'core/init.php';

$user = new User();
$messenger = null;
$messages = array();
$db = null;

if($user->isLoggedIn()){
	$messenger = new Messenger($user->data()->id);
	$db = DB::getInstance();

} else {
	Redirect::to('index.php');
}

$messages = $db->leftJoin('messages', 'users', 'to_user', array('from_user', '=', $user->data()->id));

if($messages->count()){
	$messages = $messages->results();
?>

	<table class="table table-hover table-dark">
	<thead>
	<tr>
	  
	  <th>Za</th>
	  <th>Poruka</th>
	  <th>Datum</th>
	</tr>
	</thead>

	<tbody>

		<?php

		$cnt = 0;
		foreach ($messages as $message) {
		?>

		<tr>
		  
		  <td><a href="index.php?profile=<?php echo $message->username; ?>"> <?php echo $message->username; ?> </a> </td>
		  <td><?php echo substr($message->text, 0, 7) . '...'; ?>(<a id='showId<?php echo $cnt; ?>' href="#">pogledaj sve</a>)</td>
		  <td><?php echo $message->date; ?></td>
		</tr>

	   	<?php
	    		$cnt++;
	    	}
	    $cnt = 0;
	    ?>
	    
	</tbody>

	</table>

<?php
} else {

?>	
	<h2 class="text-danger">Nema poruka</h2>

<?php
}
?>

<script>
	
	$(function(){

		<?php
			$cnt = 0;
			foreach ($messages as $message) {

		?>
			$("#showId" + <?php echo $cnt; ?>).click(function(){

				$(this).parent().html('<?php echo $message->text; ?>');
			});
		<?php
				$cnt++;
			}

			$cnt = 0;
		?>
		
	});

</script>