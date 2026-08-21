<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<?php if (isset($_SESSION['admin'])): ?>
  <?php
  require_once(__DIR__ . "/php/Class/Purchase.php");
  require_once(__DIR__ . "/php/Class/Product.php");
  require_once(__DIR__ . "/php/Class/RetourFournisseur.php");
  $active = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "active", 0, 0);

  $success = false;
  if (isset($_POST['add'])) {
    extract($_POST);
    RetourFournisseur::add($num_app, $num_pr, (int) $qte, $motif);
    $success = true;
  }

  $purchases = Purchase::displayAllPur();
  $products = Product::afficher("produit");
  ?>
  <!DOCTYPE html>
  <html lang="fr">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta name="description" content="POS - Modèle d'administration Bootstrap" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Retour fournisseur</title>

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
              <h4>Retour fournisseur</h4>
              <h6>Déclarer un retour de marchandise (produit défectueux / non conforme)</h6>
            </div>
          </div>

          <?php if ($success): ?>
            <div class="alert alert-success">Retour fournisseur enregistré et stock mis à jour avec succès.</div>
          <?php endif ?>

          <div class="card">
            <form class="card-body" method="post" action="addsupplierreturn.php">
              <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                    <label>Achat concerné</label>
                    <select class="select" name="num_app">
                      <option value="">Sélectionner (optionnel)</option>
                      <?php foreach ($purchases as $p): ?>
                        <option value="<?= $p['num_app'] ?>">
                          N°<?= $p['num_app'] ?> — <?= $p['nom'] . ' ' . $p['prenom'] ?> (<?= $p['date_app'] ?>)
                        </option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                    <label>Produit</label>
                    <select class="select" name="num_pr" required>
                      <option value="">Choisir un produit</option>
                      <?php foreach ($products as $pr): ?>
                        <option value="<?= $pr['num_pr'] ?>"><?= $pr['lib_pr'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                    <label>Quantité retournée</label>
                    <input type="number" min="1" name="qte" required />
                  </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                    <label>Motif</label>
                    <input type="text" name="motif" placeholder="Défectueux, non conforme..." />
                  </div>
                </div>
              </div>
              <button class="btn btn-submit me-2" type="submit" name="add">Enregistrer le retour</button>
              <a href="supplierreturnlist.php" class="btn btn-cancel">Voir la liste</a>
            </form>
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