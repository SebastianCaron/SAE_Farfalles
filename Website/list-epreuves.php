<?php
    include 'libs/config.php';

    global $db; // Recupere l'acces à la base de donnee


	function getEpreuves($db, $filters) {
		$whereClause = '';
		foreach ($filters as $field => $value) {
			$validFields = array('ID_Epreuves', 'Nom_Epreuves', 'Name_Epreuves', 'Categorie_Epreuves', 'Type_Epreuves', 'Logo_Epreuves');
			if (in_array($field, $validFields)) {
				if ($whereClause !== '') {
					$whereClause .= ' AND ';
				}
				$whereClause .= "$field LIKE :$field";
			}
		}
	
		$query = "SELECT * FROM Epreuves";
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
		$olympic = $_POST["olympic"];
		$paralympic = $_POST["paralympic"];
		$individual = $_POST["individual"];
		$collective = $_POST["collective"];
	
		$filters = array();
		if (!empty($nom)) {
			$filters['Nom_Epreuves'] = $nom;
		}
		if (!empty($olympic)) {
			$filters['Categorie_Epreuve'] = 0;
		}
		if (!empty($paralympic)) {
			$filters['Categorie_Epreuve'] = 1;
		}
		if (!empty($individual)) {
			$filters['Type_Epreuves'] = 0;
		}
		if (!empty($collective)) {
			$filters['Type_Epreuves'] = 1;
		}
		$epreuves = getEpreuves($db, $filters);
	}else{
		$epreuves = getEpreuves($db, array());
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
			<a href="./list-Epreuves.html">Epreuves</a>
			<a href="./list-transports.html">Transports</a>
			<a href="./list-sites.html">Sites</a>
		</div>

		<img src="./img/phryge.png" alt="mascotte paris2024">
	</div>

	<!-- FIN DE LA NAVIGATION -->

	<div class="content">
		<h2>Liste des Epreuves</h2>
		<div class="options">
			<h3>Filtre</h3>
			<from method="post">
				<label for="nom">Nom Epreuve :</label>
				<input type="text" id="nom" name="nom">
				<label for="olympic">Epreuves olympiques :</label>
				<input type="radio" id="olympic" checked>
				<label for="paralympic">Epreuves paralympiques :</label>
				<input type="radio" id="paralympic" checked>
				<label for="individual">Epreuves individuelles :</label>
				<input type="radio" id="individual" checked>
				<label for="collective">Epreuves collectives :</label>
				<input type="radio" id="collective" checked>
				<br>
				<br>
				<input type="submit" value="Filtrer">
			</from>
		</div>
		<table>
			<tr>
                <th>.</th>
				<th>NOM EPREUVE</th>
				<th>NOM ANGLAIS EPREUVE</th>
				<th>CATEGORIE EPREUVE</th>
				<th>TYPE EPREUVE</th>
				<th>DATE EPREUVE</th>
				<th>SITE EPREUVE</th>
            </tr>
            <?php foreach ($epreuves as $epreuves):
				echo "<tr>";
                echo "<td><img src='" . $res['Logo_Epreuves'] . "' alt='" . $res['Nom_Epreuves'] . "'></td>";
                echo "<td>" . $res['Nom_Epreuves'] . "</td>";
                echo "<td>" . $res['Name_Epreuves'] . "</td>";
                if ($res['Categorie_Epreuves'] == 1){
                    echo "<td>Collectif</td>";
                }
                else {
                    echo "<td>Individuel</td>";
                }
                if ($res['Type_Epreuves'] == 1){
                    echo "<td>Paralympique</td>";
                }
                else {
                    echo "<td>Olympique</td>";
                }
                echo "<td>DATEDATEDAET</td>";
                echo "<td>SITESITESITE</td>";
                echo "</tr>";
			    endforeach; 
            ?>

	</div>
	<script src="./js/navigation.js"></script>
</body>
</html>
