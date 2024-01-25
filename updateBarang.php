<?php
require_once('koneksi.php');

session_start();

if(empty($_SESSION['status'])){
    header('Location: 401.html');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_barang'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $material = $_POST['material'];
    $ukuran = $_POST['ukuran'];
    $warna = $_POST['warna'];
    $tipe = $_POST['tipe'];
    $ft_lama = $_POST['ft_lama'];
    
    if(empty(basename($_FILES["ft_barang"]["name"]))){
        $update_query = "UPDATE tbl_databarang 
        SET nama='$nama', harga=$harga, deskripsi='$deskripsi', 
            material='$material', ukuran='$ukuran', warna='$warna', tipe='$tipe' 
        WHERE id_barang=$id";
        if ($conn->query($update_query) === TRUE) {
            header('Location: barang.php');
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {

        if (file_exists($ft_lama)) {
            unlink($ft_lama);
        }

        $target_directory = "uploads/"; 
        $target_file = $target_directory . basename($_FILES["ft_barang"]["name"]);

        if (move_uploaded_file($_FILES["ft_barang"]["tmp_name"], $target_file)) {
    
            $update_query = "UPDATE tbl_databarang 
                            SET nama='$nama', harga=$harga, deskripsi='$deskripsi', 
                                material='$material', ukuran='$ukuran', warna='$warna', tipe='$tipe', ft_barang='$target_file'
                            WHERE id_barang=$id";
        
            if ($conn->query($update_query) === TRUE) {
                header('Location: barang.php');
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Upload Error";
        }

    }

}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $select_query = "SELECT * FROM tbl_databarang WHERE id_barang=$id";
    $result = $conn->query($select_query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc(); 
        $id = $row["id_barang"];
        $nama = $row["nama"];
        $harga = $row["harga"];
        $deskripsi = $row["deskripsi"];
        $material = $row["material"];
        $ukuran = $row["ukuran"];
        $warna = $row["warna"];
        $tipe = $row["tipe"];
        $ft_barang = $row["ft_barang"];
    } else {
        echo "Kosong";
        exit;
    }
} else {
    echo "Kosong";
    exit;
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
        <title>Barang - Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-primary bg-primary">
            <img src="assets/img/logo.svg" alt="newLogo.png" class="ms-3" style="max-width: 100%;max-height:100%;">
            <a class="navbar-brand ps-3 fw-bolder fs-3" href="index.html">Her'Style</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 text-dark" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" method="POST">
                <div class="input-group">
                    <input class="form-control" type="text" name="search" id="search" placeholder="Cari..." aria-label="Cari..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-secondary" id="btnNavbarSearch" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item">
                    <i class="fa-solid fa-circle-user fs-2 text-white mt-3 me-2"></i>
                 </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?=$_SESSION['username']?>
                        <br>
                        Admin                 
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading text-white">
                         
                                <a class="nav-link text-white" href="dashboard.php">
                                    <div class="sb-nav-link-icon"><img class="sb-nav-link-icon" src="assets/img/user.png" alt="newLogo.png" class="ms-3" width="50"></div>
                                    <p class="align-items-center">
                                        <span class="pt-2">Zhahira</span> 
                                        <br>
                                        <i class="fa fa-circle me-2 mt-2 text-success" aria-hidden="true"></i>Online
                                    </p>
                                </a>
                                <a class="nav-link" href="dashboard.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Dashboard
                                </a>
                                <a class="nav-link text-white fw-bolder" href="barang.php">
                                    <div class="sb-nav-link-icon"><i class="fa fa-list-alt text-white fw-bolder" aria-hidden="true"></i></div>
                                    Data Barang
                                </a>
                                <a class="nav-link" href="pesanan.php">
                                    <div class="sb-nav-link-icon"><i class="fa-sharp fa-light fa-cart-shopping"></i></div>
                                    Pesanan
                                </a>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTransaksi" aria-expanded="false" aria-controls="collapseTransaksi">
                                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                    Transaksi
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseTransaksi" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="transaksi.php?metode=BRI">BRI</a>
                                        <a class="nav-link" href="transaksi.php?metode=OVO">OVO</a>
                                    </nav>
                                </div>
                                <a class="nav-link " href="coment.php">
                                    <div class="sb-nav-link-icon"><i class="fa-sharp fa-light fa-comment "></i></div>
                                    Coment
                                </a>
                                <a class="nav-link " href="ulasan.php">
                                    <div class="sb-nav-link-icon"><i class="fa-sharp fa-light fa-comments "></i></i></div>
                                    Ulasan
                                </a>
                                <a class="nav-link" href="logout.php">
                                    <div class="sb-nav-link-icon"><i class="fa-sharp fa-light fa-arrow-right-from-bracket"></i></div>
                                    Logout
                                </a>
                            
                            </div>
                    </div>
                    
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">
                            Edit Data Barang
                        </h1>
                        <div class="row mt-3">
                            <div class="col">
                                <div class="card mb-4">
                                    <div class="card-body">
                                    <form action="#" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id_barang" value="<?php echo $id; ?>">
                                        <div class="row mt-3">
                                            <div class="col-1">
                                                <label for="nama">Nama Barang</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $nama; ?>" required>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-1">
                                                <label for="warna">Color</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" name="warna" id="warna" class="form-control" value="<?php echo $warna; ?>" required>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-1">
                                                <label for="material">Material</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" name="material" id="material" class="form-control" value="<?php echo $material; ?>" required>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-1">
                                                <label for="tipe">Type</label>
                                            </div>
                                            <div class="col-3">
                                                <select name="tipe" class="form-select">
                                                    <option value="Dresses" <?php echo ($tipe == 'Dresses') ? 'selected' : ''; ?>>Dresses</option>
                                                    <option value="Pants" <?php echo ($tipe == 'Pants') ? 'selected' : ''; ?>>Pants</option>
                                                    <option value="Shorts" <?php echo ($tipe == 'Shorts') ? 'selected' : ''; ?>>Shorts</option>
                                                    <option value="Tops" <?php echo ($tipe == 'Tops') ? 'selected' : ''; ?>>Tops</option>
                                                    <option value="Skirts" <?php echo ($tipe == 'Skirts') ? 'selected' : ''; ?>>Skirts</option>
                                                    <option value="Jumpsuits" <?php echo ($tipe == 'Jumpsuits') ? 'selected' : ''; ?>>Jumpsuits</option>
                                                 </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-1">
                                                <label for="ukuran">Size</label>
                                            </div>
                                            <div class="col-3">
                                                <select name="ukuran" class="form-select">
                                                    <option value="XS" <?php echo ($ukuran == 'XS') ? 'selected' : ''; ?>>XS</option>
                                                    <option value="S" <?php echo ($ukuran == 'S') ? 'selected' : ''; ?>>S</option>
                                                    <option value="M" <?php echo ($ukuran == 'M') ? 'selected' : ''; ?>>M</option>
                                                    <option value="L" <?php echo ($ukuran == 'L') ? 'selected' : ''; ?>>L</option>
                                                    <option value="XL" <?php echo ($ukuran == 'XL') ? 'selected' : ''; ?>>XL</option>
                                                 </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-1">
                                                <label for="deskripsi">Deskripsi</label>
                                            </div>
                                            <div class="col-3">
                                                <textarea name="deskripsi" id="deskripsi" class="form-control" required><?php echo $deskripsi; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-1">
                                                <label for="harga">Harga</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="number" name="harga" id="harga" class="form-control" value="<?php echo $harga; ?>" required>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-1">
                                                <label for="ft_barang">Upload Gambar</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="file" name="ft_barang" id="ft_barang" class="form-control">
                                            </div>
                                            <input type="hidden" name="ft_lama" id="ft_lama" class="form-control" value="<?php echo $ft_barang; ?>">
                                        </div>
                                        <div class="row mt-3">
                                            <div class="offset-1">
                                                <input type="submit" class="btn btn-secondary rounded-pill col-2" value="Submit">
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div> 

                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Her'Style 2023</div>
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

