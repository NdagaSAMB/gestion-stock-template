<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<?php if (isset($_SESSION['admin'])): ?>
  <?php
  require_once(__DIR__ . "/php/Class/Inventaire.php");
  $active = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "active", 0, 0, 0);
  $inventaires = Inventaire::liste();
  ?>
  <!DOCTYPE html>
  <html lang="fr">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta name="description" content="POS - Modèle d'administration Bootstrap" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Liste des Inventaires</title>

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
              <h4>Inventaires</h4>
              <h6>Historique des sessions de comptage physique</h6>
            </div>
            <div class="page-btn">
              <a href="addinventory.php" class="btn btn-added">
                <img src="assets/img/icons/plus.svg" alt="img" class="me-1" />
                Nouvel inventaire
              </a>
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table datanew">
                  <thead>
                    <tr>
                      <th>N° Inventaire</th>
                      <th>Date</th>
                      <th>Statut</th>
                      <th>Produits comptés</th>
                      <th>Écarts constatés</th>
                      <th>Commentaire</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($inventaires as $inv): ?>
                      <tr>
                        <td>#<?= $inv['id_inventaire'] ?></td>
                        <td><?= $inv['date_inventaire'] ?></td>
                        <td>
                          <?php if ($inv['statut'] === 'valide'): ?>
                            <span class="badges bg-lightgreen">Validé</span>
                          <?php else: ?>
                            <span class="badges bg-lightyellow">En cours</span>
                          <?php endif ?>
                        </td>
                        <td><?= $inv['nb_lignes'] ?></td>
                        <td><?= $inv['nb_ecarts'] ?></td>
                        <td><?= $inv['commentaire'] ?></td>
                        <td>
                          <?php if ($inv['statut'] === 'valide'): ?>
                            <a href="inventory-details.php?id_inventaire=<?= $inv['id_inventaire'] ?>">
                              <img src="assets/img/icons/eye.svg" alt="img" />
                            </a>
                          <?php else: ?>
                            <a href="addinventory.php?id_inventaire=<?= $inv['id_inventaire'] ?>">Reprendre</a>
                          <?php endif ?>
                        </td>
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