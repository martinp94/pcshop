<?php

require_once 'core/init.php';

$user = new User();
$data = $user->data();
$toEdit = '';
$min = '';
$max = '';

$errors = array();

if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		if(Input::get('telToAdd_provider') !== '' && Input::get('telToAdd_num') !== '') {
			$validate = new Validate();
		
			$validation = $validate->check($_POST, array(

				'telToAdd_provider' => array(
					'required' => true,
					'min' => 3,
					'max' => 3
				),
				'telToAdd_num' => array(
					'required' => true,
					'min' => 6,
					'max' => 8
				)

			));

			if($validation->passed()) {

				try {

					$errors[] = $user->data()->id . ' ' . Input::get('telToAdd_provider') . ' ' . Input::get('telToAdd_num');
					$user->addPhone(array(

						'user_id' => $user->data()->id,
						'provider' => Input::get('telToAdd_provider'),
						'num' => Input::get('telToAdd_num')

					));

					$errors[] = 'Uspješno dodat telefon';
					$_SESSION['errors'] = $errors;
					Redirect::to('index.php');
				} catch(Exception $e) {

					$errors[] = $e;
					$_SESSION['errors'] = $errors;
					Redirect::to('index.php');
				}
			} else {

				foreach ($validation->errors() as $error) {
					$errors[] = $error;
				}

				$_SESSION["errors"] = $errors;
				Redirect::to('index.php');
			}
		} 

		if(Input::get('f_name') !== ''){
			$toEdit = 'f_name';
			$min = '3';
			$max = '20';
		} else if (Input::get('l_name') !== ''){
			$toEdit = 'l_name';
			$min = '3';
			$max = '20';
		} else if (Input::get('adress') !== ''){
			$toEdit = 'adress';
			$min = '10';
			$max = '128';
		
		} else {
			Redirect::to('index.php?edit');
		}

		$validate = new Validate();
		
		$validation = $validate->check($_POST, array(

			$toEdit => array(
				'required' => true,
				'min' => $min,
				'max' => $max
			)

		));

		if($validation->passed()) {
			try{
				$user->update(array(
					$toEdit => Input::get($toEdit)
				));

				Redirect::to('index.php?edit');

			} catch (Exception $e) {
				$errors[] = $e;
				$_SESSION['errors'] = $errors;
				Redirect::to('index.php');
			}

		} else {


			foreach ($validation->errors() as $error) {
				$errors[] = $error;
			}

			$_SESSION["errors"] = $errors;
			Redirect::to('index.php');
		}
	} 
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
	          <img src="images/user-icon.png" class="img-fluid img-thumbnail " alt="profilna slika" >
	      <?php
	        }
	      ?>





	      <form action="imgupload.php?userimg" method="post" enctype="multipart/form-data">
	      		<input type="hidden" name="size" value="1000000" />
	      		<div>
	      			<input type="file" name="imageToUpload" />
	      		</div>
	      		<div>
	      			<button type="submit" class="btn btn-success">Promjeni</button>
	      		</div>
	      		<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	      </form>
    
   	    </div>

	    <div class="col-md-2">
	      <h3><?php echo escape($data->username); ?></h3>
		</div>

	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<form action="update.php" method="post">
				<table class="table table-dark table-hover">
  					<tbody>
					    <tr>
					      <th scope="row">Ime</th>
					      <td><input type="text" name="f_name" placeholder="<?php echo escape($data->f_name); ?>" /></td>
					      <td><button type="submit" class="btn btn-info">Izmjeni</button></td>
					      
					    </tr>

					    <tr>
					      <th scope="row">Prezime</th>
					      <td><input type="text" name="l_name" placeholder="<?php echo escape($data->l_name); ?>" /></td>
					      <td><button type="submit" class="btn btn-info">Izmjeni</button></td>
					      
					    </tr>
				   
					
						<tr>
					      <th scope="row">Adresa</th>
					      <td><input type="text" name="adress" placeholder="<?php echo escape($data->adress); ?>" /></td>
					      <td><button type="submit" class="btn btn-info">Izmjeni</button></td>
					      
					    </tr>

						<tr>
					    	<td><select name="telToAdd_provider">
					    		<option value="067">067</option>
					    		<option value="068">068</option>
					    		<option value="069">069</option>
					    	</select></td>
					    	<td><input type="text" class="tel" name="telToAdd_num" /></td>
					    	<td><button type="submit" class="btn btn-info">Dodaj telefon</button></td>
					    </tr>

				  	</tbody>
				</table>
					
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
			</form>
		</div>
	</div>
</div>


<script type="text/javascript">
	
	$(function(){
		$(".tel").mask('000-0000');
	});

</script>
