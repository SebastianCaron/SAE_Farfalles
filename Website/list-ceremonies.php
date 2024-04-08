<?php
    include 'libs/config.php';

    global $db; // Recupere l'acces à la base de donnee


	function getCeremonies($db, $filters) {
		$whereClause = '';
		foreach ($filters as $field => $value) {
			$validFields = array('ID_Ceremonies', 'Nom_Ceremonies', 'Longitude_Site', 'Latitude_Site', 'Date_Debut', 'Date_Fin', 'Nom_Sites');
			if (in_array($field, $validFields)) {
				if ($whereClause !== '') {
					$whereClause .= ' AND ';
				}
				$whereClause .= "$field LIKE :$field";
			}
		}
	
		$query = "SELECT * FROM Ceremonies JOIN Se_Produit ON Ceremonies.ID_Ceremonies = Se_Produit.ID_Ceremonies JOIN Sites ON Se_Produit.Longitude_Sites = Sites.Longitude_Sites AND Se_Produit.Latitude_Sites = Sites.Latitude_Sites";
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
		$ceremonies = array();
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$ceremonies[] = $row;
		}
	
		return $ceremonies;
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$nom = $_POST["nom"];
	
		$filters = array();
		if (!empty($nom)) {
			$filters['Nom_Ceremonies'] = $nom;
		}
		$ceremonies = getCeremonies($db, $filters);
	}else{
		$ceremonies = getCeremonies($db, array());
	}
	?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Liste Cérémonies</title>
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
		<a href="./index.html"><h2>Farfalles!</h2></a>
		<div class="img" onclick="goTo('https://www.paris2024.org/fr/',true);">
			<img src="./img/paris2024.gif" alt="paris2024 image">
		</div>
	</nav>

	<div class="navigation">
		<div class="links">
			<a href="./index.html">Accueil</a>
            <a href="./list-athletes.html">Athlètes</a>
			<a href="./list-epreuves.html">Épreuves</a>
			<a href="./list-ceremonies.html">Ceremonies</a>
			<a href="./list-transports.html">Transports</a>
			<a href="./list-sites.html">Sites</a>
		</div>

		<img src="./img/phryge.png" alt="mascotte paris2024">
	</div>

	<!-- FIN DE LA NAVIGATION -->

	<div class="content">
		<h2>Liste des Ceremonies</h2>
		<img src="./img/svgs/svg_ceremonies.svg" alt="svg logo ceremonie">
		<div class="options">
			<h3>Filtre</h3>
			<from method="post">
				<label for="nom">Nom Epreuve :</label>
				<input type="text" id="nom" name="nom">
				<br>
				<br>
				<input type="submit" value="Filtrer">
			</from>
		</div>
		<table>
			<tr>
				<th>NOM CEREMONIE</th>
				<th>DATE CEREMONIE</th>
				<th>SITE CEREMONIE</th>
            </tr>
            <?php foreach ($ceremonies as $ceremonies): ?>
            <tr>
                <td><?php echo $ceremonies['Nom_Ceremonies']; ?></td>
                <td><?php echo $ceremonies['Date_Debut'] . ' - ' . $ceremonies['Date_Fin']; ?></td>
                <td><a href="sites-details.php?id=<?php echo $ceremonies['ID_Sites']; ?>"><?php echo $ceremonies['Nom_Sites']; ?></a></td>
            </tr>
        <?php endforeach; ?>
</table>
	</div>
	<script src="./js/navigation.js"></script>
</body>
</html>
