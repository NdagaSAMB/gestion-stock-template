<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php if (isset($_SESSION['admin'])): ?>
<?php
  require_once(__DIR__ . "/php/Class/Product.php");
  require_once(__DIR__ . "/php/Class/Categorie.php");
  require_once(__DIR__ . "/php/Class/Marque.php");
  require_once(__DIR__ . "/php/Class/Stock.php");

  if (isset($_POST['edit'])) {
    if (isset($_POST['edit'])) {
      // print_r($_POST);
      extract($_POST);
      $pr = Product::displayPr($old_num_pr); // bach ikoun num_pr l9dim 
      $ancienne_qte = (int) $pr['qte_stock'];
      $seuil_min = $seuil_min !== '' ? (int) $seuil_min : 5;
      $seuil_max = $seuil_max !== '' ? (int) $seuil_max : 100;
      $unite_mesure = $unite_mesure !== '' ? $unite_mesure : 'pièce';
      $code_barre = $code_barre !== '' ? $code_barre : null;
      $date_peremption = $date_peremption !== '' ? $date_peremption : null;
      // kanchof wach khona ma chnageach image ila oui kanakhod path l9dim dialha o kansefto f modifiermarque 
      // sinon kansupprimer l9dima mn ne3d ka n uploadi jdida o kanghewet 3la modifier marque 
      if ($_FILES["image"]["name"] === "") {
        Product::editPr($pr['num_pr'], $id_cat, $id_marque, $lib_pr, $desc_pr, $prix_uni, $prix_achat, $qte_stock, $old_image, $new_num_pr, $seuil_min, $seuil_max, $code_barre, $unite_mesure, $date_peremption);
      } else {
        $filename = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $image = "./image/product/" . $filename;

        // var_dump($image);
        // echo "<pre>";
        // var_dump($tempname);

        if (move_uploaded_file($tempname, $image)) {
          if (unlink($old_image)) {
            Product::editPr($pr['num_pr'], $id_cat, $id_marque, $lib_pr, $desc_pr, $prix_uni, $prix_achat, $qte_stock, $image, $new_num_pr, $seuil_min, $seuil_max, $code_barre, $unite_mesure, $date_peremption);
          } else {
            exit("<h3> Failed to delete image!</h3>");
          }
        } else {
          exit("<h3> Failed to upload image!</h3>");
        }
      }
      // Traçabilité : toute modification manuelle de la quantité en stock via ce formulaire
      // est enregistrée comme un mouvement d'ajustement, afin de garder un historique complet.
      $ecart_manuel = (int) $qte_stock - $ancienne_qte;
      if ($ecart_manuel !== 0) {
        Stock::addMouvement($new_num_pr, Stock::AJUSTEMENT_MANUEL, $ecart_manuel, $new_num_pr, "Modification manuelle depuis la fiche produit (" . ($ecart_manuel > 0 ? "+{$ecart_manuel}" : $ecart_manuel) . ")");
      }
      $cats = Categorie::afficher("categorie");
      $brands = Marque::afficher("marque");
      $pr = Product::displayPr($new_num_pr);
    }
  }
  if (isset($_GET['num_pr'])) {
    extract($_GET);
    $cats = Categorie::afficher("categorie");
    $brands = Marque::afficher("marque");
    $pr = Product::displayPr($num_pr);
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
  <title>Edit Product</title>

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
            <h4>Edit Product</h4>
            <h6>Update Your Product</h6>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <form class="row" method="post" action="editproduct.php" enctype="multipart/form-data">
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Reference</label>
                  <input type="text" name="new_num_pr" value="<?= $pr['num_pr']; ?>" />
                  <input type="hidden" name="old_num_pr" value="<?= $pr['num_pr']; ?>" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Product Name</label>
                  <input type="text" name="lib_pr" value="<?= $pr['lib_pr']; ?>" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Category</label>
                  <select class="select" name="id_cat">
                    <option value="<?= $pr['id_cat']; ?>">
                      <?= $pr['lib_cat']; ?>
                    </option>
                    <?php foreach ($cats as $item): ?>
                    <option value="<?= $item['id_cat']; ?>">
                      <?= $item['lib_cat']; ?>
                    </option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Brand</label>
                  <select class="select" name="id_marque">
                    <option value="<?= $pr['id_marque']; ?>">
                      <?= $pr['nom_marque']; ?>
                    </option>
                    <?php foreach ($brands as $item): ?>
                    <option value="<?= $item['id_marque']; ?>">
                      <?= $item['nom_marque']; ?>
                    </option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Quantity</label>
                  <input type="text" name="qte_stock" value="<?= $pr['qte_stock']; ?>" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>purchase price</label>
                  <input type="text" name="prix_achat" value="<?= $pr['prix_achat']; ?>" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>unit price</label>
                  <input type="text" name="prix_uni" value="<?= $pr['prix_uni']; ?>" />
                </div>
              </div>

              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>description</label>
                  <input type="text" name="desc_pr" value="<?= $pr['desc_pr']; ?>" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Seuil de stock minimum</label>
                  <input type="text" name="seuil_min" value="<?= $pr['seuil_min'] ?? 5; ?>" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Seuil de stock maximum</label>
                  <input type="text" name="seuil_max" value="<?= $pr['seuil_max'] ?? 100; ?>" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Code-barres / QR code</label>
                  <input type="text" name="code_barre" value="<?= $pr['code_barre'] ?? ''; ?>" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Unité de mesure</label>
                  <input type="text" name="unite_mesure" value="<?= $pr['unite_mesure'] ?? 'pièce'; ?>" />
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="form-group">
                  <label>Date de péremption</label>
                  <input type="date" name="date_peremption" value="<?= $pr['date_peremption'] ?? ''; ?>" />
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group">
                  <label> Product Image</label>
                  <div class="image-upload">
                    <input type="file" name="image" />
                    <input type="hidden" name="old_image" value="<?= $pr['pr_image']; ?>" />
                    <div class="image-uploads">
                      <img src="assets/img/icons/upload.svg" alt="img" />
                      <h4>Drag and drop a file to upload</h4>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12">
                <button class="btn btn-submit me-2" name="edit">Update</button>
                <a href="productlist.php" class="btn btn-cancel">Cancel</a>
              </div>
            </form>
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

  <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
  <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

  <script src="assets/js/script.js"></script>
</body>

</html>
<?php else: ?>
<?php header("Location: signin.php"); ?>
<?php endif ?>