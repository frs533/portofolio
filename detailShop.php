<?php
require_once('koneksi.php');

session_start();

if($_POST){

    if (isset($_POST['isi_coment'])) {
        $isi_coment = $_POST['isi_coment'];
        $tgl_coment = date("Y-m-d");
        $id_user = $_POST['id_user'];
        $id_barang = $_POST['id_barang'];
        
        $query = "INSERT INTO tbl_coment (isi_coment, tgl_coment, id_user, id_barang) VALUES ('$isi_coment', '$tgl_coment', '$id_user', '$id_barang')";
        
        if ($conn->query($query) === TRUE) {
            header('Location: detailShop.php?id='. $id_barang);
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    } else {
        $id_user = $_POST['id_user'];
        $id_barang = $_POST['id_barang'];
        $tgl_pesanan = date("Y-m-d");
        $total_biaya = $_POST['harga'];
        $status_pesanan = "menunggu";
        
        $query = "INSERT INTO tbl_pesanan (id_user, id_barang, tgl_pesanan, total_biaya, status_pesanan, jumlah) VALUES ('$id_user', '$id_barang', '$tgl_pesanan', '$total_biaya', '$status_pesanan', 1)";
        
        if ($conn->query($query) === TRUE) {
            header('Location: keranjang.php');
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
       
    }
}

$id = $_GET['id'];
$query = "SELECT * FROM tbl_databarang WHERE id_barang LIKE '$id'";
$listBarang = $conn->query($query);

$data = $listBarang->fetch_assoc();

$ukuran = [
    'XS' => 'Extra Small',
    'S' => 'Small',
    'M' => 'Medium',
    'L' => 'Large',
    'XL' => 'Extra Large',
];

$namaBarang = $data['nama'];
$dataUkuran = "SELECT id_barang,ukuran FROM tbl_databarang WHERE nama LIKE '$namaBarang'";
$listUkuran = $conn->query($dataUkuran);


$dataComent = "SELECT c.*, u.nama_pelanggan FROM tbl_coment c LEFT JOIN tbl_user u ON c.id_user = u.id_user WHERE c.id_barang = '$id';";
$listComent = $conn->query($dataComent);




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
        <title>Detail Barang</title>
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
                    <div class="container-fluid mt-5">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="home.php" class="text-decoration-none text-dark">HOME</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="shop.php" class="text-decoration-none text-dark">Shop</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a class="text-decoration-none text-dark"><?= $data['tipe']?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a class="text-decoration-none text-dark"><?= ucwords(strtolower($data['nama']))?></a></li>
                            </ol>
                        </nav>
                        <div class="row mb-5">
                            <div class="col-md-4 pt-5">
                                <div class="d-flex justify-content-center align-items-center">
                                    <img src="<?= $data['ft_barang']?>" alt="" class=" mx-auto" height="400">
                                </div>
                            </div>

                            <div class="col-md-8 pe-5 ps-5 pt-5">
                                <p class="fs-3"><?= ucwords(strtolower($data['nama']))?></p>
                                <p class="fs-4">IDR <?= number_format($data['harga'])?></p>
                                <p><?= $data['deskripsi']?></p>
                                <p class="fs-5">Size : <?= $ukuran[$data['ukuran']]?></p>
                                    <?php 
                                    if ($listUkuran->num_rows > 0) {
                                        while ($row = $listUkuran->fetch_assoc()) {  
                                            if($row['ukuran'] == $data['ukuran'] ){
                                            ?>
                                                <a href="detailShop.php?id=<?=$row['id_barang']?>" class="btn btn-dark"><?=$row['ukuran']?></a>
                                                <?php
                                                } else {
                                                ?>
                                                <a href="detailShop.php?id=<?=$row['id_barang']?>" class="btn btn-light"><?=$row['ukuran']?></a>
                                            <?php
                                            }    
                                            ?>
                                        <?php 
                                        } 
                                    }
                                    ?>
                                <p class="fs-5">Color : <?= ucwords(strtolower($data['warna']))?></p>
                                <p class="fs-5">Material : <?= ucwords(strtolower($data['material']))?></p>
                                <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Coment</button>

                                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                <div class="offcanvas-header bg-secondary text-white">
                                    <h5 id="offcanvasRightLabel">Comment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>

                                    <div class="offcanvas-body">
                                    <?php 
                                    if ($listComent->num_rows > 0) {
                                        while ($row = $listComent->fetch_assoc()) {
                                    ?>
                                        <h5 class="fw-bold"><?= ucwords(strtolower($row['nama_pelanggan'])) ?></h5>
                                        <p><?= $row['isi_coment'] ?></p>
                                        <hr>
                                    <?php 
                                        } 
                                    } else {
                                        echo '<p>Belum ada komentar</p>';
                                    }

                                    if(!empty($_SESSION['id'])){
                                    ?>
        
                                        <form action="" method="post">
                                            <input type="text" name="id_user" value="<?= $_SESSION['id']?>" hidden>
                                            <input type="text" name="id_barang" value="<?= $_GET['id']?>" hidden>
                                            <textarea name="isi_coment" class="form-control" cols="10" rows="1"></textarea>
                                            <input type="submit" class="btn btn-secondary mt-3" value="Kirim">
                                        </form>

                                    <?php } ?>
                                        
                                    </div>
                                </div>
                                <br>

                                <?php if(!empty($_SESSION['id'])){ ?>
                                <form action="" method="post">
                                    <input type="text" name="id_user" value="<?= $_SESSION['id']?>" hidden>
                                    <input type="text" name="id_barang" value="<?= $_GET['id']?>" hidden>
                                    <input type="number" name="harga" value="<?=$data['harga']?>" hidden>
                                    <input type="submit" class="btn btn-secondary mt-3 col-8" value="Add to bag">
                                </form> 
                                <?php } else { ?> 
                                    <a href="loginUser.php"class="btn btn-secondary mt-3 col-8">Add to bag</a>
                                <?php } ?> 
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
