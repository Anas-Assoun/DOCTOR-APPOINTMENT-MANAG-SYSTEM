<?php

//login.php

include('header.php');

?>

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<span id="message"></span>
			<div class="card">
				<div class="card-header">S'inscrire</div>
				<div class="card-body">
					<form method="post" id="patient_register_form">
						<div class="form-group">
							<label>Addresse email du patient<span class="text-danger">*</span></label>
							<input type="text" name="patient_email" id="patient_email" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" />
						</div>
						<div class="form-group">
							<label>Mot de passe du patient<span class="text-danger">*</span></label>
							<input type="password" name="patient_password" id="patient_password" class="form-control" required  data-parsley-trigger="keyup" />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Prenom patient<span class="text-danger">*</span></label>
									<input type="text" name="patient_prenom" id="patient_prenom" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Nom patient <span class="text-danger">*</span></label>
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
							<input type="hidden" name="action" value="patient_register" />
							<input type="submit" name="patient_register_button" id="patient_register_button" class="btn btn-primary" value="S'inscrire" />
						</div>

						<div class="form-group text-center">
							<p><a href="login.php">Se connecter</a></p>
						</div>
					</form>
				</div>
			</div>
			<br />
			<br />
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

	$('#patient_register_form').parsley();

	$('#patient_register_form').on('submit', function(event){

		event.preventDefault();

		if($('#patient_register_form').parsley().isValid())
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
				beforeSend:function(){
					$('#patient_register_button').attr('disabled', 'disabled');
				},
				success:function(data)
				{
					$('#patient_register_button').attr('disabled', false);
					$('#patient_register_form')[0].reset();
					if(data.error !== '')
					{
						$('#message').html(data.error);
					}
					if(data.success != '')
					{
						$('#message').html(data.success);
					}
				}
			});
		}

	});

});

</script>