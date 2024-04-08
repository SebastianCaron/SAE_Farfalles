<?php
include 'libs/config.php';
global $db; 

$resultat = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requete = $_POST["requete"];
    $resultat = $db->query($requete);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <link rel="stylesheet" type="text/css" href="./css/all.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAE BDD Farfalles</title>
</head>

<body>
        <nav>
            <div class="menu-bars" onclick="showNavigationMenu();">
                <div>
                    <img id="menu_bt" src="./img/menu-bars.svg" alt="bars menu">
                    <img id="menu_bt_close" src="./img/close.svg" alt="Bouton pour fermer le menu">
                </div>
                <h4>Menu</h4>
            </div>
            <a href="./index.html">
                <h2>Farfalles!</h2>
            </a>
            <div class="img" onclick="goTo('https://www.paris2024.org/fr/',true);">
                <img src="./img/paris2024.gif" alt="paris2024 image">
            </div>
        </nav>

        <div class="navigation">
            <div class="links">
                <a href="#" onclick="showNavigationMenu();">Accueil</a>
                <a href="./list-epreuves.php">Epreuves</a>
                <a href="./list-athletes.php">Athletes</a>
                <a href="#transports" onclick="showNavigationMenu();">Transports</a>
                <a href="./list-sites.php">Sites</a>
            </div>

            <img src="./img/phryge.png" alt="mascotte paris2024">
        </div>
    <main>
        <section class="logos">
            <span class="goldBox1"></span>
            <img src="./img/Paralympics.svg" alt="Paralympics Logo">
            <span class="goldBox2"></span>
            <img src="./img/Jo2024ogo.svg" alt="JO 2024 Logo">
            <span class="goldBox2"></span>
            <img src="./img/OlympicsLogo.svg" alt="JO Logo">
            <span class="goldBox1"></span>
        </section>
        <section class="titre">
            <h1>JO PARIS 2024</h1>
            <div class="goldBox3"></div>
            <div>Discover</div>
            <div>Pastas and Datas</div>
        </section>

        <section class="compteur">
            <img src="./img/Lapatte.svg" alt="Farfalle Logo">
            <span>Nombres de Farfalles Produites Jusqu'aux Jeux</span>
            <span class="nombre">178 098</span>
        </section>

        <div class="GoldBoxBg"></div>
        <div class="rechercheTexte">Executer Une Requete SQL</div>
        <section class="recherche">
            <img src="./img/MascotteFete.svg" alt="image Mascotte Jo qui font la fête">

            <form class="boxBlanche" method="post">
                <img src="./img/search.svg" alt="Logo Loupe">
                <input type="text" name="requete" placeholder="Requete" >
                <input type="submit" text="GO !">
            </form>
            <img src="./img/mascottesEau.svg" alt="image Mascotte Jo qui font du sport">
        </section>

        <div class="resultats">
            <h2>Résultats de la Requête</h2>
            <?php 
            if ($resultat) {
                $row_count = $resultat->rowCount();
                if ($row_count > 0) {
                    echo "<div class='tablescroll'>";
                    echo "<table>";
                    // Entêtes de colonnes
                    echo "<tr>";
                    foreach ($resultat->fetch(PDO::FETCH_ASSOC) as $key => $value) {
                        echo "<th>" . htmlspecialchars($key) . "</th>";
                    }
                    echo "</tr>";

                    // Afficher les données
                    $resultat->execute(); // Réinitialiser le curseur
                    while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<p>Aucun résultat trouvé.</p>";
                }
            } else {
                echo "<p>Pas de resultats pour le moment...</p>";
            }
            ?>
        </div>
        <!-- <div class="resultats">
            <h2>Resultat(s) de la Requete</h2>
            <p>Pas de resultats pour le moment</p>
        </div> -->

        <div class="links">
            <h2>Les Liens</h2>

            <ul>
                <li>
                    <h2>Les Epreuves & Cérémonies</h2>
                    <ul>
                        <li><a href="./list-epreuves.php">list-epreuves</a></li>
                        <li><a href="./list-ceremonies.php">list-ceremonies</a></li>
                    </ul>
                </li>
                <li>
                    <h2>Les Athletes</h2>
                    <ul>
                        <li><a href="./list-athletes.php">list-athletes</a></li>
                        <li><a href="./athlete-details.php?id=1">athlete-details</a></li>
                    </ul>
                </li>
                <li>
                    <h2>Les Sites</h2>
                    <ul>
                        <li><a href="./list-sites.php">list-sites</a></li>
                    </ul>
                </li>
                <li>
                    <h2>Les Pays</h2>
                    <ul>
                        <li><a href="./list-pays.php">list-pays</a></li>
                    </ul>
                </li>
                <li id="transports">
                    <h2>Les Transports</h2>
                    <h3>Les listes</h3>
                    <ul>
                        <li><a href="./list-agences.php">list-agences</a></li>
                        <li><a href="./list-lignes.php">list-lignes</a></li>
                        <li><a href="./list-voyages.php">list-voyages</a></li>
                        <li><a href="./list-arrets.php">list-arrets</a></li>
                    </ul>
                    <h3>Les Details</h3>
                    <ul>
                        <li><a href="./agence-details.php?id=1046">agence-details</a></li>
                        <li><a href="./ligne-details.php?id=02372">ligne-details</a></li>
                        <li><a href="./voyage-details.php?id=BIEVRE_BUS_MOBILITES:108111-C00344-17062223">voyage-details</a></li>
                        <li><a href="./arret-details.php?id=15198">arret-details</a></li>
                    </ul>
                    <h3>Autres</h3>
                    <ul>
                        <li><a href="./trajets.php">Effectuer un Trajets</a></li>
                        <li><a href="./trajet-details.php?id=N4_MOBILITES:129876-C00031-17929682">Detail du voyage</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="auteurs">
            <h2>Les Artistes</h2>

            <div class="lesauteurs">
                <div>
                    <img src="./img/Lapatte.svg" alt="lina">
                    <h2>Lina LETHOOR</h2>
                    <p>Une desc ?</p>
                </div>
                <div>
                    <img src="./img/Lapatte.svg" alt="lina">
                    <h2>Quentin FOUET</h2>
                    <p>Une desc ?</p>
                </div>
                <div>
                    <img src="./img/Lapatte.svg" alt="lina">
                    <h2>Pierre MAGIEU</h2>
                    <p>Une desc ?</p>
                </div>
                <div>
                    <img src="./img/Lapatte.svg" alt="lina">
                    <h2>Sebastian CARON</h2>
                    <p>Une desc ?</p>
                </div>
            </div>

        </div>

    </main>
    <script src="./js/navigation.js"></script>
</body>

</html>
