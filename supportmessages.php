<?php

include_once 'core/init.php';

$user = new User();
$data = null;
$db = DB::getInstance();

if($user->isLoggedIn()) {
	if($user->hasPermission('Admin')) {

		if($db->leftJoin('support_questions','users', 'user_id')->count()){
			$data = $db->results();
		} else {
			Redirect::to('index.php');
		}


	} else {
		Redirect::to('index.php');
	}
} else {
	Redirect::to('index.php');
}

?>

<div class="container-fluid">
	<?php
		foreach ($data as $value) {
			if($value->username != 'Administrator') {
	?>	

	<div class="row well" style="margin-top: 30px;">
		<div class="col-md-3">
			<h4><a href="index.php?profile=<?php echo $value->username; ?>"><?php echo $value->username; ?></a></h4>

		</div>
		<div class="col-md-3">
			<h4><?php echo $value->date; ?></h4>
			
		</div>
		<div class="col-md-6">
			<p><?php echo $value->question; ?></p>
			
		</div>
	</div>

	<?php
		}
	}
	?>
	
</div>