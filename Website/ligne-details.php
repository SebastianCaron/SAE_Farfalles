<?php
include 'libs/config.php';
global $db; // Récupère l'accès à la base de données

$ligne = null;
if(isset($_GET['id'])) {
    $ligne_id = $_GET['id'];
    $query = "SELECT * FROM Lignes WHERE ID_Lignes = :ligne_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':ligne_id', $ligne_id, PDO::PARAM_STR);
    $statement->execute();
    $ligne = $statement->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Détails de la ligne</title>
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
        <?php if($ligne): ?>
            <h1>Détails de la ligne : <?php echo $ligne['Nom_Lignes']; ?></h1>
            <p>ID: <?php echo $ligne['ID_Lignes']; ?></p>
            <p>Nom long: <?php echo $ligne['Nom_long_Lignes']; ?></p>
            <p>Mode: <?php echo $ligne['Mode_Lignes']; ?></p>
            <p>ID Agence: <?php echo $ligne['ID_Agences']; ?></p>
        <?php else: ?>
            <p>Ligne non trouvée.</p>
        <?php endif; ?>
    </div>

    <script src="./js/navigation.js"></script>
</body>
</html>
