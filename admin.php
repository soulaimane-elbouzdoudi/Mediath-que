<?php
session_start();

if (isset($_POST["add"])) {
    $name_ouvrage = $_POST["name_ouvrage"];
    $state_ouvrage = $_POST["state_ouvrage"];
    $date_achat = $_POST["date_achat"];
    $date_edition = $_POST["date_edition"];
    $type_ouvrage = $_POST["type_ouvrage"];
    $pages_ouvrage = $_POST["pages_ouvrage"];
    $quantity = $_POST['quantity'];

    $image = $_FILES['image_main']['name'];
    $tmp_name = $_FILES['image_main']['tmp_name'];
    $gerant_folder = "images/" . $image;
    // move_uploaded_file($tmp_name, $gerant_folder);

    $client_folder = "library/client/images/" . $image;
    // move_uploaded_file($tmp_name, $client_folder);




    $conn = mysqli_connect("localhost", "root", "", "gestion_emprunts");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (!empty($pages_ouvrage)) {
        $sql = "INSERT INTO ouvrage (name_ouvrage, state_ouvrage, date_achat, date_edition, type_ouvrage, pages_ouvrage, image_main) VALUES ";
        $values = array();
        for ($i = 0; $i < $quantity; $i++) {
            $values[] = "('$name_ouvrage','$state_ouvrage','$date_achat','$date_edition','$type_ouvrage','$pages_ouvrage','$gerant_folder')";
        }
        $sql .= implode(",", $values);
    } else {
        $sql = "INSERT INTO ouvrage (name_ouvrage, state_ouvrage, date_achat, date_edition, type_ouvrage, pages_ouvrage, image_main) VALUES ";
        $values = array();
        for ($i = 0; $i < $quantity; $i++) {
            $values[] = "('$name_ouvrage','$state_ouvrage','$date_achat','$date_edition','$type_ouvrage','0','$gerant_folder')";
        }
        $sql .= implode(",", $values);
    }

    mysqli_close($conn);
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

if (isset($_POST["accept"])) {
$reservation = $_POST["id_reservation"];
// connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_emprunts";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}
$QUERY = "INSERT into emprunts (id_reservation, date_retour ,date_emprunts) VALUES ('$reservation','' ,NOW()) ";

if (mysqli_query($conn, $QUERY) == true) {
$SQL = "UPDATE reservation SET state_reservation = 'validée' WHERE id_reservation = $reservation";
if (mysqli_query($conn, $SQL)) {
echo '<div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
    style="position:fixed; z-index:99; top:80%; left:70%;" role="alert">
    Reservation a été acceptée !
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
;


}


}
if (isset($_POST["retour"])) {
$reservation = $_POST["id_reservation"];
// connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_emprunts";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}
$QUERYS = "UPDATE emprunts SET date_retour = NOW()";

if (mysqli_query($conn, $QUERYS) == true) {
$row = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM emprunts WHERE id_reservation = '$reservation'"));
$date_emprunt = strtotime($row['date_emprunts']);
$date_retour = strtotime($row['date_retour']);
$days_diff = floor(($date_retour - $date_emprunt) / (60 * 60 * 24));
if ($days_diff > 15) {
$rowss = mysqli_fetch_array(mysqli_query($conn, "SELECT id_membre FROM reservation WHERE id_reservation =
'$reservation'"));
$id_membre = $rowss['id_membre'];
$query_banned = "UPDATE membre SET banned = banned + 1 WHERE id_membre ='$id_membre'";
mysqli_query($conn, $query_banned);
}
$SQL = "UPDATE reservation SET state_reservation = 'Retournée' WHERE id_reservation = $reservation";
if (mysqli_query($conn, $SQL)) {
echo '<div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show"
    style="position:fixed; z-index:99; top:80%; left:70%;" role="alert">
    Emprunt a été retournée !
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
;


}


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
    #main {
        margin-left: 0;
    }
</style>


<body>

    <!-- ======= Header ======= -->

    <header id="header" class="header fixed-top d-flex align-items-center">



        <div class="d-flex align-items-center justify-content-between">
            <a href="index.php" class="logo d-flex align-items-center">
                <i class="fa-solid fa-book" style="font-size:34.3px;"></i>
                <span style="color: #4154f1; "><span
                        style="background-color:#4154f1; color: rgb(255, 255, 255);">Media</span>théque</span>
            </a>

        </div><!-- End Logo -->


        <form methode="GET" action="index.php" style="margin-left:60%;">
            <button style="background-color:white; border:none;" name="logout">
                <a class="dropdown-item d-flex align-items-center">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sign Out</span>
                </a>
            </button>
        </form>
        <button type="button" class="btn btn-primary" style="background-color: #4154f1; width:80%;"
            data-bs-toggle="modal" data-bs-target="#AJOUTER">
            Ajouter
        </button>

        <!-- End Profile Dropdown Items -->

    </header>
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>DashBaord</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item">Pages</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">reservations</h5>
                            <?php require_once('connexion.php');
                            // prepare SQL statement to retrieve ads
                            $sql = "SELECT * FROM reservation WHERE DATEDIFF(CURRENT_DATE(),date_reservation)=0 AND state_reservation = 'en cours de validation'";
                            $result = $conn->query($sql) ?>

                            <?php

                            ?>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#id_reservation</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">ouvrage</th>
                                        <th scope="col">id_ouvrage</th>
                                        <th scope="col">date</th>
                                        <th scope="col">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php


                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            $id_ouvrage = $row['id_ouvrage'];
                                            $id_membre = $row['id_membre'];
                                            $query = "SELECT * FROM ouvrage WHERE id_ouvrage = '$id_ouvrage'";
                                            $results = mysqli_query($conn, $query);
                                            while ($rows = mysqli_fetch_assoc($results)) {
                                                $name_ouvrage = $rows["name_ouvrage"];

                                            }
                                            ;
                                            ?>
                                            <tr>
                                                <th scope="row">
                                                    <?php echo $row["id_reservation"] ?>
                                                </th>
                                                <th scope="row">

                                                    <?php $query1 = "SELECT * FROM membre WHERE id_membre = '$id_membre'";
                                                    $results1 = mysqli_query($conn, $query1);
                                                    while ($rows1 = mysqli_fetch_assoc($results1)) {
                                                        $firstname = $rows1["first_name"];
                                                        $lastname = $rows1["last_name"];
                                                        echo $firstname;
                                                        echo $lastname;


                                                    } ?>
                                                </th>
                                                <th scope="row">
                                                    <?php echo $name_ouvrage; ?>
                                                </th>
                                                <th scope="row">
                                                    <?php echo $id_ouvrage; ?>
                                                </th>
                                                <th scope="row">
                                                    <?php echo $row["date_reservation"] ?>
                                                </th>
                                                <th>
                                                    <form action="admin.php" method="post">
                                                        <input type="hidden" name="id_reservation"
                                                            value=" <?php echo $row["id_reservation"] ?>">

                                                        <button type="submit" name="accept" class="btn btn-success"><i
                                                                class="bi bi-check-circle"></i></button>
                                                    </form>

                                                </th>

                                            </tr>

                                            <?php
                                        }
                                    } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Emprunts En Cours</h5>
                            <?php require_once('connexion.php');
                            // prepare SQL statement to retrieve ads
                            $sql = "SELECT * FROM reservation WHERE state_reservation = 'validée'";
                            $result = $conn->query($sql) ?>

                            <?php

                            ?>

                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">ouvrage</th>
                                        <th scope="col">id_ouvrage</th>
                                        <th scope="col">date</th>
                                        <th scope="col">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php


                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            $id_ouvrage = $row['id_ouvrage'];
                                            $id_membre = $row['id_membre'];
                                            $query = "SELECT * FROM ouvrage WHERE id_ouvrage = '$id_ouvrage'";
                                            $results = mysqli_query($conn, $query);
                                            while ($rows = mysqli_fetch_assoc($results)) {
                                                $name_ouvrage = $rows["name_ouvrage"];

                                            }
                                            ;
                                            ?>
                                            <tr>

                                                <th scope="row">

                                                    <?php $query1 = "SELECT * FROM membre WHERE id_membre = '$id_membre'";
                                                    $results1 = mysqli_query($conn, $query1);
                                                    while ($rows1 = mysqli_fetch_assoc($results1)) {
                                                        $firstname = $rows1["first_name"];
                                                        $lastname = $rows1["last_name"];
                                                        echo $firstname;
                                                        echo $lastname;


                                                    } ?>
                                                </th>
                                                <th scope="row">
                                                    <?php echo $name_ouvrage; ?>
                                                </th>
                                                <th scope="row">
                                                    <?php echo $id_ouvrage; ?>
                                                </th>
                                                <th scope="row">
                                                    <?php echo $row["date_reservation"] ?>
                                                </th>
                                                <th>
                                                    <form action="admin.php" method="post">
                                                        <input type="hidden" name="id_reservation"
                                                            value=" <?php echo $row["id_reservation"] ?>">
                                                        <button type="submit" name="retour" class="btn btn-warning"><i
                                                                class="bi bi-arrow-counterclockwise"></i></button>
                                                    </form>

                                                </th>

                                            </tr>

                                            <?php
                                        }
                                    } ?>
                                </tbody>

                            </table>

                        </div>
                    </div>

                </div>
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Emprunts Retournée</h5>
                            <?php require_once('connexion.php');
                            // prepare SQL statement to retrieve ads
                            $sql = "SELECT * FROM reservation WHERE state_reservation = 'Retournée'";
                            $result = $conn->query($sql) ?>

                            <?php

                            ?>

                            <table class="table datatable">
                                <thead>
                                    <tr>

                                        <th scope="col">Name</th>
                                        <th scope="col">ouvrage</th>
                                        <th scope="col">id_ouvrage</th>
                                        <th scope="col">date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php


                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            $id_ouvrage = $row['id_ouvrage'];
                                            $id_membre = $row['id_membre'];
                                            $query = "SELECT * FROM ouvrage WHERE id_ouvrage = '$id_ouvrage'";
                                            $results = mysqli_query($conn, $query);
                                            while ($rows = mysqli_fetch_assoc($results)) {
                                                $name_ouvrage = $rows["name_ouvrage"];

                                            }
                                            ;
                                            ?>
                                            <tr>

                                                <th scope="row">

                                                    <?php $query1 = "SELECT * FROM membre WHERE id_membre = '$id_membre'";
                                                    $results1 = mysqli_query($conn, $query1);
                                                    while ($rows1 = mysqli_fetch_assoc($results1)) {
                                                        $firstname = $rows1["first_name"];
                                                        $lastname = $rows1["last_name"];
                                                        echo $firstname;
                                                        echo $lastname;


                                                    } ?>
                                                </th>
                                                <th scope="row">
                                                    <?php echo $name_ouvrage; ?>
                                                </th>
                                                <th scope="row">
                                                    <?php echo $id_ouvrage; ?>
                                                </th>
                                                <th scope="row">
                                                    <?php echo $row["date_reservation"] ?>
                                                </th>


                                            </tr>

                                            <?php
                                        }
                                    } ?>
                                </tbody>

                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
    <div class="modal" id="AJOUTER" tabindex="-1" style="z-index:1000000;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        TEST
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="sign-up-form" method="post" enctype="multipart/form-data">
                        <input type="text" placeholder="Name" name="name_ouvrage" />
                        <input type="date" placeholder="Date of buy" name="date_achat" />
                        <input type="date" placeholder="Date of edition" name="date_edition" />
                        <div style="display: flex;margin-left:22%">
                            <select name="state_ouvrage">
                                <option selected>STATE</option>
                                <option value="EXCELLENT">EXCELLENT</option>
                                <option value="MEDUIM">MEDUIM</option>
                            </select>
                            <select name="type_ouvrage" style="margin-left: 3%;" onchange="showType(this)">
                                <option selected>TYPE</option>
                                <option value="BOOK">BOOK</option>
                                <option value="CD">CD</option>
                                <option value="NOVEL">NOVEL</option>
                                <option value="MAGAZINE">MAGAZINE</option>
                            </select>
                        </div>

                        <input type="number" id="pages" style="display: none;margin-left:15%" placeholder="Pages"
                            name="pages_ouvrage" />
                        <input type="file" name="image_main" />
                        <input type="number" placeholder="Quantity" name="quantity" />
                        <button class="control-button up" type="submit" name="add">Add Book</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- End Icons Navigation -->





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