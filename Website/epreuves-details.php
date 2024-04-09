<?php

include 'libs/config.php';
global $db; // Recupere l'acces à la base de donnee

$epreuve = null;
$record = null;
$athleterecord = null;
if(isset($_GET['id'])) {
    $epreuve_id = $_GET['id'];
    $query = "
        SELECT * 
        FROM Epreuves 
        WHERE ID_Epreuves = :epreuve_id;";
    $statement = $db->prepare($query);
    $statement->bindParam(':epreuve_id', $epreuve_id, PDO::PARAM_INT);
    $statement->execute();
    $epreuve = $statement->fetch(PDO::FETCH_ASSOC);

    $query2 = "
        SELECT * 
        FROM Athletes 
        WHERE ID_Epreuves = :epreuve_id;";
    $statement2 = $db->prepare($query2);
    $statement2->bindParam(':epreuve_id', $epreuve_id, PDO::PARAM_INT);
    $statement2->execute();

    $athletes = $statement2->fetchAll(PDO::FETCH_ASSOC);
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
            <a href="./list-epreuves.pho">Epreuves</a>
            <a href="./list-athletes.php">Athletes</a>
            <a href="./list-ceremonies.php">Ceremonies</a>
            <a href="./index.php#transports">Transports</a>
            <a href="./list-sites.php">Sites</a>
        </div>
        <img src="./img/phryge.png" alt="mascotte paris2024">
    </div>
    <!-- FIN DE LA NAVIGATION -->

    <div class="content">
        <?php if($epreuve): ?>
            <img alt="Logo de L'épreuve" src="./img/svgs/<?php echo $epreuve['Logo_Epreuves']; ?>">
            <h2><?php echo $epreuve['Nom_Epreuves']; ?></h2>
            <h2><?php echo $epreuve['Name_Epreuves']; ?></h2>
            <h3><?php echo $epreuve['ID_Epreuves']; ?></h3>
            <?php
            if ($epreuve['Categorie_Epreuves'] == 1){
                    echo "<h3>Collectif</h3>";
                }
                else {
                    echo "<h3>Individuel</h3>";
                }
                if ($epreuve['Type_Epreuves'] == 1){
                    echo "<h3>Paralympique</h3>";
                }
                else {
                    echo "<h3>Olympique</h3>";
                }
                ?>
            <h3>LISTE ATHLETES</h3>
            <?php foreach ($athletes as $athlete): ?>
				<li>
					
					<a href="athlete-details.php?id=<?php echo $athlete['ID_Athletes']; ?>"><?php echo $athlete['Nom_Athletes']; ?></a>
					<a href="athlete-details.php?id=<?php echo $athlete['ID_Athletes']; ?>"><?php echo $athlete['ID']; ?></a>
					<a href="athlete-details.php?id=<?php echo $athlete['ID_Athletes']; ?>"><?php echo $athlete['Date_naissance_Athletes']; ?></a>
					<a href="athlete-details.php?id=<?php echo $athlete['ID_Athletes']; ?>"><?php echo $athlete['Lieu_naissance_Athletes']; ?></a>

				</li>
			<?php endforeach; ?>
            
        <?php else: ?>
            <p>Epreuve non trouvée.</p>
        <?php endif; ?>
    </div>

    <script src="./js/navigation.js"></script>
</body>
</html>
