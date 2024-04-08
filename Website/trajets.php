<?php
include 'libs/config.php';

// Récupération des arrêts depuis la base de données pour les options du formulaire
$query_arrets = "
    SELECT DISTINCT Nom_Arrets
    FROM Arrets
    GROUP BY ID_Voyages
    HAVING COUNT(*) > 2
    ORDER BY Nom_Arrets ASC";
$statement_arrets = $db->query($query_arrets);
$arrets = $statement_arrets->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de trajets</title>
    <link rel="stylesheet" type="text/css" href="./css/all.css">
    <!-- VOTRE CSS -->
</head>
<body>
    <!-- NAVIGATION -->
    <nav>
        <div class="menu-bars" onclick="showNavigationMenu();">
            <div>
                <img id="menu_bt" src="./img/menu-bars.svg" alt="bars menu">
                <img id="menu_bt_close" src="./img/close.svg" alt="Bouton pour fermer le menu">
            </div>
            <h4>Menu</h4>
        </div>
        <a href="./index.html"><h2>Farfalles!</h2></a>
        <div class="img" onclick="goTo('https://www.paris2024.org/fr/',true);">
            <img src="./img/paris2024.gif" alt="paris2024 image">
        </div>
    </nav>

    <div class="navigation">
        <div class="links">
            <a href="./index.html">Accueil</a>
            <a href="./list-epreuves.html">Epreuves</a>
            <a href="./list-athletes.php">Athletes</a>
            <a href="./list-transports.html">Transports</a>
            <a href="./list-sites.html">Sites</a>
        </div>
        <img src="./img/phryge.png" alt="mascotte paris2024">
    </div>
    <!-- FIN DE LA NAVIGATION -->

    <div class="content">
        <h1>Recherche de trajets</h1>
        <form action="" method="GET">
            <label for="depart">POINT A :</label>
            <select name="depart" id="depart" onchange="updateDestinations()">
                <?php foreach ($arrets as $arret) : ?>
                    <option value="<?php echo $arret; ?>"><?php echo $arret; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="arrivee">POINT B :</label>
            <select name="arrivee" id="arrivee">
=
            </select>
            <br>
            <button type="submit">Rechercher</button>
        </form>

        <?php
        if (isset($_GET['depart']) && isset($_GET['arrivee'])) {
            $depart = $_GET['depart'];
            $arrivee = $_GET['arrivee'];
        
            $query_trajets = "
                SELECT 
                    CONCAT(A1.Nom_Arrets, '  :  ', A1.Heure_depart_Arrets) AS depart,
                    CONCAT(A2.Nom_Arrets, '  :  ', A2.Heure_arrive_Arrets) AS arrivee,
                    A1.Heure_depart_Arrets AS Hdepart,
                    A2.Heure_depart_Arrets AS Harrivee,
                    A1.ID_Voyages AS idvoy,
                    ABS(A2.Numero_Arrets - A1.Numero_Arrets) AS arrets
                FROM 
                    Arrets A1
                JOIN 
                    Arrets A2 ON A1.ID_Voyages = A2.ID_Voyages
                WHERE 
                    A1.Nom_Arrets = :depart AND A2.Nom_Arrets = :arrivee
                GROUP BY 
                    A1.ID_Voyages, A2.ID_Voyages";
        
        
            $statement_trajets = $db->prepare($query_trajets);
            $statement_trajets->bindParam(':depart', $depart, PDO::PARAM_STR);
            $statement_trajets->bindParam(':arrivee', $arrivee, PDO::PARAM_STR);
            $statement_trajets->execute();
            $trajets = $statement_trajets->fetchAll(PDO::FETCH_ASSOC);
        
            // Affichage des résultats
            if (!empty($trajets)) {
                echo "<p>Trajets possibles de $depart à $arrivee :</p>";
                foreach ($trajets as $trajet) {
                    $horaire_depart = $trajet['Hdepart'];
                    $horaire_arrivee = $trajet['Harrivee'];
        
                    $inverse = ($horaire_depart > $horaire_arrivee);

                    if ($inverse) {
                        echo "<a href='trajet-details.php?id={$trajet['idvoy']}'>{$trajet['arrivee']} -> {$trajet['depart']}, {$trajet['arrets']} arrêts</a><br>";
                    } else {
                        echo "<a href='trajet-details.php?id={$trajet['idvoy']}'>{$trajet['depart']} -> {$trajet['arrivee']}, {$trajet['arrets']} arrêts</a><br>";
                    }
        
                }
            } else {
                echo "<p>Aucun trajet trouvé entre $depart et $arrivee.</p>";
            }
        }
        ?>
    </div>

    <script>
        function updateDestinations() {
            var departSelect = document.getElementById("depart");
            var arriveeSelect = document.getElementById("arrivee");

            arriveeSelect.innerHTML = '';
            var depart = departSelect.value;
            fetch('./libs/get_destinations.php?depart=' + depart)
                .then(response => response.json())
                .then(destinations => {
                    destinations.forEach(destination => {
                        var option = document.createElement('option');
                        option.text = destination;
                        arriveeSelect.add(option);
                    });
                });
        }
    </script>

    <script src="./js/navigation.js"></script>
</body>
</html>
