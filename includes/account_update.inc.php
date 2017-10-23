<form action='<? echo $_SERVER['PHP_SELF']; ?>' method='POST' name='account_update_form' id='account_update_form' enctype='multipart/form-data'>
	<fieldset><legend>Registration</legend>
		<?php
			create_form_field('First Name:', 'text', 'first_name', 'first_name', ['maxlength'=>'30', 'size'=>'20', 'tabindex'=>'1', 'title'=>'Type in Your First Name Here', 'required'=>'required', 'pattern'=>'[A-Za-z]{2,20}', 'placeholder'=>'First Name'], $errors_array);
			create_form_field('Last Name:', 'text', 'last_name', 'last_name', ['maxlength'=>'30', 'size'=>'20', 'tabindex'=>'2', 'title'=>'Type in Your Last Name Here', 'required'=>'required', 'pattern'=>'[A-Za-z]{2,20}', 'placeholder'=>'Last Name'], $errors_array);
			create_form_field('Email:', 'email', 'email', 'email', ['maxlength'=>'40', 'size'=>'20', 'tabindex'=>'3', 'title'=>'Type in Your email Here', 'required'=>'required', 'placeholder'=>'email@you.com'], $errors_array);
			create_form_field('Phone:', 'tel', 'phone', 'phone', ['maxlength'=>'20', 'size'=>'20', 'tabindex'=>'4', 'title'=>'Type in Your Phone Number', 'placeholder'=>'(XXX)-XXX-XXXX'], $errors_array);
			create_form_field('Address 1:', 'text', 'address_1', 'address_1', ['maxlength'=>'100', 'size'=>'50', 'tabindex'=>'6', 'title'=>'Home Address', 'required'=>'required', 'pattern'=>'[A-Za-z0-9_\.\#\' \-:=]{2,100}', 'placeholder'=>'100 Market Street'], $errors_array);
			create_form_field('Address 2:', 'text', 'address_2', 'address_2', ['maxlength'=>'100', 'size'=>'50', 'tabindex'=>'7', 'title'=>'Home Address', 'pattern'=>'[A-Za-z0-9_\.\#\' \-:=]{0,100}', 'placeholder'=>'Suite #9'], $errors_array);
			create_form_field('City:', 'text', 'city', 'city', ['maxlength'=>'50', 'size'=>'20', 'tabindex'=>'8', 'title'=>'City', 'pattern'=>'[A-Za-z]{2,50}', 'placeholder'=>'Youngstown'], $errors_array);
			/***************** Create function call for state drop down menu ********************************/
			$select_states = "SELECT shirt_states_id, state, abbr from shirt_states";
			$exec_select_states = @mysqli_query($link, $select_states);
			if(!$exec_select_states){
				exit("The following error occurred: ".mysqli_error($link));
				mysqli_close($link);
			}else{
				$multi_array = array();
				while($one_record = mysqli_fetch_assoc($exec_select_states)){
					$multi_array[] = $one_record;
				}
				create_drop_down_from_query('State: ', 'shirt_states_id', 'shirt_states_id', $multi_array, ['tabindex'=>'9', 'title'=>'State'], $errors_array);
			}
			
			/***************** End function call for state drop down menu ********************************/
			
			create_form_field('Zip:', 'text', 'zip', 'zip', ['maxlength'=>'5', 'size'=>'5', 'tabindex'=>'10', 'title'=>'Zip Code', 'placeholder'=>'44555'], $errors_array);
			create_form_field('Date Created:', 'date', 'date_created', 'date_created', ['tabindex'=>'11', 'title'=>'Today Date', 'placeholder'=>'YYYY/MM/DD'], $errors_array);
		?>
	</fieldset>
	<fieldset>
	<p>
		<label>
			<input type='hidden' value='<?php echo $shirt_customers_id; ?>' name='shirt_customers_id' id='form_submitted' />
			<input type='submit' value='Update' />
			<!--<input type='image' src='./Penguins.jpg' name='penguins' id='penguins' />-->
			<input type='reset' value='Reset' />
		</label>
	</p>
	</fieldset>

</form>