<?php
session_start();
$con=mysqli_connect("localhost", "root", "root", "messenger_db") or die("could not connect " .mysqli_error($con));

if($_POST['username']&&$_POST['name']&&$_POST['password']&&$_POST["retype_password"])
{
	$sql= "INSERT INTO USER (username, name, password) 
	VALUES
	('$_POST[username]','$_POST[name]','$_POST[password]')";

	if(!mysqli_query($con, $sql))
	{
		die("error executing query" .mysqli_error($con));
	}
	else 
	{
		header( 'Location: chatbox.php' ); 
		$sql="SELECT id FROM USER WHERE username='$_POST[username]'";
		$result= mysqli_query($con, $sql) or die ("peoblem executing query".mysqli_error($con));
		$row=mysqli_fetch_array($result);
		$ret=$row['id'];
		$_SESSION["QWERTY"]=$ret;
		echo "successfully signed up";
	}


}
elseif($_POST['entered_username'])
{
	$abc=$_POST['entered_username'];
	$sql="SELECT id FROM USER WHERE username='$abc'";
	$result = mysqli_query($con, $sql) or die('problem executing query' .mysqli_error($con));
	if(mysqli_num_rows($result)>0){
		echo true;
	}else{
		echo 0;
	}
}
elseif($_POST['username']&&$_POST['password'])
{
	$username=$_POST['username'];$pass=$_POST['password'];
	$sql="SELECT password FROM USER WHERE username='$username'";
	$result=mysqli_query($con, $sql) or die ('prblem executing query'.mysqli_error());
	$row=mysqli_fetch_array($result);
	$ret=$row['password'];
	
	
	if($pass==$ret)
	{	
		$sql="SELECT id FROM USER WHERE username='$_POST[username]'";
		$result= mysqli_query($con, $sql) or die ("peoblem executing query".mysqli_error($con));
		$row=mysqli_fetch_array($result);
		$ret=$row['id'];
		$_SESSION["QWERTY"]=$ret;
		header ('location:chatbox.php');
	}
	else{
		//header ('location:index.php');
		echo "wrong password";
	}

}

else {

	echo "database entry can't be empty";
}
mysqli_close($con);
?>

