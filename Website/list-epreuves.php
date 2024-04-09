<?php
include 'libs/config.php';

global $db; // Récupère l'accès à la base de données

function getEpreuves($db, $filters) {
    $whereClause = '';
    foreach ($filters as $field => $value) {
        $validFields = array('ID_Epreuves', 'Nom_Epreuves', 'Name_Epreuves', 'Categorie_Epreuves', 'Type_Epreuves', 'Logo_Epreuves', 'Date_Debut', 'Date_Fin', 'ID_Sites','Nom_Sites');
        if (in_array($field, $validFields)) {
            if ($whereClause !== '') {
                $whereClause .= ' AND ';
            }
            $whereClause .= "$field LIKE :$field";
        }
    }

    $query = "SELECT * FROM Epreuves JOIN Se_Deroule ON Epreuves.ID_Epreuves = Se_Deroule.ID_Epreuves JOIN Sites ON Se_Deroule.Longitude_Sites = Sites.Longitude_Sites AND Se_Deroule.Latitude_Sites = Sites.Latitude_Sites";
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
    $epreuves = array();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $epreuves[] = $row;
    }

    return $epreuves;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $olympic = isset($_POST["olympic"]) ? $_POST["olympic"] : null;
    $paralympic = isset($_POST["paralympic"]) ? $_POST["paralympic"] : null;
    $individual = isset($_POST["individual"]) ? $_POST["individual"] : null;
    $collective = isset($_POST["collective"]) ? $_POST["collective"] : null;

    $filters = array();
    if (!empty($nom)) {
        $filters['Nom_Epreuves'] = $nom;
    }
    if ($olympic === "on") {
        $filters['Type_Epreuves'] = 0;
    }
    if ($paralympic === "on") {
        $filters['Type_Epreuves'] = 1;
    }
    if ($individual === "on") {
        $filters['Categorie_Epreuves'] = 0;
    }
    if ($collective === "on") {
        $filters['Categorie_Epreuves'] = 1;
    }
    $epreuves = getEpreuves($db, $filters);
} else {
    $epreuves = getEpreuves($db, array());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des Epreuves</title>
    <link rel="stylesheet" type="text/css" href="./css/all.css">

    <!-- VOTRE CSS -->
    <link rel="stylesheet" type="text/css" href="./css/epreuves-ceremonies.css">
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
        <a href="./list-ceremonies.php">Ceremonies</a>
        <a href="./index.php#transports">Transports</a>
        <a href="./list-sites.php">Sites</a>
    </div>

    <img src="./img/phryge.png" alt="mascotte paris2024">
</div>
<!-- FIN DE LA NAVIGATION -->

<div class="content">
    <h2>Liste des Epreuves</h2>
    <div class="options">
        <h3>Filtre</h3>
        <form method="post">
            <label for="nom">Nom Epreuve :</label>
            <input type="text" id="nom" name="nom">
            <label for="olympic">Epreuves olympiques :</label>
            <input type="checkbox" id="olympic" name="olympic">
            <label for="paralympic">Epreuves paralympiques :</label>
            <input type="checkbox" id="paralympic" name="paralympic">
            <br>
            <label for="individual">Epreuves individuelles :</label>
            <input type="radio" id="individual" name="individual">
            <label for="collective">Epreuves collectives :</label>
            <input type="radio" id="collective" name="collective">
            <br>
            <input type="submit" value="Filtrer">
        </form>
    </div>
    <table>
        <tr>
            <th>LOGO</th>
            <th>NOM EPREUVE</th>
            <th>NOM ANGLAIS EPREUVE</th>
            <th>CATEGORIE EPREUVE</th>
            <th>TYPE EPREUVE</th>
            <th>DATE EPREUVE</th>
            <th>SITE EPREUVE</th>
        </tr>
        <?php foreach ($epreuves as $epreuve): ?>
            <tr>
                <td><a href="epreuves-details.php?id=<?php echo $epreuve['ID_Epreuves']; ?>"><img src='./img/svgs/<?php echo $epreuve['Logo_Epreuves']; ?>' alt='<?php echo $epreuve['Nom_Epreuves']; ?>'></a></td>
                <td><a href="epreuves-details.php?id=<?php echo $epreuve['ID_Epreuves']; ?>"><?php echo $epreuve['Nom_Epreuves']; ?></a></td>
                <td><a href="epreuves-details.php?id=<?php echo $epreuve['ID_Epreuves']; ?>"><?php echo $epreuve['Name_Epreuves']; ?></a></td>
                <td><?php echo $epreuve['Categorie_Epreuves'] == 1 ? 'Collectif' : 'Individuel'; ?></td>
                <td><?php echo $epreuve['Type_Epreuves'] == 1 ? 'Paralympique' : 'Olympique'; ?></td>
                <td><?php echo $epreuve['Date_Debut'] . ' - ' . $epreuve['Date_Fin']; ?></td>
                <td><a href="sites-details.php?id=<?php echo $epreuve['Nom_Sites']; ?>"><?php echo $epreuve['Nom_Sites']; ?></a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<script src="./js/navigation.js"></script>
</body>
</html>
