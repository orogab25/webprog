<meta charset="utf-8">
<?php
session_start();

if (isset($_SESSION["admin"]))
{
	mb_internal_encoding("utf-8");
	require_once("orogab_adminFunctions.php");
	
	$connect=dbconnect();
	
	//Feltételek
	if (isset($_POST["trackingUserOk"]))
	{
		adminTracking($connect);
	}
	
	if (isset($_POST["addMenuOk"]))
	{
		addAdminMenu($connect);
		header("Location: orogab_admin.php");
	}
	
	if (isset($_POST["modifyMenuOk"]))
	{
		modifyAdminMenu($connect);
		header("Location: orogab_admin.php");
	}
	
	if (isset($_POST["deleteOk"]))
	{
		adminDelete($connect);
		header("Location: orogab_admin.php");
	}
	
	if (isset($_POST["confirmOk"]))
	{
		adminConfirm($connect);
		header("Location: orogab_admin.php");
	}
	
	if (isset($_POST["refuseOk"]))
	{
		adminRefuse($connect);
		header("Location: orogab_admin.php");
	}
	
	if (isset($_POST["logout"]))
	{
		logout();
	}
	
	//Felhasználó követés
	print "<hr>";
	print "<h1>Felhasználó követés</h1>";
	$sql='SELECT * FROM orogab_tracking_users';
	$query=pg_query($connect,$sql);
	print'
	Látogató: 
	<form name="tracking" method="post" action="">
	<td><select name="trackingUser">
	<option value="Összes">Összes</option>';
	
	while ($row = pg_fetch_row($query))
	{
		print'
		<option value="'.$row[1].'">'.$row[1].'</option>';
	}
	print'
	</select></td>
	<input type="submit" name="trackingUserOk" value="Lekérdezés">
	</form>
	';
	
	//Menü
	print "<hr>";
	print "<h1>Menü</h1>";
	
	$sql='SELECT * FROM orogab_menu ORDER BY date DESC';
	$query=pg_query($connect,$sql);
	$result=pg_fetch_all($query);
	
	if ($result!==false)
	{
		print '<table border="1">
		<tr>
			<td>Név</td>
			<td>Leírás</td>
			<td>Típus</td>
			<td>Ár</td>
			<td>Felszolgálás dátuma</td>
			<td>Kép</td>
		</tr>';
		
		foreach ($result as $record)
		{
			print "<tr>";
				print '
				<form enctype="multipart/form-data" name="modifyMenu" method="post" action="">
				<td><input type="text" name="name" value="'.$record['name'].'"></td>
				<td><input type="text" name="description" value="'.$record['description'].'"></td>
				<td><select name="type" select="'.$record['type'].'">
				<option value="breakfast">Reggeli</option>
				<option value="entree">Főétel</option>
				<option value="dessert">Desszert</option>
				<option value="drink">Ital</option>
				</select></td>
				<td><input type="text" name="price" value="'.$record['price'].'"></td>
				<td><input type="date" name="date" value="'.$record['date'].'"></td>
				<td><input type="file" name="fileUpload"></td>
				<td><input type="submit" name="modifyMenuOk" value="Módosítás"></td>
				<input type="hidden" name="table" value="orogab_menu" />
				<input type="hidden" name="field" value="id" />
				<input type="hidden" name="key" value="' . $record['id'] . '" />
				<td><input type="submit" value="Törlés" name="deleteOk"/></td>
				</form>';
		}
		print "</td>";
		print "</tr>";
	}
	print "</table>";
	print "<br />";
	print '
	<form enctype="multipart/form-data" name="addMenu" method="post" action="">
	Név: <input type="text" name="name">
	Leírás: <input type="text" name="description">
	Típus: <select name="type">
    <option value="breakfast">Reggeli</option>
    <option value="entree">Főétel</option>
    <option value="dessert">Desszert</option>
	<option value="drink">Ital</option>
	</select>
	Ár: <input type="text" name="price">
	Felszolgálás dátuma: <input type="date" name="date">
	Kép: <input type="file" name="fileUpload">
	<input type="submit" name="addMenuOk" value="Hozzáadás">
	</form>';
	
	//Asztalfoglalás
	print "<hr>";
	print "<h1>Függőben lévő asztalfoglalások</h1>";
	$sql='SELECT id,name,email,phone,gnumber,date,regdate FROM orogab_reservation WHERE accepted=0';
	$query=pg_query($connect,$sql);
	$result=pg_fetch_all($query);
	
	if ($result!==false)
	{
		print '<table border="1">
		<tr>
			<td>Azonosító</td>
			<td>Név</td>
			<td>E-mail</td>
			<td>Telefon</td>
			<td>Fő</td>
			<td>Foglalás időpontja</td>
			<td>Regisztráció időpontja</td>
		</tr>';
		
		foreach ($result as $record)
		{
			print "<tr>";
			foreach ($record as $field)
			{
				print "<td>$field</td>";
			}
			print '
			<form name="del" method="post" action="">
				<input type="hidden" name="table" value="orogab_reservation" />
				<input type="hidden" name="field" value="id" />
				<input type="hidden" name="key" value="' . $record['id'] . '" />
				<input type="hidden" name="name" value="' . $record['name'] . '" />
				<input type="hidden" name="email" value="' . $record['email'] . '" />
				<td><input type="submit" value="Elfogadás" name="confirmOk"/></td>
				<td><input type="submit" value="Elutasítás" name="refuseOk"/></td>
			</form>';
		}
		print "</tr>";
	}
	print "</table>";
	print "<br />";
	
	print "<hr>";
	print "<h1>Elfogadott asztalfoglalások</h1>";
	$sql='SELECT id,name,email,phone,gnumber,date,regdate FROM orogab_reservation WHERE accepted=1';
	$query=pg_query($connect,$sql);
	$result=pg_fetch_all($query);
	
	if ($result!==false)
	{
		print '<table border="1">
		<tr>
			<td>Azonosító</td>
			<td>Név</td>
			<td>E-mail</td>
			<td>Telefon</td>
			<td>Fő</td>
			<td>Foglalás időpontja</td>
			<td>Regisztráció időpontja</td>
		</tr>';
		
		foreach ($result as $record)
		{
			print "<tr>";
			foreach ($record as $field)
			{
				print "<td>$field</td>";
			}
			print '
			<form name="del" method="post" action="">
				<input type="hidden" name="table" value="orogab_reservation" />
				<input type="hidden" name="field" value="id" />
				<input type="hidden" name="key" value="' . $record['id'] . '" />
				<input type="hidden" name="name" value="' . $record['name'] . '" />
				<input type="hidden" name="email" value="' . $record['email'] . '" />
				<td><input type="submit" value="Elfogadás" name="confirmOk"/></td>
				<td><input type="submit" value="Elutasítás" name="refuseOk"/></td>
			</form>';
		}
		print "</tr>";
	}
	print "</table>";
	
	print "<hr>";
	print "<h1>Elutasított asztalfoglalások</h1>";
	$sql='SELECT id,name,email,phone,gnumber,date,regdate FROM orogab_reservation WHERE accepted=2';
	$query=pg_query($connect,$sql);
	$result=pg_fetch_all($query);
	
	if ($result!==false)
	{
		print '<table border="1">
		<tr>
			<td>Azonosító</td>
			<td>Név</td>
			<td>E-mail</td>
			<td>Telefon</td>
			<td>Fő</td>
			<td>Foglalás időpontja</td>
			<td>Regisztráció időpontja</td>
		</tr>';
		
		foreach ($result as $record)
		{
			print "<tr>";
			foreach ($record as $field)
			{
				print "<td>$field</td>";
			}
			print '
			<form name="del" method="post" action="">
				<input type="hidden" name="table" value="orogab_reservation" />
				<input type="hidden" name="field" value="id" />
				<input type="hidden" name="key" value="' . $record['id'] . '" />
				<input type="hidden" name="name" value="' . $record['name'] . '" />
				<input type="hidden" name="email" value="' . $record['email'] . '" />
				<td><input type="submit" value="Elfogadás" name="confirmOk"/></td>
				<td><input type="submit" value="Elutasítás" name="refuseOk"/></td>
			</form>';
		}
		print "</tr>";
	}
	print "</table>";
	
	//Admin
	print "<hr>";
	print "Bejelentkezve mint: ".$_SESSION["admin"]." ";
	print '<form name="logout" method="post" action="">
	<input type="submit" name="logout" value="Kijelentkezés" />
	</form>';
}
else
{
	header("Location: orogab_login.php");
}
?>
