<?php if (!defined("ABSPATH")) { exit; }

// CART : Logique panier - transmission et affichage des ingrédients sélectionnés dans la "ligne panier"

// 1) Validation conformité de la commande lors du clic sur "Ajouter au panier"

add_filter("woocommerce_add_to_cart_validation", "pb_validate_add_to_cart", 10, 4);

function pb_validate_add_to_cart($passed , $product_id , $qty , $variation_id = 0) {

    // Vérification de la cohérence produit concerné <-> ingrédients cochés (sont-ils activés pour ce produit ?)
    $allowed_ids = pb_get_allowed_ingredients_ids($product_id);
    if (!$allowed_ids) {
        return $passed;
    }

    $selected_ids = pb_get_selected_ingredients_ids();

    // Exemple de règle : max 3 ingrédients
    if (count($selected_ids) > 3) {
        wc_add_notice("Maximum 3 ingrédients !!!!!!!!!", "error");
        return false; // le produit ne "passera pas" ce filtre
    }

    // Vérification : chaque ingrédient coché est-il autorisé pour ce produit ?
    foreach($selected_ids as $ing_id) {
        if (!in_array($ing_id, $allowed_ids, true)) { // Si l'ingrédient ne fait pas partie des ingrédients autorisés
            wc_add_notice("Ingrédient invalide pour cette pizza !!!!!!!!", "error");
            return false;
        }
    }

    return $passed;
}

function pb_get_allowed_ingredients_ids($product_id) {
    $ingredients = get_the_terms($product_id , PB_TAX);

    if (empty($ingredients) || is_wp_error($ingredients)) {
        return [];
    }

    // Array_map : permet d'obtenir, d'après $ingredients (tableau), un nouveau tableau. Celui-ci se construire en reprenant tour à tour chacun des éléments de $ingredients, passés par une fonction de transformation. Dans notre cas, la transformation consiste à réduire chaque ingrédient (objet "complexe" possédant id, name, prix, etc) à son id.
    return array_map( function($ing) { return $ing -> term_id; } , $ingredients );
}

function pb_get_selected_ingredients_ids() {
    
    if (isset($_POST["pb_ing"])) {
        $selected = $_POST["pb_ing"]; // Voir si nécessaire ? forcer sous forme de tableau
    } else {
        $selected = [];
    }
    
    $selected = array_map("intval", $selected); // Forcer chaque élément de $selected à bien être un nombre entier (éviter confusions du style 10 / "10")
    
    $selected = array_filter($selected , function($ing) { return ($ing > 0); } );

    sort($selected);

    return $selected;

}

// LA SUITE : Stocker les infos dans la ligne panier (hook "woocommerce_add_cart_item_data")