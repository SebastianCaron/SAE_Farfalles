<?php
include 'libs/config.php';

global $db; // Recupere l'acces à la base de donnee

function getArrets($db, $filters) {
    $whereClause = '';
    foreach ($filters as $field => $value) {
        $validFields = array('ID_Arrets', 'ID_Voyages', 'Nom_Arrets', 'Latitude_Arrets', 'Longitude_Arrets', 'Heure_arrive_Arrets', 'Heure_depart_Arrets', 'Numero_Arrets');
        if (in_array($field, $validFields)) {
            if ($whereClause !== '') {
                $whereClause .= ' AND ';
            }
            $whereClause .= "$field LIKE :$field";
        }
    }

    $query = "SELECT * FROM Arrets";
    if ($whereClause !== '') {
        $query .= " WHERE $whereClause";
    }

    // LIMITE
    $query .= " LIMIT 100";

    $statement = $db->prepare($query);

    foreach ($filters as $field => $value) {
        $statement->bindValue(":$field", "%$value%", PDO::PARAM_STR);
    }

    $statement->execute();
    $arrets = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $arrets[] = $row;
    }

    return $arrets;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomArret = $_POST["nom_arret"];
    $idVoyages = $_POST["id_voyages"];

    $filters = array();
    if (!empty($nomArret)) {
        $filters['Nom_Arrets'] = $nomArret;
    }
    if (!empty($idVoyages)) {
        $filters['ID_Voyages'] = $idVoyages;
    }
    $arrets = getArrets($db, $filters);
} else {
    $arrets = getArrets($db, array());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des Arrêts</title>
    <link rel="stylesheet" type="text/css" href="./css/all.css">

    <!-- VOTRE CSS -->
    <link rel="stylesheet" type="text/css" href="./css/list-athletes.css">

    <!-- VOS SCRIPTS -->

</head>
<body>

<!-- NAVIGATION -->
<script></script>
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
    <h2>Liste des Arrêts</h2>

    <div class="options">
        <h3>Filtre :</h3>
        <form method="post">
            <label for="nom_arret">Nom de l'Arrêt :</label>
            <input type="text" id="nom_arret" name="nom_arret">

            <label for="id_voyages">ID du Voyage :</label>
            <input type="text" id="id_voyages" name="id_voyages">

            <br>
            <br>
            <input type="submit" value="Filtrer">
        </form>
    </div>

    <ul>
        <li>
            <a>NOM DE L'ARRÊT</a>
            <a>ID DU VOYAGE</a>
            <a>LATITUDE</a>
            <a>LONGITUDE</a>
            <a>HEURE ARRIVÉE</a>
            <a>HEURE DÉPART</a>
            <a>NUMÉRO</a>
        </li>
        <?php foreach ($arrets as $arret): ?>
            <li>
                <a href="arret-details.php?id=<?php echo $arret['ID_Arrets']; ?>"><?php echo $arret['Nom_Arrets']; ?></a>
                <a href="arret-details.php?id=<?php echo $arret['ID_Arrets']; ?>"><?php echo $arret['ID_Voyages']; ?></a>
                <a href="arret-details.php?id=<?php echo $arret['ID_Arrets']; ?>"><?php echo $arret['Latitude_Arrets']; ?></a>
                <a href="arret-details.php?id=<?php echo $arret['ID_Arrets']; ?>"><?php echo $arret['Longitude_Arrets']; ?></a>
                <a href="arret-details.php?id=<?php echo $arret['ID_Arrets']; ?>"><?php echo $arret['Heure_arrive_Arrets']; ?></a>
                <a href="arret-details.php?id=<?php echo $arret['ID_Arrets']; ?>"><?php echo $arret['Heure_depart_Arrets']; ?></a>
                <a href="arret-details.php?id=<?php echo $arret['ID_Arrets']; ?>"><?php echo $arret['Numero_Arrets']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script src="./js/navigation.js"></script>
</body>
</html>
