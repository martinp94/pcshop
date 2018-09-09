<?php
require_once 'core/init.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ITPRODAJAONLINE</title>
	<link rel="icon" href="favicon.png" type="image/png" sizes="16x16">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet"> 
	<script src="plugins/jQuery-Mask-Plugin-master/src/jquery.mask.js"></script>
</head>
<body>

<div class="wrapper">

	<div id="headerInclude">
		
	<?php include 'header.php'; ?>
	</div>
	
	<div class="container-fluid">
		<div id="main" class="row">
			<?php include 'maincontent.php'; ?>
		</div>
	</div>
</div>  <!-- wrapper div end -->

<div id="footerInclude"> <?php include 'footer.php'; ?>
</div>


</body>
</html>