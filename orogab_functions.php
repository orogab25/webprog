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

function visit($connect)
{
	if(!isset($_COOKIE["name"]))
	{
		print "<script>
		alert('Az oldalunk sütiket használ adatai tárolására.');
		</script>";
		
		$sql='SELECT * FROM orogab_tracking_users';
		$query=pg_query($connect,$sql);
		$rows=pg_numrows($query);
		
		setcookie("name","latogato_".$rows,time() + (86400 * 900));
		setcookie("ip",$_SERVER['REMOTE_ADDR'],time() + (86400 * 900));
		
		$sql='INSERT INTO orogab_tracking (name,ip)
		VALUES (\''.'latogato_'.$rows.'\',\''.$_SERVER['REMOTE_ADDR'].'\')';		
		pg_query($connect,$sql);
		
		$sql='INSERT INTO orogab_tracking_users (name)
		VALUES (\''.'latogato_'.$rows.'\')';		
		pg_query($connect,$sql);
	}
	else
	{
		$sql='INSERT INTO orogab_tracking (name,ip)
		VALUES (\''.$_COOKIE["name"].'\',\''.$_COOKIE["ip"].'\')';		
		pg_query($connect,$sql);
		
		$sql='SELECT * FROM orogab_tracking_users WHERE name=\''.$_COOKIE["name"].'\'';
		$query=pg_query($connect,$sql);
		
		if(pg_numrows($query)===0)
		{
		$sql='INSERT INTO orogab_tracking_users (name)
		VALUES (\''.$_COOKIE["name"].'\')';		
		pg_query($connect,$sql);
		}
	}
}

function selectWeeklyMenu($connect)
{
	$days=array(
	$monday = date( 'Y-m-d', strtotime( 'monday this week' ) ),
	$tuesday = date( 'Y-m-d', strtotime( 'tuesday this week' ) ),
	$wednesday = date( 'Y-m-d', strtotime( 'wednesday this week' ) ),
	$thursday = date( 'Y-m-d', strtotime( 'thursday this week' ) ),
	$friday = date( 'Y-m-d', strtotime( 'friday this week' ) ),
	$saturday = date( 'Y-m-d', strtotime( 'saturday this week' ) ),
	$sunday = date( 'Y-m-d', strtotime( 'sunday this week' ) )
	);
	$daysString=array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
								
	for($i=0;$i<sizeof($days);$i++)
	{
		$sql='SELECT name,description,price,image FROM orogab_menu WHERE date=\''.$days[$i].'\'';
		$result=pg_query($connect,$sql);
									
		while ($row = pg_fetch_row($result))
		{
		print'<li class="item '.$daysString[$i].'">
		<h2 class="white">'.$row[0].'</h2>
		<img src="images/'.$row[3].'" title="'.$row[1].'">
		<h2 class="white">'.$row[2].'</h2>
		</li>';
		}
	}
}

function selectDailyMenu($connect)
{
	date_default_timezone_set('Europe/Budapest');
	$currentDate = date('Y-m-d');
	$sql='SELECT name,description,price,image FROM orogab_menu WHERE date=\''.$currentDate.'\'';
	$result=pg_query($connect,$sql);
	$row=pg_fetch_row($result);
	
	print'
	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	<ol class="carousel-indicators">
	<li data-target="#carousel-example-generic" data-slide-to="0" class="active" ></li>';
	$i=1;
					
	if($i<pg_numrows($result))
	{
		print'
		<li data-target="#carousel-example-generic" data-slide-to="'.$i.'">
		</li>';
		$i++;
	}
	print'</ol>
	<div class="carousel-inner" align="middle">
	<div class="item active">
	<h1 class="black">'.$row[0].'</h1>
	<img src="images/'.$row[3].'"title="'.$row[1].'" width="320" height="240">
	<h2 class="black">'.$row[2].'</h2>
	</div>';
	
	while($row=pg_fetch_row($result))
	{
		print'
		<div class="item">
		<h1 class="black">'.$row[0].'</h1>
		<img src="images/'.$row[3].'" title="'.$row[1].'" width="320" height="240">
		<h2 class="black">'.$row[2].'</h2>
		</div>';
	}
	print'
	</div>
	</div>
	</div>';
}

function addReservation($connect)
{
	setcookie("name",$_POST['email']);
	
	$sql='UPDATE orogab_tracking SET name=\''.$_POST['email'].'\' WHERE name=\''.$_COOKIE['name'].'\'';
	pg_query($connect,$sql);
	
	$sql='UPDATE orogab_tracking_users SET name=\''.$_POST['email'].'\' WHERE name=\''.$_COOKIE['name'].'\'';
	pg_query($connect,$sql);
	
	$sql='INSERT INTO orogab_reservation (name,email,phone,gnumber,date) 
	VALUES (\''.$_POST["name"].'\', \''.$_POST["email"].'\', \''.$_POST["phone"].'\', \''.$_POST["gnumber"].'\', \''.$_POST["datepicker"].'\')';	
	pg_query($connect,$sql);
}

function addSubscribe($connect)
{
	setcookie("name",$_POST['email']);
	
	$sql='UPDATE orogab_tracking SET name=\''.$_POST['email'].'\' WHERE name=\''.$_COOKIE['name'].'\'';
	pg_query($connect,$sql);
	
	$sql='UPDATE orogab_tracking_users SET name=\''.$_POST['email'].'\' WHERE name=\''.$_COOKIE['name'].'\'';
	pg_query($connect,$sql);
	
	$characters="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	$activate="";
	$activateLength=rand(30,40);
	$delete="";
	$deleteLength=rand(30,40);

	for($i=0;$i<$activateLength;$i++)
	{
		$activate.=mb_substr($characters,rand(0,mb_strlen($characters)-1),1);
	}
	
	for($i=0;$i<$deleteLength;$i++)
	{
		$delete.=mb_substr($characters,rand(0,mb_strlen($characters)-1),1);
	}
	
	$sql='INSERT INTO orogab_subscribe (name,email,confirmLink,deleteLink) 
	VALUES (\''.$_POST["name"].'\', \''.$_POST["email"].'\', \''.$activate.'\', \''.$delete.'\')';	
	pg_query($connect,$sql);
	
	$message='Kedves '.$_POST["name"]."!\r\n
	Kérjük hogy az alábbi aktiváló linket használva erősítse meg a feliratkozási szándékát. \r\n
	<a href=\"http://c-ta-php.ttk.pte.hu/~orogab/hazi_feladat/orogab_index.php?activate=$activate&email=".$_POST["email"]."\">Megerősítés</a>\r\n\r\n
	Amennyiben törölni szeretné feliratkozását kattintson az alábbi linkre. \r\n
	<a href=\"http://c-ta-php.ttk.pte.hu/~orogab/hazi_feladat/orogab_index.php?delete=$delete&email=".$_POST["email"]."\">Törlés</a>\r\n";
	
	$headers=array('Content-type: text/html; charset="utf-8";','From: <konferencia@c-ta-php.ttk.pte.hu>');			
	$header=implode("\r\n",$headers);
	mail($_POST["email"],"Feliratkozás",nl2br($message),$header);
}

function subscribeConfirm($connect)
{
	$sql='UPDATE orogab_subscribe SET confirmed=true WHERE confirmLink=\''.$_GET['activate'].'\'';
	pg_query($connect,$sql);
}

function subscribeDelete($connect)
{
	$sql='DELETE from orogab_subscribe WHERE deleteLink=\''.$_GET['delete'].'\'';
	pg_query($connect,$sql);
}

?>
