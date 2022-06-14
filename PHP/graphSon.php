<?php
    /* Database connection settings */
    include_once('config.php');

    $Mesure_Atmospherique = '';
    $heure ='';

    //Récupérer les données
    $sql = "SELECT donnee, `date` FROM `donnees` INNER JOIN capteurs ON donnees.idCapteur = capteurs.idCapteur WHERE type = 'son' ";
    $result = $db -> query($sql);

    //Boucle données
    while ($row = $result->fetch_assoc()) {
        $heure = $heure . '"'. $row['date'].'",';

        $Mesure_Atmospherique = $Mesure_Atmospherique . '"'. $row['donnee'].'",';
    }

    $Mesure_Atmospherique = trim($Mesure_Atmospherique,",");
    $heure = trim($heure,",");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenRoad</title>
    <link rel="stylesheet" href="../CSS/statsetdonnees.css?v=<?php echo time(); ?>"> 
</head>

<body>
<div>
<canvas id="myChart"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        const labels =
        //'0:00','01:00','02:00','03:00','04:00','05:00','6:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00'
        [<?php echo $heure;?>];

        const data = {
        labels: labels,
        datasets: [{
          label: 'Intensité sonore (dB)',
          backgroundColor: '#20bf6b',
          borderColor: '#20bf6b',
          data:[<?php echo $Mesure_Atmospherique;?>],
        }]
        };

        const config = {
        type: 'line',
        data: data,
        options: {}
        };

</script>

<script>
    const myChart = new Chart(
        document.getElementById('myChart'),
        config
        );
</script>

</body>
</html>