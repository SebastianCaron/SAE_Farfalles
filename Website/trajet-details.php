<?php
include 'libs/config.php';
global $db; // Récupère l'accès à la base de données

$voyage = null;
if(isset($_GET['id'])) {
    $voyage_id = $_GET['id'];
    $query = "SELECT * FROM Arrets WHERE ID_Voyages = :voyage_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':voyage_id', $voyage_id, PDO::PARAM_STR);
    $statement->execute();
    $voyage = $statement->fetch(PDO::FETCH_ASSOC);

    $query_arrets = "
        SELECT * 
        FROM Arrets 
        WHERE ID_Voyages = :voyage_id
        ORDER BY Numero_Arrets ASC";
    $statement_arrets = $db->prepare($query_arrets);
    $statement_arrets->bindParam(':voyage_id', $voyage_id, PDO::PARAM_STR);
    $statement_arrets->execute();
    $arrets = $statement_arrets->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détails du Voyage</title>
    <link rel="stylesheet" type="text/css" href="./css/all.css">
    
    <!-- VOTRE CSS -->
    <link rel="stylesheet" type="text/css" href="./css/details.css">

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
        <?php if($voyage): ?>
            <p>ID: <?php echo $voyage['ID_Voyages']; ?></p>

            <h2>Arrêts du Voyage :</h2>
            <ul>
                <?php foreach ($arrets as $arret): ?>
                    <li><a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo $arret['Longitude_Arrets'];?>,<?php echo $arret['Latitude_Arrets'];?>"><?php echo $arret['Nom_Arrets']; ?> -  <?php echo $arret['Heure_depart_Arrets']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Voyage non trouvé.</p>
        <?php endif; ?>
    </div>

    <script src="./js/navigation.js"></script>
</body>
</html>
