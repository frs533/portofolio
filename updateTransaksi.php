<?php
require_once('koneksi.php');

session_start();

if(empty($_SESSION['status'])){
    header('Location: 401.html');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
        header("Location: transaksi.php?search=$search"); 
    } else {

        $id_transaksi = $_POST['id_transaksi'];
        $tgl_transaksi = $_POST['tgl_transaksi'];
        $no_rek = $_POST['no_rek'];
        $total_biaya = $_POST['total_biaya'];
        $metode_pembayaran = $_POST['metode_pembayaran'];
        $id_user = $_POST['id_user'];
        $status_transaksi = $_POST['status_transaksi'];
    
        $update_query = "UPDATE tbl_transaksi SET tgl_transaksi='$tgl_transaksi', no_rek='$no_rek', total_biaya='$total_biaya', metode_pembayaran='$metode_pembayaran', id_user='$id_user', status_transaksi='$status_transaksi' WHERE id_transaksi='$id_transaksi'";
    
        if ($conn->query($update_query) === TRUE) {
            header('Location: transaksi.php?metode='.$metode_pembayaran);
        } else {
            echo "Error: " . $update_query . "<br>" . $conn->error;
        }
    }
}

if (isset($_GET['id'])) {
    $id_transaksi = $_GET['id'];

    $query = "SELECT * FROM tbl_transaksi WHERE id_transaksi='$id_transaksi'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $tgl_transaksi = $row["tgl_transaksi"];
        $no_rek = $row["no_rek"];
        $total_biaya = $row["total_biaya"];
        $metode_pembayaran = $row["metode_pembayaran"];
        $id_user = $row["id_user"];
        $status_transaksi = $row["status_transaksi"];
    } else {
        echo "Data transaksi tidak ditemukan.";
        exit;
    }
} else {
    echo "ID transaksi tidak diberikan.";
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
        <title>Transaksi - Admin</title>
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
                                <a class="nav-link" href="barang.php">
                                    <div class="sb-nav-link-icon"><i class="fa fa-list-alt" aria-hidden="true"></i></div>
                                    Data Barang
                                </a>
                                <a class="nav-link" href="pesanan.php">
                                    <div class="sb-nav-link-icon"><i class="fa-sharp fa-light fa-cart-shopping"></i></div>
                                    Pesanan
                                </a>
                                <a class="nav-link text-white fw-bolder collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTransaksi" aria-expanded="false" aria-controls="collapseTransaksi">
                                    <div class="sb-nav-link-icon"><i class="fas fa-columns text-white fw-bolder "></i></div>
                                    Transaksi
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseTransaksi" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="transaksi.php?metode=BRI">BRI</a>
                                        <a class="nav-link" href="transaksi.php?metode=OVO">OVO</a>
                                    </nav>
                                </div>
                                <a class="nav-link" href="coment.php">
                                    <div class="sb-nav-link-icon"><i class="fa-sharp fa-light fa-comment"></i></div>
                                    Coment
                                </a>
                                <a class="nav-link" href="ulasan.php">
                                    <div class="sb-nav-link-icon"><i class="fa-sharp fa-light fa-comments"></i></i></div>
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
                            Edit Transaksi
                        </h1>
                        <div class="row mt-3">
                            <div class="col">
                                <div class="card mb-4">
                                    <div class="card-body">
                                    <form action="" method="post">
                                    <input type="hidden" name="id_transaksi" value="<?php echo $id_transaksi; ?>">
                                    <div class="row">
                                            <div class="col-1 me-3">
                                                <label for="no_rek">Nomor Transaksi</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" name="no_rek" id="no_rek" class="form-control" value="<?php echo $no_rek; ?>" required><br><br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-1 me-3">
                                                <label for="id_user">ID User</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" name="id_user" id="id_user" class="form-control" value="<?php echo $id_user; ?>" required><br><br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-1 me-3">
                                                <label for="total_biaya">Total Transaksi</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="number" name="total_biaya" id="total_biaya" class="form-control" value="<?php echo $total_biaya; ?>"required><br><br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-1 me-3">
                                                <label for="tgl_transaksi">Tanggal Transaksi</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="date" name="tgl_transaksi" id="tgl_transaksi" class="form-control" value="<?php echo $tgl_transaksi; ?>" required><br><br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-1 me-3">
                                                <label for="metode_pembayaran">Metode Pembayaran</label>
                                            </div>
                                            <div class="col-3">
                                                <select name="metode_pembayaran" class="form-select">
                                                    <option value="OVO" <?php echo ($metode_pembayaran == 'OVO') ? 'selected' : ''; ?>>OVO</option>
                                                    <option value="BRI" <?php echo ($metode_pembayaran == 'BRI') ? 'selected' : ''; ?>>BRI</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-1 me-3">
                                                <label for="status_transaksi">Status Transaksi</label>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" name="status_transaksi" id="status_transaksi" class="form-control" value="<?php echo $status_transaksi; ?>" required><br><br>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
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