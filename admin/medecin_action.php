<?php

//medecin_action.php

include('../class/RDV.php');

$object = new RDV;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('medecin_nom', 'medecin_status');

		$output = array();

		$main_query = "
		SELECT * FROM table_medecin ";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'WHERE medecin_email LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR medecin_nom LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR medecin_tel LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR medecin_dateNaiss LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR medecin_experience LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR medecin_specialite LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR medecin_status LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY medecin_id DESC ';
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
			$sub_array[] = '<img src="'.$row["medecin_image"].'" class="img-thumbnail" width="75" />';
			$sub_array[] = $row["medecin_email"];
			$sub_array[] = $row["medecin_password"];
			$sub_array[] = $row["medecin_nom"];
			$sub_array[] = $row["medecin_tel"];
			$sub_array[] = $row["medecin_specialite"];
			$status = '';
			if($row["medecin_status"] == 'Active')
			{
				$status = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["medecin_id"].'" data-status="'.$row["medecin_status"].'">Active</button>';
			}
			else
			{
				$status = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["medecin_id"].'" data-status="'.$row["medecin_status"].'">Inactive</button>';
			}
			$sub_array[] = $status;
			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["medecin_id"].'"><i class="fas fa-eye"></i></button>
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["medecin_id"].'"><i class="fas fa-edit"></i></button>
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["medecin_id"].'"><i class="fas fa-times"></i></button>
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

	if($_POST["action"] == 'Add')
	{
		$error = '';

		$success = '';

		$data = array(
			':medecin_email'	=>	$_POST["medecin_email"]
		);

		$object->query = "
		SELECT * FROM table_medecin 
		WHERE medecin_email = :medecin_email
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Adresse email déjà existe</div>';
		}
		else
		{
			$medecin_image = '';
			if($_FILES['medecin_image']['name'] != '')
			{
				$allowed_file_format = array("jpg", "png");

	    		$file_extension = pathinfo($_FILES["medecin_image"]["name"], PATHINFO_EXTENSION);

	    		if(!in_array($file_extension, $allowed_file_format))
			    {
			        $error = "<div class='alert alert-danger'>Les formats acceptés sont: jpg, png</div>";
			    }
			    else if (($_FILES["medecin_image"]["size"] > 2000000))
			    {
			       $error = "<div class='alert alert-danger'>Taille de fichier doit etre moins de 2MB</div>";
			    }
			    else
			    {
			    	$new_name = rand() . '.' . $file_extension;

					$destination = '../images/' . $new_name;

					move_uploaded_file($_FILES['medecin_image']['tmp_name'], $destination);

					$medecin_image = $destination;
			    }
			}
			else
			{
				$character = $_POST["medecin_nom"][0];
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
			    $medecin_image = $path;
			}

			if($error == '')
			{
				$data = array(
					':medecin_email'			=>	$object->clean_input($_POST["medecin_email"]),
					':medecin_password'				=>	$_POST["medecin_password"],
					':medecin_nom'					=>	$object->clean_input($_POST["medecin_nom"]),
					':medecin_image'			=>	$medecin_image,
					':medecin_tel'				=>	$object->clean_input($_POST["medecin_tel"]),
					':medecin_addresse'				=>	$object->clean_input($_POST["medecin_addresse"]),
					':medecin_dateNaiss'			=>	$object->clean_input($_POST["medecin_dateNaiss"]),
					':medecin_experience'				=>	$object->clean_input($_POST["medecin_experience"]),
					':medecin_specialite'				=>	$object->clean_input($_POST["medecin_specialite"]),
					':medecin_status'				=>	'Active',
					':medecin_date_ajout'				=>	$object->now
				);

				$object->query = "
				INSERT INTO table_medecin 
				(medecin_email, medecin_password, medecin_nom, medecin_image, medecin_tel, medecin_addresse, medecin_dateNaiss, medecin_experience, medecin_specialite, medecin_status, medecin_date_ajout) 
				VALUES (:medecin_email, :medecin_password, :medecin_nom, :medecin_image, :medecin_tel, :medecin_addresse, :medecin_dateNaiss, :medecin_experience, :medecin_specialite, :medecin_status, :medecin_date_ajout)
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">Médecin ajouté</div>';
			}
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM table_medecin 
		WHERE medecin_id = '".$_POST["medecin_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['medecin_email'] = $row['medecin_email'];
			$data['medecin_password'] = $row['medecin_password'];
			$data['medecin_nom'] = $row['medecin_nom'];
			$data['medecin_image'] = $row['medecin_image'];
			$data['medecin_tel'] = $row['medecin_tel'];
			$data['medecin_addresse'] = $row['medecin_addresse'];
			$data['medecin_dateNaiss'] = $row['medecin_dateNaiss'];
			$data['medecin_experience'] = $row['medecin_experience'];
			$data['medecin_specialite'] = $row['medecin_specialite'];
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$data = array(
			':medecin_email'	=>	$_POST["medecin_email"],
			':medecin_id'			=>	$_POST['hidden_id']
		);

		$object->query = "
		SELECT * FROM table_medecin 
		WHERE medecin_email = :medecin_email 
		AND medecin_id != :medecin_id
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Adresse email déjà existe</div>';
		}
		else
		{
			$medecin_image = $_POST["hidden_medecin_image"];

			if($_FILES['medecin_image']['name'] != '')
			{
				$allowed_file_format = array("jpg", "png");

	    		$file_extension = pathinfo($_FILES["medecin_image"]["name"], PATHINFO_EXTENSION);

	    		if(!in_array($file_extension, $allowed_file_format))
			    {
			        $error = "<div class='alert alert-danger'>Les formats acceptés sont: jpg, png</div>";
			    }
			    else if (($_FILES["medecin_image"]["size"] > 2000000))
			    {
			       $error = "<div class='alert alert-danger'>Taille de fichier doit etre moins de 2MB</div>";
			    }
			    else
			    {
			    	$new_name = rand() . '.' . $file_extension;

					$destination = '../images/' . $new_name;

					move_uploaded_file($_FILES['medecin_image']['tmp_name'], $destination);

					$medecin_image = $destination;
			    }
			}

			if($error == '')
			{
				$data = array(
					':medecin_email'			=>	$object->clean_input($_POST["medecin_email"]),
					':medecin_password'				=>	$_POST["medecin_password"],
					':medecin_nom'					=>	$object->clean_input($_POST["medecin_nom"]),
					':medecin_image'			=>	$medecin_image,
					':medecin_tel'				=>	$object->clean_input($_POST["medecin_tel"]),
					':medecin_addresse'				=>	$object->clean_input($_POST["medecin_addresse"]),
					':medecin_dateNaiss'			=>	$object->clean_input($_POST["medecin_dateNaiss"]),
					':medecin_experience'				=>	$object->clean_input($_POST["medecin_experience"]),
					':medecin_specialite'				=>	$object->clean_input($_POST["medecin_specialite"])
				);

				$object->query = "
				UPDATE table_medecin  
				SET medecin_email = :medecin_email, 
				medecin_password = :medecin_password, 
				medecin_nom = :medecin_nom, 
				medecin_image = :medecin_image, 
				medecin_tel = :medecin_tel, 
				medecin_addresse = :medecin_addresse, 
				medecin_dateNaiss = :medecin_dateNaiss, 
				medecin_experience = :medecin_experience,  
				medecin_specialite = :medecin_specialite 
				WHERE medecin_id = '".$_POST['hidden_id']."'
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">Données médecin modifiés</div>';
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
			':medecin_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE table_medecin 
		SET medecin_status = :medecin_status 
		WHERE medecin_id = '".$_POST["id"]."'
		";

		$object->execute($data);

		echo '<div class="alert alert-success">Status changé vers '.$_POST['next_status'].'</div>';
	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM table_medecin 
		WHERE medecin_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Données médecin supprimés</div>';
	}
}

?>