<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once(__DIR__ . "/php/Class/Stock.php");
  $active = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "active", 0, 0, 0, 0);
  $mouvements = Stock::tousLesMouvements();

  // Filtre simple côté serveur (produit / type de mouvement) via GET.
  if (!empty($_GET['num_pr'])) {
    $mouvements = array_values(array_filter($mouvements, fn($m) => $m['num_pr'] === $_GET['num_pr']));
  }
  if (!empty($_GET['type_mvt'])) {
    $mouvements = array_values(array_filter($mouvements, fn($m) => $m['type_mvt'] === $_GET['type_mvt']));
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="POS - Bootstrap Admin Template" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Stock Movements</title>

  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/animate.css" />
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
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
            <h4>Mouvements de stock</h4>
            <h6>Historique complet des entrées, sorties et ajustements</h6>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <form class="row mb-3" method="get" action="stockmovements.php">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group mb-0">
                  <label>Référence produit</label>
                  <input type="text" name="num_pr" class="form-control" placeholder="Ex : PR001"
                    value="<?= htmlspecialchars($_GET['num_pr'] ?? '') ?>" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group mb-0">
                  <label>Type de mouvement</label>
                  <select class="form-control" name="type_mvt">
                    <option value="">Tous</option>
                    <?php foreach ([Stock::ENTREE_ACHAT, Stock::ENTREE_INITIALE, Stock::SORTIE_VENTE, Stock::RETOUR_CLIENT, Stock::RETOUR_FOURNISSEUR, Stock::AJUSTEMENT_INVENTAIRE, Stock::AJUSTEMENT_MANUEL, Stock::PERTE] as $t): ?>
                    <option value="<?= $t ?>" <?= (($_GET['type_mvt'] ?? '') === $t) ? 'selected' : '' ?>>
                      <?= Stock::libelleType($t) ?>
                    </option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12 d-flex align-items-end">
                <button type="submit" class="btn btn-submit me-2">Filtrer</button>
                <a href="stockmovements.php" class="btn btn-cancel">Réinitialiser</a>
              </div>
            </form>

            <div class="table-responsive">
              <table class="table datanew">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Produit</th>
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
                    <td class="productimgname">
                      <a class="product-img" href="product-details.php?num_pr=<?= $m['num_pr'] ?>">
                        <img src="<?= $m['pr_image'] ?>" alt="product" />
                      </a>
                      <a href="product-details.php?num_pr=<?= $m['num_pr'] ?>"><?= $m['lib_pr'] ?></a>
                    </td>
                    <td><?= Stock::libelleType($m['type_mvt']) ?></td>
                    <td><?= $m['quantite'] ?> <?= $m['unite_mesure'] ?></td>
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

  <script src="assets/js/jquery-3.6.0.min.js"></script>
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/jquery.slimscroll.min.js"></script>
  <script src="assets/js/jquery.dataTables.min.js"></script>
  <script src="assets/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/plugins/select2/js/select2.min.js"></script>
  <script src="assets/js/script.js"></script>
</body>

</html>
<?php else: ?>
<?php header("Location: signin.php"); ?>
<?php endif ?>
