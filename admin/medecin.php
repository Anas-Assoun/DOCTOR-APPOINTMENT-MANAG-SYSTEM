<?php

//medecin.php

include('../class/RDV.php');

$object = new RDV;

if(!$object->is_login())
{
    header("location:".$object->base_url."admin");
}

if($_SESSION['type'] != 'Admin')
{
    header("location:".$object->base_url."");
}

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Gestion des médecins</h1>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Liste des médecins</h6>
                            	</div>
                            	<div class="col" align="right">
                            		<button type="button" name="add_doctor" id="add_doctor" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table_medecin" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Adresse email</th>
                                            <th>Mot de passe</th>
                                            <th>Nom</th>
                                            <th>Téléphone</th>
                                            <th>Specialité</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php
                include('footer.php');
                ?>

<div id="doctorModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="doctor_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Ajouter médecin</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Adresse email <span class="text-danger">*</span></label>
                                <input type="text" name="medecin_email" id="medecin_email" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" />
                            </div>
                            <div class="col-md-6">
                                <label>Mot de passe<span class="text-danger">*</span></label>
                                <input type="password" name="medecin_password" id="medecin_password" class="form-control" required  data-parsley-trigger="keyup" />
                            </div>
		          		</div>
		          	</div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nom <span class="text-danger">*</span></label>
                                <input type="text" name="medecin_nom" id="medecin_nom" class="form-control" required data-parsley-trigger="keyup" />
                            </div>
                            <div class="col-md-6">
                                <label>Téléphone<span class="text-danger">*</span></label>
                                <input type="text" name="medecin_tel" id="medecin_tel" class="form-control" required  data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Adresse postale </label>
                                <input type="text" name="medecin_addresse" id="medecin_addresse" class="form-control" />
                            </div>
                            <div class="col-md-6">
                                <label>Date de naissnace </label>
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
                                <label>Specialité <span class="text-danger">*</span></label>
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
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" />
          			<input type="hidden" name="action" id="action" value="Add" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">Voir détails du médecin</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="doctor_details">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

	var dataTable = $('#table_medecin').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"medecin_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[0, 1, 2, 4, 5, 6, 7],
				"orderable":false,
			},
		],
	});

    $('#medecin_dateNaiss').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    });

	$('#add_doctor').click(function(){
		
		$('#doctor_form')[0].reset();

		$('#doctor_form').parsley().reset();

    	$('#modal_title').text('Ajout médecin');

    	$('#action').val('Add');

    	$('#submit_button').val('Ajouter');

    	$('#doctorModal').modal('show');

    	$('#form_message').html('');

	});

	$('#doctor_form').parsley();

	$('#doctor_form').on('submit', function(event){
		event.preventDefault();
		if($('#doctor_form').parsley().isValid())
		{		
			$.ajax({
				url:"medecin_action.php",
				method:"POST",
				data: new FormData(this),
				dataType:'json',
                contentType: false,
                cache: false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				success:function(data)
				{
					$('#submit_button').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_message').html(data.error);
						$('#submit_button').val('Add');
					}
					else
					{
						$('#doctorModal').modal('hide');
						$('#message').html(data.success);
						dataTable.ajax.reload();

						setTimeout(function(){

				            $('#message').html('');

				        }, 5000);
					}
				}
			})
		}
	});

	$(document).on('click', '.edit_button', function(){

		var medecin_id = $(this).data('id');

		$('#doctor_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"medecin_action.php",

	      	method:"POST",

	      	data:{medecin_id:medecin_id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{

	        	$('#medecin_email').val(data.medecin_email);

                $('#medecin_email').val(data.medecin_email);
                $('#medecin_password').val(data.medecin_password);
                $('#medecin_nom').val(data.medecin_nom);
                $('#uploaded_image').html('<img src="'+data.medecin_image+'" class="img-fluid img-thumbnail" width="150" />')
                $('#hidden_medecin_image').val(data.medecin_image);
                $('#medecin_tel').val(data.medecin_tel);
                $('#medecin_addresse').val(data.medecin_addresse);
                $('#medecin_dateNaiss').val(data.medecin_dateNaiss);
                $('#medecin_experience').val(data.medecin_experience);
                $('#medecin_specialite').val(data.medecin_specialite);

	        	$('#modal_title').text('Modifier médecin');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Modifier');

	        	$('#doctorModal').modal('show');

	        	$('#hidden_id').val(medecin_id);

	      	}

	    })

	});

	$(document).on('click', '.status_button', function(){
		var id = $(this).data('id');
    	var status = $(this).data('status');
		var next_status = 'Active';
		if(status == 'Active')
		{
			next_status = 'Inactive';
		}
		if(confirm("Voulez-vous vraiment "+next_status+" ce médecin?"))
    	{

      		$.ajax({

        		url:"medecin_action.php",

        		method:"POST",

        		data:{id:id, action:'change_status', status:status, next_status:next_status},

        		success:function(data)
        		{

          			$('#message').html(data);

          			dataTable.ajax.reload();

          			setTimeout(function(){

            			$('#message').html('');

          			}, 5000);

        		}

      		})

    	}
	});

    $(document).on('click', '.view_button', function(){
        var medecin_id = $(this).data('id');

        $.ajax({

            url:"medecin_action.php",

            method:"POST",

            data:{medecin_id:medecin_id, action:'fetch_single'},

            dataType:'JSON',

            success:function(data)
            {
                var html = '<div class="table-responsive">';
                html += '<table class="table">';

                html += '<tr><td colspan="2" class="text-center"><img src="'+data.medecin_image+'" class="img-fluid img-thumbnail" width="150" /></td></tr>';

                html += '<tr><th width="40%" class="text-right">Adresse email</th><td width="60%">'+data.medecin_email+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Mot de passe</th><td width="60%">'+data.medecin_password+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Nom</th><td width="60%">'+data.medecin_nom+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Téléphone</th><td width="60%">'+data.medecin_tel+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Adresse postale</th><td width="60%">'+data.medecin_addresse+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Date de naissance</th><td width="60%">'+data.medecin_dateNaiss+'</td></tr>';
                html += '<tr><th width="40%" class="text-right">Expérience</th><td width="60%">'+data.medecin_experience+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Specialité</th><td width="60%">'+data.medecin_specialite+'</td></tr>';

                html += '</table></div>';

                $('#viewModal').modal('show');

                $('#doctor_details').html(html);

            }

        })
    });

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Voulez-vous vraiment le supprimer?"))
    	{

      		$.ajax({

        		url:"medecin_action.php",

        		method:"POST",

        		data:{id:id, action:'delete'},

        		success:function(data)
        		{

          			$('#message').html(data);

          			dataTable.ajax.reload();

          			setTimeout(function(){

            			$('#message').html('');

          			}, 5000);

        		}

      		})

    	}

  	});



});
</script>