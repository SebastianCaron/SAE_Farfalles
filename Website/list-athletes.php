<?php
    include 'libs/config.php';

    global $db; // Recupere l'acces à la base de donnee


	function getAthletes($db, $filters) {
		$whereClause = '';
		foreach ($filters as $field => $value) {
			$validFields = array('ID_Athletes', 'Nom_Athletes', 'Date_naissance_Athletes', 'Lieu_naissance_Athletes', 'Taille_Athletes', 'ID_Epreuves', 'ID');
			if (in_array($field, $validFields)) {
				if ($whereClause !== '') {
					$whereClause .= ' AND ';
				}
				$whereClause .= "$field LIKE :$field";
			}
		}
	
		$query = "SELECT * FROM Athletes";
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
		$athletes = array();
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$athletes[] = $row;
		}
	
		return $athletes;
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$nom = $_POST["nom"];
		$dateNaissance = $_POST["date_naissance"];
		$lieuNaissance = $_POST["lieu_naissance"];
		$epreuve = $_POST["epreuve"];
		$pays = $_POST["pays"];
	
		$filters = array();
		if (!empty($nom)) {
			$filters['Nom_Athletes'] = $nom;
		}
		if (!empty($dateNaissance)) {
			$filters['Date_naissance_Athletes'] = $dateNaissance;
		}
		if (!empty($lieuNaissance)) {
			$filters['Lieu_naissance_Athletes'] = $lieuNaissance;
		}
		if (!empty($epreuve)) {
			$filters['ID_Epreuves'] = $epreuve;
		}
		if (!empty($pays)) {
			$filters['ID'] = $pays;
		}
		$athletes = getAthletes($db, $filters);
	}else{
		$athletes = getAthletes($db, array());
	}
	?>
	


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>list-athletes</title>
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
		<h2>Liste des Athletes</h2>

		<div class="options">
			<h3>Filtre :</h3>
			<form method="post">
				<label for="nom">Nom :</label>
				<input type="text" id="nom" name="nom">
				
				<label for="date_naissance">Date de naissance :</label>
				<input type="date" id="date_naissance" name="date_naissance">
		
				<label for="lieu_naissance">Lieu de naissance :</label>
				<input type="text" id="lieu_naissance" name="lieu_naissance">
		
				<label for="epreuve">ID Epreuves :</label>
				<input type="text" id="epreuve" name="epreuve">
		
				<label for="pays">ID Pays :</label>
				<input type="text" id="pays" name="pays">
				<br>
				<br>
				<input type="submit" value="Filtrer">
			</form>
		</div>

		<ul>
			<li>
				<a>NOM PRENOM</a>
				<a>DISCIPLINE</a>
				<a>PAYS</a>
				<a>DATE NAISSANCE</a>
				<a>LIEU NAISSANCE</a>
			</li>
			<?php foreach ($athletes as $athlete): ?>
				<li>
					
					<a href="athlete-details.php?id=<?php echo $athlete['ID_Athletes']; ?>"><?php echo $athlete['Nom_Athletes']; ?></a>
					<a href="athlete-details.php?id=<?php echo $athlete['ID_Athletes']; ?>"><?php echo $athlete['ID_Epreuves']; ?></a>
					<a href="athlete-details.php?id=<?php echo $athlete['ID_Athletes']; ?>"><?php echo $athlete['ID']; ?></a>
					<a href="athlete-details.php?id=<?php echo $athlete['ID_Athletes']; ?>"><?php echo $athlete['Date_naissance_Athletes']; ?></a>
					<a href="athlete-details.php?id=<?php echo $athlete['ID_Athletes']; ?>"><?php echo $athlete['Lieu_naissance_Athletes']; ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<script src="./js/navigation.js"></script>
</body>
</html>