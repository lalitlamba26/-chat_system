<?php session_start(); ?>
<html>
<title>Lalit Messenger</title>
<head> 
<script src="//code.jquery.com/jquery-1.7.2.min.js"></script>
<script>
$(function(){     // jquery 

        $("#retype_password").keyup(function(){
        	if($("#password").val()==$("#retype_password").val())
        		$("#pass").html("password matched");
        	else
        		$("#pass").html("please enter same password!!");

        });

        $("#username_check").blur(function(){
                $.ajax({
                        type:'POST',
                        url:'sign_up.php',
                        data: {entered_username:$("#username_check").val()},           
                        success : function (data){
                                if (data==1) {
                                     $("#user_check").html("");
                                     $("#submit_button2").attr("disabled", false);
                                }else{
                                        $("#user_check").html("there is no such username, sign up"); 
                                        $("#submit_button2").attr("disabled", true);  
                                }                 
                        },
                        failure: function(){
                                alert("failed");
                        },

                });

        });






});


</script>
</head>


<body>

<form method="POST" action="sign_up.php">

	username : <input type= "text" name="username" id="username" required> <br><br>          <!-- //has to be checked in database for unique  -->
	name: <input type="text" required name="name" > <br><br>
	password : <input type= "password" name="password" id="password" required> <br><br>
	retype password : <input type="password" name="retype_password" id= "retype_password" required > <abc id="pass"></abc> <br><br>
	<input type= "submit" id= "submit_button" value= "sign up">

</form>

<form method="POST" action="sign_up.php" >
        username : <input type="text" name="username" id="username_check" required><abc id="user_check"></abc><br><br>
        password : <input type="password" name="password" required><br><br>
        <input type="submit" id="submit_button2" value="sign in">
</form>



</body>
</html>