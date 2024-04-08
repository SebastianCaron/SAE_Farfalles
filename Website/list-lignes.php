<?php
include 'libs/config.php';
global $db; // Récupère l'accès à la base de données

function getLignes($db, $filters) {
    $whereClause = '';
    foreach ($filters as $field => $value) {
        $validFields = array('ID_Lignes', 'Nom_Lignes', 'Nom_long_Lignes', 'Mode_Lignes', 'ID_Agences');
        if (in_array($field, $validFields)) {
            if ($whereClause !== '') {
                $whereClause .= ' AND ';
            }
            $whereClause .= "$field LIKE :$field";
        }
    }

    $query = "SELECT * FROM Lignes";
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
    $lignes = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $lignes[] = $row;
    }

    return $lignes;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $nomLong = $_POST["nom_long"];
    $mode = $_POST["mode"];
    $agence = $_POST["agence"];

    $filters = array();
    if (!empty($nom)) {
        $filters['Nom_Lignes'] = $nom;
    }
    if (!empty($nomLong)) {
        $filters['Nom_long_Lignes'] = $nomLong;
    }
    if (!empty($mode)) {
        $filters['Mode_Lignes'] = $mode;
    }
    if (!empty($agence)) {
        $filters['ID_Agences'] = $agence;
    }

    $lignes = getLignes($db, $filters);
} else {
    $lignes = getLignes($db, array());
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des lignes</title>
    <link rel="stylesheet" type="text/css" href="./css/all.css">

    <!-- VOTRE CSS -->
    <link rel="stylesheet" type="text/css" href="./css/list-athletes.css">

    <!-- VOS SCRIPTS -->

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
        <a href="./index.php"><h2>Farfalles!</h2></a>
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

<div class="content">
    <h2>Liste des Lignes</h2>

    <div class="options">
        <h3>Filtre :</h3>
        <form method="post">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom">

            <label for="nom_long">Nom long :</label>
            <input type="text" id="nom_long" name="nom_long">

            <label for="mode">Mode :</label>
            <input type="text" id="mode" name="mode">

            <label for="agence">ID Agence :</label>
            <input type="text" id="agence" name="agence">
            <br>
            <br>
            <input type="submit" value="Filtrer">
        </form>
    </div>

    <ul>
        <li>
            <a>Nom</a>
            <a>Nom long</a>
            <a>Mode</a>
            <a>Agence</a>
        </li>
        <?php foreach ($lignes as $ligne): ?>
            <li>
                <a href="ligne-details.php?id=<?php echo $ligne['ID_Lignes']; ?>"><?php echo $ligne['Nom_Lignes']; ?></a>
                <a href="ligne-details.php?id=<?php echo $ligne['ID_Lignes']; ?>"><?php echo $ligne['Nom_long_Lignes']; ?></a>
                <a href="ligne-details.php?id=<?php echo $ligne['ID_Lignes']; ?>"><?php echo $ligne['Mode_Lignes']; ?></a>
                <a href="agence-details.php?id=<?php echo $ligne['ID_Agences']; ?>"><?php echo $ligne['ID_Agences']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script src="./js/navigation.js"></script>
</body>
</html>
