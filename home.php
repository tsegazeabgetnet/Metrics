<!DOCTYPE html>
<?php
session_start();
include("includes/connection.php");
 if (!isset($_SESSION['user_email'])) {
 	header("Location: signin.php");
 }
 
?>
<html>
<head>
<title>MyChat</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport"  content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap-4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/home.css">
	 <script type="text/javascript" src="bootstrap-4.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>

</head>
<body>
	<div class="container main-section">
		<div class="row">
			<div class="friend-list cols-md-12">
				<div class="input-group searchbox">
					<div class="input-group-btn">
	<?php 
		$user = $_SESSION['user_email'];
		$get_user= "SELECT * from users where user_email='$user'";
		$run_user = mysqli_query($con, $get_user);
		$row = mysqli_fetch_array($run_user);
		$user_id= $row['user_id'];
		$user_name=$row['user_name'];
		echo "
            <a href='includes/find_friends.php?user_name=$user_name'><i class ='search-icon-tool' >&#x1F50D;</i>
			<button class='btn-add btn-default search-icon'name='search_user' type='submit'>Search Friends</button>
			</a>

		";
	?> 
					 <div class="icon_show_hide"> &#9776; </div>
					</div>
				</div>	
				<div class="left-chat">
					<ul style="list-style: none;">
						<?php include("includes/get_users_data.php");?>
					</ul>
				</div>
	<script type="text/javascript" src="js/ScriptHideShow.js"></script>			
			</div>
			<div class="online-user cols-md-12">
				<div class="row">
					<!-- get user info-->
					<?php 
						$user = $_SESSION['user_email'];
						$get_user= "SELECT * from users where user_email='$user'";
						$run_user = mysqli_query($con, $get_user);
						$row = mysqli_fetch_array($run_user);
						$user_id= $row['user_id'];
						$user_name=$row['user_name'];
					?>
					<!-- get user  data  clicked-->
					<?php 
					    if(isset($_GET['user_name'])){
					    	global $con;
					    	$get_user_name = $_GET['user_name'];
						    $get_user= "SELECT * from users where user_name='$get_user_name'";
						    $run_user = mysqli_query($con, $get_user);
						    $row_user = mysqli_fetch_array($run_user);
					    
						    $username=$row_user['user_name'];
							$user_profile_img = $row_user['user_profile'];
					    }
					 $total_massages = "SELECT * from chat_user where (sender_name = '$user_name' 
					 AND reciever_name='$username') OR (reciever_name='$user_name'
					 AND sender_name='$username')";

					 $run_msg = mysqli_query($con, $total_massages);
					 $total = mysqli_num_rows($run_msg);
                     
                      
					 ?>
					 <div class="cols-md-12">
					 	<div class="right-header-image">
					 		<img class="online-user-profile" src="<?php echo"$user_profile_img";?>">
					 	</div>
					 	<div class="right-header-detail">
					 		<form method="POST" action="logout.php">
					 			<p><?php echo "$username";?></p>
					 			<span><?php echo $total."&nbsp;"; ?>messages</span>
                                 <input class="logedInUser" style="display: none;" type="text" name="nm" value="<?php echo $user_name ?>"> 

					 			<button name="<?php $user_name ?>" class="btn_logout btn btn-danger" 
					 			title="Logout -> <?php echo $user_name ?>">Logout once</button>
					 		</form>
					 		
					 	</div>
					 </div>
				</div>
				<div class="row">
					<div id="scrolling_to_bottom" class="cols-md-12 right-header-contentChat">
						<?php
						$update_msg= mysqli_query($con, "UPDATE chat_user SET msg_status = 'read' where sender_name='$username' AND reciever_name='$user_name'");
						$sel_msg = "SELECT * from chat_user where (sender_name='$user_name' AND reciever_name='$username')OR (reciever_name='$user_name'
					        AND sender_name='$username') ORDER by 1 ASC";
					    $run_msg= mysqli_query($con, $sel_msg);    

					    While($row= mysqli_fetch_array($run_msg)){
                         $sender_username=$row['sender_name'];
                         $reciever_username=$row['reciever_name'];
                         $msg_content =$row['msg_content'];
                         $msg_date= $row['msg_date'];
					      
					     $explod_1 =explode(" ", $msg_date);

					     $explod_2 = explode(":", $explod_1[1]);
                         
					     $time="";
					     if ($explod_2[0]>12) {
					     	 $explod_2[0]=$explod_2[0]-12;
					     	 $time ="PM";
					     }
					     else{
					     	$time ="AM";
					     }
						?>
						<ul style="list-style: none;">
							<?php 
							if($user_name==$sender_username AND $username==$reciever_username){
								echo "
								<li class='chat_info'>
								<div class='rightside-right-chat'>
								<br><br>
                                <p>$msg_content<br>

	                                <span>You<!--$user_name-->&nbsp;&nbsp;<small>
	                                $explod_1[0]&nbsp;&nbsp; $explod_2[0]:$explod_2[1] $time</small></span></p>
									 <b class='right_arrow'>^</b>
									
								</div>
								</li>
								";
							}
							else if($user_name==$reciever_username AND $username==$sender_username){
								echo "
								<li>
								<div class='rightside-left-chat'>
								<br><br>
								<p> $msg_content<br> 
								  <span>$username &nbsp;&nbsp;<small>
	                                $explod_1[0]&nbsp;&nbsp; $explod_2[0]:$explod_2[1] $time</small></span>
								</p>
								<b class='left_arrow'>^</b>
								
								</div>
								</li>
								";
							}
							?>
						</ul>
						<?php
						}
						?>
					</div>
				</div>
				<div class="row">
					<div class="cols-md-12 right-chat-textbox">
						<form method="POST">
							<div class="sss cols-md-12"><br>
							<input class="input_text" autocomplete="off" type="text" name="msg_content" placeholder="write Massage..."><br>  
							 <!-- 
							 <textarea rows="2" cols="40"  class="input_text" autocomplete="off" name="msg_content" placeholder="write Massage...">
							  
							 </textarea>-->
							<button class="btn btn_send" name="submit"> 
								<img src="images/icon/email-send-icon.png" 
								style="width: 50px; height: 50px;"> </button>
							</div> 
						</form>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
    <?php
     if(isset($_POST['submit'])){
     	$msg = htmlentities($_POST['msg_content']);
     	if ($msg =="") {
     		echo "<script>
		       alert('Massage was unable to send'+$msg_content);
	          </script> ";
     	}

     else if (strlen($msg)>300) {
     		echo "<script>
		       alert('Massage is to long! unable to send'+$msg_content);
	          </script> ";
     	}
     	else{
     		$insert = "INSERT into chat_user(sender_name,reciever_name,msg_content,msg_status)
     		values('$user_name','$username','$msg','unread')";
     		$run_insert= mysqli_query($con,$insert);
     	}
     }
    ?>
 <script type="text/javascript">
 	$('#scrolling_to_bottom').animate({
 		scrollTop: $('#scrolling_to_bottom').get(0).scrollHeight},1000);
  
 </script>
 <script type="text/javascript">
 	$(document).ready(function(){
    var height= $(window).height();
    $('.left-chat').css('height',(height-92)+'px');
    $('.right-header-contentChat').css('height',(height -160)+'px');
 	});
 </script>
</body>
</html>

