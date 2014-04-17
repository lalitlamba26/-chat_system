<?php
	session_set_cookie_params(0);
	session_start();
	if(!(isset($_SESSION['QWERTY']))){
	 	header('Location: index.php');
	 	exit();
	}
?>

<html>
<title> chatting box </title>
<style type="text/css">
	#invite_box{
		position: absolute;
		right:200px;
		top:380px;

	}
	#invite_button{
		position: absolute;
		right: 100px;
		top:380px;
	}
	#logout_button{
		position:absolute;
		right:100px;
		top:5px;
	}
	#enter_text{
		resize:none;
	}
	#chat_box{
		border:1px solid;
		border-radius:5px;
		height: 50%;
		width: 70%;
		overflow:auto;
	}

	#friends{
		border:1px solid;
		border-radius:5px;
		height: 50%;
		width: 20%;
		position:absolute;
        right:100px;
        top:20px;
        overflow:auto;
	}
	
</style>
<head> 
<script src="//code.jquery.com/jquery-1.7.2.min.js"></script>
<script>


$(function(){
	var glob;
	setInterval(ajaxcall, 2000);
	$.ajax({
		type:'POST',
		url:'message_db.php',
		data: {show_all_friends:'1'},           
		success : function (data){
			if (data=="<table id='friend_table'></table>") {
				$('#friends').html("you have no friends. To add friends enter the username in the invite box"); 
				
				
			}   else if(data)
			       { 
			       	    $('#friends').html(data);
			       	    

            	    }              
		},
		failure: function(){
			alert("failed");
		},
	});


	$('#send_button').click(function(){
		$.ajax({
			type:'POST',
			url:'message_db.php',
			data: {message_content:$("#enter_text").val(), sent_to:glob },   
			success : function (data){
				if (data) {

					$("#enter_text").attr("");
				
				}                  
			},
			failure: function(){
				alert("failed");
			},

		});

	});

	$('#logout_button').click(function(){
		$.ajax({
			type:'POST',
			url:'logout.php',
			data:{abc:"1"},
			success :function(data){
				if(data){
				alert("successfully logged out");
				location.reload();

			}

			},
			failure : function(){
				alert("failed");
			},

		});
	});

	$('#invite_button').click(function(){
		$.ajax({
			type:'POST',
			url:'message_db.php',
			data:{request_acceptor:$("#invite_box").val()},
			success : function(data){
				if (data=="u can not be your friend")
					alert("everyone is freind of himself/herself");
				else if (data=="the person has alreay send the request please accept it")
					alert(data);
				else if(data=="u have already send a request")
					alert("request pending. u have already invited the person");
				else if(data=="there is no such username")
					alert(data);
				else if(data=="the person is in your freind list")
					alert(data);
				else
				alert( data+" "+"request has been send");


			},
			failure : function(){
				alert(failed);
			}
		});



	});

	
		$.ajax({
			type:'POST',
			url:'message_db.php',
			data:{show_request:"abc"},
			success : function(data){
				$('#friend_request').html(data);

			},
			failure : function(){
				alert(failed);
			}
		});
   


	$(".accept_req").live('click',function(){   
		$.ajax({
			type:'POST',
			url:'message_db.php',
			data: {friend:$(this).attr("id")},           
			success : function (data){
				if (data) {
					$('#friend_request').find("table tr#"+data).remove();
					location.reload();
				}                
			},
			failure: function(){
				alert("failed");
			},

		});

	});

	$(".ignore_req").live('click',function(){   
		$.ajax({
			type:'POST',
			url:'message_db.php',
			data: {ignore_user:$(this).attr("id")},           
			success : function (data){
				if (data) {
					$('#friend_request').find("table tr#"+data).remove();
				}                
			},
			failure: function(){
				alert("failed");
			},

		});

	});

	$(".chat_button").live('click',function(){   
		var friend_id=$(this).attr("id");
		glob= friend_id;
		
		$.ajax({
			type:'POST',
			url:'message_db.php',
			data: {chat_button_id:friend_id, gname:$("table#friend_table").find("tr#"+friend_id).find('td:nth-child(1)').html()},           
			success : function (data){
				if(data){
					$('#chat_box').html(data);
				}               
			},
			failure: function(){
				alert("failed");
			},

		});

	});


function ajaxcall(){
	$.ajax({
		type:'POST',
		url:'server_update.php',
		data: {friend_id:glob},           
		success : function (data){
			if (data) {
				$('#chat_box').html(data+'<br>'); 
				}              
		},
		failure: function(){
			alert("failed");
		},
	});
}


});


</script>
</head>

<body>
 
  		
	 		<?php if (isset($_SESSION["QWERTY"])) 
	 		{ 
	 			$con=mysqli_connect("localhost", "root", "root", "messenger_db") or die("could not connect mysqli_error" .mysqli_error($con));
	 			$sql="SELECT  username FROM USER WHERE id='$_SESSION[QWERTY]' ";
	 			$result=mysqli_query($con, $sql) or die ("problem executing query".mysqli_error($con));
	 			$row=mysqli_fetch_array($result);


	 			echo $row['username'];  
	 		}?>
		
		CHAT BOX: <div name="chat_box" id="chat_box"></div>
		

		ENTER MESSAGE: <textarea rows="3" cols="40" name="enter_text" id="enter_text" placeholder="click on chat button and enter message here"></textarea>
		<input type="button" id="send_button" value="send">

		<div name="friends" id="friends"></div>

		
		<input type="text" name="invite_box" id="invite_box" placeholder="enter username to invite">
		<input type="button" name="invite button" id="invite_button" value= "invite person">

		
		<div id="friend_request"><b>friend request</b></div>

		<input type="button" name="logout_button" id="logout_button" value = "logout">
		


</body>

</html>
