<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once(__DIR__ . "/php/Class/Product.php");
  require_once(__DIR__ . "/php/Class/Inventaire.php");
  $active = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "active");

  $step = "start"; // start -> count -> done
  $id_inventaire = null;

  // Etape 1 : démarrage d'une nouvelle session d'inventaire.
  if (isset($_POST['start_inventory'])) {
    $id_inventaire = Inventaire::creer($_POST['commentaire'] ?? null);
    $step = "count";
  }

  // Etape 2 : validation du comptage saisi -> calcul des écarts + régularisation du stock.
  if (isset($_POST['validate_inventory'])) {
    $id_inventaire = (int) $_POST['id_inventaire'];
    $qtes_theoriques = $_POST['qte_theorique'] ?? [];
    $qtes_comptees = $_POST['qte_comptee'] ?? [];
    foreach ($qtes_comptees as $num_pr => $qte_comptee) {
      if ($qte_comptee === '') {
        continue; // produit non compté dans cette session, ignoré
      }
      Inventaire::ajouterLigne($id_inventaire, $num_pr, (int) $qtes_theoriques[$num_pr], (int) $qte_comptee);
    }
    Inventaire::valider($id_inventaire);
    $step = "done";
  }

  if ($step === "count" || (isset($_GET['id_inventaire']))) {
    $id_inventaire = $id_inventaire ?? (int) $_GET['id_inventaire'];
    $step = "count";
    $products = Product::afficher("produit");
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
  <meta name="description" content="POS - Bootstrap Admin Template" />
  <meta name="robots" content="noindex, nofollow" />
  <title>New Inventory Count</title>

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
            <h4>Nouvel inventaire</h4>
            <h6>Comptage physique et régularisation du stock</h6>
          </div>
        </div>

        <?php if ($step === "start"): ?>
        <div class="card">
          <div class="card-body">
            <p>Démarrer une nouvelle session d'inventaire physique. Vous pourrez ensuite saisir la quantité réellement comptée pour chaque produit ; les écarts avec le stock théorique seront calculés et le stock sera automatiquement régularisé à la validation.</p>
            <form method="post" action="addinventory.php">
              <div class="row">
                <div class="col-lg-6 col-sm-12 col-12">
                  <div class="form-group">
                    <label>Commentaire (optionnel)</label>
                    <input type="text" class="form-control" name="commentaire" placeholder="Ex : Inventaire trimestriel T3" />
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-submit" name="start_inventory">Démarrer l'inventaire</button>
              <a href="inventorylist.php" class="btn btn-cancel">Annuler</a>
            </form>
          </div>
        </div>
        <?php endif ?>

        <?php if ($step === "count"): ?>
        <div class="alert alert-info">Session d'inventaire n°<?= $id_inventaire ?> ouverte. Saisissez la quantité comptée pour chaque produit, puis validez pour régulariser le stock.</div>
        <div class="card">
          <div class="card-body">
            <form method="post" action="addinventory.php">
              <input type="hidden" name="id_inventaire" value="<?= $id_inventaire ?>" />
              <div class="table-responsive">
                <table class="table datanew">
                  <thead>
                    <tr>
                      <th>Produit</th>
                      <th>Référence</th>
                      <th>Stock théorique</th>
                      <th>Quantité comptée</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($products as $pr): ?>
                    <tr>
                      <td class="productimgname">
                        <a class="product-img"><img src="<?= $pr['pr_image'] ?>" alt="product" /></a>
                        <a href="javascript:void(0);"><?= $pr['lib_pr'] ?></a>
                      </td>
                      <td><?= $pr['num_pr'] ?></td>
                      <td>
                        <?= $pr['qte_stock'] ?>
                        <input type="hidden" name="qte_theorique[<?= $pr['num_pr'] ?>]" value="<?= $pr['qte_stock'] ?>" />
                      </td>
                      <td style="max-width:140px;">
                        <input type="number" class="form-control" name="qte_comptee[<?= $pr['num_pr'] ?>]" placeholder="Non compté" />
                      </td>
                    </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
              <button type="submit" class="btn btn-submit me-2" name="validate_inventory">Valider l'inventaire</button>
              <a href="inventorylist.php" class="btn btn-cancel">Annuler</a>
            </form>
          </div>
        </div>
        <?php endif ?>

        <?php if ($step === "done"): ?>
        <div class="card">
          <div class="card-body text-center">
            <h4 class="mb-3">Inventaire n°<?= $id_inventaire ?> validé ✅</h4>
            <p>Les écarts constatés ont été enregistrés et le stock a été régularisé automatiquement, avec un mouvement d'ajustement tracé pour chaque produit concerné.</p>
            <a href="inventory-details.php?id_inventaire=<?= $id_inventaire ?>" class="btn btn-submit me-2">Voir le détail des écarts</a>
            <a href="inventorylist.php" class="btn btn-cancel">Retour à la liste</a>
          </div>
        </div>
        <?php endif ?>

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
