<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once(__DIR__ . "/php/Class/Inventaire.php");
  $active = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "active", 0, 0, 0);
  $id_inventaire = (int) ($_GET['id_inventaire'] ?? 0);
  $inventaire = Inventaire::afficher($id_inventaire);
  $lignes = Inventaire::details($id_inventaire);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="POS - Bootstrap Admin Template" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Inventory Details</title>

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
            <h4>Détail de l'inventaire #<?= $id_inventaire ?></h4>
            <h6>Réalisé le <?= $inventaire['date_inventaire'] ?? '' ?> — statut : <?= $inventaire['statut'] ?? '' ?></h6>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table datanew">
                <thead>
                  <tr>
                    <th>Produit</th>
                    <th>Stock théorique</th>
                    <th>Stock compté</th>
                    <th>Écart</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($lignes as $l): ?>
                  <tr>
                    <td class="productimgname">
                      <a class="product-img"><img src="<?= $l['pr_image'] ?>" alt="product" /></a>
                      <a href="javascript:void(0);"><?= $l['lib_pr'] ?></a>
                    </td>
                    <td><?= $l['qte_theorique'] ?></td>
                    <td><?= $l['qte_comptee'] ?></td>
                    <td style="font-weight:600; color:<?= $l['ecart'] == 0 ? '#28a745' : ($l['ecart'] > 0 ? '#2E5395' : '#C0392B') ?>;">
                      <?= $l['ecart'] > 0 ? '+' . $l['ecart'] : $l['ecart'] ?>
                    </td>
                  </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
            <a href="inventorylist.php" class="btn btn-cancel mt-3">Retour à la liste des inventaires</a>
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
