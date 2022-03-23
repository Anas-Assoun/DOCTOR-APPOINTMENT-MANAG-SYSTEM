<?php

//patient.php

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
                    <h1 class="h3 mb-4 text-gray-800">Gestion des Patients </h1>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Liste des Patients </h6>
                            	</div>
                            	<div class="col" align="right">
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table_patient" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Adresse email</th>
                                            <th>Téléphone</th>
                                            <th>Email Verification Status</th>
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

<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">Voir détails patient</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="patient_details">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

	var dataTable = $('#table_patient').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"patient_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[5],
				"orderable":false,
			},
		],
	});

    $(document).on('click', '.view_button', function(){

        var patient_id = $(this).data('id');

        $.ajax({

            url:"patient_action.php",

            method:"POST",

            data:{patient_id:patient_id, action:'fetch_single'},

            dataType:'JSON',

            success:function(data)
            {
                var html = '<div class="table-responsive">';
                html += '<table class="table">';

                html += '<tr><th width="40%" class="text-right">Adresse email</th><td width="60%">'+data.patient_email+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Mot de passe</th><td width="60%">'+data.patient_password+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Nom</th><td width="60%">'+data.patient_prenom+' '+data.patient_nom+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Téléphone</th><td width="60%">'+data.patient_tel+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Adresse postale</th><td width="60%">'+data.patient_addresse+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Date de naissance</th><td width="60%">'+data.patient_dateNaiss+'</td></tr>';
                html += '<tr><th width="40%" class="text-right">Civilité</th><td width="60%">'+data.patient_sexe+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Situation familiale</th><td width="60%">'+data.patient_situation_fam+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Email Verification Status</th><td width="60%">'+data.email_verification+'</td></tr>';

                html += '</table></div>';

                $('#viewModal').modal('show');

                $('#patient_details').html(html);

            }

        })
    });

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Voulez-vous vraiment supprimer ce patient?"))
    	{

      		$.ajax({

        		url:"patient_action.php",

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