<?php
session_start();
$title = 'Login';
require('./includes/mysql.inc.php');
$errors_array = array();
require('./includes/functions.inc.php');
if(isset($_SESSION['shirt_customers_id']) && isset($_SESSION['full_name'])){
	redirect('You are already logged in', 'view_products.php', 1);
}else{
	if(isset($_POST['submitted'])){
		if(!empty($_POST['email'])&&filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$email = htmlspecialchars(add_slashes($_POST['email']));
		}else{
			$errors_array['email'] = "Please enter a valid email!";
		}
		
		if(!empty($_POST['password'])){
			$password = htmlspecialchars($_POST['password']);
		}else{
			$errors_array['password'] = "Please enter a valid password!";
		}
		
		if(count($errors_array) == 0){
			$sel_customer = "SELECT shirt_customers_id, password, concat(first_name,' ', last_name) as full_name from shirt_customers WHERE email = '$email'";
			$exec_sel_customer = @mysqli_query($link, $sel_customer);
			if(!$exec_sel_customer){
				rollback('An error occurred'.mysqli_error($link));
			}elseif(mysqli_num_rows($exec_sel_customer) > 1){
				rollback('There seem to be more than one customer with tshirt email. Please call our customer care.');
			}else{
				$one_record = mysqli_fetch_assoc($exec_sel_customer);
				$hashed_password = $one_record['password'];
				if(password_verify($password, $hashed_password)){
					//setcookie('shirt_customers_id', $one_record['shirt_customers_id'], time()+3600, '/', '', 0, 1);
					//setcookie('full_name', $one_record['full_name'], time()+3600, '/', '', 0, 1);
					$_SESSION['shirt_customers_id'] = $one_record['shirt_customers_id'];
					$_SESSION['full_name'] = $one_record['full_name'];
					redirect('Succesfull Login...', 'view_products.php', 1);
				}else{
					redirect('', 'login.php', 0);
				}
			}
		}
		
	}else{
		echo "<form action='{$_SERVER['PHP_SELF']}' method='POST' name='login_form' id='login_form'>";
		
		create_form_field('Email:', 'email', 'email', 'email', ['maxlength'=>'40', 'size'=>'20', 'tabindex'=>'3', 'title'=>'Type in Your email Here', 'required'=>'required', 'placeholder'=>'email@you.com'], $errors_array);
		create_form_field('Password:', 'password', 'password', 'password', ['maxlength'=>'15', 'size'=>'10', 'tabindex'=>'5', 'title'=>'Type in Your Password', 'required'=>'required', 'placeholder'=>'xxxxxxxx'], $errors_array);
		
		echo "<input type='hidden' value='form_submitted' name='submitted' id='form_submitted' />
			<input type='submit' value='Submit' />
			<input type='reset' value='Reset' />
		</form>
		<a href='registration.php'>Register</a>";
	}
}

?>