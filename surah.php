<?php

if (isset($_POST["snum"])) {
    $snumber = $_POST["snum"];
    $response = file_get_contents("https://api.alquran.cloud/v1/surah/$snumber/ar.abdurrahmaansudais");
    $response = json_decode($response, true);
    $dataQuran = $response["data"]["ayahs"];
}

// Handle search for specific Ayah
$ayahSearch = '';
if (isset($_POST['ayah_search'])) {
    $ayahSearch = intval(trim($_POST['ayah_search']));
    $dataQuran = array_filter($dataQuran, function($ayah) use ($ayahSearch) {
        return $ayah["number"] === $ayahSearch;
    });
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surah Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri+Quran&display=swap" rel="stylesheet">
    <style>
            body {
            direction: rtl;
            font-family: 'Amiri Quran', serif;
            background-color:#f9f9f9;
            font-size:29px;
            text-align:center;
            background-color: black;
     color: #bd8908; 
           
           
        }


    </style>
</head>

<body>
    <form method="post" class="mb-4">
        <input type="number" name="ayah_search" class="form-control" placeholder="Search by Ayah" value="<?= htmlspecialchars($ayahSearch) ?>">
        <input type="hidden" name="snum" value="<?= htmlspecialchars($snumber) ?>">
        <button type="submit" class="btn btn-warning mt-2">Search Ayah</button>
    </form>

    <?php
    if (!empty($dataQuran)) {
        foreach ($dataQuran as $value) {
            // Fetch the translation for this Ayah
            $translationResponse = file_get_contents("https://api.alquran.cloud/v1/ayah/{$value['number']}/en.sahih");
            $translationData = json_decode($translationResponse, true);
            $translationText = $translationData["data"]["text"] ?? "Translation not available";

            echo '<p>' . $value["text"] . ' (' . $value["number"] . ')</p>';
            echo '<p><strong>Translation:</strong> ' . $translationText . '</p>';
            echo '<audio controls src="' . $value["audio"] . '"></audio>';
        }
    } else {
        echo '<p>No Ayahs found.</p>';
    }
    ?>
</body>
</html>
