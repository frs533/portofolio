<?php
require_once('koneksi.php');

session_start();

if($_POST){
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = "SELECT * FROM tbl_admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);
    
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();

        $_SESSION["username"] = $data['username'];
        $_SESSION["status"] = 'Admin';

        unset($_SESSION['error_message']); 
        
        header('Location: dashboard.php');
        
    } else {
        $_SESSION['error_message'] = "Login gagal. Periksa kembali username dan password anda.";
        header('Location: loginAdmin.php');
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet"/>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-primary bg-primary">
            <img src="assets/img/logo.svg" alt="newLogo.png" class="ms-3" style="max-width: 100%;max-height:100%;">
            <a class="navbar-brand ps-3 fw-bolder fs-3" href="index.html">Her'Style</a>
        </nav>
        <div id="layoutAuthentication" class="mt-5">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container mt-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                            <h3 class="text-center font-weight-light my-4">Login Admin</h3>
                                <div class="card shadow-lg border-0 rounded-lg" style="background-color: #A4CDF2;">
                                    <div class="card-body">
                                        
                                        <p class="text-center">Sign in to your session</p>
                                        <form action="" method="post">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputUsername" type="text" name="username" id="username" required />
                                                <label for="inputUsername">Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputPassword" type="password" name="password" id="password" required />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <?php
                                                if (isset($_SESSION['error_message'])) {
                                                    echo '
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    '.$_SESSION['error_message'].'
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>';
                                                }
                                                
                                            ?>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <input class="btn btn-primary col-8 offset-2" type="submit" value="Sign In">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
