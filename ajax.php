<?php
session_start(); 
include('includes/config.php');




if($_REQUEST['action']=="expirydate"){

  $now = time(); // or your date as well

  $userdate=$db->get_row("select * from users where id='".$_SESSION['userid']."'");

  $date=date("Y-m-d");
  $your_date = date("Y-m-d",strtotime($userdate['expiry']));

  $datetime1 = new DateTime($date);

$datetime2 = new DateTime($your_date);

$difference = $datetime2->diff($datetime1);

 if($difference->d <=5)
 $_SESSION['expirydays']=$difference->d;

if($date>$your_date)
echo 0;
else                      
echo $difference->d;






}

if($_REQUEST['action']=="acceptproject"){

$date=date("Y-m-d h:i:s");




$booking=$db->query("update bookings set status='1',totalamount='".$_REQUEST['amount']."' where id='".$_REQUEST['did']."'");

$book=$db->get_row("select * from bookings where id='".$_REQUEST['did']."'");

$msg='Designer has been accepted for your request on project : '.$book['project'].'<br> Total Amount : '.$_REQUEST['amount'].'$';

$inscoment=$db->query("insert into notifications set fromid='".$_SESSION['userid']."',toid='".$book['userid']."',message='".$msg."',date='".$date."',status='0',chat='1'");

if($inscoment)
echo "Accepted Sucessfully";


}



if($_REQUEST['action']=="hiredesignask"){

$date=date("Y-m-d h:i:s");

$user=$db->get_row("select * from users where id='".$_SESSION['userid']."'");

$msg=$user['fname'].' '.$user['lname'].' is looking to hire you.<br> Project Nature : '.$_REQUEST['nature'].'<br> Due Date : '.$_REQUEST['date'];


$booking=$db->query("insert into bookings set userid='".$_SESSION['userid']."',designer='".$_REQUEST['did']."',project='".$_REQUEST['nature']."',duedate='".$_REQUEST['date']."',status='0'");

$inscoment=$db->query("insert into notifications set fromid='".$_SESSION['userid']."',toid='".$_REQUEST['did']."',message='".$msg."',date='".$date."',status='0',chat='1'");

if($inscoment)
echo "Asked Suucessfully";
}



if($_REQUEST['action']=="hoursamount"){

$designeramt=$db->get_row("select * from designer where did='".$_REQUEST['did']."'");

$hrs=$_REQUEST['hours']*$designeramt['amount'];

echo $hrs;
}



if(isset($_REQUEST['action']) && ($_REQUEST['action']=='get_look'))
{
 	/*$set=$_REQUEST['set'];
 	$dt=$db->get_results("select * from look where set_id='".$set."' and lstatus=1 order by lid asc");
	 $op="<option value=''>Select Look</option>"; 
	if(count($dt))
	 {
 	foreach($dt as $p)
	{
	 		$op.="<option value='".$p['lid']."'>".$p['look_name']."</option>";
	 	}
	}
	echo $op;*/


 	$look=$_REQUEST['set'];
 	$dt=$db->get_results("select * from designer where dset_id='".$look."' and dstatus=1 order by did asc");
	 $op="<option value=''>Select Designer</option>"; 
	if(count($dt))
	 {
 	foreach($dt as $p)
	{
	 		$op.="<option value='".$p['did']."'>".$p['designer_name']."</option>";
	 	}
	}
	echo $op;

}

//private
if(isset($_REQUEST['action']) && ($_REQUEST['action']=='private'))
{
   if($_REQUEST['check']=='true')
   {  
     $db->query("UPDATE colsets SET private='1' WHERE id='".$_REQUEST['cid']."'");
   } 

   else

    { 
       $db->query("UPDATE colsets SET private='0' WHERE id='".$_REQUEST['cid']."'");  

   }

}

//get_check
if(isset($_REQUEST['action']) && ($_REQUEST['action']=='get_check'))
{
   if($_REQUEST['check']=='true')
   { ?>
     <div class="state p-on">
                         <i class="fa fa-heart"></i>
                   </div>
   <?php } else { ?>

     <div class="state p-off">
            <i class="fa fa-heart"></i>
         </div>
         
<?php
   }

}


if(isset($_REQUEST['action']) && ($_REQUEST['action']=='get_item'))
{    $look=$_REQUEST['set'];

	//$des_id=$db->get_row("select * from designer where did='".$look."'");
	
	  $des=$db->get_results("select * from subitems where pdesign_id='".$look."' group by pname");
	  
	 // print_r($des);
	  //print_r($des);
   // foreach($des as $d)
   
      $op="<option value=''>Select Item</option>"; 

   for($i=0;$i<count($des);$i++)
  {
 $dt1=$db->get_row("select * from subitems where pdesign_id='".$des[$i]['did']."' and pstatus=1 ");
  //$dt=$db->get_results("select * from products where pdesign_id='".$look."' and pstatus=1 order by pid asc");
  
  $dt=$db->get_row("select * from products where pid='".$dt1['pid']."' and pstatus=1 ");
  //echo "select * from products where plook_id='".$d['dlook_id']."' and pstatus=1 order by pid asc";exit;
  //if(count($dt))
   //{
 // foreach($dt as $p)
  //{
 
      $op.="<option value='".$des[$i]['pname']."'>".$des[$i]['pname']."</option>";
  
    //}
  //}
  
  }
  echo $op;

}



if(isset($_REQUEST['action']) && ($_REQUEST['action']=='get_designer'))
{
 	$look=$_REQUEST['look'];
 	$dt=$db->get_results("select * from designer where dlook_id='".$look."' and dstatus=1 order by did asc");
	 $op="<option value=''>Select Designer</option>"; 
	if(count($dt))
	 {
 	foreach($dt as $p)
	{
	 		$op.="<option value='".$p['did']."'>".$p['designer_name']."</option>";
	 	}
	}
	echo $op;
}



if(isset($_REQUEST['action']) && ($_REQUEST['action']=='addtocloset'))
{
      $itemid=$_REQUEST['itemid'];
      $closet=$_REQUEST['closet'];
      $date=date('Y-m-d H:i:s');


$prod=$db->get_row("select * from products where pid='".$itemid."'");

$image=$db->get_row("select * from gallery where pid='".$itemid."'");

       $inscoment=$db->query("insert into closet_items set userid='".$_SESSION['userid']."',closet='".$closet."',title='".$prod['pname']."',image='".$image['pimage']."',description='".$prod['description']."',date='".$date."',status='1',type='cart'");
     
     
    
       if($inscoment)
       {

?>
                       <div id="commentsblog"<?php echo $itemid; ?>>
                  <?php  
                        $commentscount=$db->num_rows("select *from comments where itemid='".$itemid."'");
                      if($commentscount > 0)
                      {/*
                        $comments=$db->get_results("select *from comments where itemid='".$itemid."'");
                        foreach ($comments as $key => $value) { 
                          $user=$db->get_row("select * from users where id='".$value['userid']."'");
                  ?>
                   
                     <label>User- <?php echo $user['fname']; ?></label>
                     <div><?php echo $value['comment']; ?></div>
                   
                  <?php  } */} else { ?>
                     <!--  <h2>No Comments Found</h2>  -->
                   <?php } 

                   if($_SESSION['utype']!='retailer' || $_SESSION['utype']!='customer')
                   {/*
                   ?>
                  <form method="post">
                    <input type="text" name="commentitem" id="commentitem<?php echo $itemid; ?>" value="<?php echo $itemid; ?>" style="display: none;">
                    <textarea class="form-control" name="bcomment" id="bcomment<?php echo $itemid; ?>"></textarea>
                    <div class="form-group">        
        <button type="button" onclick="submitComment(<?php echo $itemid; ?>);">Submit</button>
        </div>
                  </form> 

                <?php */} ?>

                       <form method="post">
                    <input type="text" name="additem" id="additem<?php echo $itm['pid']; ?>" value="<?php echo $itm['pid']; ?>" style="display: none;">
                       <select class="form-control" name="closet" id="closet<?php echo $itm['pid']; ?>">
         <?php 
         $colsets=$db->get_results("SELECT * FROM colsets WHERE userid='".$_SESSION['userid']."' and status='1' ORDER BY id DESC");
           foreach ($colsets as $cls) {
             ?>
             <option value="<?php echo $cls['id']; ?>"><?php echo $cls['title']; ?></option>
             <?php
           }
         ?>
      </select>
                    <div class="form-group">        
        <button type="button" class="btn btn-default" style="font-size: 14px;    font-size: 14px;background: #000;color: #fff;border: 0px;" onclick="addItem(<?php echo $itm['pid'];?>);">Add to closet</button>
        </div>
                  </form> 

    <?php  }  
       else
       {
        echo "fail";
       } 
}  


if(isset($_REQUEST['action']) && ($_REQUEST['action']=='addComment'))
{
      $itemid=$_REQUEST['itemid'];
      $acoment=$_REQUEST['acoment'];
      $date=date('Y-m-d H:i:s');


       $inscoment=$db->query("insert into comments set userid='".$_SESSION['userid']."',itemid='".$itemid."',comment='".$acoment."',date='".$date."',status='1'");
	   
	    $commentscount=$db->num_rows("select *from comments where itemid='".$itemid."'");
	   
	    $item_update=$db->query("update products set comments='".$commentscount."' where pid='".$itemid."'");
		
       if($inscoment)
       {

?>
                       <div id="commentsblog"<?php echo $itemid; ?>>
                  <?php  
                        $commentscount=$db->num_rows("select *from comments where itemid='".$itemid."'");
                      if($commentscount > 0)
                      {
                        $comments=$db->get_results("select *from comments where itemid='".$itemid."'");
                        foreach ($comments as $key => $value) { 
                          $user=$db->get_row("select * from users where id='".$value['userid']."'");
                  ?>
                   
                     <label>User- <?php echo $user['fname']; ?></label>
                     <div><?php echo $value['comment']; ?></div>
                   
                  <?php  } } else { ?>
                      <h2>No Comments Found</h2> 
                   <?php } ?>
                  <form method="post">
                    <input type="text" name="commentitem" id="commentitem<?php echo $itemid; ?>" value="<?php echo $itemid; ?>" style="display: none;">
                    <textarea class="form-control" name="bcomment" id="bcomment<?php echo $itemid; ?>"></textarea>
                    <div class="form-group">        
        <button type="button" onclick="submitComment(<?php echo $itemid; ?>);">Click me</button>
        </div>
                  </form> 

                         <form method="post">
                    <input type="text" name="additem" id="additem<?php echo $itm['pid']; ?>" value="<?php echo $itm['pid']; ?>" style="display: none;">
                       <select class="form-control" name="closet" id="closet<?php echo $itm['pid']; ?>">
         <?php 
         $colsets=$db->get_results("SELECT * FROM colsets WHERE userid='".$_SESSION['userid']."' and status='1' ORDER BY id DESC");
           foreach ($colsets as $cls) {
             ?>
             <option value="<?php echo $cls['id']; ?>"><?php echo $cls['title']; ?></option>
             <?php
           }
         ?>
      </select>
                    <div class="form-group">        
        <button type="button" class="btn btn-default" style="font-size: 14px;    font-size: 14px;background: #000;color: #fff;border: 0px;" onclick="addItem(<?php echo $itm['pid'];?>);">Add to closet</button>
        </div>
                  </form> 

    <?php  }  
       else
       {
        echo "fail";
       } 
}  
 
if(isset($_REQUEST['action']) && ($_REQUEST['action']=='get_items'))
{
  $closet=$_REQUEST['closet'];
  $dt=$db->get_results("select * from closet_items where closet='".$closet."' and status=1 order by id asc");
   if(count($dt))
   {
    foreach ($dt as $key => $value) { 
     ?>   
                   
       <div style="float: left;    margin-right: 5px;">  <input type="checkbox" name="clitems[]" id="clitems" value="<?php echo $value['id']; ?>">
       <span><?php echo $value['title']; ?></span><br>
	   
	   <?php
	    $image1=explode(':',$value['image']);
       
        if($image1[0]=='http' || $image1[0]=='https')
        { ?>
		 <img src="<?php echo $value['image']; ?>" style="width: 100px; height: 100px;">
		<?php
		}
		
		else
		{
	   	if($value['type']!='cart')
				{
				?>
                  <img src="./images/closets/items/<?php echo $value['image']; ?>" style="width: 100px; height: 100px;">
				  
				  <?php } else{ ?>
				  
				  <img src="admin/<?php echo $value['image'];?>"  class="img-reponsive" alt=""/>
				  
				  <?php } } ?>
				  
				  
      </div>
   
    <?php } }  
       else
       {
        echo "No Items Found In This Closet";
       } 
}  
?>
