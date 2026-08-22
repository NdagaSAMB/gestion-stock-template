<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li>
                    <a href="index.php" class="<?= $active[0]; ?>"><img src="assets/img/icons/dashboard.svg"
                            alt="img"><span>
                            Tableau de bord</span> </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/quotation1.svg" alt="img"><span>
                            Catégorie</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="categorylist.php" class="<?= $active[3]; ?>">Liste des catégories</a></li>
                        <li><a href="addcategory.php" class="<?= $active[4]; ?>">Ajouter une catégorie</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/scanners.svg" alt="img"><span>
                            Marque</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="brandlist.php" class="<?= $active[1]; ?>">Liste des marques</a></li>
                        <li><a href="addbrand.php" class="<?= $active[2]; ?>">Ajouter une marque</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img"><span>
                            Produit</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="productlist.php" class="<?= $active[5]; ?>">Liste des produits</a></li>
                        <li><a href="addproduct.php" class="<?= $active[6]; ?>">Ajouter un produit</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span>
                            Client</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="customerlist.php" class="<?= $active[11]; ?>">Liste des clients</a></li>
                        <li><a href="addcustomer.php" class="<?= $active[12]; ?>">Ajouter un client</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/sales1.svg" alt="img"><span>
                            Ventes</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="salesreturnlists.php" class="<?= $active[7]; ?>">Liste des ventes</a></li>
                        <li><a href="createsalesreturns.php" class="<?= $active[8]; ?>">Nouvelle vente</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img"><span>
                            Fournisseur</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="supplierlist.php" class="<?= $active[13]; ?>">Liste des fournisseurs</a></li>
                        <li><a href="addsupplier.php" class="<?= $active[14]; ?>">Ajouter un fournisseur</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/purchase1.svg" alt="img"><span>
                            Achat</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="purchaselist.php" class="<?= $active[9]; ?>">Liste des achats</a></li>
                        <li><a href="addpurchase.php" class="<?= $active[10]; ?>">Ajouter un achat</a></li>
                        <li><a href="addsupplierreturn.php" class="<?= $active[19] ?? ''; ?>">Retour fournisseur</a></li>
                        <li><a href="supplierreturnlist.php" class="<?= $active[20] ?? ''; ?>">Liste des retours fournisseur</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/transcation.svg" alt="img"><span>
                            Stock</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="stockmovements.php" class="<?= $active[17] ?? ''; ?>">Mouvements de stock</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="assets/img/icons/scanner.svg" alt="img"><span>
                            Inventaire</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="inventorylist.php" class="<?= $active[18] ?? ''; ?>">Liste des inventaires</a></li>
                        <li><a href="addinventory.php" class="<?= $active[21] ?? ''; ?>">Nouvel inventaire</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="assets/img/icons/users1.svg" alt="img">
                        <span>
                            Admin
                        </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="newuser.php" class="<?= $active[15]; ?>">Nouvel administrateur</a></li>
                        <li><a href="userlists.php" class="<?= $active[16]; ?>">Liste des administrateurs</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>