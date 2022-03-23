<?php

//medecin_action.php

include('../class/RDV.php');

$object = new RDV;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('patient_prenom', 'patient_nom', 'patient_email', 'patient_tel', 'email_verification');

		$output = array();

		$main_query = "
		SELECT * FROM table_patient ";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'WHERE patient_prenom LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR patient_nom LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR patient_email LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR patient_tel LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR email_verification LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY patient_id DESC ';
		}

		$limit_query = '';

		if($_POST["length"] != -1)
		{
			$limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$object->query = $main_query . $search_query . $order_query;

		$object->execute();

		$filtered_rows = $object->row_count();

		$object->query .= $limit_query;

		$result = $object->get_result();

		$object->query = $main_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = $row["patient_prenom"];
			$sub_array[] = $row["patient_nom"];
			$sub_array[] = $row["patient_email"];
			$sub_array[] = $row["patient_tel"];
			$status = '';
			if($row["email_verification"] == 'Oui')
			{
				$status = '<span class="badge badge-success">Oui</span>';
			}
			else
			{
				$status = '<span class="badge badge-danger">Non</span>';
			}
			$sub_array[] = $status;
			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["patient_id"].'"><i class="fas fa-eye"></i></button>
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["patient_id"].'"><i class="fas fa-times"></i></button>
			</div>
			';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"    			=> 	intval($_POST["draw"]),
			"recordsTotal"  	=>  $total_rows,
			"recordsFiltered" 	=> 	$filtered_rows,
			"data"    			=> 	$data
		);
			
		echo json_encode($output);

	}

	/*if($_POST["action"] == 'Add')
	{
		$error = '';

		$success = '';

		$data = array(
			':doctor_email_address'	=>	$_POST["doctor_email_address"]
		);

		$object->query = "
		SELECT * FROM table_patient 
		WHERE doctor_email_address = :doctor_email_address
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
		}
		else
		{
			$doctor_profile_image = '';
			if($_FILES['doctor_profile_image']['name'] != '')
			{
				$allowed_file_format = array("jpg", "png");

	    		$file_extension = pathinfo($_FILES["doctor_profile_image"]["name"], PATHINFO_EXTENSION);

	    		if(!in_array($file_extension, $allowed_file_format))
			    {
			        $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
			    }
			    else if (($_FILES["doctor_profile_image"]["size"] > 2000000))
			    {
			       $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
			    }
			    else
			    {
			    	$new_name = rand() . '.' . $file_extension;

					$destination = '../images/' . $new_name;

					move_uploaded_file($_FILES['doctor_profile_image']['tmp_name'], $destination);

					$doctor_profile_image = $destination;
			    }
			}
			else
			{
				$character = $_POST["doctor_name"][0];
				$path = "../images/". time() . ".png";
				$image = imagecreate(200, 200);
				$red = rand(0, 255);
				$green = rand(0, 255);
				$blue = rand(0, 255);
			    imagecolorallocate($image, 230, 230, 230);  
			    $textcolor = imagecolorallocate($image, $red, $green, $blue);
			    imagettftext($image, 100, 0, 55, 150, $textcolor, '../font/arial.ttf', $character);
			    imagepng($image, $path);
			    imagedestroy($image);
			    $doctor_profile_image = $path;
			}

			if($error == '')
			{
				$data = array(
					':doctor_email_address'			=>	$object->clean_input($_POST["doctor_email_address"]),
					':doctor_password'				=>	$_POST["doctor_password"],
					':doctor_name'					=>	$object->clean_input($_POST["doctor_name"]),
					':doctor_profile_image'			=>	$doctor_profile_image,
					':doctor_phone_no'				=>	$object->clean_input($_POST["doctor_phone_no"]),
					':doctor_address'				=>	$object->clean_input($_POST["doctor_address"]),
					':doctor_date_of_birth'			=>	$object->clean_input($_POST["doctor_date_of_birth"]),
					':doctor_degree'				=>	$object->clean_input($_POST["doctor_degree"]),
					':doctor_expert_in'				=>	$object->clean_input($_POST["doctor_expert_in"]),
					':doctor_status'				=>	'Active',
					':doctor_added_on'				=>	$object->now
				);

				$object->query = "
				INSERT INTO doctor_table 
				(doctor_email_address, doctor_password, doctor_name, doctor_profile_image, doctor_phone_no, doctor_address, doctor_date_of_birth, doctor_degree, doctor_expert_in, doctor_status, doctor_added_on) 
				VALUES (:doctor_email_address, :doctor_password, :doctor_name, :doctor_profile_image, :doctor_phone_no, :doctor_address, :doctor_date_of_birth, :doctor_degree, :doctor_expert_in, :doctor_status, :doctor_added_on)
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">Doctor Added</div>';
			}
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}*/

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM table_patient 
		WHERE patient_id = '".$_POST["patient_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['patient_email'] = $row['patient_email'];
			$data['patient_password'] = $row['patient_password'];
			$data['patient_prenom'] = $row['patient_prenom'];
			$data['patient_nom'] = $row['patient_nom'];
			$data['patient_dateNaiss'] = $row['patient_dateNaiss'];
			$data['patient_sexe'] = $row['patient_sexe'];
			$data['patient_addresse'] = $row['patient_addresse'];
			$data['patient_tel'] = $row['patient_tel'];
			$data['patient_situation_fam'] = $row['patient_situation_fam'];
			if($row['email_verification'] == 'Oui')
			{
				$data['email_verification'] = '<span class="badge badge-success">Oui</span>';
			}
			else
			{
				$data['email_verification'] = '<span class="badge badge-danger">Non</span>';
			}
		}

		echo json_encode($data);
	}

	/*if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$data = array(
			':doctor_email_address'	=>	$_POST["doctor_email_address"],
			':doctor_id'			=>	$_POST['hidden_id']
		);

		$object->query = "
		SELECT * FROM doctor_table 
		WHERE doctor_email_address = :doctor_email_address 
		AND doctor_id != :doctor_id
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
		}
		else
		{
			$doctor_profile_image = $_POST["hidden_doctor_profile_image"];

			if($_FILES['doctor_profile_image']['name'] != '')
			{
				$allowed_file_format = array("jpg", "png");

	    		$file_extension = pathinfo($_FILES["doctor_profile_image"]["name"], PATHINFO_EXTENSION);

	    		if(!in_array($file_extension, $allowed_file_format))
			    {
			        $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
			    }
			    else if (($_FILES["doctor_profile_image"]["size"] > 2000000))
			    {
			       $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
			    }
			    else
			    {
			    	$new_name = rand() . '.' . $file_extension;

					$destination = '../images/' . $new_name;

					move_uploaded_file($_FILES['doctor_profile_image']['tmp_name'], $destination);

					$doctor_profile_image = $destination;
			    }
			}

			if($error == '')
			{
				$data = array(
					':doctor_email_address'			=>	$object->clean_input($_POST["doctor_email_address"]),
					':doctor_password'				=>	$_POST["doctor_password"],
					':doctor_name'					=>	$object->clean_input($_POST["doctor_name"]),
					':doctor_profile_image'			=>	$doctor_profile_image,
					':doctor_phone_no'				=>	$object->clean_input($_POST["doctor_phone_no"]),
					':doctor_address'				=>	$object->clean_input($_POST["doctor_address"]),
					':doctor_date_of_birth'			=>	$object->clean_input($_POST["doctor_date_of_birth"]),
					':doctor_degree'				=>	$object->clean_input($_POST["doctor_degree"]),
					':doctor_expert_in'				=>	$object->clean_input($_POST["doctor_expert_in"])
				);

				$object->query = "
				UPDATE doctor_table  
				SET doctor_email_address = :doctor_email_address, 
				doctor_password = :doctor_password, 
				doctor_name = :doctor_name, 
				doctor_profile_image = :doctor_profile_image, 
				doctor_phone_no = :doctor_phone_no, 
				doctor_address = :doctor_address, 
				doctor_date_of_birth = :doctor_date_of_birth, 
				doctor_degree = :doctor_degree,  
				doctor_expert_in = :doctor_expert_in 
				WHERE doctor_id = '".$_POST['hidden_id']."'
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">Doctor Data Updated</div>';
			}			
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'change_status')
	{
		$data = array(
			':doctor_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE doctor_table 
		SET doctor_status = :doctor_status 
		WHERE doctor_id = '".$_POST["id"]."'
		";

		$object->execute($data);

		echo '<div class="alert alert-success">Class Status change to '.$_POST['next_status'].'</div>';
	}*/

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM table_patient 
		WHERE patient_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Patient supprimé avec succès</div>';
	}
}

?>