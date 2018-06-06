<?php
require_once 'core/init.php';

$cart = array();

Session::put('cartItemCounter', 0);


if(Session::exists('cart')) {
  $cart = json_decode(Session::get('cart'), true);
  $cartItemCounter = 0;

  foreach ($cart as $shopItems) {
    foreach ($shopItems as $item) {
      $cartItemCounter++;
    }
  }

  Session::put('cartItemCounter', $cartItemCounter);
}

?>

<section id="headerSection">
<header>

<div class="container-fluid">
	
  <div class="row" id="header-img">
    <div class="col-md-12">
       <a href="index.php"> <img class="img img-responsive" src="images/header_top_background.png"> </a> 
    </div>
  </div>

	<div class="row" id="header-top">
		
    <div class="headerTopContainer col-md-1 col-md-offset-10">
      <a href="?poruke"><img class="img-thumbnail img-responsive" src="images/poruke.png"></a>

      <div class="top-right"><strong><span class="circle">
         0
      </span> </strong></div>

    </div>

		<div class="headerTopContainer col-md-1">
      <a href="?kupovnakorpa"><img class="img-thumbnail img-responsive" src="images/korpa.png"></a>
      <div class="top-right"><strong><span class="circle" id="cartItemCounter">
        <?php
           echo Session::get('cartItemCounter');
        ?> 
      </span> </strong></div>

    </div>
    
		

	</div>


	 
</div>
 


<nav class="navbar navbar-default navbar-fixed-top">
  <hr>
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
     
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-left">
        <li class="dropdown active">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> RAČUNARI <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="?proizvodi=desktop" id="desktopracunariAHref">DESKTOP RAČUNARI</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="?proizvodi=laptop" id="laptopracunariAHref">LAPTOP RAČUNARI</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="#" id="tabletracunariAHref">TABLET RAČUNARI</a></li>

            </ul>
          </li>
          <li><a href="?proizvodi=komponente">KOMPONENTE </a></li>
          
          <li><a href="?proizvodi=mobilni">MOBILNI TELEFONI</a></li>
          <li><a href="#">SASTAVI PC</a></li>
        
      </ul>

      
      <ul class="nav navbar-nav navbar-right">

    <?php
        $user = new User();
          if($user->isLoggedIn()){

          if($user->data()->activated == 1) {

            if($user->isBan()){


              Session::put('banned', true);
            }
    ?>

      

      <li class='dropdown'>
              <a href='profile.php' class='' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'> <?php echo  escape($user->data()->username) ?> <span class='caret'></span></a>
              <ul class='dropdown-menu'>
                <li><a href='?profile=<?php echo $user->data()->username?>' id='profileAHref'>Moj nalog</a></li>
                <li role='separator' class='divider'></li>
                
                <li><a href='?edit' id='editujNalogAHref'>Edituj nalog </a></li>
                <li role='separator' class='divider'></li>

                <li><a href='?changepassword' id='changepasswordAHref'>Promjeni lozinku</a></li>
                <li role='separator' class='divider'></li>

                <li><a href='logout.php' id='logoutAHref'>Izloguj se</a></li>
                

                

              </ul>
        </li>

  <?php
    } else {
  ?>

      <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Pretraga">
        </div>
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
      </form>

      <li class='dropdown'>
              <a href='profile.php' class='' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'> <?php echo  escape($user->data()->username) ?> <span class='caret'></span></a>
              <ul class='dropdown-menu'>
                <li><a href='logout.php' id='logoutAHref'>Izloguj se</a></li>
                <li role='separator' class='divider'></li>
                <li><a href='?activate' id='aktivirajNalogAHref'>Aktiviraj nalog </a></li>
                
    
              </ul>
      </li>


  <?php
    }

  

} else{

  ?>
  
  <!--'<p> Trebas se <a id="loginAHref" href="#"> ulogovati </a> ili <a href="signup.php">registrovati</a></p>' -->
  
       <form class="navbar-form navbar-left" action="loginH.php" method="post">
          <div class="form-group">
            <input name="usernameH" type="text" class="form-control input-sm" placeholder="Korisničko ime">
            <input name="passwordH" type="password" class="form-control input-sm" placeholder="Lozinka">
            <label for="rememberH"> Zapamti me</label>
             <input type="checkbox" name="rememberH" id="rememberH" />
          </div>
  
          <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
          <button type="submit" class="btn btn-default">Uloguj se</button>
  
          Nemate nalog? <a id="registerAHref" href="?registracija">Registrujte se</a>
        </form>

<?php
  }

  if($user->isLoggedIn()){

        if($user->data()->activated == 1) {
        if(!$user->hasPermission('Admin')) {
    
?>  
      
      
        <li><a href="?podrska">PODRŠKA</a></li>
        <?php  } ?>
        <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control input-sm" placeholder="Pretraga">
        </div>
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
      </form>


      <?php 
        if($user->hasPermission('Admin')) {
    
      ?>  
        <li><a href="index.php?administracija" style="color: red; font-weight: bold;">ADMINISTRACIJA</a></li>
      <?php
    } else if ($user->hasShop(1)){
      ?>

        <li><a href="index.php?shop&info" style="color: aquamarine; font-weight: bold;">PRODAVNICA</a></li>

      <?php
    }

  }
}
?>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

</header>
</section>

<script>
  
  $('.navbar-nav a').on( 'click', function () {
    $( '.navbar-nav' ).find( 'li.active' ).removeClass( 'active' );
    $( this ).parent( 'li' ).addClass( 'active' );
  });


</script>