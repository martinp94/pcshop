<?php

include_once 'core/init.php';

$user = new User();
$comments = null;
$db = DB::getInstance();

if(!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if(!$user->hasPermission('Admin')) {

		Redirect::to('index.php');
}

if(isset($_GET['users']) && $_GET['users'] !== ''){

	$username = $_GET['users'];

	if($user->find($username)) {
		if($user->data()->username === 'Administrator') {
			Redirect::to('index.php?administracija&users');
		}

	}

	$commentsSql = "SELECT c.id as 'comment_id', c.reply_to, c.product_id, c.text, c.date, u.id, u.username, u.profile_image FROM comments c LEFT JOIN users u ON c.user_id = u.id WHERE u.username = ?";

		$comments = $db->query($commentsSql, array($username));
		if($comments->count()) {
			$comments = $comments->results();
		} else {
			$comments = null;
		}

} else {
	Redirect::to('index.php');
}

?>

<div class="container-fluid" style="margin-top: 30px;">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-hover table-dark">
				<thead>
					 <th scope="col" class="text-center">Korisničko ime</th>
					 <th scope="col" colspan="2" class="text-center">Opcije</th>
				</thead>

				<tbody>

					<tr class="text-center">
						<td><?php echo $user->data()->username; ?></td>
						<?php if($user->isBan() && $user->ban()[0]->permanent == false){
							echo '<td><strong>Banovan do ' . $user->ban()[0]->expires . '</strong></td>';
						
						} else if ($user->isBan() && $user->ban()[0]->permanent == true) {
							echo '<td><strong class="text-danger"> BANOVAN TRAJNO </strong></td>';

						} else {
							echo '<td><a style="text-decoration: none;" class="text-danger" id="ban" href="#"> <strong>Ban</strong>  </a></td>';
						}

						?>
						
						<td><a style="text-decoration: none;" class="text-info" id="komentari" href="#"> <strong>Komentari</strong>  </a></td>
					</tr>

				</tbody>

			</table>
			
			
		</div>

		
	</div>

	<div class="row">
		<div class="col-md-12">
			
			<div id="banDiv" class="hidden">
				<form method="post" action="ban.php">
					<div class="form-group">
						<label for="bantime">Trajanje</label>
						<input type="number" name="bantime" /> dana
						<input type="submit" class="btn btn-success" name="submitbantime" value="Ban" />
					</div>

					<div>
						<input type="submit" class="btn btn-warning" name="submitpermanentban" value="Trajni ban" />
					</div>

					<input type="hidden" name="userid" value="<?php echo $user->data()->id; ?>" />
				</form>
				
			</div>
			
		</div>
	</div>

	<div class="row">
		<br>
		<br>
		<div class="col-md-12">
			
			<div id="komDiv" class="hidden">
				
				<?php 
					if($comments != null) {

						foreach ($comments as $comment) {
				?>
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-12" style="background-color: orange; color: white;">
										<strong><i><?php echo $comment->date; ?></i></strong>
										<strong class="pull-right"><a href="comment.php?delete=<?php echo $comment->comment_id; ?>" style="cursor: pointer;">  <span style="color:red;" class="glyphicon glyphicon-remove"></span> </a></strong>
									</div>
								</div>

								<div class="row">
									<div class="col-md-2 text-center" style="padding-bottom: 5px;">
										<br>
									 <a href="index.php?profile=<?php echo $comment->username; ?>"><?php echo $comment->username; ?></a>
										<img class="img-thumbnail img-responsive" src="images/uploads/profile_images/<?php echo $comment->profile_image; ?>">

									</div>

									<div class="col-md-10">
										<p><?php echo $comment->text; ?></p>
									</div>
								</div>
							</div>
				<?php
						}
					}
				?>


			</div>
			
		</div>
	</div>
	
</div>



<script type="text/javascript">
	

	$(function(){

		
		$("#ban").click(function(){
			$("#banDiv").removeClass("hidden");
		});

		$("#banno").click(function(){
			$("#banDiv").addClass("hidden");
		});

		$("#komentari").click(function(){
			$("#komDiv").removeClass("hidden");
		});
	});

</script>