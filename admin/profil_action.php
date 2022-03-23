<?php

include('../class/RDV.php');

$object = new RDV;

if($_POST["action"] == 'doctor_profile')
{
	sleep(2);

	$error = '';

	$success = '';

	$doctor_profile_image = '';

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
		$doctor_profile_image = $_POST["hidden_medecin_image"];

		if($_FILES['medecin_image']['name'] != '')
		{
			$allowed_file_format = array("jpg", "png");

	    	$file_extension = pathinfo($_FILES["medecin_image"]["name"], PATHINFO_EXTENSION);

	    	if(!in_array($file_extension, $allowed_file_format))
		    {
		        $error = "<div class='alert alert-danger'>les format acceptés sont: jpg, png</div>";
		    }
		    else if (($_FILES["medecin_image"]["size"] > 2000000))
		    {
		       $error = "<div class='alert alert-danger'>Taille du fichier doit etre moins de 2MB</div>";
		    }
		    else
		    {
		    	$new_name = rand() . '.' . $file_extension;

				$destination = '../images/' . $new_name;

				move_uploaded_file($_FILES['medecin_image']['tmp_name'], $destination);

				$doctor_profile_image = $destination;
		    }
		}

		if($error == '')
		{
			$data = array(
				':medecin_email'			=>	$object->clean_input($_POST["medecin_email"]),
				':medecin_password'				=>	$_POST["medecin_password"],
				':medecin_nom'					=>	$object->clean_input($_POST["medecin_nom"]),
				':medecin_image'			=>	$doctor_profile_image,
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

			$success = '<div class="alert alert-success">Données médecin modifiées</div>';
		}			
	}

	$output = array(
		'error'					=>	$error,
		'success'				=>	$success,
		'medecin_email'	=>	$_POST["medecin_email"],
		'medecin_password'		=>	$_POST["medecin_password"],
		'medecin_nom'			=>	$_POST["medecin_nom"],
		'medecin_image'	=>	$doctor_profile_image,
		'medecin_tel'		=>	$_POST["medecin_tel"],
		'medecin_addresse'		=>	$_POST["medecin_addresse"],
		'medecin_dateNaiss'	=>	$_POST["medecin_dateNaiss"],
		'medecin_experience'			=>	$_POST["medecin_experience"],
		'medecin_specialite'		=>	$_POST["medecin_specialite"],
	);

	echo json_encode($output);
}

if($_POST["action"] == 'admin_profile')
{
	sleep(2);

	$error = '';

	$success = '';

	$hospital_logo = $_POST['hidden_hopital_logo'];

	if($_FILES['hopital_logo']['name'] != '')
	{
		$allowed_file_format = array("jpg", "png");

	    $file_extension = pathinfo($_FILES["hopital_logo"]["name"], PATHINFO_EXTENSION);

	    if(!in_array($file_extension, $allowed_file_format))
		{
		    $error = "<div class='alert alert-danger'>Les formats acceptés sont: jpg, png</div>";
		}
		else if (($_FILES["hopital_logo"]["size"] > 2000000))
		{
		   $error = "<div class='alert alert-danger'>Taille du fichier doit etre moins de 2MB</div>";
	    }
		else
		{
		    $new_name = rand() . '.' . $file_extension;

			$destination = '../images/' . $new_name;

			move_uploaded_file($_FILES['hopital_logo']['tmp_name'], $destination);

			$hospital_logo = $destination;
		}
	}

	if($error == '')
	{
		$data = array(
			':admin_email'			=>	$object->clean_input($_POST["admin_email"]),
			':admin_password'				=>	$_POST["admin_password"],
			':admin_nom'					=>	$object->clean_input($_POST["admin_nom"]),
			':hopital_nom'				=>	$object->clean_input($_POST["hopital_nom"]),
			':hopital_addresse'				=>	$object->clean_input($_POST["hopital_addresse"]),
			':hopital_contact_no'			=>	$object->clean_input($_POST["hopital_contact_no"]),
			':hopital_logo'				=>	$hospital_logo
		);

		$object->query = "
		UPDATE table_admin  
		SET admin_email = :admin_email, 
		admin_password = :admin_password, 
		admin_nom = :admin_nom, 
		hopital_nom = :hopital_nom, 
		hopital_addresse = :hopital_addresse, 
		hopital_contact_no = :hopital_contact_no, 
		hopital_logo = :hopital_logo 
		WHERE admin_id = '".$_SESSION["admin_id"]."'
		";
		$object->execute($data);

		$success = '<div class="alert alert-success">Données admin modifiées</div>';

		$output = array(
			'error'					=>	$error,
			'success'				=>	$success,
			'admin_email'	=>	$_POST["admin_email"],
			'admin_password'		=>	$_POST["admin_password"],
			'admin_nom'			=>	$_POST["admin_nom"], 
			'hopital_nom'			=>	$_POST["hopital_nom"],
			'hopital_addresse'		=>	$_POST["hopital_addresse"],
			'hopital_contact_no'	=>	$_POST["hopital_contact_no"],
			'hopital_logo'			=>	$hospital_logo
		);

		echo json_encode($output);
	}
	else
	{
		$output = array(
			'error'					=>	$error,
			'success'				=>	$success
		);
		echo json_encode($output);
	}
}

?>