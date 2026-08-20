<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once(__DIR__ . "/php/Class/Product.php");
  require_once(__DIR__ . "/php/Class/Stock.php");
  if (isset($_GET['num_pr'])) {
    extract($_GET);
    $pr = Product::displayPr($num_pr);
    $mouvements = Stock::historiqueProduit($num_pr);
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="POS - Bootstrap Admin Template" />
  <meta name="keywords"
    content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects" />
  <meta name="author" content="Dreamguys - Bootstrap Admin Template" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Product Details</title>

  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />

  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

  <link rel="stylesheet" href="assets/css/animate.css" />

  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />

  <link rel="stylesheet" href="assets/plugins/owlcarousel/owl.carousel.min.css" />

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
            <h4>Product Details</h4>
            <h6>Full details of the product</h6>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-8 col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="productdetails">
                  <ul class="product-bar">
                    <li>
                      <h4>Reference</h4>
                      <h6>
                        <?= $pr['num_pr'] ?>
                      </h6>
                    </li>
                    <li>
                      <h4>Product</h4>
                      <h6>
                        <?= $pr['lib_pr'] ?>
                      </h6>
                    </li>
                    <li>
                      <h4>Description</h4>
                      <h6>
                        <?= $pr['desc_pr'] ?>
                      </h6>
                    </li>
                    <li>
                      <h4>Category</h4>
                      <h6>
                        <?= $pr['lib_cat'] ?>
                      </h6>
                    </li>
                    <li>
                      <h4>Brand</h4>
                      <h6>
                        <?= $pr['nom_marque'] ?>
                      </h6>
                    </li>
                    <li>
                      <h4>Purchase price</h4>
                      <h6>
                        <?= $pr['prix_achat'] ?>DH
                      </h6>
                    </li>

                    <li>
                      <h4>Unit price</h4>
                      <h6>
                        <?= $pr['prix_uni'] ?>DH
                      </h6>
                    </li>
                    <li>
                      <h4>Quantity</h4>
                      <h6 style="<?= (isset($pr['seuil_min']) && $pr['qte_stock'] <= $pr['seuil_min']) ? 'color:#C0392B; font-weight:600;' : '' ?>">
                        <?= $pr['qte_stock'] ?> <?= $pr['unite_mesure'] ?? '' ?>
                        <?php if (isset($pr['seuil_min']) && $pr['qte_stock'] <= $pr['seuil_min']): ?>
                        <span class="badges bg-lightred">Sous le seuil min.</span>
                        <?php endif ?>
                      </h6>
                    </li>
                    <li>
                      <h4>Seuils (min / max)</h4>
                      <h6><?= $pr['seuil_min'] ?? '—' ?> / <?= $pr['seuil_max'] ?? '—' ?></h6>
                    </li>
                    <li>
                      <h4>Code-barres</h4>
                      <h6><?= $pr['code_barre'] ?: '—' ?></h6>
                    </li>
                    <li>
                      <h4>Date de péremption</h4>
                      <h6><?= $pr['date_peremption'] ?: '—' ?></h6>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="slider-product-details">
                  <div class="owl-carousel owl-theme product-slide">
                    <div class="slider-product">
                      <img src="<?= $pr['pr_image'] ?>" style="display: inline-block;" alt="img" />
                      <h4>
                        <?= $pr['lib_pr'] ?>.jpg
                      </h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Historique des mouvements de stock</h4>
                <div class="table-responsive dataview">
                  <table class="table datatable">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Sens</th>
                        <th>Document / réf.</th>
                        <th>Commentaire</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($mouvements as $m): ?>
                      <tr>
                        <td><?= $m['date_mvt'] ?></td>
                        <td><?= Stock::libelleType($m['type_mvt']) ?></td>
                        <td><?= $m['quantite'] ?></td>
                        <td>
                          <?php if (Stock::estEntree($m['type_mvt'])): ?>
                          <span class="badges bg-lightgreen">Entrée</span>
                          <?php else: ?>
                          <span class="badges bg-lightred">Sortie</span>
                          <?php endif ?>
                        </td>
                        <td><?= $m['doc_origine'] ?></td>
                        <td><?= $m['commentaire'] ?></td>
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
    </div>
  </div>

  <script src="assets/js/jquery-3.6.0.min.js"></script>

  <script src="assets/js/feather.min.js"></script>

  <script src="assets/js/jquery.slimscroll.min.js"></script>

  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <script src="assets/plugins/owlcarousel/owl.carousel.min.js"></script>

  <script src="assets/plugins/select2/js/select2.min.js"></script>

  <script src="assets/js/script.js"></script>
</body>

</html>
<?php else: ?>
<?php header("Location: signin.php"); ?>
<?php endif ?>