<?php 
    include 'libs/config.php';

    global $db;

    $sql = 'SELECT * FROM Sites';
    $sql2 = 'SELECT * FROM Epreuves';

    $auMoinsUnChamp = false;

    // Vérification des champs du formulaire
    $champs = ['Latitude_Sites', 'Longitude_Sites', 'Nom_Sites', 'Date_de_construction_Sites', 'Capacite_d_acceuil_Sites', 'Accessibilite_Sites', 'Nom_Villes'];
    foreach ($champs as $champ) {
        if (!empty($_POST[$champ])) {
            // Si un champ est rempli, on ajoute une condition à la requête SQL
            if (!$auMoinsUnChamp) {
                $sql .= ' WHERE ';
                $auMoinsUnChamp = true;
            } else {
                $sql .= ' AND ';
            }
            $sql .= "$champ LIKE '%" . $_POST[$champ] . "%'";
        }
    }

    // Si au moins 1 champ est rempli, on exécute la requête
    if ($auMoinsUnChamp) {
        $query = $db->prepare($sql);
        $query->execute();
    }else{
        $query = $db->prepare("SELECT * FROM Sites;");
        $query->execute();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paris 2024 - Sites</title>
    <link rel="stylesheet" type="text/css" href="./css/all.css">


    <!-- VOTRE CSS -->
    <link rel="stylesheet" type="text/css" href="./css/list-sites.css">
    

    <!-- VOS SCRIPTS -->

</head>
<body>

    <!-- NAVIGATION -->
    <script src="./js/navigation.js"></script>
    <nav>
        <div class="menu-bars" onclick="showNavigationMenu();">
            <div>
                <img id="menu_bt" src="./img/menu-bars.svg" alt="bars menu">
                <img id="menu_bt_close" src="./img/close.svg" alt="Bouton pour fermer le menu">
            </div>
            <h4>Menu</h4>
        </div>
        <a href="./index"><h2>Farfalles!</h2></a>
        <div class="img" onclick="goTo('https://www.paris2024.org/fr/',true);">
            <img src="./img/paris2024.gif" alt="paris2024 image">
        </div>
    </nav>

    <div class="navigation">
        <div class="links">
            <a href="./index">Accueil</a>
            <a href="./list-epreuves.php">Epreuves</a>
            <a href="./list-athletes.php">Athletes</a>
            <a href="./index.php#transports">Transports</a>
            <a href="./list-sites.php">Sites</a>
        </div>

        <img src="./img/phryge.png" alt="mascotte paris2024">
    </div>
    <!-- FIN DE LA NAVIGATION -->
    
    <div class="options">
        <h1>Liste des Sites</h1>
        <form method="post">
            <input type="text" name="Nom_Sites" placeholder="Nom">
            <input type="text" name="Nom_Villes" placeholder="Ville">
            <input type="text" name="Latitude_Sites" placeholder="Latitude">
            <input type="text" name="Longitude_Sites" placeholder="Longitude"><br>
            <input type="text" name="Date_de_construction_Sites" placeholder="Date de construction">
            <input type="text" name="Capacite_d_acceuil_Sites" placeholder="Capacité d'accueil">
            <input type="text" name="Accessibilite_Sites" placeholder="Accessibilité"><br>
            <input type="submit" value="Filtrer">
        </form>


        <table>
            <tr>
                <th>Nom</th>
                <th>Ville</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Date de construction</th>
                <th>Capacité d'accueil</th>
                <th>Accessibilité</th>
                <th id="epreuves_column" class="hide">Épreuves</th>
            </tr>

            <br>

            <script>
                function showEpreuves(element) {
                var tr = element.parentNode.parentNode;
                var epreuvesCell = tr.cells[tr.cells.length - 1]; 

                if (epreuvesCell.classList.contains('hide')) {
                    epreuvesCell.classList.remove('hide');
                } else {
                    epreuvesCell.classList.add('hide');
                }
                }
            </script>
            
            <?php while ($row = $query->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><a href="https://www.google.com/maps/search/?api=1&query=<?= $row["Latitude_Sites"] ?>,<?= $row["Longitude_Sites"] ?>" target="_blank"><?= $row["Nom_Sites"] ?></a></td>
                    <td><a href="javascript:void(0);" onclick="showEpreuves(this)"><?= $row["Nom_Villes"] ?></a></td>
                    <td><?= $row["Latitude_Sites"] ?></td>
                    <td><?= $row["Longitude_Sites"] ?></td>
                    <td><?= $row["Date_de_construction_Sites"] ?></td>
                    <td><?= $row["Capacite_d_acceuil_Sites"] ?></td>
                    <td><?= $row["Accessibilite_Sites"] ?></td>
                    <td class="hide">
                        <?php
                        $ville = $row["Nom_Villes"];
                        $query_epreuves = $db->prepare("SELECT DISTINCT Epreuves.Nom_Epreuves FROM Epreuves JOIN Se_Deroule ON Epreuves.ID_Epreuves = Se_Deroule.ID_Epreuves JOIN Sites ON Se_Deroule.Latitude_Sites = Sites.Latitude_Sites AND Se_Deroule.Longitude_Sites = Sites.Longitude_Sites WHERE Sites.Nom_Villes = :ville");
                        $query_epreuves->bindParam(':ville', $ville);
                        $query_epreuves->execute();
                        $epreuvesList = "Liste des épreuves : " . "<br>";
                        while ($epreuve = $query_epreuves->fetch(PDO::FETCH_ASSOC)) {
                            $epreuvesList .= $epreuve["Nom_Epreuves"] . "<br>";
                        }
                        echo $epreuvesList;
                        ?>
                    </td>
                    
                </tr>
            <?php endwhile; ?>
        </table>


        
    </div>
</body>
</html>
