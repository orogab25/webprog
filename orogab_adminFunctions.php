<?php
function dbconnect()
{
	$host='localhost';
	$port='5432';
	$dbname='orogab';
	$user='orogab';
	$password='ASDbcgiz,39u4z,9ö7t88ö7ö8zhlö8t';
	$connect=pg_connect('host=\''.$host.'\' port=\''.$port.'\' dbname=\''.
	    $dbname.'\' user=\''.$user.'\' password=\''.$password.'\'');
	return($connect);
}

function adminTracking($connect)
{
	if($_POST['trackingUser']==='Összes')
	{
	$sql='SELECT name,ip,time FROM orogab_tracking ORDER BY time DESC';
	}
	else
	{
	$sql='SELECT name,ip,time FROM orogab_tracking WHERE name=\''.$_POST['trackingUser'].'\' ORDER BY time DESC';
	}
	$query=pg_query($connect,$sql);
	$result=pg_fetch_all($query);
	
	if ($result!==false)
	{
		print '
		<h1>'.'Név: '.$_POST['trackingUser'].'</h1>
		<h1>'.'Látogatások száma: '.pg_num_rows($query).'</h1>
		<table border="1">
		<tr>
		<td>Név</td>
		<td>IP</td>
		<td>Idő</td>
		</tr>';
		foreach ($result as $record)
		{
			print "<tr>";
			foreach ($record as $field)
			{
				print "<td>$field</td>";
			}
		}
		print "</tr>";
	}
	print "</table>";
}

function addAdminMenu($connect)
{
	$source=$_FILES["fileUpload"]["tmp_name"];
	$destination="/home/orogab/public_html/hazi_feladat/images/".$_FILES["fileUpload"]["name"];
	move_uploaded_file($source,$destination);
	
	$sql='INSERT INTO orogab_menu (name,description,type,price,date,image)
	VALUES (\''.$_POST["name"].'\', \''.$_POST["description"].'\', \''.$_POST["type"].'\', \''.$_POST["price"].'\', \''.$_POST["date"].'\', \''.$_FILES["fileUpload"]["name"].'\')';
	
	if (pg_query($connect,$sql))
	{
		print "Menü kínálat hozzáadása sikeres.";
	}
	else
	{
		print "Menü kínálat hozzáadása sikertelen.";
	}
}

function modifyAdminMenu($connect)
{
	$source=$_FILES["fileUpload"]["tmp_name"];
	$destination="/home/orogab/public_html/hazi_feladat/images/".$_FILES["fileUpload"]["name"];
	move_uploaded_file($source,$destination);
	
	$sql='UPDATE orogab_menu SET name=\''.$_POST["name"].'\', description=\''.$_POST["description"].'\', type=\''.$_POST["type"].'\', price=\''.$_POST["price"].'\', date=\''.$_POST["date"].'\', image=\''.$_FILES["fileUpload"]["name"].'\' WHERE id=\''.$_POST["key"].'\'';
	pg_query($connect,$sql);
}

function adminDelete($connect)
{
	$sql='DELETE FROM '.$_POST["table"].
	' WHERE '.$_POST["field"].'=\''.$_POST["key"].'\'';
	pg_query($connect,$sql);
}

function adminConfirm($connect)
{
	$sql='UPDATE orogab_reservation SET accepted=1 WHERE id=\''.$_POST["key"].'\'';
	pg_query($connect,$sql);
	
	$message='Kedves '.$_POST["name"]."!\r\n
	Értesítjük hogy az éttermünkbe való asztalfoglalását elfogadtuk. \r\n";
	$headers=array('Content-type: text/html; charset="utf-8";','From: <konferencia@c-ta-php.ttk.pte.hu>');			
	$header=implode("\r\n",$headers);
	mail($_POST["email"],"Asztalfoglalás",nl2br($message),$header);
}

function adminRefuse($connect)
{
	$sql='UPDATE orogab_reservation SET accepted=2 WHERE id=\''.$_POST["key"].'\'';
	pg_query($connect,$sql);
	
	$message='Kedves '.$_POST["name"]."!\r\n
	Sajnálatos módon foglalási kérelmét elutasítottuk. \r\n";
	$headers=array('Content-type: text/html; charset="utf-8";','From: <konferencia@c-ta-php.ttk.pte.hu>');			
	$header=implode("\r\n",$headers);
	mail($_POST["email"],"Asztalfoglalás",nl2br($message),$header);
}

function logout()
{
session_start();
session_unset();
session_destroy();
header("Location: orogab_login.php");
}
?>
