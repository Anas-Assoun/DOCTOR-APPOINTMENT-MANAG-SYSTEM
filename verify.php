<?php

//verify.php

include('header.php');

include('class/RDV.php');

$object = new RDV;

if(isset($_GET["code"]))
{
	$object->query = "
	UPDATE table_patient 
	SET email_verfication = 'Yes' 
	WHERE patient_verification_code = '".$_GET["code"]."'
	";

	$object->execute();

	$_SESSION['success_message'] = '<div class="alert alert-success">Votre Email à été verifié, vous pouvez se connecter au système</div>';

	header('location:login.php');
}

include('footer.php');

?>