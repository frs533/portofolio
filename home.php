<?php
require_once('koneksi.php');

session_start();

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    header('Location: shop.php?search='.$search);
} else {
    $query = "SELECT * FROM tbl_databarang GROUP BY nama";
    $new = "SELECT * FROM tbl_databarang GROUP BY nama ORDER BY id_barang LIMIT 3 ";
}

$listBarang = $conn->query($query);
$newBarang = $conn->query($new);

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
        <title>Home</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/style2.css" rel="stylesheet" />
        <link href="css/mobile.css" rel="stylesheet" />
        <link href="css/carousel.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-primary justify-content-between bg-white">
            <div style="margin-left:3%">
                <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link fs-3 fw-bolder text-dark" href="shop.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Shop
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
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
                    <div class="jumbotron jumbotron-fluid text-center">
                        <a class="btn btn-lg text-dark" style="background-color: #C68888;" href="shop.php" role="button"> <span class="fw-bold me-2">Shop Our Latest</span>  <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="container-fluid mt-5">

                        <div class="card-group my-5">

                            <div class="card mx-5 position-relative" style="border:none">
                                <img src="assets/img/pantsStyle.png" style="max-width: 100%; height: 600px;" alt="image.png" class="img-type img-fluid">
                                <div class="btn-container">
                                    <button class="btn btn-secondary bg-secondary fw-bolder fs-4 text-dark "><h1>Pants <br> Style</h1></button>
                                </div>
                            </div>
                            <div class="card mx-5 position-relative" style="border:none">
                                <img src="assets/img/dressStyle.png" style="max-width: 100%; height: 550px;" alt="image.png" class="img-type img-fluid">
                                <div class="btn-container">
                                    <button class="btn btn-secondary  bg-secondary  fw-bolder text-dark "><h1>Dress <br> Style</h1></button>
                                </div>
                            </div>
                            <div class="card mx-5 position-relative" style="border:none">
                                <img src="assets/img/shortStyle.png" style="max-width: 100%; height: 600px;" alt="image.png" class="img-type img-fluid">
                                <div class="btn-container">
                                    <button class="btn btn-secondary bg-secondary fw-bolder text-dark "><h1>Short <br> Style</h1></button>
                                </div>
                            </div>
                            
                        </div>

                        <div class="text-center mb-5">
                            <h1 class="fw-bolder" id="best_seller"> <span>Best Seller</span></h1>
                        </div>
                        <div class="text-center my-5">
                            <div class="row mx-auto my-auto justify-content-center">
                                <div id="recipeCarousel" class="carousel slide text-center" data-bs-ride="carousel">
                                    <div class="carousel-inner" role="listbox">
                                        <?php 
                                        if ($listBarang->num_rows > 0) {
                                            $i = 0;
                                            while ($row = $listBarang->fetch_assoc()) {       
                                                if($i==0){
                                                    echo '<div class="carousel-item active">';
                                                } else {
                                                    echo '<div class="carousel-item">';
                                                }
                                        ?>
                                                <div class="col-md-6">
                                                    <div class="card my-5" style="border:none;">
                                                        <div class="card-img">
                                                            <img src="<?= $row['ft_barang'] ?>" style="max-width: 100%; height: 500px;" class="img-carousel img-fluid">
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                        <?php 
                                            echo '</div>';
                                            $i++;
                                            } 
                                        }
                                        ?>
                                    </div>
                                    <a class="carousel-control-prev w-aut" href="#recipeCarousel" role="button" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    </a>
                                    <a class="carousel-control-next w-aut" href="#recipeCarousel" role="button" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <h1 class="fw-bolder">< NEW ARRIVAL ></h1>
                        </div>
                        <div class="card-group my-5">
                            <?php 
                            if ($newBarang->num_rows > 0) {
                                while ($row = $newBarang->fetch_assoc()) {       
                                    
                            ?>
                            <div class="card mx-5" style="border:none">
                                <img src="<?= $row['ft_barang']?>" style="max-width: 100%; height: 550px;" alt="image.png" class="img-arrival img-fluid">
                                <div class="card-body">
                                <h5 class="card-title"><?= ucwords(strtolower($row['nama']))?></h5>
                                <p class="card-text">IDR <?= number_format($row['harga'])?></p>
                                <a href="detailShop.php?id=<?= $row['id_barang']?>" class="btn col-12 text-center text-dark fw-bold p-3 fs-4" style="background-color: #F3E8E8;border: 1px solid #7B7373;">Add to Bag</a>
                                </div>
                            </div>
                            <?php 
                                } 
                            }
                            ?>
                            
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
        <script src="js/carousel.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
