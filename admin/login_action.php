<?php

//login_action.php

include('../class/RDV.php');

$object = new RDV;

if(isset($_POST["admin_email"]))
{
	sleep(2);
	$error = '';
	$url = '';
	$data = array(
		':admin_email'	=>	$_POST["admin_email"]
	);

	$object->query = "
		SELECT * FROM table_admin 
		WHERE admin_email = :admin_email
	";

	$object->execute($data);

	$total_row = $object->row_count();

	if($total_row == 0)
	{
		$object->query = "
			SELECT * FROM table_medecin 
			WHERE medecin_email = :admin_email
		";
		$object->execute($data);

		if($object->row_count() == 0)
		{
			$error = '<div class="alert alert-danger">Adresse email incorrecte</div>';
		}
		else
		{
			$result = $object->statement_result();

			foreach($result as $row)
			{
				if($row["medecin_status"] == 'Inactive')
				{
					$error = '<div class="alert alert-danger">Votre compte est inactive, merci de contactez administarteur</div>';
				}
				else
				{
					if($_POST["admin_password"] == $row["medecin_password"])
					{
						$_SESSION['admin_id'] = $row['medecin_id'];
						$_SESSION['type'] = 'Doctor';
						$url = $object->base_url . 'admin/horaire.php';
					}
					else
					{
						$error = '<div class="alert alert-danger">Mot de passe incorrecte</div>';
					}
				}
			}
		}
	}
	else
	{
		//$result = $statement->fetchAll();

		$result = $object->statement_result();

		foreach($result as $row)
		{
			if($_POST["admin_password"] == $row["admin_password"])
			{
				$_SESSION['admin_id'] = $row['admin_id'];
				$_SESSION['type'] = 'Admin';
				$url = $object->base_url . 'admin/tbl_bord.php';
			}
			else
			{
				$error = '<div class="alert alert-danger">Mot de passe incorrecte</div>';
			}
		}
	}

	$output = array(
		'error'		=>	$error,
		'url'		=>	$url
	);

	echo json_encode($output);
}

?>