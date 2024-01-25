<?php
require_once('koneksi.php');

session_start();

if(empty($_SESSION)){
    header('Location: loginUser.php');
}

if($_POST){

    if (isset($_POST['tambah'])) {
        $id_pesanan = $_POST['id_pesanan'];
        $total_biaya = $_POST['total_biaya'] + $_POST['harga'];
        $jumlah = $_POST['jumlah'] + 1;
        
        $query = "UPDATE tbl_pesanan SET total_biaya = $total_biaya, jumlah = $jumlah WHERE id_pesanan = $id_pesanan";
        
        if ($conn->query($query) === TRUE) {
            header('Location: keranjang.php');
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    } else if(isset($_POST['kurang'])){

        $id_pesanan = $_POST['id_pesanan'];
        $total_biaya = $_POST['total_biaya'] - $_POST['harga'];
        $jumlah = $_POST['jumlah'] - 1;
        
        $query = "UPDATE tbl_pesanan SET total_biaya = $total_biaya, jumlah = $jumlah WHERE id_pesanan = $id_pesanan";
        
        if ($conn->query($query) === TRUE) {
            header('Location: keranjang.php');
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }  
    } else if(isset($_POST['hapus'])){

        $id_to_delete = $_POST['hapus'];
       
        $delete_query = "DELETE FROM tbl_pesanan WHERE id_pesanan = $id_to_delete";
        if ($conn->query($delete_query) === TRUE) {
            header("Location: keranjang.php"); 
        } else {
            echo "Error deleting record: " . $conn->error;
        } 
    } else if(isset($_POST['metode_pembayaran'])){

        $no_rek = '';

        for ($i = 0; $i < 10; $i++) {
            $no_rek .= mt_rand(0, 9); 
        }

        $tgl_transaksi =  date("Y-m-d");
        $total_biaya = $_POST['total_biaya'];
        $metode_pembayaran = $_POST['metode_pembayaran'];
        $status_transaksi = 'Belum';
        $id_user = $_SESSION['id'];
        
        $query = "INSERT INTO tbl_transaksi (tgl_transaksi, no_rek, total_biaya, metode_pembayaran, status_transaksi, id_user) VALUES ('$tgl_transaksi', '$no_rek', '$total_biaya', '$metode_pembayaran', '$status_transaksi', '$id_user')";
        
        if ($conn->query($query) === TRUE) {
            header('Location: pembayaran.php');
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }
}

$id = $_SESSION['id'];

$query = "SELECT p.*, d.nama, d.harga, d.ft_barang, d.material, d.ukuran, d.warna
        FROM tbl_pesanan p
        LEFT JOIN tbl_databarang d ON p.id_barang = d.id_barang
        WHERE p.id_user = '$id' AND p.status_pesanan = 'menunggu';
        ";

$result = $conn->query($query);

$totalHarga = "SELECT SUM(p.total_biaya) AS total
            FROM tbl_pesanan p
            LEFT JOIN tbl_databarang d ON p.id_barang = d.id_barang
            WHERE p.id_user = '$id' AND p.status_pesanan = 'menunggu';
            ";

$hasil = $conn->query($totalHarga);
$hasil = $hasil->fetch_assoc();


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
        <title>Keranjang</title>
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
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8 mb-5">
                                <?php 
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>

                                <div class="card mb-3 mt-5 none-mobile" style="background-color: #F3E8E8;border: none;">
                                    <div class="row g-0">
                                        <div class="col-md-3 p-2">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <img src="<?=$row['ft_barang']?>" style="max-height: 250px;max-width:250px" class="img-fluid rounded-start" alt="...">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-body">
                                                <div class="d-flex bd-highlight">
                                                    <div class="flex-grow-1 bd-highlight">
                                                        <h3 class="card-title"><?=$row['nama']?></h3>
                                                    </div>
                                                    <div class="bd-highlight mx-2">
                                                        <h3 class="card-title">IDR <?=number_format($row['harga']*$row['jumlah'])?></h3>
                                                    </div>
                                                    <div class="bd-highlight mx-2">
                                                        <form action="" method="post">
                                                            <input class="form-control" type="text" name="hapus" value="<?=$row['id_pesanan']?>" hidden/>
                                                            <button class="btn bg-transparent" type="submit"><i class="fa-solid fa-xmark"></i></button>
                                                        </form>
                                                    </div>
                                                    
                                                </div>
                                                <h5 class="card-title">IDR <?=number_format($row['harga'])?></h5>

                                                <p class="card-text text-secondary">
                                                    Size : <?=$row['ukuran']?>
                                                    <br>
                                                    Color : <?= ucwords(strtolower($row['warna']))?>
                                                    <br>
                                                    Material : <?= ucwords(strtolower($row['material']))?>
                                                </p>
                                                
                                                <div class="d-flex align-items-center">
                                                    <form action="" method="post">
                                                        <input class="form-control" type="number" name="id_pesanan" value="<?=$row['id_pesanan']?>" hidden/>
                                                        <input class="form-control" type="number" name="harga" value="<?=$row['harga']?>" hidden/>
                                                        <input class="form-control" type="number" name="total_biaya" value="<?=$row['total_biaya']?>" hidden/>
                                                        <input class="form-control" type="number" name="jumlah" value="<?=$row['jumlah']?>" hidden/>
                                                        <input class="form-control" type="number" name="kurang" value="1" hidden/>
                                                        <button class="btn btn-light" type="submit"><i class="fa-solid fa-minus"></i></button>
                                                    </form>
                                                    <span class="mx-3"><?=$row['jumlah']?></span>
                                                    <form action="" method="post">
                                                        <input class="form-control" type="number" name="id_pesanan" value="<?=$row['id_pesanan']?>" hidden/>
                                                        <input class="form-control" type="number" name="harga" value="<?=$row['harga']?>" hidden/>
                                                        <input class="form-control" type="number" name="total_biaya" value="<?=$row['total_biaya']?>" hidden/>
                                                        <input class="form-control" type="number" name="jumlah" value="<?=$row['jumlah']?>" hidden/>
                                                        <input class="form-control" type="number" name="tambah" value="1" hidden/>
                                                        <button class="btn btn-light" type="submit"><i class="fa-solid fa-plus"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="none-mobile block-mobile">  
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0 ">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <img src="<?=$row['ft_barang']?>" style="max-height: 250px;max-width:250px" class="img-fluid rounded-start" alt="...">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3 ">
                                            <div class="d-flex">
                                                <div class="flex-fill">
                                                    <h5 class="card-title"><?=$row['nama']?></h5>
                                                </div>
                                                <div class="flex-fill">
                                                    <form action="" method="post">
                                                        <input class="form-control" type="text" name="hapus" value="<?=$row['id_pesanan']?>" hidden/>
                                                        <button class="btn bg-transparent" type="submit"><i class="fa-solid fa-xmark"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-5 text-secondary">
                                                <div class="flex-fill"> Size : <br><?=$row['ukuran']?></div>
                                                <div class="flex-fill"> Color : <br><?= ucwords(strtolower($row['warna']))?></div>
                                                <div class="flex-fill"> Material : <br><?= ucwords(strtolower($row['material']))?></div>
                                            </div>
                                            <div class="d-flex mt-4">
                                                <div class="flex-fill">
                                                    <div class="d-flex align-items-center">
                                                            <form action="" method="post">
                                                                <input class="form-control" type="number" name="id_pesanan" value="<?=$row['id_pesanan']?>" hidden/>
                                                                <input class="form-control" type="number" name="harga" value="<?=$row['harga']?>" hidden/>
                                                                <input class="form-control" type="number" name="total_biaya" value="<?=$row['total_biaya']?>" hidden/>
                                                                <input class="form-control" type="number" name="jumlah" value="<?=$row['jumlah']?>" hidden/>
                                                                <input class="form-control" type="number" name="kurang" value="1" hidden/>
                                                                <button class="btn btn-sm btn-light" type="submit"><i class="fa-solid fa-minus"></i></button>
                                                            </form>
                                                            <span class="mx-3"><?=$row['jumlah']?></span>
                                                            <form action="" method="post">
                                                                <input class="form-control" type="number" name="id_pesanan" value="<?=$row['id_pesanan']?>" hidden/>
                                                                <input class="form-control" type="number" name="harga" value="<?=$row['harga']?>" hidden/>
                                                                <input class="form-control" type="number" name="total_biaya" value="<?=$row['total_biaya']?>" hidden/>
                                                                <input class="form-control" type="number" name="jumlah" value="<?=$row['jumlah']?>" hidden/>
                                                                <input class="form-control" type="number" name="tambah" value="1" hidden/>
                                                                <button class="btn btn-sm btn-light" type="submit"><i class="fa-solid fa-plus"></i></button>
                                                            </form>
                                                        </div>
                                                </div>
                                                <div class="flex-fill">
                                                    <p class="card-title">IDR <?=number_format($row['harga']*$row['jumlah'])?></p>
                                                </div>
                                            </div>
                                        
                                        </div>
                                    </div>
                                </div>
                                
                                <?php 
                                    } 
                                } else {
                                    echo '<h1 class="text-center">Your Cart is Empty</h1>';
                                }
                                ?>
                                
                            </div>

                            <div class="col-md-4 pt-5 none-mobile" style="background-color: #D9BCAB;">
                                <div class="text-center align-items-center">
                                    <h1>Bag Total IDR <?=number_format($hasil['total'])?></h1>
                                    <div class="mt-5">
                                        <button class="btn text-white col-5 fw-bolder fs-5" style="background-color: #C38B8C;" data-bs-toggle="modal" data-bs-target="#myModal">Checkout</button>
                                    </div>
                                    <div class="my-5">
                                        <h3>Accepted payment method <i class="fa-solid fa-arrow-right fs-3"></i></h3> 
                                        <img src="assets/img/ovo.png" class="img-fluid rounded-start mx-3 my-3" alt="...">
                                        <img src="assets/img/bri.png" class="img-fluid rounded-start mx-3 my-3" alt="...">
                                    </div>
                                </div>
                            </div>

                            <div class="none-mobile block-mobile">
                         
                                    <div class="p-3" style="background-color: #D9BCAB;">
                                        <h4 >Bag Total  <span class="fw-bold">IDR <?=number_format($hasil['total'])?></span> </h4>
                                        <div class="mt-4">
                                            <button class="btn btn-sm text-white col-12 fw-bold fs-4" style="background-color: #C38B8C;" data-bs-toggle="modal" data-bs-target="#myModal">Checkout</button>
                                        </div>
                                        <div class="my-4">
                                            <h5>Accepted payment method <i class="fa-solid fa-arrow-right fs-3"></i></h5> 
                                            <div class="d-flex justify-items-center">
                                                <img src="assets/img/ovo.png" class="img-fluid rounded-start mx-3 my-3" alt="...">
                                                <img src="assets/img/bri.png" class="img-fluid rounded-start mx-3 my-3" alt="...">
                                            </div>
                                        </div>
                                    </div>
                                
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

            <div class="modal" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content" style="background-color: #D9BCAB;">
                        <div class="modal-header" style="border:none;">
                            <h4 class="modal-title fw-bold">Checkout</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post">
                            <input class="form-control" type="number" name="total_biaya" value="<?=$hasil['total']?>" hidden/>
                                
                                <div class="form-group my-2">
                                    <label for="metode_pembayaran" class="my-2 fs-5">Metode Pembayaran</label>
                                    <select name="metode_pembayaran" class="form-select">
                                        <option value="OVO">OVO</option>
                                        <option value="BRI">BRI</option>
                                    </select>
                                </div>
                                <button class="btn col-12 mt-3 text-white fw-bold" style="background-color: #C38B8C;" type="submit">Lanjutkan Pembayaran</button>
                            </form>
                        </div>
                    </div>
                </div>
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
