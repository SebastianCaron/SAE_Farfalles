<?php
    include 'libs/config.php';

    global $db; // Recupere l'acces à la base de donnee

    $q = $db->prepare('SELECT * FROM Athletes LIMIT 15'); // Requete SQL

    $q->execute([]); // Execution de la requete

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST</title>
</head>
<body>
    <h1>TEST</h1>
    <img src="https://i.gifer.com/origin/e3/e3bdbd1210a42c8d19e0d6300ef6f95b.gif" alt="gif"/>

    <table>
        <tr>
            <th>Image</th>
            <th>Nom</th>
            <th>Sport</th>
            <th>Pays</th>
            <th>Date de naissance</th>
            <th>Lieu de naissance</th>
            <th>Taille</th>
        </tr>
        
        <?php
        
        while ($res = $q->fetch()) {
            echo "<tr>";
            echo "<td><img src='https:" . $res['image'] . "' alt='Athlete'></td>";
            echo "<td>" . $res['nom'] . "</td>";
            echo "<td>" . $res['sport'] . "</td>";
            echo "<td>" . $res['pays'] . "</td>";
            echo "<td>" . $res['dateNaissance'] . "</td>";
            echo "<td>" . $res['lieuNaissance'] . "</td>";
            echo "<td>" . $res['taille'] . "</td>";
            echo "</tr>";
        }
        ?>
        
    </table>
</body>
</html>