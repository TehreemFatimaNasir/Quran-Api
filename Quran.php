<?php

$response = file_get_contents("http://api.alquran.cloud/v1/meta");
$response = json_decode($response, true);
$dataQuranIndex = $response["data"]["surahs"]["references"];

// Search functionality
$searchQuery = '';
if (isset($_POST['search'])) {
    $searchQuery = strtolower(trim($_POST['search']));
    $dataQuranIndex = array_filter($dataQuranIndex, function($item) use ($searchQuery) {
        return strpos(strtolower($item['englishName']), $searchQuery) !== false || 
               strpos(strtolower($item['name']), $searchQuery) !== false;
    });
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quran Surahs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
           body {
            direction: rtl;
    font-family: "jameel";
    background-color: black;
     color: #bd8908; 
}
        h1 {
        direction: ltr;
    color: #bd8908; 
    text-align: center;
    margin-top: 20px;
    margin-bottom: 20px;
    font-family: serif;
    font-size: 2.3rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
    transition: color 0.4s ease, transform 0.4s ease, text-shadow 0.4s ease;
}
 h1:hover {
    color: #bd8908; 
    transform: scale(1.05); 
    text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.5); 
}
        .card {
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #e4d995;
            border-color: #d4af37;
            border-width: 8px;
            color: #54530a;   
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
    transform: translateY(-20px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    border-color: #bd8908; 
} 
.card-text {
    font-size: 16px;
    line-height: 1.5;
    margin-bottom: 10px;
}

        .card-title {
            font-size: 20px;
            color: #333;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .btn {
    background-color: #54530a; 
    border-color: #d4af37; 
    color: white;
}

.btn:hover {
    background-color: #d4af37; 
    color: #ffffff; 
    border-color:  black ;
}

    </style>
</head>
<body>

<h1><center>The QURAN of the Prophet Muhammad (صلى الله عليه و سلم)</center></h1>

<div class="container">
    <form method="post" class="mb-4">
        <input type="text" name="search" class="form-control" placeholder="Search by Surah or Ayah" value="<?= htmlspecialchars($searchQuery) ?>">
    </form>

    <div class="row">
        <?php foreach ($dataQuranIndex as $item): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $item["englishName"] ?> (<?= $item["name"] ?>)</h5>
                        <p class="card-text">Translation: <?= $item["englishNameTranslation"] ?></p>
                        <p class="card-text">Verses: <?= $item["numberOfAyahs"] ?></p>
                        <p class="card-text">Revelation Type: <?= $item["revelationType"] ?></p>
                        <form action="surah.php" method="post">
                            <input type="hidden" name="snum" value="<?= $item["number"] ?>">
                            <input class="btn btn-warning" type="submit" value="Read Surah">
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
