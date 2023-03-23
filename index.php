<?php
session_start();
if (!isset($_SESSION["email"])) {
    header('Location: login.php');
    exit;
}


if (isset($_GET['logout'])) {
    // Unset all session variables
    session_unset();
    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header('Location: index.php');
    exit;

}
;

?>
<?php
if (isset($_GET['id_ouvrage'])) {
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
        $row = $result3->fetch_assoc();
        $state_reservation = $row["state_ouvrage"];
        $id_membre = $_SESSION["id"];

    }
    // Requête SQL pour insérer la réservation dans la table des réservations
    $query = "INSERT INTO reservation ( state_reservation,id_membre ,id_ouvrage )
        VALUES ('en cours de validation',$id_membre , $id_ouvrage)";

    if ($conn->query($query) === TRUE) {
        // Récupération de l'ID de la réservation qui vient d'être créée
        $reservation_id = $conn->insert_id;
        echo '<div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" style="position:fixed; z-index:99; top:80%; left:70%;" role="alert">
            Ouvrage a été Reservée !
           <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';

    } else {
        echo "Erreur lors de la création de la réservation : " . $conn->error;
    }

    // Fermeture de la connexion à la base de données
    $conn->close();

}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>MediaThéque</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

</head>
<style>
    .card {
        width: 20%;
    }

    #section {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;

    }

    @media (max-width: 768px) {
        .card {
            width: 50%;
        }
    }
</style>

<body>

    <!-- ======= Header ======= -->

    <header id="header" class="header fixed-top d-flex align-items-center">
        <i class="bi bi-list toggle-sidebar-btn"></i>


        <div class="d-flex align-items-center justify-content-between" style="margin-left:5%;">
            <a href="index.php" class="logo d-flex align-items-center">
                <i class="fa-solid fa-book" style="font-size:34.3px;"></i>
                <span style="color: #4154f1; "><span
                        style="background-color:#4154f1; color: rgb(255, 255, 255);">Media</span>théque</span>
            </a>

        </div><!-- End Logo -->

        <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" name="search" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div><!-- End Search Bar -->



        <?php
        if (!isset($_SESSION['email'])) {

            // Rediriger l'utilisateur vers la page de connexion
            ?>
            <a href="login.php" class="btn btn-primary " style="width:20%; margin-left:10%;background-color: #4154f1; ">sign
                In</a>


        <?php } else { ?>
            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">

                    <li class="nav-item dropdown pe-3">

                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                            <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                            <span class="d-none d-md-block dropdown-toggle ps-2">
                                <?php echo $_SESSION["username"] ?>
                            </span>
                        </a><!-- End Profile Iamge Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6>
                                    <?php echo $_SESSION["lastname"] ?>
                                </h6>
                                <span>
                                    <?php echo $_SESSION["firstname"] ?>
                                </span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="profile.php">
                                    <i class="bi bi-person"></i>
                                    <span>My Profile</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="contact.php">
                                    <i class="bi bi-question-circle"></i>
                                    <span>Need Help?</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form methode="GET" action="index.php">
                                    <button style="background-color:white; border:none;" name="logout">
                                        <a class="dropdown-item d-flex align-items-center">
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span>Sign Out</span>
                                        </a>
                                    </button>
                                </form>

                            </li>
                        </ul><!-- End Profile Dropdown Items -->
                    </li><!-- End Profile Nav -->
                    <li class="nav-item d-block d-lg-none">
                        <a class="nav-link nav-icon search-bar-toggle " href="#">
                            <i class="bi bi-search"></i>
                        </a>
                    </li><!-- End Search Icon-->

                </ul>
            </nav>
        <?php }
        ; ?>



        <!-- End Icons Navigation -->


        <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">

            <ul class="sidebar-nav" id="sidebar-nav">

                <li class="nav-item">
                    <a class="nav-link " href="index.php">
                        <i class="bi bi-grid"></i>
                        <span>Accueil</span>
                    </a>
                </li><!-- End Dashboard Nav -->



                <li class="nav-item">
                    <a class="nav-link collapsed" href="profile.php">
                        <i class="bi bi-person"></i>
                        <span>Profile</span>
                    </a>
                </li><!-- End Profile Page Nav -->

                <li class="nav-item">
                    <a class="nav-link collapsed" href="contact.php">
                        <i class="bi bi-envelope"></i>
                        <span>Contact</span>
                    </a>
                </li><!-- End Contact Page Nav -->

            </ul>

        </aside><!-- End Sidebar-->


    </header><!-- End Header -->
    <main id="main" class="main">

        <section id="section">
            <?php
            require_once('connexion.php');
            // prepare SQL statement to retrieve ads
            $sql = "SELECT * FROM ouvrage";
            $result = $conn->query($sql);
            // check if there are any ads in the database
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    // display the ad information in HTML
                    $id_ouvrage = $row["id_ouvrage"];

                    ?>
                    <div class="card">
                        <img src="./assets/img/book.png" alt="image">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo $row["name_ouvrage"] ?>

                            </h5>
                            <h6>
                                <?php echo $row["type_ouvrage"]; ?>
                            </h6>
                            <code> <?php echo $row["state_ouvrage"]; ?></code>
                            <p>ID d'ouvrage: <code> <?php echo $id_ouvrage; ?></code></p>

                            <!-- Vertically centered Modal -->
                            <button type="button" class="btn btn-primary" style="background-color: #4154f1; width:100%;"
                                data-bs-toggle="modal" data-bs-target="#id<?php echo $id_ouvrage ?>">
                                emprunter
                            </button>
                            <div class="modal fade" id="id<?php echo $id_ouvrage ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <?php echo $row["name_ouvrage"] ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Non omnis incidunt qui sed occaecati magni asperiores est mollitia. Soluta at et
                                            reprehenderit.
                                            Placeat autem numquam et fuga numquam. Tempora in facere consequatur sit dolor
                                            ipsum. Consequatur
                                            nemo amet incidunt est facilis. Dolorem neque recusandae quo sit molestias sint
                                            dignissimos.
                                        </div>
                                        <div class="modal-footer">
                                            <form method="get" action="index.php">
                                                <input type="hidden" name="id_ouvrage" value="<?php echo $id_ouvrage ?>">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>

                                                <?php $servername = "localhost";
                                                $username = "root";
                                                $password = "";
                                                $dbname = "gestion_emprunts";

                                                // Vérifier si la connexion est réussie
                                                $conn = mysqli_connect($servername, $username, $password, $dbname);
                                                if (!$conn) {
                                                    die("Connection failed: " . mysqli_connect_error());
                                                }
                                                ;

                                                $id_membre = $_SESSION['id'];
                                                $query = "SELECT COUNT(*) as numberreserve FROM reservation where DATEDIFF(CURRENT_DATE(),date_reservation) = 0 AND id_membre = '$id_membre'";
                                                $result_count = mysqli_query($conn, $query);
                                                $number_reservations = mysqli_fetch_assoc($result_count)['numberreserve'];
                                                $sql_counts = "SELECT COUNT(*) AS num_reservations
                                                FROM reservation r
                                                INNER JOIN emprunts e ON r.id_reservation = e.id_reservation
                                                WHERE r.id_membre = $id_membre
                                                AND e.date_emprunts IS NOT NULL
                                                AND e.date_retour IS NULL";
                                                $result_count1 = mysqli_query($conn, $sql_counts);
                                                $number_reservation = mysqli_fetch_assoc($result_count1)['num_reservations'];


                                                if ($number_reservations == 3 || $number_reservation == 3) {
                                                    echo "<button type='button' class='btn btn-danger' disabled><code style='color:white;'>tu est 3 reservations! </code></button>";

                                                } elseif ($number_reservation == 1) {
                                                    if ($number_reservations < 2) {
                                                        echo "<button type='submit' name='submit' class='btn btn-primary'>Reserve</button>";

                                                    } else {

                                                        echo "<button type='button' class='btn btn-danger' disabled><code style='color:white;'>tu est 3 reservations! </code></button>";
                                                    }


                                                } elseif ($number_reservation == 2) {
                                                    if ($number_reservations < 1) {
                                                        echo "<button type='submit' name='submit' class='btn btn-primary'>Reserve</button>";

                                                    } else {

                                                        echo "<button type='button' class='btn btn-danger' disabled><code style='color:white;'>tu est 3 reservations! </code></button>";
                                                    }

                                                } else {

                                                    echo "<button type='submit' name='submit' class='btn btn-primary'>Reserve</button>";
                                                }


                                                ?>
                                            </form>


                                        </div>
                                    </div>
                                </div>
                            </div><!-- End Vertically centered Modal-->

                        </div>
                    </div>



                    <?php
                }
                ;

            }
            ; ?>

        </section>
    </main>
</body>
<!-- Vendor JS Files -->
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="https://kit.fontawesome.com/62b7831ac8.js" crossorigin="anonymous"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>
<!-- Vendor JS Files -->

</html>