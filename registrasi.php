<?php
require_once('koneksi.php');

if($_POST){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    
    $query = "INSERT INTO tbl_user (username, password, nama_pelanggan, alamat) VALUES ('$username', '$password', '$nama_pelanggan', '$alamat')";
    
    if ($conn->query($query) === TRUE) {
        header('Location: loginUser.php');
    } else {
        header('Location: registrasi.php');
    }
}


if(!empty($_SESSION['id'])){
    $id = $_SESSION['id'];
    $totalPesanan = "SELECT COUNT(p.id_pesanan) AS jumlah_pesanan
                FROM tbl_pesanan p
                LEFT JOIN tbl_databarang d ON p.id_barang = d.id_barang
                WHERE p.id_user = '$id' AND p.status_pesanan = 'menunggu';
                ";
    
    $pesanan = $conn->query($totalPesanan);
    $pesanan = $pesanan->fetch_assoc();
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    header('Location: shop.php?search='.$search);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Registrasi</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/mobile.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
        body{
            font-family: "PT Serif Caption";
        }
        .navbar-nav.ms-auto.ms-md-0.me-3.me-lg-4 .nav-link {
                display: inline;
        }
        </style>
    </head>
    <body class="sb-nav-fixed" style="background-color: #F3E8E8;">
        <nav class="sb-topnav navbar navbar-expand navbar-primary justify-content-between" style="background-color: #F3E8E8;">
            <div style="margin-left:3%">
                <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link fs-3 fw-bolder text-dark" href="shop.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Shop
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style="background-color: #F3E8E8;">
                            <li><a class="dropdown-item">Clothing</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="shop.php?tipe=Dresses">Dresses</a></li>
                            <li><a class="dropdown-item" href="shop.php?tipe=Pants">Pants</a></li>
                            <li><a class="dropdown-item" href="shop.php?tipe=Shorts">Shorts</a></li>
                            <li><a class="dropdown-item" href="shop.php?tipe=Tops">Tops</a></li>
                            <li><a class="dropdown-item" href="shop.php?tipe=Skirts">Skirts</a></li>
                            <li><a class="dropdown-item" href="shop.php?tipe=Jumpsuits">Jumpsuits</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div style="margin-left:10%">
                <img src="assets/img/logo.svg" alt="newLogo.png" class="ps-5 img-fluid" width="40%" style="max-width: 100%;max-height:100%;">
                <a style="font-family: PT Serif Caption;" class="navbar-brand fw-bolder fs-2" href="home.php">Her'Style</a>
            </div>
            <div>
                <div class="row">
                    <div class="col">
                        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" method="GET">
                            <div class="input-group">
                                <input class="form-control" type="text" name="search" id="search" placeholder="Cari..." aria-label="Cari..." aria-describedby="btnNavbarSearch" />
                                <button class="btn btn-secondary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="col">  
                    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                            <li class="nav-item">
                                <button type="button" class="btn-sm position-relative bg-transparent" style="border:none">
                                    <a class="nav-link" href="keranjang.php"><i class="fa-sharp fa-light fa-bag-shopping fs-2"></i></a>  
                                    <?php if(!empty($pesanan['jumlah_pesanan'])){ ?>    
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            <?=$pesanan['jumlah_pesanan']?>
                                        </span>
                                    <?php }; ?>     
                                </button>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-circle-user fs-2"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <?php if(empty($_SESSION)){ ?>
                                        <li><a class="dropdown-item" href="loginUser.php">Sign In</a></li>
                                        <li><a class="dropdown-item" href="registrasi.php">Register</a></li>
                                    <?php } else {?>
                                        <li><a class="dropdown-item" href="account.php">Account Dashboard</a></li>
                                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>  
            </div>
        </nav>
        <!-- navbar mobile -->
        <nav class="sb-topnav navbar navbar-expand navbar-primary nav-mobile-block" style="background-color: #F3E8E8;">
        
            <div style="margin-left:3%">
                <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                    <li class="nav-item dropdown" style="list-style-type: none;">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-bars fs-2"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li class="dropdown dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" id="multilevelDropdownMenu1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Shop</a>
                                <ul class="dropdown-menu" aria-labelledby="multilevelDropdownMenu1">
                                    <li><a class="dropdown-item">Clothing</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="shop.php?tipe=Dresses">Dresses</a></li>
                                    <li><a class="dropdown-item" href="shop.php?tipe=Pants">Pants</a></li>
                                    <li><a class="dropdown-item" href="shop.php?tipe=Shorts">Shorts</a></li>
                                    <li><a class="dropdown-item" href="shop.php?tipe=Tops">Tops</a></li>
                                    <li><a class="dropdown-item" href="shop.php?tipe=Skirts">Skirts</a></li>
                                    <li><a class="dropdown-item" href="shop.php?tipe=Jumpsuits">Jumpsuits</a></li>
                                </ul>
                            </li>
                            <li><a class="dropdown-item" href="shop.php">New Arrival</a></li>
                            <li class="dropdown dropend">
                                <a class="dropdown-item dropdown-toggle" href="#" id="multilevelDropdownMenu2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Account</a>
                                <ul class="dropdown-menu" aria-labelledby="multilevelDropdownMenu2">
                                    <?php if(empty($_SESSION)){ ?>
                                        <li><a class="dropdown-item" href="loginUser.php">Sign In</a></li>
                                        <li><a class="dropdown-item" href="registrasi.php">Register</a></li>
                                    <?php } else {?>
                                        <li><a class="dropdown-item" href="account.php">Account Dashboard</a></li>
                                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        </ul>
                        
                    </li>
                </ul>
            </div>
            <div style="margin-left:10%;" >
                <a style="font-family: PT Serif Caption;" class="navbar-brand fw-bolder" href="home.php">
                <img src="assets/img/logo.svg" alt="newLogo.png" class="img-fluid" style="max-width: 40%;max-height:auto;">Her'Style
                </a>
            </div>
            <div style="margin-left:10%">
                <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                    <li class="nav-item">
                        <button type="button" class="btn-sm position-relative bg-transparent" style="border:none" data-bs-toggle="collapse" data-bs-target="#search" aria-expanded="false" aria-controls="search">
                            <i class="fa-solid fa-magnifying-glass fs-2"></i>     
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn-sm position-relative bg-transparent" style="border:none">
                            <a class="nav-link" href="keranjang.php"><i class="fa-sharp fa-light fa-bag-shopping fs-2"></i></a>  
                            <?php if(!empty($pesanan['jumlah_pesanan'])){ ?>    
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?=$pesanan['jumlah_pesanan']?>
                                </span>
                            <?php }; ?>     
                        </button>
                    </li>
                    
                </ul>
            </div>
        </nav>
        <!-- end navbar mobile -->
        <div id="layoutAuthentication" class="mt-5"> 
            <div id="layoutAuthentication_content">
                <main>
                    <div class="collaps nav-mobile-block" id="search">
                        <div class="card card-body" style="background-color: #F3E8E8;border:none;">
                        <form class="" method="GET">
                            <div class="input-group">
                                <input class="form-control" type="text" name="search" id="search" placeholder="Cari..." aria-label="Cari..." aria-describedby="btnNavbarSearch" />
                                <button class="btn btn-secondary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                        </div>
                    </div>
                    <div class="container mt-5">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="text-mobile">
                                    <div class="logo-mobile">
                                        <img src="assets/img/logo.svg " alt="newLogo.png" class="none-mobile block-mobile">
                                    </div>
                                    <h1 class="font-weight-light mt-4 none-mobile" style="font-family: PT Serif Caption;">H'S</h1>
                                    <p><span class="bold-mobile">Create your account</span> <br> Or sign in to an existing account</p>
                                </div>
                                <form action="" method="POST"> 
                                    <label for="inputnama">Nama Lengkap</label>
                                    <input class="form-control my-2" id="inputnama" type="text" name="nama_pelanggan" id="nama_pelanggan" required />
                                    <label for="inputalamat">Alamat</label>
                                    <input class="form-control my-2" id="inputalamat" type="text" name="alamat" id="alamat" required />
                                    <label for="inputUsername">Username</label>
                                    <input class="form-control my-2" id="inputUsername" type="text" name="username" id="username" required />
                                    <label for="inputPassword">Password</label>
                                    <input class="form-control my-2" id="inputPassword" type="password" name="password" id="password" required />
                                    <input class="btn col-8 offset-2 my-2 text-white fw-bold" style="background-color:#C38B8C;" type="submit" value="Sign In">
                                </form>
                            </div>
                            <div class="col-md-7">
                                <img src="assets/img/loginImage.png" alt="image.png" class="img-fluid px-2 mb-5 none-mobile" style="max-width: 100%;max-height:100%;">
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 mt-5" style="background-color: #C99798;" >
                    <div class="container-fluid ps-4">
                        <div class="row text-white none-mobile ms-3">
                            <div class="col-md-3 my-2">
                                <h3 class="fw-bold">NEWSLETTER</h3>
                                <p>Sign up for out Newsletter join over 4000 others on the Her'Syle list to get access to our latest news and exclusive discounts!</p>
                                <p>Social media</p>
                                <p><img src="assets/img/instagram.png" alt="instagram.png" style="max-width: 10%;max-height:10%;" class="me-3"> <a href=" " class="text-decoration-none text-white">Her'Style</a></p>
                            </div>
                            <div class="col-md-3 my-2">
                                <h3 class="fw-bold">ACCOUNT</h3>
                                <a href="loginUser.php" class="text-decoration-none text-white">Login</a>
                                <br>
                                <a href="registrasi.php" class="text-decoration-none text-white">Sign Up</a>
                            </div>
                            <div class="col-md-3 my-2">
                                <h3 class="fw-bold">ORDER & PAYMENT</h3>
                                <p>
                                    BRI
                                    <br>
                                    OVO
                                </p>
                            </div>
                            <div class="col-md-3 my-2">
                                <h3 class="fw-bold">CONTACT</h3>
                                <p>
                                    JL. Ir Rais GG IV
                                    <br>
                                    Indonesia
                                    <br>
                                    083166492225
                                </p>
                            </div>
                        </div>
                        <div class="d-flex bd-highlight footer-text-mobile text-white">
                            <div class="p-2 flex-fill bd-highlight none-mobile block-mobile">
                                <div class="mb-3">
                                    <h3 class="fw-bold">NEWSLETTER</h3>
                                    <p>Sign up for out Newsletter join over 4000 others on the Her'Syle list to get access to our latest news and exclusive discounts!</p>
                                </div>
                                <div class="mb-3">
                                    <h3 class="fw-bold">CONTACT</h3>
                                    <p>
                                        JL. Ir Rais GG IV
                                        <br>
                                        Indonesia
                                        <br>
                                        083166492225
                                </p>
                                </div>
                            </div>
                            <div class="p-2 flex-fill bd-highlight text-end none-mobile block-mobile">
                                <div class="mb-3">
                                    <h3 class="fw-bold">ACCOUNT</h3>
                                    <a href="loginUser.php" class="text-decoration-none text-white">Login</a>
                                    <br>
                                    <a href="registrasi.php" class="text-decoration-none text-white">Sign Up</a>
                                </div>
                                <div class="my-3">
                                    <p>
                                        Social media
                                        <br>
                                        <a href=" " class="text-decoration-none text-white me-3">Her'Style</a><img src="assets/img/instagram.png" alt="instagram.png" style="max-width: 25%;max-height:25%;">
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="none-mobile block-mobile">

                            <div class="text-center text-white fs-5 ">
                                <p>Order & Payment</p>
                            </div>
                            <div class="d-flex justify-content-center">
                                <img src="assets/img/bri.png" alt="" class="img-fluid mx-2" style="width: 100px;height: 40px;">
                                <img src="assets/img/ovo.png" alt="" class="img-fluid mx-2" style="width: 100px;height: 40px;">
                            </div>
                        </div>
                        <div class="text-center text-white mt-5">&copy; Copyright by nvn All rigth Reserved</div>
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
