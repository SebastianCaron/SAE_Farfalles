<?php
    include 'libs/config.php';

    global $db;

    $sql = 'SELECT * FROM Sites';

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
    <!-- <link rel="stylesheet" type="text/css" href="./css/all.css"> -->


    <!-- VOTRE CSS -->
    <link rel="stylesheet" type="text/css" href="./css/list-sites.css">
    <style>
        :root{
            --text: #FFFFFF;
            --background: #080807;
            --accent: #D7C378;
            --gold: #FFC900;
            --silver: #FFFFFF;
            --bronze: #FFBD98;
            --primary: #FFC800;
            --secondary: #344366;
        }


        @font-face {
            font-family: 'Paris2024';
            src: url('fonts/Paris2024-Variable.woff2') format('woff2'), /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
                url('fonts/Paris2024-Variable.ttf') format('truetype'); /* Chrome 4+, Firefox 3.5, Opera 10+, Safari 3—5 */
        }  

        @font-face {
            font-family: 'SourceSans';
            src: url('fonts/SourceSans3.ttf') format('truetype'); /* Chrome 4+, Firefox 3.5, Opera 10+, Safari 3—5 */
        }  

        body{
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
            box-sizing: border-box;
            font-family: 'SourceSans', sans-serif;
            background-color: var(--background);
            color: var(--text);
            overflow-x: hidden;
        }

        h1, h2, h3{
            font-family: 'Paris2024', sans-serif;
        }

        nav{
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            background-color: var(--background);

        }


        nav > div:nth-child(1){
            width: 20vw;
            min-width: 50px;
            max-width: 200px;
        }

        nav > div:nth-child(2){
            width: 60vw;
            min-width: 100px;
            max-width: 200px;
        }
        nav > div:nth-child(3){
            width: 20vw;
            min-width: 50px;
            max-width: 200px;
            max-height: 80px;
        }

        nav > div{
            height: 100px;
        }

        nav > .menu-bars{
            display: flex;
            justify-content: center;
            flex-direction: row;
            align-items: center;
            gap: 20px;
            cursor: pointer;
        }

        nav > .menu-bars > h4{
            font-size: 1.2em;
        }

        nav > .menu-bars > div{
            border-radius: 50000px;
            background-color: var(--text);
            padding: 20px;
            aspect-ratio: 1 / 1;
            display: flex;
            justify-content: center;
            align-items: center;
            max-width: 60px;
            max-height: 60px;
        }

        nav > a{
            color: var(--text);
            font-size: 1.4em;
            text-decoration: none;
            font-weight: 200;
        }


        nav .img{
            text-align: right;
            margin-right: 10px;
            cursor: pointer;
            max-width: 80px;
            max-height: 80px;
        }
        nav .img img{
            aspect-ratio: 1 / 1;
            max-width: 80px;
            max-height: 80px;
            object-fit: contain;

        }

        .navigation{
            position: fixed;
            top: -100%;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            z-index: 999;
            background-color: white;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: start;
            transition: all 0.3s ease;
        }

        .navigation.active{
            top: 0;
            transition: all 0.3s ease;
        }

        #menu_bt{
            display: block;
            transform: rotateY(0) perspective(1000px) translateX(0);
            transition: all 0s ease-in-out;
            transition: opacity 0.22s ease-in transform 0.25s ease-in-out;
            opacity: 1;
        }
        #menu_bt.anim{
            transform: rotateY(90deg) perspective(1000px) translateX(200%);
            opacity: 0.01;
            display: none;
        }

        #menu_bt_close{
            display: none;
            transform: rotateY(90deg) perspective(1000px) translateX(-200%);
            transition: all 0s ease-in-out;
            transition: opacity 0.22s ease-in transform 0.25s ease-in-out;
            opacity: 0.01;
        }
        #menu_bt_close.anim{
            transform: rotateY(0) perspective(1000px) translateX(0);
            opacity: 1;
            display: block;
        }

        .navigation > .links{
            display: flex;
            flex-direction: column;
            margin-left: 5vw;
            gap: 20px;
        }

        .navigation > .links > a{
            color: var(--secondary);
            font-size: 3em;
            font-weight: 500;
            font-family: 'Paris2024';
            text-decoration: none;
            letter-spacing: 3%;
            position: relative;
        }

        .navigation > .links > a:after {    
            background: none repeat scroll 0 0 transparent;
            border-radius: 3px;
            content: "";
            display: block;
            height: 5px;
            position: absolute; 
            left: 0;
            bottom: 0;
            background: var(--secondary);
            width: 0;
        }

        .navigation > .links > a:hover:after{
            animation: 0.3s linear hoverlinks;
            animation-fill-mode: forwards;
        }

        .navigation > img{
            margin-left: 30%;
            padding-top: 15px;
        }

        @keyframes hoverlinks{
            0%{
                width: 0;
                left: 0;
                bottom: 0;
            }
            50% {
                width: 100%;
            }
            100% {
                width: 15%;
            }
        }

        .options{
            text-align: center;
        }

        .options h1{
            font-size: 3em;
            color: var(--accent);
        }

        .options label{
            font-family: "SourceSans";
            font-size: 1.2em;
        }

        .options form{
            background-color: var(--grey);
            color: var(--text);
        }

        .options input{
            padding: 15px;
            border-radius: 25px;
            margin: 10px;
        }

        .options th {
            font-size: 1.2em;
            border: solid;
            border-radius: 25px;
            padding: 5px; 
        }
        .options td, tr, a{
            color: var(--text);
            text-decoration: none;
            font-size: 1.1em;
            font-family: "Paris2024";
        }

        .options td {
            border: solid;
            border-radius: 25px;
        }

        table {
        width: 100%;
        }

        .hide {
            display: none;
        }

    </style>

    

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
        <a href="./index.html"><h2>Farfalles!</h2></a>
        <div class="img" onclick="goTo('https://www.paris2024.org/fr/',true);">
            <img src="./img/paris2024.gif" alt="paris2024 image">
        </div>
    </nav>

    <div class="navigation">
        <div class="links">
            <a href="./index.html">Accueil</a>
            <a href="./list-epreuves.html">Epreuves</a>
            <a href="./list-athletes.html">Athletes</a>
            <a href="./list-transports.html">Transports</a>
            <a href="./list-sites.html">Sites</a>
        </div>

        <img src="./img/phryge.png" alt="mascotte paris2024">
    </div>
    <!-- FIN DE LA NAVIGATION -->
    
    <div class="options">
        <h1>Paris 2024 - Sites</h1>
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
                        echo $ville;
                        $query_epreuves = $db->prepare("SELECT Nom_Epreuves FROM Epreuves WHERE Nom_Sites IN (SELECT Nom_Sites FROM Sites WHERE Nom_Villes = :ville)");
                        echo $query_epreuves;
                        $query_epreuves->bindParam(':ville', $ville);
                        $query_epreuves->execute();
                        $epreuvesList = "Liste des épreuves : " . "<br>";
                        echo $epreuvesList;
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
