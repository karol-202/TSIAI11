<?php
include('readCSV.php');

$id = $_GET['id'];
$school = findSchoolWithId($rows, $id);
$searchPhrase = createSearchPhrase($school);

function showDetailsRows($headers, $details) {
    $compound = array_map(null, $headers, $details);
    foreach ($compound as $row) {
        echo '<tr>';
        foreach ($row as $cell) {
            echo '<td>'.$cell.'</td>';
        }
        echo '</tr>';
    }
}

function createSearchPhrase($school) {
    $city = getSchoolCity($school);
    $street = getSchoolStreet($school);
    $houseNumber = getSchoolHouseNumber($school);
    return $city.' '.$street.' '.$houseNumber;
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>TSIAI11 - CSV</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><a href="index.php">Lista szkół</a></h1>
    <div class="details-main">
        <div class="details-data">
            <table class="details">
                <?php showDetailsRows($header, $school) ?>
            </table>
        </div>
        <div class="details-map">
            <iframe width="600" height="450" frameborder="0"
                    class="map"
                    src="https://maps.google.com/maps/embed/v1/place?key=AIzaSyD25x-G5Mb5-dqSEy_t2IZfmuvIHKrmkQs&q=<?php echo $searchPhrase ?>"
                    allowfullscreen>
            </iframe>
        </div>
    </div>
</body>
</html>

