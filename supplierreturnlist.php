<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once(__DIR__ . "/php/Class/RetourFournisseur.php");
  $active = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "active", 0);
  $retours = RetourFournisseur::liste();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="POS - Bootstrap Admin Template" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Supplier Return List</title>

  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/animate.css" />
  <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
  <div id="global-loader">
    <div class="whirly-loader"></div>
  </div>

  <div class="main-wrapper">
    <?php require_once("header.php"); ?>
    <?php require_once("sidebar.php"); ?>
    <div class="page-wrapper">
      <div class="content">
        <div class="page-header">
          <div class="page-title">
            <h4>Retours fournisseurs</h4>
            <h6>Historique des marchandises retournées aux fournisseurs</h6>
          </div>
          <div class="page-btn">
            <a href="addsupplierreturn.php" class="btn btn-added">
              <img src="assets/img/icons/plus.svg" alt="img" class="me-1" />
              Nouveau retour
            </a>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table datanew">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Produit</th>
                    <th>Achat lié</th>
                    <th>Quantité</th>
                    <th>Motif</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($retours as $r): ?>
                  <tr>
                    <td><?= $r['date_retour'] ?></td>
                    <td class="productimgname">
                      <a class="product-img"><img src="<?= $r['pr_image'] ?>" alt="product" /></a>
                      <a href="javascript:void(0);"><?= $r['lib_pr'] ?></a>
                    </td>
                    <td><?= $r['num_app'] ?: '—' ?></td>
                    <td><?= $r['qte'] ?></td>
                    <td><?= $r['motif'] ?></td>
                  </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/js/jquery-3.6.0.min.js"></script>
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/jquery.slimscroll.min.js"></script>
  <script src="assets/js/jquery.dataTables.min.js"></script>
  <script src="assets/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/script.js"></script>
</body>

</html>
<?php else: ?>
<?php header("Location: signin.php"); ?>
<?php endif ?>
