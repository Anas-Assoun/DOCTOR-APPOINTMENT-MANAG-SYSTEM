<?php

//index.php

include('class/RDV.php');

$object = new RDV;

if(isset($_SESSION['patient_id']))
{
	header('location:tbl_bord.php');
}

$object->query = "
SELECT * FROM table_horaire 
INNER JOIN table_medecin 
ON table_medecin.medecin_id = table_horaire.medecin_id
WHERE table_horaire.horaire_date >= '".date('Y-m-d')."' 
AND table_horaire.horaire_status = 'Active' 
AND table_medecin.medecin_status = 'Active' 
ORDER BY table_horaire.horaire_date ASC
";

$result = $object->get_result();

include('header.php');

?>
		      	<div class="card">
		      		<form method="post" action="result.php">
			      		<div class="card-header"><h3><b>Liste des rendez-vous disponibles</b></h3></div>
			      		<div class="card-body">
		      				<div class="table-responsive">
		      					<table class="table table-striped table-bordered">
		      						<tr>
		      							<th>Nom medecin</th>
		      							<th>Experience</th>
		      							<th>Specialité</th>
		      							<th>Date RDV</th>
		      							<th>Jour RDV</th>
		      							<th>Heure de disponibilité</th>
		      							<th>Action</th>
		      						</tr>
		      						<?php
		      						foreach($result as $row)
		      						{
		      							echo '
		      							<tr>
		      								<td>'.$row["medecin_nom"].'</td>
		      								<td>'.$row["medecin_experience"].'</td>
		      								<td>'.$row["medecin_specialite"].'</td>
		      								<td>'.$row["horaire_date"].'</td>
		      								<td>'.$row["horaire_jour"].'</td>
		      								<td>'.$row["horaire_debut"].' - '.$row["horaire_fin"].'</td>
		      								<td><button type="button" name="get_appointment" class="btn btn-primary btn-sm get_appointment" data-id="'.$row["horaire_id"].'">Prendre le RDV</button></td>
		      							</tr>
		      							';
		      						}
		      						?>
		      					</table>
		      				</div>
		      			</div>
		      		</form>
		      	</div>
		    

<?php

include('footer.php');

?>

<script>

$(document).ready(function(){
	$(document).on('click', '.get_appointment', function(){
		var action = 'check_login';
		var doctor_schedule_id = $(this).data('id');
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{action:action, doctor_schedule_id:doctor_schedule_id},
			success:function(data)
			{
				window.location.href=data;
			}
		})
	});
});

</script>