<?php

//rdv.php

include('../class/RDV.php');

$object = new RDV;

if(!isset($_SESSION['admin_id']))
{
    header('location:'.$object->base_url.'');
}

include('header.php');

?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Gestion rendez-vous</h1>

                    <!-- DataTales Example -->
                    <span id="message"></span>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        	<div class="row">
                            	<div class="col-sm-6">
                            		<h6 class="m-0 font-weight-bold text-primary">Liste des rendez-vous</h6>
                            	</div>
                            	<div class="col-sm-6" align="right">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="row input-daterange">
                                                <div class="col-md-6">
                                                    <input type="text" name="start_date" id="start_date" class="form-control form-control-sm" readonly />
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="end_date" id="end_date" class="form-control form-control-sm" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <button type="button" name="search" id="search" value="Search" class="btn btn-info btn-sm"><i class="fas fa-search"></i></button>
                                                &nbsp;<button type="button" name="refresh" id="refresh" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></button>
                                            </div>
                                        </div>
                                    </div>
                            	</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="table_rdv">
                                    <thead>
                                        <tr>
                                            <th>Numéro du rendez-vous</th>
                                            <th>Nom du Patient</th>
                                            <?php
                                            if($_SESSION['type'] == 'Admin')
                                            {
                                            ?>
                                            <th>Nom du médecin</th>
                                            <?php
                                            }
                                            ?>
                                            <th>Date rendez-vous</th>
                                            <th>Heure rendez-vous</th>
                                            <th>Jour rendez-vous</th>
                                            <th>Status </th>
                                            <th>Consulter</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php
                include('footer.php');
                ?>

<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="edit_appointment_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Consulter les détail du rendez-vous</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="appointment_details"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_rdv_id" id="hidden_rdv_id" />
                    <input type="hidden" name="action" value="change_appointment_status" />
                    <input type="submit" name="save_appointment" id="save_appointment" class="btn btn-primary" value="OK" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){

    fetch_data('no');

    function fetch_data(is_date_search, start_date='', end_date='')
    {
        var dataTable = $('#table_rdv').DataTable({
            "processing" : true,
            "serverSide" : true,
            "order" : [],
            "ajax" : {
                url:"rdv_action.php",
                type:"POST",
                data:{
                    is_date_search:is_date_search, start_date:start_date, end_date:end_date, action:'fetch'
                }
            },
            "columnDefs":[
                {
                    <?php
                    if($_SESSION['type'] == 'Admin')
                    {
                    ?>
                    "targets":[7],
                    <?php
                    }
                    else
                    {
                    ?>
                    "targets":[6],
                    <?php
                    }
                    ?>
                    "orderable":false,
                },
            ],
        });
    }

	/*var dataTable = $('#table_rdv').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"rdv_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
                <?php
                //if($_SESSION['type'] == 'Admin')
                //{
                ?>
				"targets":[7],
                <?php
               // }
               // else
              //  {
                ?>
                "targets":[6],
                <?php
               // }
                ?>
				"orderable":false,
			},
		],
	});*/

    $(document).on('click', '.view_button', function(){

        var rdv_id = $(this).data('id');

        $.ajax({

            url:"rdv_action.php",

            method:"POST",

            data:{rdv_id:rdv_id, action:'fetch_single'},

            success:function(data)
            {
                $('#viewModal').modal('show');

                $('#appointment_details').html(data);

                $('#hidden_rdv_id').val(rdv_id);

            }

        })
    });

    $('.input-daterange').datepicker({
        todayBtn:'linked',
        format: "yyyy-mm-dd",
        autoclose: true
    });

    $('#search').click(function(){
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        if(start_date != '' && end_date !='')
        {
            $('#table_rdv').DataTable().destroy();
            fetch_data('yes', start_date, end_date);
        }
        else
        {
            alert("Tous les dates sont obligatoires");
        }
    });

    $('#refresh').click(function(){
        $('#table_rdv').DataTable().destroy();
        fetch_data('no');
    });

    $('#edit_appointment_form').parsley();

    $('#edit_appointment_form').on('submit', function(event){
        event.preventDefault();
        if($('#edit_appointment_form').parsley().isValid())
        {       
            $.ajax({
                url:"rdv_action.php",
                method:"POST",
                data: $(this).serialize(),
                beforeSend:function()
                {
                    $('#save_appointment').attr('disabled', 'disabled');
                    $('#save_appointment').val('wait...');
                },
                success:function(data)
                {
                    $('#save_appointment').attr('disabled', false);
                    $('#save_appointment').val('Save');
                    $('#viewModal').modal('hide');
                    $('#message').html(data);
                    $('#table_rdv').DataTable().destroy();
                    fetch_data('no');
                    setTimeout(function(){
                        $('#message').html('');
                    }, 5000);
                }
            })
        }
    });

});
</script>