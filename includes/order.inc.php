<form action='<? echo $_SERVER['PHP_SELF']; ?>' method='POST' name='order_form' id='order_form' enctype='multipart/form-data'>
	<fieldset><legend>Products</legend>
	<?php
	$toggle = isset($_GET['toggle'])?$_GET['toggle']:TRUE;
	$order_by = isset($_GET['order_by'])?$_GET['order_by']:'category';
	$asc_desc = ($toggle)?'ASC':'DESC';

	$select_producst = "SELECT shirts_id, category, description, size, keyword, code, price, stock_quantity, photo
		FROM shirts
		INNER JOIN shirt_categories USING (shirt_categories_id)
		INNER JOIN shirt_sizes USING (shirt_sizes_id)
		INNER JOIN shirt_colors USING (shirt_colors_id)
		ORDER BY $order_by $asc_desc";

	$exec_select_producst = @mysqli_query($link, $select_producst);
	if(!$exec_select_producst){
		rollback('Product info could not be retrieved becase '.mysqli_error($link));
	}elseif(mysqli_num_rows($exec_select_producst) > 0){
		echo "<table class='product_info_table'>
			<tr class='header'>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=category&toggle=".!$toggle."'>Category</a></th>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=description&toggle=".!$toggle."'>Description</a></th>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=size&toggle=".!$toggle."'>Size</a></th>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=keyword&toggle=".!$toggle."'>Color</a></th>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=price&toggle=".!$toggle."'>Price</a></th>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=stock_quantity&toggle=".!$toggle."'>Stock Quantity</a></th>
				<th>Photo</th>
				<th>Quantity</th>
			</tr>";
		while($one_record = mysqli_fetch_assoc($exec_select_producst)){
			$shirts_id = $one_record['shirts_id'];
			$price = $one_record['price'];
			$max = $one_record['stock_quantity'];
			echo "<tr>
				<td>{$one_record['category']}</td>
				<td>{$one_record['description']}</td>
				<td>{$one_record['size']}</td>
				<td style='background: {$one_record['code']}'>&nbsp;</td>
				<td>\${$one_record['price']}</td>
				<td>{$one_record['stock_quantity']}</td>
				<td><img src='{$one_record['photo']}'></td>
				<td><input type='number' name='quantity[$shirts_id][$price]' id='quantity' min='0' max='$max'";
					if(isset($quantity)&&!empty($quantity[$shirts_id][$price])) echo "value='{$quantity[$shirts_id][$price]}'";
				echo "></td></tr>";
		}
		echo "</table>";
		mysqli_free_result($exec_select_producst);
	}else{
		echo "No Product to Show";
	}
	?>
	</fieldset>	
	<fieldset><legend>Payment</legend>
		<?php create_checkbox_radio_drop_down('Credit Type: ', 'radio', 'credit_type', ['visa'=>'Visa', 'master'=>'Master', 'discover'=>'Discover'], $errors_array); ?>
		<?php create_form_field('Credit No: ', 'text', 'credit_no', 'credit_no', ['required'=>'required', 'pattern'=>"^[0-9]{16,24}$", 'maxlength'=>'20', 'size'=>'16', 'title'=>'Type in your credit no', 'placeholder'=>'XXXXXXXXXXXXXXXX'], $errors_array); ?>
	</fieldset>	
	<fieldset><legend>Shipping & Billing Address</legend>
	<?php
		$select_address = "SELECT address_1, address_2, city, shirt_states_id, zip from shirt_customers WHERE shirt_customers_id=$shirt_customers_id";
		$exec_select_address = @mysqli_query($link, $select_address);
		if(!$exec_select_address){
			rollback('The following error occurred.'.mysqli_error($link));
		}else{
			$one_record = mysqli_fetch_assoc($exec_select_address);
			$address_1 = $one_record['address_1'];
			$address_2 = $one_record['address_2'];
			$city = $one_record['city'];
			$shirt_states_id = $one_record['shirt_states_id'];
			$zip = $one_record['zip'];
		}
	
		create_form_field('Address 1:', 'text', 'address_1', 'address_1', ['required'=>'required', 'pattern'=>"^[1-9][0-9]*[ ,]?[a-zA-Z0-9_.# ]+$", 'maxlength'=>'100', 'size'=>'50', 'title'=>'Home Address', 'placeholder'=>'100 Market Street'], $errors_array);
		create_form_field('Address 2:', 'text', 'address_2', 'address_2', ['pattern'=>"^[1-9][0-9]*[ ,]?[a-zA-Z0-9_.# ]+$", 'maxlength'=>'100', 'size'=>'50', 'title'=>'Home Address', 'placeholder'=>'Suite #9'], $errors_array);
		create_form_field('City:', 'text', 'city', 'city', ['required'=>'required', 'pattern'=>"^[a-zA-Z][a-zA-Z 0-9]{2,49}$", 'maxlength'=>'50', 'size'=>'20', 'title'=>'City', 'placeholder'=>'Youngstown'], $errors_array);
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
			create_drop_down_from_query('State: ', 'shirt_states_id', 'shirt_states_id', $multi_array, ['required'=>'required', 'pattern'=>"^[1-5][0-9]?$", 'title'=>'State'], $errors_array);
		}
			
			/***************** End function call for state drop down menu ********************************/
			
			create_form_field('Zip:', 'text', 'zip', 'zip', ['required'=>'required', 'pattern'=>"^[0-9]{5}([ -]\d{4})?$", 'maxlength'=>'5', 'size'=>'5', 'title'=>'Zip Code', 'placeholder'=>'44555'], $errors_array);	
	?>
	</fieldset>		
	<fieldset><legend>Shipping Method</legend>	
		<?php
		$select_carriers_methods = "SELECT shirt_carriers_methods_id, carrier, method, fee from shirt_carriers_methods";
		$exec_select_carriers_methods = @mysqli_query($link, $select_carriers_methods);
		if(!$exec_select_carriers_methods){
			exit("The following error occurred: ".mysqli_error($link));
			mysqli_close($link);
		}else{
			$multi_array = array();
			while($one_record = mysqli_fetch_assoc($exec_select_carriers_methods)){
				$multi_array[] = $one_record;
			}
			create_drop_down_from_query('Shipping Method: ', 'shirt_carriers_methods_id', 'shirt_carriers_methods_id', $multi_array, ['required'=>'required', 'pattern'=>"^[0-9]{1,3}$", 'title'=>'Shipping Method'], $errors_array);
		}
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