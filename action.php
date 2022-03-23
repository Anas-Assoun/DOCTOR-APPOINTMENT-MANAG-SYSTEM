<?php

//action.php

include('class/RDV.php');

$object = new RDV;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'check_login')
	{
		if(isset($_SESSION['patient_id']))
		{
			echo 'tbl_bord.php';
		}
		else
		{
			echo 'login.php';
		}
	}

	if($_POST['action'] == 'patient_register')
	{
		$error = '';

		$success = '';

		$data = array(
			':patient_email'	=>	$_POST["patient_email"]
		);

		$object->query = "
		SELECT * FROM table_patient
		WHERE patient_email = :patient_email
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Cette addresse email déjà Existe</div>';
		}
		else
		{
			$patient_verification_code = md5(uniqid());
			$data = array(
				':patient_email'		=>	$object->clean_input($_POST["patient_email"]),
				':patient_password'				=>	$_POST["patient_password"],
				':patient_prenom'			=>	$object->clean_input($_POST["patient_prenom"]),
				':patient_nom'			=>	$object->clean_input($_POST["patient_nom"]),
				':patient_dateNaiss'		=>	$object->clean_input($_POST["patient_dateNaiss"]),
				':patient_sexe'				=>	$object->clean_input($_POST["patient_sexe"]),
				':patient_addresse'				=>	$object->clean_input($_POST["patient_addresse"]),
				':patient_tel'				=>	$object->clean_input($_POST["patient_tel"]),
				':patient_situation_fam'		=>	$object->clean_input($_POST["patient_situation_fam"]),
				':patient_date_ajout'				=>	$object->now,
				':patient_verification_code'	=>	$patient_verification_code,
				':email_verification'					=>	'Oui'
			);

			$object->query = "
			INSERT INTO table_patient
			(patient_email, patient_password, patient_prenom, patient_nom, patient_dateNaiss, patient_sexe, patient_addresse, patient_tel, patient_situation_fam, patient_date_ajout, patient_verification_code, email_verification) 
			VALUES (:patient_email, :patient_password, :patient_prenom, :patient_nom, :patient_dateNaiss, :patient_sexe, :patient_addresse, :patient_tel, :patient_situation_fam, :patient_date_ajout, :patient_verification_code, :email_verification)
			";

			$object->execute($data);

			require 'class/class.phpmailer.php';
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->Host = 'smtpout.secureserver.net';
			$mail->Port = '80';
			$mail->SMTPAuth = true;
			$mail->Username = 'xxxxx';
			$mail->Password = 'xxxxx';
			$mail->SMTPSecure = '';
			$mail->From = 'hopitalachifaa@gmail.com';
			$mail->FromName = 'Hopital Achifaa';
			$mail->AddAddress($_POST["patient_email"]);
			$mail->WordWrap = 50;
			$mail->IsHTML(true);
			$mail->Subject = 'Code de verification de votre adresse email';

			$message_body = '
			<p>Pour verifier votre adresse email cliquez sur ce lien <a href="'.$object->base_url.'verify.php?code='.$patient_verification_code.'"><b>link</b></a>.</p>
			<p>Sincerely,</p>
			<p>Webslesson.info</p>
			';
			$mail->Body = $message_body;

			if($mail->Send())
			{
				$success = '<div class="alert alert-success">Veuillez consultez votre boite mail pour verifier votre adresse</div>';
			}
			else
			{
				$error = '<div class="alert alert-danger">' . $mail->ErrorInfo . '</div>';
			}
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);
		echo json_encode($output);
	}

	if($_POST['action'] == 'patient_login')
	{
		$error = '';

		$data = array(
			':patient_email'	=>	$_POST["patient_email"]
		);

		$object->query = "
		SELECT * FROM table_patient
		WHERE patient_email = :patient_email
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{

			$result = $object->statement_result();

			foreach($result as $row)
			{
				if($row["email_verification"] == 'Oui')
				{
					if($row["patient_password"] == $_POST["patient_password"])
					{
						$_SESSION['patient_id'] = $row['patient_id'];
						$_SESSION['patient_name'] = $row['patient_prenom'] . ' ' . $row['patient_nom'];
					}
					else
					{
						$error = '<div class="alert alert-danger">Mot de passe incorrecte</div>';
					}
				}
				else
				{
					$error = '<div class="alert alert-danger">Veuillez verifiez votre adresse email</div>';
				}
			}
		}
		else
		{
			$error = '<div class="alert alert-danger">Addresse email incorrecte</div>';
		}

		$output = array(
			'error'		=>	$error
		);

		echo json_encode($output);

	}

	if($_POST['action'] == 'fetch_schedule')
	{
		$output = array();

		$order_column = array('table_medecin.medecin_nom', 'table_medecin.medecin_experience', 'table_medecin.medecin_specialite', 'table_horaire.horaire_date', 'table_horaire.horaire_jour', 'table_horaire.horaire_debut');
		
		$main_query = "
		SELECT * FROM table_horaire 
		INNER JOIN table_medecin 
		ON table_medecin.medecin_id = table_horaire.medecin_id 
		";

		$search_query = '
		WHERE table_horaire.horaire_date >= "'.date('Y-m-d').'" 
		AND table_horaire.horaire_status = "Active" 
		AND table_medecin.medecin_status = "Active" 
		';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'AND ( table_medecin.medecin_nom LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR table_medecin.medecin_experience LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR table_medecin.medecin_specialite LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR table_horaire.horaire_date LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR table_horaire.horaire_jour LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR table_horaire.horaire_debut LIKE "%'.$_POST["search"]["value"].'%") ';
		}
		
		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY table_horaire.horaire_date ASC ';
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

		$object->query = $main_query . $search_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$sub_array[] = $row["medecin_nom"];

			$sub_array[] = $row["medecin_experience"];

			$sub_array[] = $row["medecin_specialite"];

			$sub_array[] = $row["horaire_date"];

			$sub_array[] = $row["horaire_jour"];

			$sub_array[] = $row["horaire_debut"];

			$sub_array[] = '
			<div align="center">
			<button type="button" name="prendre_rdv" class="btn btn-primary btn-sm prendre_rdv" data-medecin_id="'.$row["medecin_id"].'" data-horaire_id="'.$row["horaire_id"].'">Prendre le RDV</button>
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

	if($_POST['action'] == 'edit_profile')
	{
		$data = array(
			':patient_password'			=>	$_POST["patient_password"],
			':patient_prenom'		=>	$_POST["patient_prenom"],
			':patient_nom'		=>	$_POST["patient_nom"],
			':patient_dateNaiss'	=>	$_POST["patient_dateNaiss"],
			':patient_sexe'			=>	$_POST["patient_sexe"],
			':patient_addresse'			=>	$_POST["patient_addresse"],
			':patient_tel'			=>	$_POST["patient_tel"],
			':patient_situation_fam'	=>	$_POST["patient_situation_fam"]
		);

		$object->query = "
		UPDATE table_patient 
		SET patient_password = :patient_password, 
		patient_prenom = :patient_prenom, 
		patient_nom = :patient_nom, 
		patient_dateNaiss = :patient_dateNaiss, 
		patient_sexe = :patient_sexe, 
		patient_addresse = :patient_addresse, 
		patient_tel = :patient_tel, 
		patient_situation_fam = :patient_situation_fam 
		WHERE patient_id = '".$_SESSION['patient_id']."'
		";

		$object->execute($data);

		$_SESSION['success_message'] = '<div class="alert alert-success">Profile modifié avec succès</div>';

		echo 'done';
	}

	if($_POST['action'] == 'make_appointment')
	{
		$object->query = "
		SELECT * FROM table_patient
		WHERE patient_id = '".$_SESSION["patient_id"]."'
		";

		$patient_data = $object->get_result();

		$object->query = "
		SELECT * FROM table_horaire 
		INNER JOIN table_medecin 
		ON table_medecin.medecin_id = table_horaire.medecin_id 
		WHERE table_horaire.horaire_id = '".$_POST["horaire_id"]."'
		";

		$doctor_schedule_data = $object->get_result();

		$html = '
		<h4 class="text-center">Détails du patient</h4>
		<table class="table">
		';

		foreach($patient_data as $patient_row)
		{
			$html .= '
			<tr>
				<th width="40%" class="text-right">Nom patient</th>
				<td>'.$patient_row["patient_prenom"].' '.$patient_row["patient_nom"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Téléphone</th>
				<td>'.$patient_row["patient_tel"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Addresse</th>
				<td>'.$patient_row["patient_addresse"].'</td>
			</tr>
			';
		}

		$html .= '
		</table>
		<hr />
		<h4 class="text-center">Détails du rendez-vous</h4>
		<table class="table">
		';
		foreach($doctor_schedule_data as $doctor_schedule_row)
		{
			$html .= '
			<tr>
				<th width="40%" class="text-right">Nom medecin</th>
				<td>'.$doctor_schedule_row["medecin_nom"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Date rendez-vous</th>
				<td>'.$doctor_schedule_row["horaire_date"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Jour rendez-vous</th>
				<td>'.$doctor_schedule_row["horaire_jour"].'</td>
			</tr>
			<tr>
				<th width="40%" class="text-right">Heure de disponibilité</th>
				<td>'.$doctor_schedule_row["horaire_debut"].' - '.$doctor_schedule_row["horaire_fin"].'</td>
			</tr>
			';
		}

		$html .= '
		</table>';
		echo $html;
	}

	if($_POST['action'] == 'book_appointment')
	{
		$error = '';
		$data = array(
			':patient_id'			=>	$_SESSION['patient_id'],
			':horaire_id'	=>	$_POST['hidden_horaire_id']
		);

		$object->query = "
		SELECT * FROM table_rdv 
		WHERE patient_id = :patient_id 
		AND horaire_id = :horaire_id
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Vous avez déjà demandé un rendez-vous pour ce jour, essayez pour un autre jour.</div>';
		}
		else
		{
			$object->query = "
			SELECT * FROM table_horaire 
			WHERE horaire_id = '".$_POST['hidden_horaire_id']."'
			";

			$schedule_data = $object->get_result();

			$object->query = "
			SELECT COUNT(rdv_id) AS total FROM table_rdv 
			WHERE horaire_id = '".$_POST['hidden_horaire_id']."' 
			";

			$appointment_data = $object->get_result();

			$total_doctor_available_minute = 0;
			$average_consulting_time = 0;
			$total_appointment = 0;

			foreach($schedule_data as $schedule_row)
			{
				$end_time = strtotime($schedule_row["horaire_fin"] . ':00');

				$start_time = strtotime($schedule_row["horaire_debut"] . ':00');

				$total_doctor_available_minute = ($end_time - $start_time) / 60;

				$average_consulting_time = $schedule_row["duree_consultation"];
			}

			foreach($appointment_data as $appointment_row)
			{
				$total_appointment = $appointment_row["total"];
			}

			$total_appointment_minute_use = $total_appointment * $average_consulting_time;

			$heure_rdv = date("H:i", strtotime('+'.$total_appointment_minute_use.' minutes', $start_time));

			$status = '';

			$num_rdv = $object->Generate_appointment_no();

			if(strtotime($end_time) > strtotime($heure_rdv . ':00'))
			{
				$status = 'Réservé';
			}
			else
			{
				$status = 'Patientez...';
			}
			
			$data = array(
				':medecin_id'				=>	$_POST['hidden_medecin_id'],
				':patient_id'				=>	$_SESSION['patient_id'],
				':horaire_id'		=>	$_POST['hidden_horaire_id'],
				':num_rdv'		=>	$num_rdv,
				':motif_rdv'	=>	$_POST['motif_rdv'],
				':heure_rdv'			=>	$heure_rdv,
				':status'					=>	'Réservé'
			);

			$object->query = "
			INSERT INTO table_rdv 
			(medecin_id, patient_id, horaire_id, num_rdv, motif_rdv, heure_rdv, status) 
			VALUES (:medecin_id, :patient_id, :horaire_id, :num_rdv, :motif_rdv, :heure_rdv, :status)
			";

			$object->execute($data);

			$_SESSION['appointment_message'] = '<div class="alert alert-success">Votre rendez-vous à été <b>'.$status.'</b> avec le numéro <b>'.$num_rdv.'</b></div>';
		}
		echo json_encode(['error' => $error]);
		
	}

	if($_POST['action'] == 'fetch_appointment')
	{
		$output = array();

		$order_column = array('table_rdv.num_rdv','table_medecin.medecin_nom', 'table_horaire.horaire_date', 'table_rdv.heure_rdv', 'table_horaire.horaire_jour', 'table_rdv.status');
		
		$main_query = "
		SELECT * FROM table_rdv  
		INNER JOIN table_medecin 
		ON table_medecin.medecin_id = table_rdv.medecin_id 
		INNER JOIN table_horaire 
		ON table_horaire.horaire_id = table_rdv.horaire_id 
		
		";

		$search_query = '
		WHERE table_rdv.patient_id = "'.$_SESSION["patient_id"].'" 
		';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'AND ( table_rdv.num_rdv LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR table_medecin.medecin_nom LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR table_horaire.horaire_date LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR table_rdv.heure_rdv LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR table_horaire.horaire_jour LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR table_rdv.status LIKE "%'.$_POST["search"]["value"].'%") ';
		}
		
		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY table_rdv.rdv_id ASC ';
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

		$object->query = $main_query . $search_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$sub_array[] = $row["num_rdv"];

			$sub_array[] = $row["medecin_nom"];

			$sub_array[] = $row["horaire_date"];			

			$sub_array[] = $row["heure_rdv"];

			$sub_array[] = $row["horaire_jour"];

			$status = '';

			if($row["status"] == 'Réservé')
			{
				$status = '<span class="badge badge-warning">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'En cours')
			{
				$status = '<span class="badge badge-primary">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'Terminé')
			{
				$status = '<span class="badge badge-success">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'Annulé')
			{
				$status = '<span class="badge badge-danger">' . $row["status"] . '</span>';
			}

			$sub_array[] = $status;

			$sub_array[] = '<a href="download.php?id='.$row["rdv_id"].'" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>';

			$sub_array[] = '<button type="button" name="cancel_appointment" class="btn btn-danger btn-sm cancel_appointment" data-id="'.$row["rdv_id"].'"><i class="fas fa-times"></i></button>';

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

	if($_POST['action'] == 'cancel_appointment')
	{
		$data = array(
			':status'			=>	'Annulé',
			':rdv_id'	=>	$_POST['rdv_id']
		);
		$object->query = "
		UPDATE table_rdv 
		SET status = :status 
		WHERE rdv_id = :rdv_id
		";
		$object->execute($data);
		echo '<div class="alert alert-success">Votre rendez_vous à été annulé</div>';
	}
}



?>