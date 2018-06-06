<?php
require_once 'core/init.php';

$user = new User();
$db = DB::getInstance();
$news = null;

$newsSql = "SELECT * FROM news";
if(!$db->query($newsSql)->error()) {
	$news = $db->query($newsSql)->results();
} else {
	$news = null;
}



?>

<div class="text-center">
	<h3>NOVOSTI</h3>
</div>

<div class="container-fluid">

<?php
	if($news == null) {
		echo '<div class="text-center">Nema novosti</div>';
		die();
	}

	foreach ($news as $n) {
?>	
		<div class="newsDiv">
			<div class="row text-center">
				<div class="col-md-12">
					<h4><strong><?php echo $n->naslov; ?></strong></h4>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<p class="newsText"><?php echo $n->text; ?></p>
				</div>
			</div>
		</div>

		<br>
<?php
	}
?>
	
</div>

<script>

$(function(){

	$('.newsText').each(function(index, obj) {
		if($(this).text().length > 200){

			var textOld = $(this).text();

			var text = $(this).text();
			text.replace(/[^\w\s]|_/g, function ($1) { return ' ' + $1 + ' ';}).replace(/[ ]+/g, ' ').split(' ');
			text = text.slice(0, 200);

			var a = document.createElement('span');
			var linkText = document.createTextNode(" (pročitaj više...)");
			a.appendChild(linkText);

			$(a).css({

				'color' : 'blue',
				'cursor' : 'pointer'
			});

			$(a).click(function() {
				$(this).text(textOld).css({'color' : '', 'cursor' : ''});
			});

			$(this).text(text);
			$(this).append(a);
		}
	  
	});

	
});

</script>