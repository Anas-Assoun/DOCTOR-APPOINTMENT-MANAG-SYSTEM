<?php

//tbl_bord.php



include('class/RDV.php');

$object = new RDV;

include('header.php');

?>

<div class="container-fluid">
	<?php
	include('navbar.php');
	?>
	<br />
	<div class="card">
		<div class="card-header"><h4>Horaires des médecins</h4></div>
			<div class="card-body">
				<div class="table-responsive">
		      		<table class="table table-striped table-bordered" id="appointment_list_table">
		      			<thead>
			      			<tr>
			      				<th>Nom médecin</th>
			      				<th>Experience /an</th>
			      				<th>Spécialité</th>
			      				<th>Date</th>
			      				<th>Jour</th>
			      				<th>Heure de disponibilité</th>
			      				<th>Action</th>
			      			</tr>
			      		</thead>
			      		<tbody></tbody>
			      	</table>
			    </div>
			</div>
		</div>
	</div>

</div>

<?php

include('footer.php');

?>

<div id="appointmentModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="appointment_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Réserver rendez-vous</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <div id="appointment_detail"></div>
                    <div class="form-group">
                    	<label><b>Motif de rendez-vous</b></label>
                    	<textarea name="motif_rdv" id="motif_rdv" class="form-control" required rows="5"></textarea>
                    </div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_medecin_id" id="hidden_medecin_id" />
          			<input type="hidden" name="hidden_horaire_id" id="hidden_horaire_id" />
          			<input type="hidden" name="action" id="action" value="book_appointment" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Réserver" />
          			<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>


<script>

$(document).ready(function(){

	var dataTable = $('#appointment_list_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"action.php",
			type:"POST",
			data:{action:'fetch_schedule'}
		},
		"columnDefs":[
			{
                "targets":[6],				
				"orderable":false,
			},
		],
	});

	$(document).on('click', '.prendre_rdv', function(){

		var horaire_id = $(this).data('horaire_id');
		var medecin_id = $(this).data('medecin_id');

		$.ajax({
			url:"action.php",
			method:"POST",
			data:{action:'make_appointment', horaire_id:horaire_id},
			success:function(data)
			{
				$('#appointmentModal').modal('show');
				$('#hidden_medecin_id').val(medecin_id);
				$('#hidden_horaire_id').val(horaire_id);
				$('#appointment_detail').html(data);
			}
		});

	});

	$('#appointment_form').parsley();

	$('#appointment_form').on('submit', function(event){

		event.preventDefault();

		if($('#appointment_form').parsley().isValid())
		{

			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:"json",
				beforeSend:function(){
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('Patientez...');
				},
				success:function(data)
				{
					$('#submit_button').attr('disabled', false);
					$('#submit_button').val('Réserver');
					if(data.error != '')
					{
						$('#form_message').html(data.error);
					}
					else
					{	
						window.location.href="rdv.php";
					}
				}
			})

		}

	})

});

</script>