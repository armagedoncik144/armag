<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=music_shop', 'root', '');
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
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Менеджер музыкального магазина</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-music"></i>
                    <h1>Менеджер музыкального магазина</h1>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="app-container">
            <!-- Левая колонка -->
            <div class="left-column">
                <!-- Поиск -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-search"></i>
                        <h2>Поиск записей</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="GroupForSongsSearch">
                                    <i class="fas fa-record-vinyl"></i> Найти количество песен у ансамбля
                                </label>
                                <input type="text" id="GroupForSongsSearch" name="GroupForSongsSearch" placeholder="Введите название ансамбля">
                            </div>
                            <button type="submit" class="btn btn-block" style="background: var(--primary); color: white;">
                                <i class="fas fa-chart-bar"></i> Показать результаты
                            </button>
                        </form>
                        
                        <div class="results-container">
                            <div class="results-title">
                                <i class="fas fa-info-circle"></i> Результаты
                            </div>
                            <?php if (!empty($_REQUEST['GroupForSongsSearch'])): ?>
                                <div class="result-item">
                                    <strong><?php echo $_REQUEST['GroupForSongsSearch']; ?></strong> имеет 
                                    <?php foreach ($GroupForSongsSearch as $arr): ?>
                                        <span class="highlight"><?php echo $arr['COUNT(*)']; ?></span> песен
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Пока нет результатов. Введите название ансамбля выше.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="divider"></div>
                        
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="GroupForDiskSearch">
                                    <i class="fas fa-compact-disc"></i> Найти пластинки по ансамблю
                                </label>
                                <input type="text" id="GroupForDiskSearch" name="GroupForDiskSearch" placeholder="Введите название ансамбля">
                            </div>
                            <button type="submit" class="btn btn-block" style="background: var(--primary); color: white;">
                                <i class="fas fa-search"></i> Поиск пластинок
                            </button>
                        </form>
                        
                        <div class="results-container">
                            <div class="results-title">
                                <i class="fas fa-info-circle"></i> Результаты
                            </div>
                            <?php if (!empty($_REQUEST['GroupForDiskSearch'])): ?>
                                <div class="result-item">
                                    <strong><?php echo $_REQUEST['GroupForDiskSearch']; ?></strong> имеет 
                                    <?php foreach ($GroupForDiskSearch as $arr): ?>
                                        <span class="highlight"><?php echo $arr['COUNT(*)']; ?></span> пластинок
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Пока нет результатов. Введите название ансамбля выше.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Топ продаж -->
                <div class="card" style="margin-top: 30px;">
                    <div class="card-header">
                        <i class="fas fa-trophy"></i>
                        <h2>Топ продаж</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <p>Просмотр самых продаваемых пластинок года</p>
                            <input type="submit" name="ButtForTopSales" class="btn btn-block" 
                                   style="background: var(--warning); color: white;" 
                                   value="Показать топ пластинок">
                        </form>
                        
                        <div class="results-container" style="margin-top: 25px;">
                            <div class="results-title">
                                <i class="fas fa-list-ol"></i> Топ пластинок
                            </div>
                            <?php if (!empty($_REQUEST['ButtForTopSales'])): ?>
                                <ul class="top-sales-list">
                                    <?php foreach ($ButtForTopSales as $album): ?>
                                        <li>ID пластинки: <strong><?php echo $album; ?></strong></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-chart-pie"></i>
                                    <p>Нажмите кнопку выше, чтобы увидеть самые продаваемые пластинки</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Правая колонка -->
            <div class="right-column">
                <!-- Управление альбомами -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-edit"></i>
                        <h2>Управление пластинками</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="DiskID">
                                    <i class="fas fa-fingerprint"></i> ID пластинки
                                </label>
                                <input type="number" id="DiskID" name="DiskID" placeholder="Введите ID пластинки">
                            </div>
                            
                            <div class="grid-2">
                                <div class="form-group">
                                    <label for="GroupIDForDisk">
                                        <i class="fas fa-users"></i> ID ансамбля
                                    </label>
                                    <input type="text" id="GroupIDForDisk" name="GroupIDForDisk" placeholder="Введите ID ансамбля">
                                </div>
                                
                                <div class="form-group">
                                    <label for="Maker">
                                        <i class="fas fa-industry"></i> Производитель
                                    </label>
                                    <input type="text" id="Maker" name="Maker" placeholder="Введите производителя">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="Adress">
                                    <i class="fas fa-map-marker-alt"></i> Адрес оптовика
                                </label>
                                <input type="text" id="Adress" name="Adress" placeholder="Введите адрес оптовика">
                            </div>
                            
                            <div class="grid-2">
                                <div class="form-group">
                                    <label for="OptPrice">
                                        <i class="fas fa-tags"></i> Оптовая цена
                                    </label>
                                    <input type="number" id="OptPrice" name="OptPrice" placeholder="Введите оптовую цену">
                                </div>
                                
                                <div class="form-group">
                                    <label for="CassualPrice">
                                        <i class="fas fa-tag"></i> Розничная цена
                                    </label>
                                    <input type="number" id="CassualPrice" name="CassualPrice" placeholder="Введите розничную цену">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="MakingDate">
                                    <i class="fas fa-calendar-alt"></i> Дата выпуска
                                </label>
                                <input type="date" id="MakingDate" name="MakingDate">
                            </div>
                            
                            <div class="grid-2">
                                <div class="form-group">
                                    <label for="YearSell">
                                        <i class="fas fa-chart-bar"></i> Продажи за текущий год
                                    </label>
                                    <input type="number" id="YearSell" name="YearSell" placeholder="Введите продажи за текущий год">
                                </div>
                                
                                <div class="form-group">
                                    <label for="PreYearSell">
                                        <i class="fas fa-chart-line"></i> Продажи за прошлый год
                                    </label>
                                    <input type="number" id="PreYearSell" name="PreYearSell" placeholder="Введите продажи за прошлый год">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="NotSelled">
                                    <i class="fas fa-boxes"></i> Непроданные копии
                                </label>
                                <input type="number" id="NotSelled" name="NotSelled" placeholder="Введите количество непроданных копий">
                            </div>
                            
                            <button type="submit" class="btn btn-block" style="background: var(--success); color: white;">
                                <i class="fas fa-save"></i> Сохранить информацию о пластинке
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Управление артистами -->
                <div class="card" style="margin-top: 30px;">
                    <div class="card-header">
                        <i class="fas fa-user-edit"></i>
                        <h2>Управление ансамблями</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="GroupID">
                                    <i class="fas fa-id-badge"></i> ID ансамбля
                                </label>
                                <input type="text" id="GroupID" name="GroupID" placeholder="Введите ID ансамбля">
                            </div>
                            
                            <div class="form-group">
                                <label for="GroupName">
                                    <i class="fas fa-signature"></i> название ансамбля
                                </label>
                                <input type="text" id="GroupName" name="GroupName" placeholder="Введите название ансамбля">
                            </div>
                            
                            <div class="form-group">
                                <label for="Genre">
                                    <i class="fas fa-guitar"></i> Жанр
                                </label>
                                <input type="text" id="Genre" name="Genre" placeholder="Введите музыкальный жанр">
                            </div>
                            
                            <button type="submit" class="btn btn-block" style="background: var(--success); color: white;">
                                <i class="fas fa-save"></i> Сохранить информацию об ансамбле
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <footer>
            <p>Менеджер музыкального магазина &copy; 2025 | Создано с <i class="fas fa-heart" style="color: var(--warning);"></i> для любителей музыки</p>
        </footer>
    </div>
    
    <script>
        // Установка сегодняшней даты по умолчанию для даты выпуска
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('MakingDate').value = today;
            
            // Анимация карточек при появлении
            const cards = document.querySelectorAll('.card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            cards.forEach(card => {
                card.style.opacity = 0;
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>