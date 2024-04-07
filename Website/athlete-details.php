<?php

include 'libs/config.php';
global $db; // Recupere l'acces à la base de donnee

$athlete = null;
if(isset($_GET['id'])) {
    $athlete_id = $_GET['id'];
    $query = "SELECT * FROM Athletes WHERE ID_Athletes = :athlete_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':athlete_id', $athlete_id, PDO::PARAM_INT);
    $statement->execute();
    $athlete = $statement->fetch(PDO::FETCH_ASSOC);
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
        <?php if($athlete): ?>
            <img alt="Image De l'athlete" src="<?php echo $athlete['Image_url_Athletes']; ?>">
            <h2><?php echo $athlete['Nom_Athletes']; ?></h2>
            <h3><?php echo $athlete['ID_Epreuves']; ?></h3>
            <h3><?php echo $athlete['ID']; ?></h3>
            <h3><?php echo $athlete['Lieu_naissance_Athletes']; ?></h3>
            <h3><?php echo $athlete['Date_naissance_Athletes']; ?></h3>
            <h3><?php echo $athlete['Taille_Athletes']; ?></h3>

            <a href="<?php echo $athlete['Profil_url_Athletes']; ?>">Voir son Profil</a>
        <?php else: ?>
            <p>Athlete non trouvé.</p>
        <?php endif; ?>
    </div>

    <script src="./js/navigation.js"></script>
</body>
</html>
