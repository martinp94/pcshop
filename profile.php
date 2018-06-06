<?php

require_once 'core/init.php';
$user = new User();

if(!isset($_GET['profile'])){
  Redirect::to('index.php');
} 

$uName = $_GET['profile'];

if($user->find($uName)){
  $data = $user->data();
}else {
  Redirect::to('index.php');
}
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-4">
      <?php
        if($data->profile_image !== null){
      ?>
        <img src="images/uploads/profile_images/<?php echo $data->profile_image; ?>" class="img-fluid img-thumbnail" alt="profilna slika" >
      <?php
        } else {
      ?>
        <img src="images/user-icon.png" class="img " alt="profilna slika" >
      <?php
        }
      ?>
    </div>

    <div class="col-md-2">
      <h3><?php echo escape($data->username); ?></h3>
    </div>

    <div class="col-md-2">
      <a href="index.php?poruke&newmessage=<?php echo escape($data->username); ?>"><img src="images/poruke.png" class="img-responsive" ></a>
    </div>

  </div>
    
	<div class="row">
		<div class="col-md-12">
			<table class="table table-dark table-hover">
        <tbody>

          <tr>

            <th scope="row">Ime</th>
            <td><?php echo escape($data->f_name); ?></td>
            
          </tr>

          <tr>
            <th scope="row">Prezime</th>
            <td><?php echo escape($data->l_name); ?></td>
          </tr>

          <tr>

            <th scope="row">E-mail</th>
            <td colspan="2"><?php echo escape($data->email); ?></td>
          </tr>

          <tr>
            <th scope="row">Telefoni</th>
            <td colspan="2"></td>
          </tr>

          <?php
              $phones = $user->phoneNumbers();
              if(!empty($phones)) {
                foreach ($phones as $telRow) {
          ?>    
          
          <tr>
              
            <th scope="row"></th>
            <td colspan="2"><?php echo $telRow->provider . '-' . $telRow->num; ?></td>
              
          </tr>
    
          <?php         
                }
              }
          ?>
    
          <tr>
            <th scope="row">Nalog kreiran</th>
            <td colspan="2"><?php echo escape($data->joined); ?></td>
            
          </tr>

          <tr>
            <th scope="row">Adresa</th>
            <td colspan="2"><?php echo escape($data->adress); ?></td>
            
          </tr>
        </tbody>
      </table>
					
				
		</div>
	</div>
</div>

	


