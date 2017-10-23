<?php
include('./includes/password.php');

function create_form_field($label='', $type='text', $name='', $id='', $extras=array(), $errors_array){
	global $$name; //$first_name;
	if(!empty($$name)){
		$value = $$name; //$first_name, $last_name
		$value = stripslashes($value);
	}
	echo "<p>";
	if(count($errors_array) > 0 && !empty($errors_array[$name])) echo "<span style='color:red;'>{$errors_array[$name]}</span><br>";
	if(!empty($label)) echo "<label for='$id'>$label</label>";
	if(($type=='text') || ($type=='email') || ($type=='tel') || ($type=='url') || ($type=='password') || ($type=='date') || ($type=='color') || ($type=='number')){
		echo "<input type='$type' id='$id' name='$name'";
		if(!empty($value)) echo "value='$value'";
			if(count($extras) > 0){
				foreach($extras as $key=>$var){
					echo "$key='$var'";
				}
			}
		echo ">";
	}elseif($type=='textarea'){
		echo "<textarea id='$id' name='$name'";
			if(count($extras) > 0){
				foreach($extras as $key=>$var){
					echo "$key='$var'";
				}
			}
		echo ">";
		if(!empty($value)) echo "$value";
		echo "</textarea>";
	}
	echo "</p>";
}

function create_checkbox_radio_drop_down($label='', $type='text', $name, $id_data=array(), $errors_array = array()){
	global $$name;
	if(!empty($$name)) $value = $$name;
	echo "<p>";
	if(count($errors_array) > 0 && !empty($errors_array[$name])) echo "<span style='color:red;'>{$errors_array[$name]}</span><br>";
	if(!empty($label)) echo "<label>$label</label>";
	if($type == 'checkbox'){
		foreach($id_data as $id=>$data){
			echo "$data <input type='$type' name='$name"."[]'"." id='$id' value='$data'";
				if(isset($value) && in_array($data, $value)) echo "checked='checked'";
			echo ">";
		}
	}elseif($type=='radio'){
		foreach($id_data as $id=>$data){
			echo "$data <input type='$type' name='$name' id='$id' value='$data'";
				if(isset($value) && ($value == $data)) echo "checked='checked'";
			echo ">";
		}
	}elseif($type='select'){
		echo "<select name='$name' id='$name' size='1'>";
			foreach($id_data as $data=>$label){
				echo "<option value='$data'";
					if(isset($value) && ($value == $data)) echo "selected='selected'";
				echo ">$label</option>";
			}
		echo "</select>";
	}
	echo "</p>";
}

function create_drop_down_from_query($label='', $name='', $id='', $multi_array = array(), $extras=array(), $errors_array){
	global $$name; // $shirt_states_id
	if(!empty($$name)) $value = $$name;
	echo "<p>";
	if(count($errors_array) > 0 && !empty($errors_array[$name])) echo "<span style='color:red;'>{$errors_array[$name]}</span><br>";
	if(!empty($label)) echo "<label>$label</label>";
	echo "<select name='$name' id='$id' size='1'";
	if(count($extras) > 0){
		foreach($extras as $key=>$value){
			echo "$key='$value'";
		}
	}
	echo ">";
	foreach($multi_array as $ind=>$one_record){
		echo "<option value='".$one_record[$name]."'";
			if(isset($$name)&&($$name==$one_record[$name])) echo "selected='selected'";
		if($name == 'shirt_states_id') echo ">".$one_record['state']."</option>";
		if($name == 'shirt_carriers_methods_id') echo ">".$one_record['carrier']."&nbsp;=>&nbsp;".$one_record['method']."&nbsp;=>&nbsp;"."$".$one_record['fee']."</option>";
	}
	echo "</select></p>";
}

function add_slashes($data){
	global $link;
	if(get_magic_quotes_gpc()) $data = stripslashes($data);
	return mysqli_real_escape_string($link, $data);
}

function rollback($msg){
	global $link;
	mysqli_query($link, 'ROLLBACK');
	mysqli_close($link);
	exit($msg);
}

function redirect($msg, $url, $time){
	header("refresh:$time; url=$url");
	exit($msg);
}

function validate_input($key, $pattern, $input){
	global $errors_array;
	if(is_array($pattern)){
		foreach($pattern as $value){
			if(!preg_match($value, $input)){
				$errors_array[$key] = "Invalid!";
				return null;
			}
		}
		return $input;
	}elseif(!preg_match($pattern, $input)){
		$errors_array[$key] = "Invalid!";
		return null;
	}else{
		return htmlspecialchars(add_slashes($input));
	}
}

?>