<?php

//horaire_action.php

include('../class/RDV.php');

$object = new RDV;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$output = array();

		if($_SESSION['type'] == 'Admin')
		{
			$order_column = array('table_medecin.medecin_nom', 'table_horaire.horaire_date', 'table_horaire.horaire_jour', 'table_horaire.horaire_debut', 'table_horaire.horaire_fin', 'table_horaire.duree_consultation');
			$main_query = "
			SELECT * FROM table_horaire 
			INNER JOIN table_medecin 
			ON table_medecin.medecin_id = table_horaire.medecin_id 
			";

			$search_query = '';

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'WHERE table_medecin.medecin_nom LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_horaire.horaire_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_horaire.horaire_jour LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_horaire.horaire_debut LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_horaire.horaire_fin LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR table_horaire.duree_consultation LIKE "%'.$_POST["search"]["value"].'%" ';
			}
		}
		else
		{
			$order_column = array('horaire_date', 'horaire_jour', 'horaire_debut', 'horaire_fin', 'duree_consultation');
			$main_query = "
			SELECT * FROM table_horaire 
			";

			$search_query = '
			WHERE medecin_id = "'.$_SESSION["admin_id"].'" AND 
			';

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= '(horaire_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR horaire_jour LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR horaire_debut LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR horaire_fin LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR duree_consultation LIKE "%'.$_POST["search"]["value"].'%") ';
			}
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY table_horaire.horaire_id DESC ';
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
			if($_SESSION['type'] == 'Admin')
			{
				$sub_array[] = html_entity_decode($row["medecin_nom"]);
			}
			$sub_array[] = $row["horaire_date"];

			$sub_array[] = $row["horaire_jour"];

			$sub_array[] = $row["horaire_debut"];

			$sub_array[] = $row["horaire_fin"];

			$sub_array[] = $row["duree_consultation"] . ' Minute';

			$status = '';
			if($row["horaire_status"] == 'Active')
			{
				$status = '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["horaire_id"].'" data-status="'.$row["horaire_status"].'">Active</button>';
			}
			else
			{
				$status = '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["horaire_id"].'" data-status="'.$row["horaire_status"].'">Inactive</button>';
			}

			$sub_array[] = $status;

			$sub_array[] = '
			<div align="center">
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["horaire_id"].'"><i class="fas fa-edit"></i></button>
			&nbsp;
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["horaire_id"].'"><i class="fas fa-times"></i></button>
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

		$medecin_id = '';

		if($_SESSION['type'] == 'Admin')
		{
			$medecin_id = $_POST["medecin_id"];
		}

		if($_SESSION['type'] == 'Doctor')
		{
			$medecin_id = $_SESSION['admin_id'];
		}

		$data = array(
			':medecin_id'					=>	$medecin_id,
			':horaire_date'			=>	$_POST["horaire_date"],
			':horaire_jour'			=>	date('l', strtotime($_POST["horaire_date"])),
			':horaire_debut'	=>	$_POST["horaire_debut"],
			':horaire_fin'		=>	$_POST["horaire_fin"],
			':duree_consultation'		=>	$_POST["duree_consultation"]
		);

		$object->query = "
		INSERT INTO table_horaire 
		(medecin_id, horaire_date, horaire_jour, horaire_debut, horaire_fin, duree_consultation) 
		VALUES (:medecin_id, :horaire_date, :horaire_jour, :horaire_debut, :horaire_fin, :duree_consultation)
		";

		$object->execute($data);

		$success = '<div class="alert alert-success">Horaire ajouté avec succès</div>';

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM table_horaire 
		WHERE horaire_id = '".$_POST["horaire_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['medecin_id'] = $row['medecin_id'];
			$data['horaire_date'] = $row['horaire_date'];
			$data['horaire_debut'] = $row['horaire_debut'];
			$data['horaire_fin'] = $row['horaire_fin'];
			$data['duree_consultation'] = $row['duree_consultation'];
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$medecin_id = '';

		if($_SESSION['type'] == 'Admin')
		{
			$medecin_id = $_POST["medecin_id"];
		}

		if($_SESSION['type'] == 'Doctor')
		{
			$medecin_id = $_SESSION['admin_id'];
		}

		$data = array(
			':medecin_id'					=>	$medecin_id,
			':horaire_date'			=>	$_POST["horaire_date"],
			':horaire_debut'	=>	$_POST["horaire_debut"],
			':horaire_fin'		=>	$_POST["horaire_fin"],
			':duree_consultation'		=>	$_POST["duree_consultation"]
		);

		$object->query = "
		UPDATE table_horaire 
		SET medecin_id = :medecin_id, 
		horaire_date = :horaire_date, 
		horaire_debut = :horaire_debut, 
		horaire_fin = :horaire_fin, 
		duree_consultation = :duree_consultation    
		WHERE horaire_id = '".$_POST['hidden_id']."'
		";

		$object->execute($data);

		$success = '<div class="alert alert-success">Données horaire modifiées avec succès</div>';

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'change_status')
	{
		$data = array(
			':horaire_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE table_horaire 
		SET horaire_status = :horaire_status 
		WHERE horaire_id = '".$_POST["id"]."'
		";

		$object->execute($data);

		echo '<div class="alert alert-success">Horaire Status changé vers '.$_POST['next_status'].'</div>';
	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM table_horaire 
		WHERE horaire_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Horaire supprimé</div>';
	}
}

?>