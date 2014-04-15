<?php
session_start();

header('Cache-Control: no-cache');

//echo $_SESSION['frnd'];
if($_POST['friend_id'])
{
$con=mysqli_connect("localhost", "root", "root", "messenger_db") or die("could not mysqli_error" .mysqli_error($con));
$sql="SELECT * FROM MESSAGES WHERE sent_to='$_POST[friend_id]' AND sent_from= '$_SESSION[QWERTY]'
	UNION
	SELECT * FROM MESSAGES WHERE sent_to='$_SESSION[QWERTY]' AND sent_from='$_POST[friend_id]' ORDER BY send_time ";
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
		//flush();
	}else{ echo "no message";
// $time = date('r');
// echo "data: The server time is: {$time}\n\n";
//flush();
}

}
?>