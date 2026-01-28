<?php
/**
* Plugin Name: Pizza Builder
*/

//if (defined("ABSPATH")) {
  /* La constante ABSPATH (définie par Wordpress) est accessible, donc ce plugin est exécuté dans le bon contexte  */
//}

// Empêcher l'exécution de ce script par accès direct (on ne veut le voir utilisé qu'en "mode plugin")
if (!defined("ABSPATH")) { exit; }

add_action("plugins_loaded" , function() {

  define("PB_PATH" , plugin_dir_path(__FILE__));

  require_once(PB_PATH . "admin.php");
  require_once(PB_PATH . "ingredients_taxonomy.php");
  require_once(PB_PATH . "frontend.php" );
  require_once(PB_PATH . "cart.php" );

});

?>