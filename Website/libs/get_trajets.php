<?php
include 'config.php';

if (isset($_GET['depart']) && isset($_GET['arrivee'])) {
    $depart = $_GET['depart'];
    $arrivee = $_GET['arrivee'];

    $query_trajets = "
        SELECT 
            CONCAT_WS(' -> ', A1.Nom_Arrets, 'Départ : ', A1.Heure_depart_Arrets) AS depart,
            CONCAT_WS(' -> ', A2.Nom_Arrets, 'Arrivée : ', A2.Heure_arrive_Arrets) AS arrivee,
            ABS(A2.Numero_Arrets - A1.Numero_Arrets) AS arrets
        FROM 
            Arrets A1
        JOIN 
            Arrets A2 ON A1.ID_Voyages = A2.ID_Voyages
        WHERE 
            A1.Nom_Arrets = :depart AND A2.Nom_Arrets = :arrivee
        GROUP BY 
            A1.ID_Voyages, A2.ID_Voyages";


    $statement_trajets = $db->prepare($query_trajets);
    $statement_trajets->bindParam(':depart', $depart, PDO::PARAM_STR);
    $statement_trajets->bindParam(':arrivee', $arrivee, PDO::PARAM_STR);
    $statement_trajets->execute();
    $trajets = $statement_trajets->fetchAll(PDO::FETCH_ASSOC);

    // Affichage des résultats
    if (!empty($trajets)) {
        echo "<p>Trajets possibles de $depart à $arrivee :</p>";
        foreach ($trajets as $trajet) {
            // Vérification de l'ordre des horaires
            $horaire_depart = substr($trajet['depart'], strrpos($trajet['depart'], ":") + 1);
            $horaire_arrivee = substr($trajet['arrivee'], strrpos($trajet['arrivee'], ":") + 1);

            echo "<p>{$trajet['arrivee']} -> {$trajet['depart']}, {$trajet['arrets']} arrêts</p>";

        }
    } else {
        echo "<p>Aucun trajet trouvé entre $depart et $arrivee.</p>";
    }
}
?>
