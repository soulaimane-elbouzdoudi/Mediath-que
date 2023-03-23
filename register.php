<?php
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

// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {

  // Récupérer les valeurs des champs et échapper les caractères spéciaux
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
  $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
  $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);

  // Vérifier si tous les champs sont remplis
  if (!empty($email) && !empty($password) && !empty($confirm_password) && !empty($firstname) && !empty($lastname) && !empty($phone)) {

    // Vérifier si l'adresse e-mail est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = "L'adresse e-mail est invalide";
    }

    elseif (!isset($password)) {
      $error = "Le mot de passe est invalide. Il doit contenir au moins 8 caractères, 1 lettre majuscule, 1 lettre minuscule et 1 chiffre";
    }

    // Vérifier si le numéro de téléphone est valide
    elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
      $error = "Le numéro de téléphone est invalide. Il doit contenir 10 chiffres";
    }

    // Vérifier si les mots de passe correspondent
    elseif ($password !== $confirm_password) {
      $error = "Les mots de passe ne correspondent pas";
    }

    // Si tous les champs sont valides, insérer les données dans la base de données
    else {
      // Créer la requête d'insertion
      $stmt = mysqli_prepare($conn, "INSERT INTO membre (email, username, password, first_name, last_name, phone) VALUES (?, ?, ?, ?, ?, ?)");

      // Hacher le mot de passe avec l'algorithme bcrypt avant de l'insérer dans la base de données
     

      // Lier les paramètres à la requête d'insertion
      mysqli_stmt_bind_param($stmt, "ssssss", $email, $username, $password, $firstname, $lastname, $phone);

      // Exécuter la requête d'insertion
      if (mysqli_stmt_execute($stmt)) {
        // Rediriger l'utilisateur vers la page de connexion
        header("Location: login.php?email=$email&password=$password");
        exit();
       
      } else {
        $error = "Une erreur s'est produite lors de l'insertion des données dans la base de données";
         } } }; }

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

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.php" class="logo d-flex align-items-center w-auto">
                  <i class="fa-solid fa-book" style="font-size:34.3px;"></i>
                  <span style="color: #4154f1;"><span
                      style="background-color:#4154f1; color: rgb(255, 255, 255);">Media</span>théque</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                    <p class="text-center small">Enter your personal details to create account</p>
                  </div>

                  <form method="POST" action="register.php">
                  <label class="form-label" for="email">Email:</label>
                    <input class="form-control form-control-lg " name="email">
                    <label class="form-label" for="username">User Name:</label>
                    <input class="form-control form-control-lg" type="text" name="username" required>

                    

                    <label class="form-label" for="password">Mot de passe:</label>
                    <div class="input-group mb-3">
                      <input class="form-control form-control-lg" type="password" name="password" id="password"
                        required>
                    </div>

                    <label class="form-label" for="confirm_password">Confirmer le mot de passe:</label>
                    <input class="form-control form-control-lg" type="password" name="confirm_password" required>


                    <label class="form-label" for="firstname">Prénom:</label>
                    <input class="form-control form-control-lg" type="text" name="firstname" required>



                    <label class="form-label" for="lastname">Nom:</label>
                    <input class="form-control form-control-lg" type="text" name="lastname" required>

                    <label class="form-label" for="phone">Téléphone:</label>
                    <input class="form-control form-control-lg" type="text" name="phone" required>
                    <?php if (!empty($error)) { ?>
                      <div style="color: red;">
                        <?php echo $error; ?>
                      </div>
                    <?php } ?>


                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
                        <label class="form-check-label" for="acceptTerms">I agree and accept the <a href="#">terms and
                            conditions</a></label>
                        <div class="invalid-feedback">You must agree before submitting.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit" name="submit">Create Account</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Already have an account? <a href="./login.php">Log in</a></p>
                    </div>
                  </form>

                </div>
              </div>

              <div class="credits">
                Designed by <a href="">soulaimane-elbouzdoudi</a>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>