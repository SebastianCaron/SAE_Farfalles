<?php
include 'libs/config.php';

global $db; // Recupere l'acces à la base de donnee

function getVoyages($db, $filters) {
    $whereClause = '';
    foreach ($filters as $field => $value) {
        $validFields = array('ID_Voyages', 'ID_Lignes', 'Nom_Voyages', 'Accessible_Fauteuil_Voyages', 'Accessible_Velo_Voyages');
        if (in_array($field, $validFields)) {
            if ($whereClause !== '') {
                $whereClause .= ' AND ';
            }
            $whereClause .= "$field LIKE :$field";
        }
    }

    $query = "SELECT * FROM Voyages";
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
    $voyages = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $voyages[] = $row;
    }

    return $voyages;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomVoyage = $_POST["nom_voyage"];
    $idLignes = $_POST["id_lignes"];

    $filters = array();
    if (!empty($nomVoyage)) {
        $filters['Nom_Voyages'] = $nomVoyage;
    }
    if (!empty($idLignes)) {
        $filters['ID_Lignes'] = $idLignes;
    }
    $voyages = getVoyages($db, $filters);
} else {
    $voyages = getVoyages($db, array());
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des Voyages</title>
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
            <a href="./transports.php">Transports</a>
            <a href="./list-sites.php">Sites</a>
        </div>
        <img src="./img/phryge.png" alt="mascotte paris2024">
    </div>
    <!-- FIN DE LA NAVIGATION -->

<div class="content">
    <h2>Liste des Voyages</h2>

    <div class="options">
        <h3>Filtre :</h3>
        <form method="post">
            <label for="nom_voyage">Nom du Voyage :</label>
            <input type="text" id="nom_voyage" name="nom_voyage">

            <label for="id_lignes">ID de la Ligne :</label>
            <input type="text" id="id_lignes" name="id_lignes">

            <br>
            <br>
            <input type="submit" value="Filtrer">
        </form>
    </div>

    <ul>
        <li>
            <a>NOM DU VOYAGE</a>
            <a>ID DE LA LIGNE</a>
            <a>ACCESSIBLE FAUTEUIL</a>
            <a>ACCESSIBLE VELO</a>
        </li>
        <?php foreach ($voyages as $voyage): ?>
            <li>
                <a href="voyage-details.php?id=<?php echo $voyage['ID_Voyages']; ?>"><?php echo $voyage['Nom_Voyages']; ?></a>
                <a href="voyage-details.php?id=<?php echo $voyage['ID_Voyages']; ?>"><?php echo $voyage['ID_Lignes']; ?></a>
                <a href="voyage-details.php?id=<?php echo $voyage['ID_Voyages']; ?>"><?php echo $voyage['Accessible_Fauteuil_Voyages'] ? 'Oui' : 'Non'; ?></a>
                <a href="voyage-details.php?id=<?php echo $voyage['ID_Voyages']; ?>"><?php echo $voyage['Accessible_Velo_Voyages'] ? 'Oui' : 'Non'; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<script src="./js/navigation.js"></script>
</body>
</html>
