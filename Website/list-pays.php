<?php
header('Content-Type: text/html; charset=UTF-8');
include 'libs/config.php';

global $db;

function getPays($db, $filters) {
    $whereClause = '';
    foreach ($filters as $field => $value) {
        $validFields = array('ID', 'Drapeau', 'Nom_Francais', 'Nom_Anglais');
        if (in_array($field, $validFields)) {
            if ($whereClause !== '') {
                $whereClause .= ' AND ';
            }
            $whereClause .= "$field LIKE :$field";
        }
    }

    $query = "SELECT Pays.*, COUNT(Athletes.ID) AS Nombre_Athletes FROM Pays JOIN Athletes ON Pays.ID = Athletes.ID";
    if ($whereClause !== '') {
        $query .= " WHERE $whereClause";
    }
    $query .= " GROUP BY Pays.ID";

    $statement = $db->prepare($query);

    foreach ($filters as $field => $value) {
        $statement->bindValue(":$field", "%$value%", PDO::PARAM_STR);
    }

    $statement->execute();
    $pays = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $pays[] = $row;
    }

    return $pays;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filters = array(
        'ID' => $_POST["id"],
        'Drapeau' => $_POST["drapeau"],
        'Nom_Francais' => $_POST["nom_francais"],
        'Nom_Anglais' => $_POST["nom_anglais"]
    );

    $pays = getPays($db, $filters);
} else {
    $pays = getPays($db, array());
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des Pays</title>
    <link rel="stylesheet" type="text/css" href="./css/all.css">
    <link rel="stylesheet" type="text/css" href="./css/stylePays.css">
    <!-- VOTRE CSS -->
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
        <a href="./index.php">
            <h2>Farfalles!</h2>
        </a>
        <div class="img" onclick="goTo('https://www.paris2024.org/fr/',true);">
            <img src="./img/paris2024.gif" alt="paris2024 image">
        </div>
    </nav>

    <div class="navigation">
        <div class="links">
            <a href="./index.php">Accueil</a>
            <a href="./list-epreuves.php">Epreuves</a>
            <a href="./list-athletes.php">Athletes</a>
            <a href="./index.php#transports">Transports</a>
            <a href="./list-sites.php">Sites</a>
        </div>

        <img src="./img/phryge.png" alt="mascotte paris2024">
    </div>

    <!-- FIN DE LA NAVIGATION -->

    <!-- Affichage de la liste des pays -->
    <div class="pays-liste">
        <h2>Liste des Pays</h2>
        <ul>
            <?php foreach ($pays as $pays) : ?>
                <li>
                    <a href="<?php echo $pays['Drapeau']; ?>" target="_blank">
                        <img src="<?php echo $pays['Drapeau']; ?>" alt="Drapeau du pays">
                    </a>
                    <strong><?php echo $pays['ID']; ?></strong>
                    <span><strong>FR : </strong><?php echo $pays['Nom_Francais']; ?></span> 
                    <span><strong>EN : </strong><?php echo $pays['Nom_Anglais']; ?></span> 
                    <span><strong>Nombre d'athlètes :</strong> <?php echo $pays['Nombre_Athletes']; ?></span> 
                    <span><a href="https://www.olympedia.org/countries/<?php echo $pays['ID']; ?>" target="_blank">En savoir plus</a></span>
                    <span><a href="https://fr.wikipedia.org/wiki/<?php echo $pays['Nom_Francais']; ?>" target="_blank">Wikipedia du pays</a></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script src="./js/navigation.js"></script>
</body>

</html>
