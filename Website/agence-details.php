<?php
include 'libs/config.php';
global $db; // Récupère l'accès à la base de données

$agence = null;
if(isset($_GET['id'])) {
    $agence_id = $_GET['id'];
    $query = "SELECT * FROM Agences WHERE ID_Agences = :agence_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':agence_id', $agence_id, PDO::PARAM_STR);
    $statement->execute();
    $agence = $statement->fetch(PDO::FETCH_ASSOC);
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

    <div class="content">
        <?php if($agence): ?>
            <h1>Détails de l'agence : <?php echo $agence['Nom_Agences']; ?></h1>
            <p>ID: <?php echo $agence['ID_Agences']; ?></p>
            <p>Timezone: <?php echo $agence['Timezone_Agences']; ?></p>
            <p>Langue: <?php echo $agence['Lang_Agences']; ?></p>
            <p>Téléphone: <?php echo $agence['Tel_Agences']; ?></p>
            <p>Email: <?php echo $agence['Email_Agences']; ?></p>
            <p><a href="<?php echo $agence['Tarifs_Url_Agences']; ?>">Voir les tarifs</a></p>
        <?php else: ?>
            <p>Agence non trouvée.</p>
        <?php endif; ?>
    </div>

    <script src="./js/navigation.js"></script>
</body>
</html>
