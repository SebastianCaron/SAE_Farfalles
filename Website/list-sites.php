<?php
    include 'libs/config.php';

    global $db;

    $sql = 'SELECT * FROM Sites';

    $auMoinsUnChamp = false;

    // Vérification des champs du formulaire
    $champs = ['Latitude_Sites', 'Longitude_Sites', 'Nom_Sites', 'Date_de_construction_Sites', 'Capacite_d_acceuil_Sites', 'Accessibilite_Sites', 'Nom_Villes'];
    foreach ($champs as $champ) {
        if (!empty($_POST[$champ])) {
            // Si un champ est rempli, on ajoute une condition à la requête SQL
            if (!$auMoinsUnChamp) {
                $sql .= ' WHERE ';
                $auMoinsUnChamp = true;
            } else {
                $sql .= ' AND ';
            }
            $sql .= "$champ LIKE '%" . $_POST[$champ] . "%'";
        }
    }

    // Si au moins 1 champ est rempli, on exécute la requête
    if ($auMoinsUnChamp) {
        $query = $db->prepare($sql);
        $query->execute();
    }else{
        $query = $db->prepare("SELECT * FROM Sites;");
        $query->execute();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paris 2024 - Sites</title>
    <link rel="stylesheet" type="text/css" href="./css/all.css">


    <!-- VOTRE CSS -->
    <link rel="stylesheet" type="text/css" href="./css/list-sites.css">


    <!-- VOS SCRIPTS -->

</head>
<body>

    <!-- NAVIGATION -->
    <script src="./js/navigation.js"></script>
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
            <a href="./list-athletes.html">Athletes</a>
            <a href="./list-transports.html">Transports</a>
            <a href="./list-sites.html">Sites</a>
        </div>

        <img src="./img/phryge.png" alt="mascotte paris2024">
    </div>
    <!-- FIN DE LA NAVIGATION -->
    
    <div class="options">
        <h1>Paris 2024 - Sites</h1>
        <form method="post">
            <label>Latitude: <input type="text" name="Latitude_Sites"></label><br>
            <label>Longitude: <input type="text" name="Longitude_Sites"></label><br>
            <label>Nom: <input type="text" name="Nom_Sites"></label><br>
            <label>Date de construction: <input type="text" name="Date_de_construction_Sites"></label><br>
            <label>Capacité d'accueil: <input type="text" name="Capacite_d_acceuil_Sites"></label><br>
            <label>Accessibilité: <input type="text" name="Accessibilite_Sites"></label><br>
            <label>Ville: <input type="text" name="Nom_Villes"></label><br>
            <input type="submit" value="Filtrer">
        </form>

        <table>
            <tr>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Nom</th>
                <th>Date de construction</th>
                <th>Capacité d'accueil</th>
                <th>Accessibilité</th>
                <th>Ville</th>
            </tr>
            
            <?php while ($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row["Latitude_Sites"] ?></td>
                    <td><?= $row["Longitude_Sites"] ?></td>
                    <td><a href="https://www.google.com/maps/search/?api=1&query=<?= $row["Latitude_Sites"] ?>,<?= $row["Longitude_Sites"] ?>" target="_blank"><?= $row["Nom_Sites"] ?></a></td>
                    <td><?= $row["Date_de_construction_Sites"] ?></td>
                    <td><?= $row["Capacite_d_acceuil_Sites"] ?></td>
                    <td><?= $row["Accessibilite_Sites"] ?></td>
                    <td><?= $row["Nom_Villes"] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
</body>
</html>
