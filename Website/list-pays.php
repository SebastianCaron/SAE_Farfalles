<?php
include 'libs/config.php';

global $db;

function getPays($db, $filters) {
    $whereClause = '';
    foreach ($filters as $field => $value) {
        $validFields = array('ID', 'Drapeau', 'Nom_Français', 'Nom_Anglais');
        if (in_array($field, $validFields)) {
            if ($whereClause !== '') {
                $whereClause .= ' AND ';
            }
            $whereClause .= "$field LIKE :$field";
        }
    }

    $query = "SELECT * FROM Pays";
    if ($whereClause !== '') {
        $query .= " WHERE $whereClause";
    }

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
        'Nom_Français' => $_POST["nom_francais"],
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
        <a href="./index.html">
            <h2>Farfalles!</h2>
        </a>
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

    <!-- Affichage de la liste des pays -->
    <div class="pays-liste">
        <h2>Liste des Pays</h2>
        <ul>
            <?php foreach ($pays as $pays) : ?>
                <li>
                    <strong>ID:</strong> <?php echo $pays['ID']; ?>,
                    <strong>Drapeau:</strong> <?php echo $pays['Drapeau']; ?>,
                    <strong>Nom Français:</strong> <?php echo $pays['Nom_Français']; ?>,
                    <strong>Nom Anglais:</strong> <?php echo $pays['Nom_Anglais']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script src="./js/navigation.js"></script>
</body>

</html>