<?php

//medecin.php

include('../class/RDV.php');

$object = new RDV;

if(!$object->is_login())
{
    header("location:".$object->base_url."admin");
}

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Gestion des Horaires</h1>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col">
                            		<h6 class="m-0 font-weight-bold text-primary">Liste des Horaires</h6>
                            	</div>
                            	<div class="col" align="right">
                            		<button type="button" name="add_exam" id="add_doctor_schedule" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table_horaire" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <?php
                                            if($_SESSION['type'] == 'Admin')
                                            {
                                            ?>
                                            <th>Nom médecin</th>
                                            <?php
                                            }
                                            ?>
                                            <th>Date horaire</th>
                                            <th>Jour horaire</th>
                                            <th>Début horaire</th>
                                            <th>Fin horaire</th>
                                            <th>Durée consultation</th>
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

<div id="doctor_scheduleModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="doctor_schedule_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Ajouter Horaire</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <?php
                    if($_SESSION['type'] == 'Admin')
                    {
                    ?>
                    <div class="form-group">
                        <label>Seléctionner médecin</label>
                        <select name="medecin_id" id="medecin_id" class="form-control" required>
                            <option value="">Seléctionner médecin</option>
                            <?php
                            $object->query = "
                            SELECT * FROM table_medecin 
                            WHERE medecin_status = 'Active' 
                            ORDER BY medecin_nom ASC
                            ";

                            $result = $object->get_result();

                            foreach($result as $row)
                            {
                                echo '
                                <option value="'.$row["medecin_id"].'">'.$row["medecin_nom"].'</option>
                                ';
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="form-group">
                        <label>Date Horaire</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" name="horaire_date" id="horaire_date" class="form-control" required readonly />
                        </div>
                    </div>
		          	<div class="form-group">
		          		<label>Début Horaire</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-clock"></i></span>
                            </div>
		          		    <input type="text" name="horaire_debut" id="horaire_debut" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#horaire_debut" required onkeydown="return false" onpaste="return false;" ondrop="return false;" autocomplete="off" />
                        </div>
		          	</div>
                    <div class="form-group">
                        <label>Fin Horaire</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-clock"></i></span>
                            </div>
                            <input type="text" name="horaire_fin" id="horaire_fin" class="form-control datetimepicker-input" data-toggle="datetimepicker" data-target="#horaire_fin" required onkeydown="return false" onpaste="return false;" ondrop="return false;" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Durée Consultation</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-clock"></i></span>
                            </div>
                            <select name="duree_consultation" id="duree_consultation" class="form-control" required>
                                <option value="">Selectionner durée consultation</option>
                                <?php
                                $count = 0;
                                for($i = 1; $i <= 15; $i++)
                                {
                                    $count += 5;
                                    echo '<option value="'.$count.'">'.$count.' Minute</option>';
                                }
                                ?>
                            </select>
                        </div>
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

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />

<script>
$(document).ready(function(){

	var dataTable = $('#table_horaire').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"horaire_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
                <?php
                if($_SESSION['type'] == 'Admin')
                {
                ?>
                "targets":[6, 7],
                <?php
                }
                else
                {
                ?>
                "targets":[5, 6],
                <?php
                }
                ?>
				
				"orderable":false,
			},
		],
	});

    var date = new Date();
    date.setDate(date.getDate());

    $('#horaire_date').datepicker({
        startDate: date,
        format: "yyyy-mm-dd",
        autoclose: true,
        /*months:['Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre'],
        days:['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi']*/
        
    });

    $('#horaire_debut').datetimepicker({
        format: 'HH:mm'
    });

    $('#horaire_fin').datetimepicker({
        useCurrent: false,
        format: 'HH:mm'
    });

    $("#horaire_debut").on("change.datetimepicker", function (e) {
        console.log('test');
        $('#horaire_fin').datetimepicker('minDate', e.date);
    });

    $("#horaire_fin").on("change.datetimepicker", function (e) {
        $('#horaire_debut').datetimepicker('maxDate', e.date);
    });

	$('#add_doctor_schedule').click(function(){
		
		$('#doctor_schedule_form')[0].reset();

		$('#doctor_schedule_form').parsley().reset();

    	$('#modal_title').text('Ajouter Horaire');

    	$('#action').val('Add');

    	$('#submit_button').val('Ajouter');

    	$('#doctor_scheduleModal').modal('show');

    	$('#form_message').html('');

	});

	$('#doctor_schedule_form').parsley();

	$('#doctor_schedule_form').on('submit', function(event){
		event.preventDefault();
		if($('#doctor_schedule_form').parsley().isValid())
		{		
			$.ajax({
				url:"horaire_action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
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
						$('#doctor_scheduleModal').modal('hide');
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

		var horaire_id = $(this).data('id');

		$('#doctor_schedule_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"horaire_action.php",

	      	method:"POST",

	      	data:{horaire_id:horaire_id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{
                <?php
                if($_SESSION['type'] == 'Admin')
                {
                ?>
                $('#medecin_id').val(data.medecin_id);
                <?php
                }
                ?>
	        	$('#horaire_date').val(data.horaire_date);

                $('#horaire_debut').val(data.horaire_debut);

                $('#horaire_fin').val(data.horaire_fin);

	        	$('#modal_title').text('Modifier Horaire');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Modifier');

	        	$('#doctor_scheduleModal').modal('show');

	        	$('#hidden_id').val(horaire_id);

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
		if(confirm("Voulez-vous vraiment "+next_status+" cet horaire?"))
    	{

      		$.ajax({

        		url:"horaire_action.php",

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

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Voulez-vous vraiment supprimer cet horaire?"))
    	{

      		$.ajax({

        		url:"horaire_action.php",

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