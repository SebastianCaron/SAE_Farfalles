<?php

include 'libs/config.php';
global $db; // Recupere l'acces à la base de donnee

$site = null;
if(isset($_GET['id'])) {
    $site_id = $_GET['id'];
    $query = "SELECT * FROM Sites WHERE ID_Sites = :site_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':site_id', $site_id, PDO::PARAM_INT);
    $statement->execute();
    $site = $statement->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Template</title>
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
            <a href="./index.php#transports">Transports</a>
            <a href="./list-sites.php">Sites</a>
        </div>
        <img src="./img/phryge.png" alt="mascotte paris2024">
    </div>
    <!-- FIN DE LA NAVIGATION -->
    Latitude_Sites, Longitude_Sites, Nom_Sites, Date_de_construction_Sites, Capacite_d_acceuil_Sites, Accessibilite_Sites, Nom_Villes
    <div class="content">
        <?php if($site): ?>
            <h2><?php echo $site['Nom_sites']; ?></h2>
            <h3><?php echo $site['Latitude_Sites']; ?></h3>
            <h3><?php echo $site['Longitude_Sites']; ?></h3>
            <h3><?php echo $site['Date_de_construction_Sites']; ?></h3>
            <h3><?php echo $site['Capacite_d_acceuil_Sites']; ?></h3>
            <h3><?php echo $site['Accessibilite_Sites']; ?></h3>
            <h3><?php echo $site['Nom_Villes']; ?></h3>

        <?php else: ?>
            <p>Site non trouvé.</p>
        <?php endif; ?>
    </div>

    <script src="./js/navigation.js"></script>
</body>
</html>