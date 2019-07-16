<?php
session_start();
include('includes/config.php');


if(!isset($_GET['p']))
{
	
}

else if(isset($_GET['p']) && $_GET['p']=='login' || $_GET['p']=='register' || $_GET['p']=='login_retailer')
{
 include('includes/header1.php');
 }
else 
{
	if($_GET['p']=='success2' && isset($_GET['id']))
	{
		
	}
	else
	{
		include('includes/header.php');
	}
	
 }



 include('includes/pages.php');

 if(!isset($_GET['p']))
{
	
}

else if(isset($_GET['p']) && $_GET['p']=='login' || $_GET['p']=='register' || $_GET['p']=='login_retailer')
{
  include('includes/footer1.php');
}
else 
{
	if($_GET['p']=='success2' && isset($_GET['id']))
	{
		
	}
	else
	{
 	include('includes/footer.php');
    }
}


?>