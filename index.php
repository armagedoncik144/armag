<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=music_shop', 'root', '');

//		$query = $db->query('SELECT фио FROM музыкант');

//		$result = $query->FETCHALL(PDO::FETCH_ASSOC);

function isempty($request)
{
	if (empty($_REQUEST[$request])) {
		$answer = NULL;
	} else {
		$answer = $_REQUEST[$request];
	}
	return $answer;
}
$GroupForSongsSearch;
$GroupForDiskSearch;
$ButtForTopSales = ["", "", "", "", "", "", "", "", "", ""];

if (empty($_REQUEST['GroupForSongsSearch'])) {
} else {
	$group = $_REQUEST['GroupForSongsSearch'];
	$query = $db->query("SELECT COUNT(*) FROM произведение INNER JOIN ансамбль ON произведение.id_ансамбля = ансамбль.id WHERE ансамбль.название = '$group'");
	$GroupForSongsSearch = $query->FETCHALL(PDO::FETCH_ASSOC);
}


if (empty($_REQUEST['GroupForDiskSearch'])) {
} else {
	$group = $_REQUEST['GroupForDiskSearch'];
	$query = $db->query("SELECT COUNT(*) FROM пластинка INNER JOIN ансамбль ON пластинка.id_ансамбля = ансамбль.id WHERE ансамбль.название = '$group'");
	$GroupForDiskSearch = $query->FETCHALL(PDO::FETCH_ASSOC);
}

if (empty($_REQUEST['ButtForTopSales'])) {
} else {
	$query = $db->query("SELECT номер, продажи_за_год FROM пластинка");
	$result = $query->FETCHALL(PDO::FETCH_ASSOC);
	$TopSales = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	foreach ($result as $arr) {
		$sold = $arr['продажи_за_год'];
		for ($i = 9; $i >= 0; $i--) {
			if ($TopSales[$i] < $sold) {
				if ($i < 9) {
					$TopSales[$i + 1] = $TopSales[$i];
					$ButtForTopSales[$i + 1] = $ButtForTopSales[$i];
				}
				$TopSales[$i] = $sold;
				$ButtForTopSales[$i] = $arr['номер'];
			} else {
				break;
			}
		}
	}
}

if (empty($_REQUEST['DiskID'])) {
} else {
	$DiskID = $_REQUEST['DiskID'];
	$GroupIDForDisk = isempty("GroupIDForDisk");
	$Maker = isempty("Maker");
	$Adress = isempty("Adress");
	$OptPrice = isempty("OptPrice");
	$CassualPrice = isempty("CassualPrice");
	$MakingDate = isempty("MakingDate");
	$YearSell = isempty("YearSell");
	$PreYearSell = isempty("PreYearSell");
	$NotSelled = isempty("NotSelled");
	$exists = $db->query("SELECT COUNT(*) FROM пластинка WHERE номер = '$DiskID'")->FETCHALL(PDO::FETCH_ASSOC);
	if ($exists[0]['COUNT(*)'] > 0) {
		if ($GroupIDForDisk != NULL) {
			$result = $db->query("UPDATE пластинка SET id_ансамбля = '$GroupIDForDisk' WHERE номер = '$DiskID'");
		}
		if ($Maker != NULL) {
			$result = $db->query("UPDATE пластинка SET компания = '$Maker' WHERE номер = '$DiskID'");
		}
		if ($Adress != NULL) {
			$result = $db->query("UPDATE пластинка SET адрес_опт_фирмы = '$Adress' WHERE номер = '$DiskID'");
		}
		if ($OptPrice != NULL) {
			$result = $db->query("UPDATE пластинка SET опт_цены = '$OptPrice' WHERE номер = '$DiskID'");
		}
		if ($CassualPrice != NULL) {
			$result = $db->query("UPDATE пластинка SET розн_цены = '$CassualPrice' WHERE номер = '$DiskID'");
		}
		if ($MakingDate != NULL) {
			$result = $db->query("UPDATE пластинка SET дата_выпуска = '$MakingDate' WHERE номер = '$DiskID'");
		}
		if ($YearSell != NULL) {
			$result = $db->query("UPDATE пластинка SET продажи_за_год = '$YearSell' WHERE номер = '$DiskID'");
		}
		if ($PreYearSell != NULL) {
			$result = $db->query("UPDATE пластинка SET продажи_за_прошлый_год = '$PreYearSell' WHERE номер = '$DiskID'");
		}
		if ($NotSelled != NULL) {
			$result = $db->query("UPDATE пластинка SET ещё_не_проданные = '$NotSelled' WHERE номер = '$DiskID'");
		}
	} else {
		$result = $db->query("INSERT INTO пластинка (номер, id_ансамбля, компания, адрес_опт_фирмы, опт_цены, розн_цены, дата_выпуска, продажи_за_год, продажи_за_прошлый_год, ещё_не_проданные) VALUES ('$DiskID', '$GroupIDForDisk', '$Maker', '$Adress', '$OptPrice', '$CassualPrice', '$MakingDate', '$YearSell', '$PreYearSell', '$NotSelled');");
	}
}

if (empty($_REQUEST['GroupID'])) {
} else {
	$GroupID = $_REQUEST['GroupID'];
	$GroupName = isempty("GroupName");
	$Genre = isempty("Genre");
	$exists = $db->query("SELECT COUNT(*) FROM ансамбль WHERE id = '$GroupID'")->FETCHALL(PDO::FETCH_ASSOC);
	if ($exists[0]['COUNT(*)'] > 0) {
		if ($GroupName != NULL) {
			$result = $db->query("UPDATE ансамбль SET название = '$GroupName' WHERE id = '$GroupID'");
		}
		if ($Genre != NULL) {
			$result = $db->query("UPDATE ансамбль SET жанр = '$Genre' WHERE id = '$GroupID'");
		}
	} else {
		$result = $db->query("INSERT INTO ансамбль (название, жанр) VALUES ('$GroupName', '$Genre');");
	}


}

?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>

<body>
	<form action="" method="post">
		Узнать количество произведений данного ансамбл: <input type="text" name="GroupForSongsSearch"><br>
		<button></button>
	</form>
	<?php
	if (!empty($_REQUEST['GroupForSongsSearch'])) {
		foreach ($GroupForSongsSearch as $arr) {
			echo  $arr['COUNT(*)'] . " ";
		}
	}
	?>
	<form action="" method="post">
		Показать все пластинки данного ансамбля: <input type="text" name="GroupForDiskSearch"><br>
		<button></button>
	</form>
	<?php
	if (!empty($_REQUEST['GroupForDiskSearch'])) {
		foreach ($GroupForDiskSearch as $arr) {
			echo  $arr['COUNT(*)'] . " ";
		}
	}
	?>
	<form action="" method="post">
		показать топ продаж пластинок этого года: <input type="submit" name="ButtForTopSales" value="кнопка"><br>
	</form>
	<?php
	if (!empty($_REQUEST['ButtForTopSales'])) {
		foreach ($ButtForTopSales as $arr) {
			echo  $arr;
		}
	}
	?>
	<form action="" method="post">
		<p>внести изменения по пластинкам</p>
		номер пластинки: <input type="number" name="DiskID"><br>
		id ансамбля: <input type="text" name="GroupIDForDisk"><br>
		компания производитель: <input type="text" name="Maker"><br>
		адрес оптовой фирмы: <input type="text" name="Adress"><br>
		оптовая цена: <input type="number" name="OptPrice"><br>
		розничная цена: <input type="number" name="CassualPrice"><br>
		дата выпуска: <input type="date" name="MakingDate"><br>
		продажи за год: <input type="number" name="YearSell"><br>
		продажи за прошлый год: <input type="number" name="PreYearSell"><br>
		ещё не проданные копии: <input type="number" name="NotSelled"><br>
		<button></button>
	</form>
	<form action="" method="post">
		<p>внести изменения по ансамблям</p>
		id ансамбля: <input type="text" name="GroupID"><br>
		название ансамбля: <input type="text" name="GroupName"><br>
		жанр ансамбля: <input type="text" name="Genre"><br>
		<button></button>
	</form>
</body>
<?php

?>

</html>