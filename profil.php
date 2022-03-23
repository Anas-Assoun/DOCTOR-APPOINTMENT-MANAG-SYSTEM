<?php

//profil.php



include('class/RDV.php');

$object = new RDV;

$object->query = "
SELECT * FROM table_patient 
WHERE patient_id = '".$_SESSION["patient_id"]."'
";

$result = $object->get_result();

include('header.php');

?>

<div class="container-fluid">
	<?php include('navbar.php'); ?>

	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<br />
			<?php
			if(isset($_GET['action']) && $_GET['action'] == 'edit')
			{
			?>
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-6">
							Modifier les détails du profil
						</div>
						<div class="col-md-6 text-right">
							<a href="profil.php" class="btn btn-secondary btn-sm">View</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="post" id="edit_profile_form">
						<div class="form-group">
							<label>Addresse email<span class="text-danger">*</span></label>
							<input type="text" name="patient_email" id="patient_email" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" readonly />
						</div>
						<div class="form-group">
							<label>Mot de passe<span class="text-danger">*</span></label>
							<input type="password" name="patient_password" id="patient_password" class="form-control" required  data-parsley-trigger="keyup" />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Prenom<span class="text-danger">*</span></label>
									<input type="text" name="patient_prenom" id="patient_prenom" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Nom<span class="text-danger">*</span></label>
									<input type="text" name="patient_nom" id="patient_nom" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Date de naissance<span class="text-danger">*</span></label>
									<input type="text" name="patient_dateNaiss" id="patient_dateNaiss" class="form-control" required  data-parsley-trigger="keyup" readonly />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Civilité<span class="text-danger">*</span></label>
									<select name="patient_sexe" id="patient_sexe" class="form-control">
										<option value="Homme">Homme</option>
										<option value="Femme">Femme</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Téléphone<span class="text-danger">*</span></label>
									<input type="text" name="patient_tel" id="patient_tel" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Situation familiale<span class="text-danger">*</span></label>
									<select name="patient_situation_fam" id="patient_situation_fam" class="form-control">
										<option value="Célibataire">Célibataire</option>
										<option value="Marié">Marié(e)</option>
										<option value="Divorcé">Divorcé(e)</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Addresse postale<span class="text-danger">*</span></label>
							<textarea name="patient_addresse" id="patient_addresse" class="form-control" required data-parsley-trigger="keyup"></textarea>
						</div>
						<div class="form-group text-center">
							<input type="hidden" name="action" value="edit_profile" />
							<input type="submit" name="edit_profile_button" id="edit_profile_button" class="btn btn-primary" value="Edit" />
						</div>
					</form>
				</div>
			</div>

			<br />
			<br />
			

			<?php
			}
			else
			{

				if(isset($_SESSION['success_message']))
				{
					echo $_SESSION['success_message'];
					unset($_SESSION['success_message']);
				}
			?>

			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-6">
							Détails du profil
						</div>
						<div class="col-md-6 text-right">
							<a href="profil.php?action=edit" class="btn btn-secondary btn-sm">Modifier</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<table class="table table-striped">
						<?php
						foreach($result as $row)
						{
						?>
						<tr>
							<th class="text-right" width="40%">Nom</th>
							<td><?php echo $row["patient_prenom"] . ' ' . $row["patient_nom"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Email</th>
							<td><?php echo $row["patient_email"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Mot de passe</th>
							<td><?php echo $row["patient_password"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Addresse postale</th>
							<td><?php echo $row["patient_addresse"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Téléphone</th>
							<td><?php echo $row["patient_tel"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Date de naissance</th>
							<td><?php echo $row["patient_dateNaiss"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Civilité</th>
							<td><?php echo $row["patient_sexe"]; ?></td>
						</tr>
						
						<tr>
							<th class="text-right" width="40%">Situation familiale</th>
							<td><?php echo $row["patient_situation_fam"]; ?></td>
						</tr>
						<?php
						}
						?>	
					</table>					
				</div>
			</div>
			<br />
			<br />
			<?php
			}
			?>
		</div>
	</div>
</div>

<?php

include('footer.php');


?>

<script>

$(document).ready(function(){

	$('#patient_dateNaiss').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });

<?php
	foreach($result as $row)
	{

?>
$('#patient_email').val("<?php echo $row['patient_email']; ?>");
$('#patient_password').val("<?php echo $row['patient_password']; ?>");
$('#patient_prenom').val("<?php echo $row['patient_prenom']; ?>");
$('#patient_nom').val("<?php echo $row['patient_nom']; ?>");
$('#patient_dateNaiss').val("<?php echo $row['patient_dateNaiss']; ?>");
$('#patient_sexe').val("<?php echo $row['patient_sexe']; ?>");
$('#patient_tel').val("<?php echo $row['patient_tel']; ?>");
$('#patient_situation_fam').val("<?php echo $row['patient_situation_fam']; ?>");
$('#patient_addresse').val("<?php echo $row['patient_addresse']; ?>");

<?php

	}

?>

	$('#edit_profile_form').parsley();

	$('#edit_profile_form').on('submit', function(event){

		event.preventDefault();

		if($('#edit_profile_form').parsley().isValid())
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				beforeSend:function()
				{
					$('#edit_profile_button').attr('disabled', 'disabled');
					$('#edit_profile_button').val('wait...');
				},
				success:function(data)
				{
					window.location.href = "profil.php";
				}
			})
		}

	});

});

</script>