<?php
    include 'libs/config.php';

    global $db; // Accès à la base de données

    function getAgences($db, $filters) {
        $whereClause = '';
        foreach ($filters as $field => $value) {
            $validFields = array('ID_Agences', 'Nom_Agences', 'Timezone_Agences', 'Lang_Agences', 'Tel_Agences', 'Email_Agences');
            if (in_array($field, $validFields)) {
                if ($whereClause !== '') {
                    $whereClause .= ' AND ';
                }
                $whereClause .= "$field LIKE :$field";
            }
        }
    
        $query = "SELECT * FROM Agences";
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
        $agences = array();
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $agences[] = $row;
        }
    
        return $agences;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST["nom"];
        $timezone = $_POST["timezone"];
        // Ajouter d'autres filtres selon vos besoins

        $filters = array();
        if (!empty($nom)) {
            $filters['Nom_Agences'] = $nom;
        }
        if (!empty($timezone)) {
            $filters['Timezone_Agences'] = $timezone;
        }
        // Ajouter d'autres filtres selon vos besoins

        $agences = getAgences($db, $filters);
    } else {
        $agences = getAgences($db, array());
    }
?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>list-agence</title>
	<link rel="stylesheet" type="text/css" href="./css/all.css">

	<!-- VOTRE CSS -->
	<link rel="stylesheet" type="text/css" href="./css/list-athletes.css">

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
    <h2>Liste des Agences</h2>

    <div class="options">
        <h3>Filtre :</h3>
        <form method="post">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom">
            
            <label for="timezone">Timezone :</label>
            <input type="text" id="timezone" name="timezone">
            <!-- Ajouter d'autres champs de filtre selon vos besoins -->

            <br><br>
            <input type="submit" value="Filtrer">
        </form>
    </div>

    <ul>
        <li>
            <a>Nom de l'Agence</a>
            <a>URL</a>
            <a>Timezone</a>
            <a>Langue</a>
            <a>Téléphone</a>
            <a>Email</a>
        </li>
        <?php foreach ($agences as $agence): ?>
            <li>
                <a href="agence-details.php?id=<?php echo $agence['ID_Agences']; ?>"><?php echo $agence['Nom_Agences']; ?></a>
                <a href="<?php echo $agence['Url_Agences']; ?>"><?php echo $agence['Url_Agences']; ?></a>
                <a href="agence-details.php?id=<?php echo $agence['ID_Agences']; ?>"><?php echo $agence['Timezone_Agences']; ?></a>
                <a href="agence-details.php?id=<?php echo $agence['ID_Agences']; ?>"><?php echo $agence['Lang_Agences']; ?></a>
                <a href="agence-details.php?id=<?php echo $agence['ID_Agences']; ?>"><?php echo $agence['Tel_Agences']; ?></a>
                <a href="agence-details.php?id=<?php echo $agence['ID_Agences']; ?>"><?php echo $agence['Email_Agences']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>


	<script src="./js/navigation.js"></script>
</body>
</html>