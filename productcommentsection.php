
<br>
<br>
<hr>
<br>
<br>

<?php

$commentsSql = "SELECT c.id as 'comment_id', c.reply_to, c.product_id, c.text, c.date, u.id, u.username, u.profile_image FROM comments c LEFT JOIN users u ON c.user_id = u.id WHERE c.product_id = ? 
				AND c.reply_to IS NULL ORDER BY c.date DESC";

			$comments = $db->query($commentsSql, array($_GET['proizvod']));
			if($comments->count()) {
				$comments = $comments->results();
			} else {
				$comments = null;
			}

if($comments != null) {
	
	foreach ($comments as $comment) {
?>	
		<div class="container-fluid comment">
			
			<div class="row">
				<div class="col-md-12" style="background-color: orange; color: white;">
					<strong><i><?php echo $comment->date; ?></i></strong>
					<?php
						if($user->data()->username == 'Administrator') {
					?>
					<strong class="pull-right"><a href="comment.php?delete=<?php echo $comment->comment_id; ?>" style="cursor: pointer;">  <span style="color:red;" class="glyphicon glyphicon-remove"></span> </a></strong>
					<?php
						}
					?>
					<strong class="pull-right"><a id="reply<?php echo $comment->comment_id; ?>" style="text-decoration: none; cursor: pointer;">  Reply </a></strong>
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

			<?php 

			$repliesSql = "SELECT c.id as 'comment_id', c.reply_to, c.product_id, c.text, c.date, u.id, u.username, u.profile_image FROM comments c LEFT JOIN users u ON c.user_id = u.id WHERE c.reply_to = ?";

			$replies = $db->query($repliesSql, array($comment->comment_id));
			if($replies->count()) {
				$replies = $replies->results();

				foreach ($replies as $reply) {
			?>

				<div class="row">
					
					<div class="col-md-5 col-md-offset-7" style="background-color: orange; color: white;">
						<strong><i><?php echo $reply->date; ?></i></strong>
						<?php
						if($user->data()->username == 'Administrator') {
						?>
							<strong class="pull-right"><a href="comment.php?delete=<?php echo $reply->comment_id; ?>" style="cursor: pointer;">  <span style="color:red;" class="glyphicon glyphicon-remove"></span> </a></strong>
						<?php
							}
						?>
					</div>
				</div>

				<div class="row">
					<div class="col-md-2 col-md-offset-7 text-center" style="padding-bottom: 5px;">
						<br>
					 <a href="index.php?profile=<?php echo $reply->username; ?>"><?php echo $reply->username; ?></a>
						<img class="img-thumbnail img-responsive" src="images/uploads/profile_images/<?php echo $reply->profile_image; ?>">

					</div>
					<div class="col-md-3">
						<p><?php echo $reply->text; ?></p>
					</div>
				</div>

			<?php
				}
			} 

			?>

			<div class="row">
				<div class="hidden" id="replydiv<?php echo $comment->comment_id; ?>">
					<div class="col-md-12">
						<form method="post" action="comment.php">


							<div class="form-group pull-right">
									<textarea name="text" style="resize: none;" rows="6" cols="50"></textarea>
									<br>


									<button type="submit" name="submitreply" class="btn btn-primary pull-right">Odgovori</button>
								
							</div>

							<input type="hidden" name="pid" value="<?php echo $_GET['proizvod']; ?>" />
							<input type="hidden" name="to_comment" value="<?php echo $comment->comment_id; ?>" />
							

							
							
						</form>
					</div>
					
				</div>
			</div>
		</div>
		
			
		
		<br>
		<br>
	<?php
		}
	
} else {
	echo '<strong> Nema komentara </strong> <br> <br> <hr>';
}

if($commentEnabled == true){
?>

<div class="container-fluid">

	<div class="row">
		<div class="col-md-12">
			<form method="post" action="comment.php">


				<div class="form-group row">
					<div class="col-md-12">
						<textarea name="text" style="resize: none;" class="form-control"></textarea>
					</div>
						
				</div>

				<div class="form-group row">
					<div class="col-md-3 col-md-offset-9">
						<button type="submit" name="submitcomment" class="btn btn-primary form-control">Komentariši</button>
					</div>
					
				</div>

				<input type="hidden" name="pid" value="<?php echo $_GET['proizvod']; ?>" />
				<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
				
			</form>
		</div>
	</div>
	
</div>

<?php
}
?>
