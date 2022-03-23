<?php

//download.php

include('class/RDV.php');

$object = new RDV;

require_once('class/pdf.php');

if(isset($_GET["id"]))
{
	$html = '<table border="0" cellpadding="5" cellspacing="5" width="100%">';

	$object->query = "
	SELECT hopital_nom, hopital_adresse, hopital_contact_no, hopital_logo 
	FROM table_admin
	";

	$hospital_data = $object->get_result();

	foreach($hospital_data as $hospital_row)
	{
		$html .= '<tr><td align="center">';
		if($hospital_row['hopital_logo'] != '')
		{
			$html .= '<img src="'.substr($hospital_row['hopital_logo'], 3).'" /><br />';
		}
		$html .= '<h2 align="center">'.$hospital_row['hopital_nom'].'</h2>
		<p align="center">'.$hospital_row['hopital_adresse'].'</p>
		<p align="center"><b>Hopital contact - </b>'.$hospital_row['hopital_contact_no'].'</p></td></tr>
		';
	}

	$html .= "
	<tr><td><hr /></td></tr>
	<tr><td>
	";

	$object->query = "
	SELECT * FROM table_rdv
	WHERE rdv_id = '".$_GET["id"]."'
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
		
		$html .= '
		<h4 align="center">Détails du patient</h4>
		<table border="0" cellpadding="5" cellspacing="5" width="100%">';

		foreach($patient_data as $patient_row)
		{
			$html .= '<tr><th width="50%" align="right">Nom patient</th><td>'.$patient_row["patient_prenom"].' '.$patient_row["patient_nom"].'</td></tr>
			<tr><th width="50%" align="right">Téléphone</th><td>'.$patient_row["patient_tel"].'</td></tr>
			<tr><th width="50%" align="right">Addresse</th><td>'.$patient_row["patient_addresse"].'</td></tr>';
		}

		$html .= '</table><br /><hr />
		<h4 align="center">Détails du rendez-vous</h4>
		<table border="0" cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<th width="50%" align="right">Numéro rendez-vous</th>
				<td>'.$appointment_row["num_rdv"].'</td>
			</tr>
		';
		foreach($doctor_schedule_data as $doctor_schedule_row)
		{
			$html .= '
			<tr>
				<th width="50%" align="right">Nom médecin</th>
				<td>'.$doctor_schedule_row["medecin_nom"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Date rendez-vous</th>
				<td>'.$doctor_schedule_row["horaire_date"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Jour rendez-vous</th>
				<td>'.$doctor_schedule_row["horaire_jour"].'</td>
			</tr>
				
			';
		}

		$html .= '
			<tr>
				<th width="50%" align="right">Heure rendez-vous</th>
				<td>'.$appointment_row["heure_rdv"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Motif de rendez-vous</th>
				<td>'.$appointment_row["motif_rdv"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Le patient entre à lhôpital ?</th>
				<td>'.$appointment_row["patient_presence"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Préscription du médecin </th>
				<td>'.$appointment_row["medecin_comment"].'</td>
			</tr>
		</table>
			';
	}

	$html .= '
			</td>
		</tr>
	</table>';

	echo $html;

	$pdf = new Pdf();

	$pdf->loadHtml($html, 'UTF-8');
	$pdf->render();
	ob_end_clean();
	//$pdf->stream($_GET["id"] . '.pdf', array( 'Attachment'=>1 ));
	$pdf->stream($_GET["id"] . '.pdf', array( 'Attachment'=>false ));
	exit(0);

}

?>