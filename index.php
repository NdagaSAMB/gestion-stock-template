<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirection immédiate si non authentifié
if (!isset($_SESSION['admin'])) {
    header("Location: signin.php");
    exit();
}

require_once(__DIR__ . "/php/Class/Client.php");
require_once(__DIR__ . "/php/Class/Supplier.php");
require_once(__DIR__ . "/php/Class/Purchase.php");
require_once(__DIR__ . "/php/Class/Sale.php");
require_once(__DIR__ . "/php/Class/Product.php");
require_once(__DIR__ . "/php/Class/Stock.php");

$active = array("active", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

$clients = Client::nbrDesTuples("client");
$suppliers = Supplier::nbrDesTuples("fournisseur");
$purchases = Purchase::TotalLigne("approvisionnement");
$sales = Sale::TotalLigne("commande");
$products = Product::afficher() ?? [];

$low_stock_products = Stock::produitsSousSeuil() ?? [];
$expiring_products = Stock::produitsProchesPeremption(30) ?? [];
$stock_value = Stock::valeurStock();
$all_sales = Sale::topSales() ?? [];
$all_purchases = Purchase::displayAllPur() ?? [];

// Calculs sécurisés
$total_all_sales = 0;
foreach ($all_sales as $item) {
    $total_all_sales += $item['total'] ?? 0;
}

$total_all_pur = 0;
foreach ($all_purchases as $value) {
    $total_all_pur += $value['total'] ?? $value['montant'] ?? 0;
}

$total_all_pr = 0;
foreach ($products as $value) {
    $total_all_pr += $value['qte_stock'] ?? 0;
}

// Récupération sécurisée des 4 derniers produits et 4 meilleures ventes
$top_sales = array_slice($all_sales, 0, 4);
$recent_products = array_slice(array_reverse($products), 0, 4);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Modèle d'administration Bootstrap">
    <meta name="robots" content="noindex, nofollow">
    <title>GStock</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">

        <?php require_once("header.php"); ?>
        <?php require_once("sidebar.php"); ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget">
                            <div class="dash-widgetimg">
                                <span><img src="assets/img/icons/dash1.svg" alt="image"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5><span class="counters" data-count="<?= $total_all_pur ?>"><?= number_format($total_all_pur, 2, ',', ' ') ?> DH</span></h5>
                                <h6>Total Achats (DH)</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash1">
                            <div class="dash-widgetimg">
                                <span><img src="assets/img/icons/dash2.svg" alt="image"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5><span class="counters" data-count="<?= $total_all_sales ?>"><?= number_format($total_all_sales, 2, ',', ' ') ?> DH</span></h5>
                                <h6>Total Ventes (DH)</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash2">
                            <div class="dash-widgetimg">
                                <span><img src="assets/img/icons/dash3.svg" alt="image"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5><span class="counters" data-count="<?= $total_all_sales - $total_all_pur ?>"><?= number_format($total_all_sales - $total_all_pur, 2, ',', ' ') ?> DH</span></h5>
                                <h6>Bénéfice Total (DH)</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widget dash3">
                            <div class="dash-widgetimg">
                                <span><img src="assets/img/icons/dash4.svg" alt="image"></span>
                            </div>
                            <div class="dash-widgetcontent">
                                <h5><span class="counters" data-count="<?= $total_all_pr ?>"><?= $total_all_pr ?></span></h5>
                                <h6>Total Produits (Unités)</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques rapides -->
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count">
                            <div class="dash-counts">
                                <h4><?= number_format((float) ($stock_value['valeur_achat'] ?? 0), 0, ',', ' ') ?> DH</h4>
                                <h5>Valeur du stock (achat)</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="archive"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das1">
                            <div class="dash-counts">
                                <h4 style="<?= count($low_stock_products) > 0 ? 'color:#C0392B;' : '' ?>"><?= count($low_stock_products) ?></h4>
                                <h5>Produits en alerte de stock</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="alert-triangle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das2">
                            <div class="dash-counts">
                                <h4 style="<?= count($expiring_products) > 0 ? 'color:#C0392B;' : '' ?>"><?= count($expiring_products) ?></h4>
                                <h5>Produits proches de péremption</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count">
                            <div class="dash-counts">
                                <h4><?= $clients ?></h4>
                                <h5>Clients</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das1">
                            <div class="dash-counts">
                                <h4><?= $suppliers ?></h4>
                                <h5>Fournisseurs</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="user-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das2">
                            <div class="dash-counts">
                                <h4><?= $purchases ?></h4>
                                <h5>Factures d'achat</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="file-text"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 d-flex">
                        <div class="dash-count das3">
                            <div class="dash-counts">
                                <h4><?= $sales ?></h4>
                                <h5>Factures de vente</h5>
                            </div>
                            <div class="dash-imgs">
                                <i data-feather="file"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Meilleures Ventes -->
                    <div class="col-lg-7 col-sm-12 col-12 d-flex">
                        <div class="card flex-fill">
                            <h4 class="card-title mb-0" style="padding:15px;">Meilleures Ventes</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Réf. Vente</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Total Général (DH)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_sales as $sale): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($sale['num_com'] ?? '') ?></td>
                                            <td class="productimgname">
                                                <a href="javascript:void(0);" class="product-img">
                                                    <img src="<?= htmlspecialchars($sale['image'] ?? 'assets/img/default.png') ?>" alt="produit" />
                                                </a>
                                                <a href="javascript:void(0);"><?= htmlspecialchars(($sale['nom'] ?? '') . ' ' . ($sale['prenom'] ?? '')) ?></a>
                                            </td>
                                            <td><?= htmlspecialchars($sale['date_com'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($sale['total'] ?? '0') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Produits Récents -->
                    <div class="col-lg-5 col-sm-12 col-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Produits ajoutés récemment</h4>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class="dropset">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li><a href="productlist.php" class="dropdown-item">Liste des produits</a></li>
                                        <li><a href="addproduct.php" class="dropdown-item">Ajouter un produit</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive dataview">
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Produits</th>
                                                <th>Prix</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sno = 0;
                                            foreach ($recent_products as $pr): $sno++; ?>
                                                <tr>
                                                    <td><?= $sno; ?></td>
                                                    <td class="productimgname">
                                                        <a href="productlist.php" class="product-img">
                                                            <img src="<?= htmlspecialchars($pr['pr_image'] ?? 'assets/img/default.png') ?>" alt="produit">
                                                        </a>
                                                        <a href="productlist.php"><?= htmlspecialchars($pr['lib_pr'] ?? '') ?></a>
                                                    </td>
                                                    <td><?= htmlspecialchars($pr['prix_uni'] ?? '0') ?> DH</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Table Alertes Stock -->
                    <div class="col-lg-6 col-sm-12 col-12 d-flex">
                        <div class="card flex-fill mb-0">
                            <div class="card-body">
                                <h4 class="card-title">Alertes de stock (sous le seuil minimum)</h4>
                                <div class="table-responsive dataview">
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Produit</th>
                                                <th>Seuil min.</th>
                                                <th>Stock actuel</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sno = 0;
                                            foreach (array_slice($low_stock_products, 0, 8) as $pr): $sno++; ?>
                                                <tr>
                                                    <td><?= $sno; ?></td>
                                                    <td class="productimgname">
                                                        <a class="product-img" href="productlist.php">
                                                            <img src="<?= htmlspecialchars($pr['pr_image'] ?? 'assets/img/default.png') ?>" alt="produit">
                                                        </a>
                                                        <a href="productlist.php"><?= htmlspecialchars($pr['lib_pr'] ?? '') ?></a>
                                                    </td>
                                                    <td><?= htmlspecialchars($pr['seuil_min'] ?? '0') ?></td>
                                                    <td style="color:#C0392B; font-weight:600;"><?= htmlspecialchars($pr['qte_stock'] ?? '0') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Péremption -->
                    <div class="col-lg-6 col-sm-12 col-12 d-flex">
                        <div class="card flex-fill mb-0">
                            <div class="card-body">
                                <h4 class="card-title">Produits proches de la péremption (30 jours)</h4>
                                <div class="table-responsive dataview">
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>N°</th>
                                                <th>Produit</th>
                                                <th>Date péremption</th>
                                                <th>Jours restants</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sno = 0;
                                            foreach (array_slice($expiring_products, 0, 8) as $pr): $sno++; ?>
                                                <tr>
                                                    <td><?= $sno; ?></td>
                                                    <td class="productimgname">
                                                        <a class="product-img" href="productlist.php">
                                                            <img src="<?= htmlspecialchars($pr['pr_image'] ?? 'assets/img/default.png') ?>" alt="produit">
                                                        </a>
                                                        <a href="productlist.php"><?= htmlspecialchars($pr['lib_pr'] ?? '') ?></a>
                                                    </td>
                                                    <td><?= htmlspecialchars($pr['date_peremption'] ?? '') ?></td>
                                                    <td style="<?= ($pr['jours_restants'] ?? 0) < 0 ? 'color:#C0392B; font-weight:600;' : '' ?>">
                                                        <?= ($pr['jours_restants'] ?? 0) < 0 ? 'Périmé' : htmlspecialchars($pr['jours_restants']) . ' j' ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
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
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>