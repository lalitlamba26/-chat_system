<?php
session_start();
$con=mysqli_connect("localhost", "root", "root", "messenger_db") or die("could not mysqli_error" .mysqli_error($con));
if($_POST['message_content']&&$_POST['sent_to'])
{
	$sql="INSERT INTO MESSAGES(content,sent_to, sent_from)
	VALUES
	('$_POST[message_content]', '$_POST[sent_to]', '$_SESSION[QWERTY]')";
	if(!mysqli_query($con, $sql))
	{
		die("error sending message" .mysqli_error($con));
	}
	else
		echo $_POST['message_content'];
		
		

}

elseif ($_POST['request_acceptor'])
{
	    $sql="SELECT id FROM USER WHERE username='$_POST[request_acceptor]'";
		$result= mysqli_query($con, $sql) or die ("peoblem executing query".mysqli_error($con));
		$row=mysqli_fetch_array($result);
		$ret=$row['id'];
		if((mysqli_num_rows($result)>0)) // how to avoid alert on space 
{
		$sql1="SELECT id, friend  FROM INVITATION WHERE request_acceptor='$ret' AND  request_sender='$_SESSION[QWERTY]'"; //AND friend =1 can't send the request again
		$result1=mysqli_query($con, $sql1) or die ("can't execute".mysqli_error($con));
		$sql2="SELECT id, friend FROM INVITATION WHERE request_acceptor='$_SESSION[QWERTY]' AND request_sender='$ret'";//AND friend =1 if some1 has already send me the request than i can't send the request again
		$result2=mysqli_query($con, $sql2) or die ("can't execute".mysqli_error($con));
		

	if (!($ret==$_SESSION['QWERTY']))
    {
    	


		if(mysqli_num_rows($result1)==0) 
	    {
	    	
	    	if(mysqli_num_rows($result2)==0)
	    	{
	
				 $sql="INSERT INTO INVITATION (request_acceptor, request_sender) VALUES 
				 ((select id from USER where username = '".$_POST['request_acceptor']."'), ".$_SESSION['QWERTY']." )";
				
				if(!mysqli_query($con, $sql))
				 {
				 	die("error sending request".mysqli_error($con));
				 }else echo $_POST["request_acceptor"];
			}else 
			{	$row2=mysqli_fetch_array($result2);
			    $is_friend2=$row2['friend'];
			    if($is_friend2==1)
			    	echo "the person is in your freind list";
			    else 
				echo "the person has alreay send the request please accept it";}

		}else
		{ 	$row=mysqli_fetch_array($result1);
			$is_friend=$row['friend'];
			$row2=mysqli_fetch_array($result2);
			$is_friend2=$row['friend'];
			if($is_friend==1) echo "the person is in your freind list";
			else 

			echo "u have already send a request";}

    }else  echo "u can not be your friend";
}else echo "there is no such username";
}

elseif($_POST['show_request'])
{	
	$sql="SELECT * FROM USER WHERE id IN( SELECT request_sender FROM INVITATION WHERE friend=0 AND request_acceptor='".$_SESSION['QWERTY']."' )";
	$result=mysqli_query($con, $sql) or die("problem executing query". mysqli_error($con));
	$ret="<table>";
	if($result){

		while($row=mysqli_fetch_array($result)){
			$ret.='<tr id="'.$row["id"].'"><td>'.$row['username'].'</td><td><button id="'.$row["id"].'" class="accept_req">accept request</button><button id="'.$row["id"].'" class="ignore_req">ignore Request</button></td></tr>';
		}
		$ret.="</table>";
		echo $ret;
    }else echo "nothing to show";
    
	


}

elseif($_POST['friend'])
{
	$test="INSERT INTO FRIENDSHIP (user1, user2) VALUES
	('$_POST[friend]','$_SESSION[QWERTY]'),( '$_SESSION[QWERTY]','$_POST[friend]')";
	$test_result=mysqli_query($con, $test) or die("problem executing command".mysqli_error($con));
	$sql="UPDATE INVITATION SET friend=1 where request_sender= '$_POST[friend]' AND request_acceptor='$_SESSION[QWERTY]'";
	$result=mysqli_query($con, $sql) or die ("problem executing query". mysqli_error($con));

	if($result){
		echo $_POST['friend'];
	}else echo false;
}

elseif($_POST['ignore_user'])
{
	$sql="DELETE FROM INVITATION WHERE request_sender= '$_POST[ignore_user]' AND request_acceptor='$_SESSION[QWERTY]'";
	$result=mysqli_query($con, $sql) or die ("problem executing query". mysqli_error($con));
	if($result){
		echo $_POST['ignore_user'];
	}else echo false;
}

elseif($_POST["show_all_friends"])
{
	$sql="SELECT USER.id, USER.name FROM USER
	INNER JOIN FRIENDSHIP
	ON USER.id=FRIENDSHIP.user1 where FRIENDSHIP.user2='$_SESSION[QWERTY]' ";
	$result=mysqli_query($con, $sql) or die ("problem executing query". mysqli_error($con));
	$ret="<table id='friend_table'>";
	if($result){

		while($row=mysqli_fetch_array($result)){
			$ret.='<tr id="'.$row["id"].'"><td>'.$row['name'].'</td><td><button id="'.$row["id"].'" class="chat_button">chat</button></td></tr>';
		}
		$ret.="</table>";
		echo $ret;
}else echo "no friends";
}
	
elseif($_POST['chat_button_id']&&$_POST['gname'])

{	$sql="SELECT * FROM MESSAGES WHERE sent_to='$_POST[chat_button_id]' AND sent_from= '$_SESSION[QWERTY]'
	UNION
	SELECT * FROM MESSAGES WHERE sent_to='$_SESSION[QWERTY]' AND sent_from='$_POST[chat_button_id]' ORDER BY send_time ";
	$result= mysqli_query($con, $sql) or die("problem executing error".mysqli_error($con));
	$ret= '<table>';
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$ret.='<tr id="'.$row["id"].'"><td>'.$row['content'].'</td><td>'.$row['sent_time'].'</td></tr>';
		}
		$ret.='</table>';
		echo $ret;
	}
	else echo "no messages";
}


mysqli_close($con);
 
?>