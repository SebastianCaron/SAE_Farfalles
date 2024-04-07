<?php
include 'config.php';

if (isset($_GET['depart'])) {
    $depart = $_GET['depart'];

    $query_destinations = "SELECT Nom_Arrets FROM Arrets WHERE ID_Voyages IN (SELECT ID_Voyages FROM Arrets WHERE Nom_Arrets = :depart) ORDER BY Nom_Arrets ASC";
    $statement_destinations = $db->prepare($query_destinations);
    $statement_destinations->bindParam(':depart', $depart, PDO::PARAM_STR);
    $statement_destinations->execute();
    $destinations = $statement_destinations->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($destinations);
} else {
    http_response_code(400);
    echo json_encode(array('message' => 'Lieu de départ non spécifié.'));
}
?>
