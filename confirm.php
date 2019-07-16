<?php

include('includes/config.php');
session_start();
if(isset($_GET['hash'])){
	 $uid=base64_decode($_GET['hash']); 
	//$user = $db->fetchRow("select *from users where id='".$uid."' ");
	
	 $qry=$db->query("update users set emailverify=1 where id='".$uid."'") or die(mysql_error());


     $db->set_session_message('<div class="success">Thank you for registering at Casa Beau Monde.Enjoy your 30 day trail period.<img class="close" alt="" src="'.SITE_URL.'images/close.png"></div>');
     
       $db->redirect('index.php?p=login'); 

		
}

?>