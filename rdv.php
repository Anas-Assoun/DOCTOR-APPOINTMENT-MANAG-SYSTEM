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
		<span id="message"></span>
		<div class="card-header"><h4>Mes Rendez-vous</h4></div>
			<div class="card-body">
				<div class="table-responsive">
		      		<table class="table table-striped table-bordered" id="appointment_list_table">
		      			<thead>
			      			<tr>
			      				<th>Num Rendez-vous</th>
			      				<th>Nom Medecin</th>
			      				<th>Date </th>
			      				<th>Heure</th>
			      				<th>Jour</th>
			      				<th>Status</th>
			      				<th>Téléchargement</th>
			      				<th>Annuler</th>
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


<script>

$(document).ready(function(){

	var dataTable = $('#appointment_list_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"action.php",
			type:"POST",
			data:{action:'fetch_appointment'}
		},
		"columnDefs":[
			{
                "targets":[6, 7],				
				"orderable":false,
			},
		],
	});

	$(document).on('click', '.cancel_appointment', function(){
		var rdv_id = $(this).data('id');
		if(confirm("Voulez-vous vraiment annuler ce rendez-vous ??"))
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{rdv_id:rdv_id, action:'cancel_appointment'},
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