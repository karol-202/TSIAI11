<?php
include('readCSV.php');

$filteredRows = array_values(array_filter($rows, 'filterRow'));

$page = $_GET['page'] ?: 0;
$pageSize = $_GET['page-size'] ?: 100;
$pagesCount = ceil(count($filteredRows) / (float) $pageSize);
$isPreviousPageAvailable = $page > 0;
$isNextPageAvailable = $page < ($pagesCount - 1);

$paginationIndexStart = $page * $pageSize;
$paginationIndexEnd = ($page + 1) * $pageSize;

function showTableData($rows, $rowMapper, $startIndex, $endIndex) {
    foreach ($rows as $index => $row) {
        $inPage = $index >= $startIndex && $index < $endIndex;
        if(!$inPage) continue;

        $rowData = call_user_func($rowMapper, $row);

        echo '<tr onclick="showDetails('.getSchoolId($row).')">';
        foreach ($rowData as $cell) {
            echo '<td>'.$cell.'</td>';
        }
        echo '</tr>';
    }
}

function filterRow($row) {
    $voivodeship = $_GET["voivodeship"];
    if($voivodeship != null && $voivodeship != '' && getSchoolVoivodeship($row) != $voivodeship) return false;

    $county = $_GET["county"];
    if($county != null && $county != '' && getSchoolCounty($row) != $county) return false;

    $parish = $_GET["parish"];
    if($parish != null && $parish != '' && getSchoolParish($row) != $parish) return false;

    $city = $_GET["city"];
    if($city != null && $city != '' && getSchoolCity($row) != $city) return false;

    $type = $_GET["type"];
    if($type != null && $type != '' && getSchoolType($row) != $type) return false;

    $name = $_GET["name"];
    if($name != null && $name != '' && strpos(getSchoolName($row), $name) === false) return false;

    return true;
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>TSIAI11 - CSV</title>
    <link rel="stylesheet" href="style.css">
    <script src="index.js"></script>
    <script>
        const voivodeshipTree = JSON.parse('<?php echo json_encode($voivodeshipTree); ?>');
        const schoolTypes = JSON.parse('<?php echo json_encode($schoolTypes); ?>');
    </script>
</head>
<body>
    <h1><a href="index.php">Lista szkół</a></h1>
    <form action="index.php" method="get">
        <label for="select-voivodeship">Województwo:</label>
        <select id="select-voivodeship" name="voivodeship" onchange="updateCounties()"></select>
        <br>

        <label for="select-county">Powiat:</label>
        <select id="select-county" name="county" onchange="updateParishes()"></select>
        <br>

        <label for="select-parish">Gmina:</label>
        <select id="select-parish" name="parish" onchange="updateCities()"></select>
        <br>

        <label for="select-city">Miasto:</label>
        <select id="select-city" name="city"></select>
        <br>

        <label for="select-type">Typ szkoły:</label>
        <select id="select-type" name="type"></select>
        <br>

        <label for="input-name">Nazwa szkoły:</label>
        <input type="text" id="input-name" name="name">
        <br>

        <input type="submit" value="Pokaż dane">
    </form>
    <script>
        updateVoivodeships();
        updateTypes();
    </script>
    <table class="data">
        <thead>
            <tr>
                <td>Nazwa</td>
                <td>Typ</td>
                <td>Województwo</td>
                <td>Powiat</td>
                <td>Gmina</td>
                <td>Miejscowość</td>
                <td>Ulica</td>
                <td>Numer</td>
            </tr>
        </thead>
        <tbody>
            <?php showTableData($filteredRows, function ($row) {
                return array(
                    getSchoolName($row),
                    getSchoolType($row),
                    getSchoolVoivodeship($row),
                    getSchoolCounty($row),
                    getSchoolParish($row),
                    getSchoolCity($row),
                    getSchoolStreet($row),
                    getSchoolHouseNumber($row));
            }, $paginationIndexStart, $paginationIndexEnd) ?>
        </tbody>
    </table>
    <div class="pagination">
        <button onclick="previousPage(<?php echo $page ?>)" <?php if(!$isPreviousPageAvailable) echo 'disabled' ?>><<<</button>
        <a>Strona <?php echo $page + 1 ?> z <?php echo $pagesCount ?></a>
        <button onclick="nextPage(<?php echo $page ?>)" <?php if(!$isNextPageAvailable) echo 'disabled' ?>>>>></button>
    </div>
</body>
</html>
