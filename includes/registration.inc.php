<form action='<? echo $_SERVER['PHP_SELF']; ?>' method='POST' name='registration_form' id='registration_form' enctype='multipart/form-data'>
	<fieldset><legend>Registration</legend>
		<?php
			create_form_field('First Name:', 'text', 'first_name', 'first_name', ['required'=>'required', 'pattern'=>"^[a-zA-Z]{2,20}$", 'maxlength'=>'30', 'size'=>'20', 'tabindex'=>'1', 'title'=>'Type in Your First Name Here', 'placeholder'=>'First Name'], $errors_array);
			create_form_field('Last Name:', 'text', 'last_name', 'last_name', ['required'=>'required', 'pattern'=>"^[a-zA-Z]{0,20}$", 'maxlength'=>'30', 'size'=>'20', 'tabindex'=>'2', 'title'=>'Type in Your Last Name Here', 'placeholder'=>'Last Name'], $errors_array);
			create_form_field('Email:', 'email', 'email', 'email', ['required'=>'required', 'pattern'=>"^[a-zA-Z_][a-zA-Z0-9_.\-]+@[a-zA-Z]{2,}\.(com|org|edu|mil|biz)(\.[a-zA-Z]{2,3})?$", 'maxlength'=>'40', 'size'=>'20', 'tabindex'=>'3', 'title'=>'Type in Your email Here', 'placeholder'=>'email@you.com'], $errors_array);
			create_form_field('Phone:', 'tel', 'phone', 'phone', ['required'=>'required', 'pattern'=>"^\(?\d{3}[ )\-]{0,2}\d{3}[\- ]?\d{4}$", 'maxlength'=>'20', 'size'=>'20', 'tabindex'=>'4', 'title'=>'Type in Your Phone Number', 'placeholder'=>'(XXX)-XXX-XXXX'], $errors_array);
			create_form_field('Password:', 'password', 'password', 'password', ['required'=>'required', 'pattern'=>"^[0-9a-zA-Z]{6,20}$", 'maxlength'=>'15', 'size'=>'10', 'tabindex'=>'5', 'title'=>'Type in Your Password', 'placeholder'=>'********'], $errors_array);
			create_form_field('Address 1:', 'text', 'address_1', 'address_1', ['required'=>'required', 'pattern'=>"^[1-9][0-9]*[ ,]?[a-zA-Z0-9_.# ]+$", 'maxlength'=>'100', 'size'=>'50', 'tabindex'=>'6', 'title'=>'Home Address', 'placeholder'=>'100 Market Street'], $errors_array);
			create_form_field('Address 2:', 'text', 'address_2', 'address_2', ['pattern'=>"^([1-9][0-9]*[ ,]?[a-zA-Z0-9_.# ]+)?$", 'maxlength'=>'100', 'size'=>'50', 'tabindex'=>'7', 'title'=>'Home Address', 'placeholder'=>'Suite #9'], $errors_array);
			create_form_field('City:', 'text', 'city', 'city', ['required'=>'required', 'pattern'=>"^[a-zA-Z][a-zA-Z 0-9]{2,49}$", 'maxlength'=>'50', 'size'=>'20', 'tabindex'=>'8', 'title'=>'City', 'placeholder'=>'Youngstown'], $errors_array);
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
				create_drop_down_from_query('State: ', 'shirt_states_id', 'shirt_states_id', $multi_array, ['required'=>'required', 'pattern'=>"^[1-5][0-9]?$", 'tabindex'=>'9', 'title'=>'State'], $errors_array);
			}
			
			/***************** End function call for state drop down menu ********************************/
			
			create_form_field('Zip:', 'text', 'zip', 'zip', ['required'=>'required', 'pattern'=>"^[0-9]{5}([ -]\d{4})?$", 'maxlength'=>'5', 'size'=>'5', 'tabindex'=>'10', 'title'=>'Zip Code', 'placeholder'=>'44555'], $errors_array);
			create_form_field('Date Created:', 'date', 'date_created', 'date_created', ['required'=>'required', 'pattern'=>"^\d{4}[\-\/.][0-1][0-9][\-\/.][0-3][0-9]$", 'tabindex'=>'11', 'title'=>'Today Date', 'placeholder'=>'YYYY/MM/DD'], $errors_array);
		?>
	</fieldset>
	<fieldset>
	<p>
		<label>
			<input type='hidden' value='form_submitted' name='form_submitted' id='form_submitted' />
			<input type='submit' value='Submit' />
			<!--<input type='image' src='./Penguins.jpg' name='penguins' id='penguins' />-->
			<input type='reset' value='Reset' />
		</label>
	</p>
	</fieldset>

</form>