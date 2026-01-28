<?php if (!defined("ABSPATH")) { exit; }

// ADMIN : Permettre l'activation ou non du constructeur pour un produit (côté back-office)

add_action("woocommerce_product_options_general_product_data" , "pb_admin_add_checkbox");

define("PB_META_KEY" , "pizza_builder_enabled");

function pb_admin_add_checkbox() {
  echo "<div class='options_group'>";
  woocommerce_wp_checkbox([
    'id' => PB_META_KEY,
    'label' => "Activer le pizza builder",
    'description' => "Affiche le module de pizza sur mesure sur la page du produit"
    ]);
  echo "</div>";
}

add_action("woocommerce_admin_process_product_object" , "pb_admin_save_checkbox"); // Particularité de ce hook : les fonctions qu'on y greffe reçoivent par défaut comme paramètre le produit concerné...

function pb_admin_save_checkbox($product) { // ... ce qui permet de "capturer" cet objet produit pour l'utiliser dans notre fonction

  // if (isset($_POST[PB_META_KEY])) {
  //   $enabled = "yes";
  // } else {
  //   $enabled = "no";
  // }

  // Le même test écrit de manière "ternaire" :

  $enabled = (isset($_POST[PB_META_KEY])) ? "yes" : "no"; 

  // "->" : équivalent php de (ce qui s'écrirait en JS) product.update_term_meta()
  // On utilise ici update_meta_data() comme une méthode de l'objet $product
  $product -> update_meta_data(PB_META_KEY , $enabled);
}