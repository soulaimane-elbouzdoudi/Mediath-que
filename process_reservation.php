<?php 
session_start();
// Récupération des données du formulaire
$id_ouvrage = $_GET["id_ouvrage"];

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_emprunts";

// Vérifier si la connexion est réussie
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
// prepare SQL statement to retrieve ads
$sql3 = "SELECT * FROM ouvrage where id_ouvrage = $id_ouvrage";
$result3 = $conn->query($sql3);
// check if there are any ads in the database
if ($result3->num_rows > 0) {
    $row = $result->fetch_assoc();
    $state_reservation= $row["state_ouvrage"];
    $id_membre = $_SESSION["id"] ;

    



 }
// Requête SQL pour insérer la réservation dans la table des réservations
$query = "INSERT INTO reservation ( state_reservation,id_membre ,id_ouvrage )
        VALUES ('en cours de validation',$id_membre , $id_ouvrage)";

if ($conn->query($query) === TRUE) {
    // Récupération de l'ID de la réservation qui vient d'être créée
    $reservation_id = $conn->insert_id;
    header('Location:index.php');
    echo '<div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" style="position:fixed; z-index:99; top:80%; left:70%;" role="alert">
            Ouvrage a été Reservée !
           <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';

} else {
    echo "Erreur lors de la création de la réservation : " . $conn->error;
}

// Fermeture de la connexion à la base de données
$conn->close(); ?>