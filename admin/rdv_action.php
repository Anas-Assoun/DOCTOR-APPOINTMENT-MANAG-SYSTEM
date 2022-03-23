<?php

//rdv_action.php

include('../class/RDV.php');

$object = new RDV;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$output = array();

		if($_SESSION['type'] == 'Admin')
		{
			$order_column = array('table_rdv.num_rdv', 'table_patient.patient_prenom', 'table_medecin.medecin_nom', 'table_horaire.horaire_date', 'table_rdv.heure_rdv', 'table_horaire.horaire_jour', 'table_rdv.status');
			$main_query = "
			SELECT * FROM table_rdv  
			INNER JOIN table_medecin 
			ON table_medecin.medecin_id = table_rdv.medecin_id 
			INNER JOIN table_horaire 
			ON table_horaire.horaire_id = table_rdv.horaire_id 
			INNER JOIN table_patient 
			ON table_patient.patient_id = table_rdv.patient_id 
			";

			$search_query = '';

			if($_POST["is_date_search"] == "yes")
			{
			 	$search_query .= 'WHERE table_horaire.horaire_date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND (';
			}
			else
			{
				$search_query .= 'WHERE ';
			}

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'table_rdv.num_rdv LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_patient.patient_prenom LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_patient.patient_nom LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_medecin.medecin_nom LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_horaire.horaire_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_rdv.heure_rdv LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_horaire.horaire_jour LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_rdv.status LIKE "%'.$_POST["search"]["value"].'%" ';
			}
			if($_POST["is_date_search"] == "yes")
			{
				$search_query .= ') ';
			}
			else
			{
				$search_query .= '';
			}
		}
		else
		{
			$order_column = array('table_rdv.num_rdv', 'table_patient.patient_prenom', 'table_horaire.horaire_date', 'table_rdv.heure_rdv', 'table_horaire.horaire_jour', 'table_rdv.status');

			$main_query = "
			SELECT * FROM table_rdv 
			INNER JOIN table_horaire 
			ON table_horaire.horaire_id = table_rdv.horaire_id 
			INNER JOIN table_patient 
			ON table_patient.patient_id = table_rdv.patient_id 
			";

			$search_query = '
			WHERE table_rdv.medecin_id = "'.$_SESSION["admin_id"].'" 
			';

			if($_POST["is_date_search"] == "yes")
			{
			 	$search_query .= 'AND table_horaire.horaire_date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" ';
			}
			else
			{
				$search_query .= '';
			}

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'AND (table_rdv.num_rdv LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_patient.patient_prenom LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_patient.patient_nom LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_horaire.horaire_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_rdv.heure_rdv LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_horaire.horaire_jour LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_rdv.status LIKE "%'.$_POST["search"]["value"].'%") ';
			}
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY table_rdv.rdv_id DESC ';
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

			$sub_array[] = $row["patient_prenom"] . ' ' . $row["patient_nom"];

			if($_SESSION['type'] == 'Admin')
			{
				$sub_array[] = $row["medecin_nom"];
			}
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

			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["rdv_id"].'"><i class="fas fa-eye"></i></button>
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

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM table_rdv 
		WHERE rdv_id = '".$_POST["rdv_id"]."'
		";

		$appointment_data = $object->get_result();

		foreach($appointment_data as $appointment_row)
		{

			$object->query = "
			SELECT * FROM table_patient 
			WHERE patient_id = '".$appointment_row["patient_id"]."'
			";

			$patient_data = $object->get_result();

			$object->query = "
			SELECT * FROM table_horaire 
			INNER JOIN table_medecin 
			ON table_medecin.medecin_id = table_horaire.medecin_id 
			WHERE table_horaire.horaire_id = '".$appointment_row["horaire_id"]."'
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
					<th width="40%" class="text-right">Nom du patient </th>
					<td>'.$patient_row["patient_prenom"].' '.$patient_row["patient_nom"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Téléphone</th>
					<td>'.$patient_row["patient_tel"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Adresse</th>
					<td>'.$patient_row["patient_addresse"].'</td>
				</tr>
				';
			}

			$html .= '
			</table>
			<hr />
			<h4 class="text-center">Détails rendez-vous</h4>
			<table class="table">
				<tr>
					<th width="40%" class="text-right">Numéro de rendez-vous</th>
					<td>'.$appointment_row["num_rdv"].'</td>
				</tr>
			';
			foreach($doctor_schedule_data as $doctor_schedule_row)
			{
				$html .= '
				<tr>
					<th width="40%" class="text-right">Nom du médecin</th>
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
				
				';
			}

			$html .= '
				<tr>
					<th width="40%" class="text-right">Heure rendez-vous</th>
					<td>'.$appointment_row["heure_rdv"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Motif de rendez-vous</th>
					<td>'.$appointment_row["motif_rdv"].'</td>
				</tr>
			';

			if($appointment_row["status"] != 'Annulé')
			{
				if($_SESSION['type'] == 'Admin')
				{
					if($appointment_row['patient_presence'] == 'Oui')
					{
						if($appointment_row["status"] == 'Terminé')
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Patient entre à lhopital</th>
									<td>Oui</td>
								</tr>
								<tr>
									<th width="40%" class="text-right">Préscription du médecin</th>
									<td>'.$appointment_row["medecin_comment"].'</td>
								</tr>
							';
						}
						else
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Patient entre à lhopital</th>
									<td>
										<select name="patient_presence" id="patient_presence" class="form-control" required>
											<option value="">Select</option>
											<option value="Oui" selected>Oui</option>
										</select>
									</td>
								</tr
							';
						}
					}
					else
					{
						$html .= '
							<tr>
								<th width="40%" class="text-right">Patient entre à lhopital</th>
								<td>
									<select name="patient_presence" id="patient_presence" class="form-control" required>
										<option value="">Select</option>
										<option value="Oui">Oui</option>
									</select>
								</td>
							</tr
						';
					}
				}

				if($_SESSION['type'] == 'Doctor')
				{
					if($appointment_row["patient_presence"] == 'Oui')
					{
						if($appointment_row["status"] == 'Terminé')
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Préscription du médecin</th>
									<td>
										<textarea name="medecin_comment" id="medecin_comment" class="form-control" rows="8" required>'.$appointment_row["medecin_comment"].'</textarea>
									</td>
								</tr
							';
						}
						else
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Préscription du médecin</th>
									<td>
										<textarea name="medecin_comment" id="medecin_comment" class="form-control" rows="8" required></textarea>
									</td>
								</tr
							';
						}
					}
				}
			
			}

			$html .= '
			</table>
			';
		}

		echo $html;
	}

	if($_POST['action'] == 'change_appointment_status')
	{
		if($_SESSION['type'] == 'Admin')
		{
			$data = array(
				':status'							=>	'En cours',
				':patient_presence'		=>	'Oui',
				':rdv_id'					=>	$_POST['hidden_rdv_id']
			);

			$object->query = "
			UPDATE table_rdv 
			SET status = :status, 
			patient_presence = :patient_presence 
			WHERE rdv_id = :rdv_id
			";

			$object->execute($data);

			echo '<div class="alert alert-success">Status du rendez-vous est devenu En cours...</div>';
		}

		if($_SESSION['type'] == 'Doctor')
		{
			if(isset($_POST['medecin_comment']))
			{
				$data = array(
					':status'							=>	'Terminé',
					':medecin_comment'					=>	$_POST['medecin_comment'],
					':rdv_id'					=>	$_POST['hidden_rdv_id']
				);

				$object->query = "
				UPDATE table_rdv 
				SET status = :status, 
				medecin_comment = :medecin_comment 
				WHERE rdv_id = :rdv_id
				";

				$object->execute($data);

				echo '<div class="alert alert-success">Rendez-vous terminé</div>';
			}
		}
	}
	

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM table_horaire 
		WHERE horaire_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Horaire supprimé avec succès</div>';
	}
}

?>