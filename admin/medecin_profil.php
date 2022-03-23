<?php

include('../class/RDV.php');

$object = new RDV;

if(!$object->is_login())
{
    header("location:".$object->base_url."");
}

if($_SESSION['type'] != 'Doctor')
{
    header("location:".$object->base_url."");
}

$object->query = "
    SELECT * FROM table_medecin
    WHERE medecin_id = '".$_SESSION["admin_id"]."'
    ";

$result = $object->get_result();

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Profil</h1>

                    <!-- DataTales Example -->
                    
                    <form method="post" id="profile_form" enctype="multipart/form-data">
                        <div class="row"><div class="col-md-10"><span id="message"></span><div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row">
                                    <div class="col">
                                        <h6 class="m-0 font-weight-bold text-primary">Profil</h6>
                                    </div>
                                    <div clas="col" align="right">
                                        <input type="hidden" name="action" value="doctor_profile" />
                                        <input type="hidden" name="hidden_id" id="hidden_id" />
                                        <button type="submit" name="edit_button" id="edit_button" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Modifier</button>
                                        &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--<div class="row">
                                    <div class="col-md-6">!-->
                                        <span id="form_message"></span>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Adresse email du médecin<span class="text-danger">*</span></label>
                                                    <input type="text" name="medecin_email" id="medecin_email" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Mot de passe du médecin <span class="text-danger">*</span></label>
                                                    <input type="password" name="medecin_password" id="medecin_password" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Nom du médecin <span class="text-danger">*</span></label>
                                                    <input type="text" name="medecin_nom" id="medecin_nom" class="form-control" required data-parsley-trigger="keyup" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Téléphone <span class="text-danger">*</span></label>
                                                    <input type="text" name="medecin_tel" id="medecin_tel" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Addresse postale</label>
                                                    <input type="text" name="medecin_addresse" id="medecin_addresse" class="form-control" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Date de naissance </label>
                                                    <input type="text" name="medecin_dateNaiss" id="medecin_dateNaiss" readonly class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Expérience <span class="text-danger">*</span></label>
                                                    <input type="text" name="medecin_experience" id="medecin_experience" class="form-control" required data-parsley-trigger="keyup" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Spécialité <span class="text-danger">*</span></label>
                                                    <input type="text" name="medecin_specialite" id="medecin_specialite" class="form-control" required  data-parsley-trigger="keyup" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Image <span class="text-danger">*</span></label>
                                            <br />
                                            <input type="file" name="medecin_image" id="medecin_image" />
                                            <div id="uploaded_image"></div>
                                            <input type="hidden" name="hidden_medecin_image" id="hidden_medecin_image" />
                                        </div>
                                    <!--</div>
                                </div>!-->
                            </div>
                        </div></div></div>
                    </form>
                <?php
                include('footer.php');
                ?>

<script>
$(document).ready(function(){

    $('#medecin_dateNaiss').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });

    <?php
    foreach($result as $row)
    {
    ?>
    $('#hidden_id').val("<?php echo $row['medecin_id']; ?>");
    $('#medecin_email').val("<?php echo $row['medecin_email']; ?>");
    $('#medecin_password').val("<?php echo $row['medecin_password']; ?>");
    $('#medecin_nom').val("<?php echo $row['medecin_nom']; ?>");
    $('#medecin_tel').val("<?php echo $row['medecin_tel']; ?>");
    $('#medecin_addresse').val("<?php echo $row['medecin_addresse']; ?>");
    $('#medecin_dateNaiss').val("<?php echo $row['medecin_dateNaiss']; ?>");
    $('#medecin_experience').val("<?php echo $row['medecin_experience']; ?>");
    $('#medecin_specialite').val("<?php echo $row['medecin_specialite']; ?>");
    
    $('#uploaded_image').html('<img src="<?php echo $row["medecin_image"]; ?>" class="img-thumbnail" width="100" /><input type="hidden" name="hidden_medecin_image" value="<?php echo $row["medecin_image"]; ?>" />');

    $('#hidden_medecin_image').val("<?php echo $row['medecin_image']; ?>");
    <?php
    }
    ?>

    $('#medecin_image').change(function(){
        var extension = $('#medecin_image').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['png','jpg']) == -1)
            {
                alert("Image invalide");
                $('#medecin_image').val('');
                return false;
            }
        }
    });

    $('#profile_form').parsley();

	$('#profile_form').on('submit', function(event){
		event.preventDefault();
		if($('#profile_form').parsley().isValid())
		{		
			$.ajax({
				url:"profil_action.php",
				method:"POST",
				data:new FormData(this),
                dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#edit_button').attr('disabled', 'disabled');
					$('#edit_button').html('wait...');
				},
				success:function(data)
				{
					$('#edit_button').attr('disabled', false);
                    $('#edit_button').html('<i class="fas fa-edit"></i> Edit');

                    $('#medecin_email').val(data.medecin_email);
                    $('#medecin_password').val(data.medecin_password);
                    $('#medecin_nom').val(data.medecin_nom);
                    $('#medecin_tel').val(data.medecin_tel);
                    $('#medecin_addresse').text(data.medecin_addresse);
                    $('#medecin_dateNaiss').text(data.medecin_dateNaiss);
                    $('#medecin_experience').text(data.medecin_experience);
                    $('#medecin_specialite').text(data.medecin_specialite);
                    if(data.doctor_profile_image != '')
                    {
                        $('#uploaded_image').html('<img src="'+data.doctor_profile_image+'" class="img-thumbnail" width="100" />');

                        $('#user_profile_image').attr('src', data.doctor_profile_image);
                    }

                    $('#hidden_medecin_image').val(data.doctor_profile_image);
						
                    $('#message').html(data.success);

					setTimeout(function(){

				        $('#message').html('');

				    }, 5000);
				}
			})
		}
	});

});
</script>