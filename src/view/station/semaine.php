<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
foreach($DataMeteoSemaine as $DataMeteoJour){
    $date = $DataMeteoJour->getDate();
    echo "<p>$date</p>";
}
?>