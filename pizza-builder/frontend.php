<?php if (!defined("ABSPATH")) { exit; }

// FRONTEND : Affichage du builder sur les pages produit concernées

add_action("woocommerce_before_add_to_cart_button" , "pb_render_builder");


function pb_render_builder() {

    global $product; // En php, pour accéder à une variable globale depuis une fonction, on le précise via le mot-clé "global". Dans la logique Wordpress (Woocommerce plus précisément), une variable $product existe au niveau global et contient toutes les infos concernant le produit que l'on est en train de consulter ou éditer.

    // Contrôler si le builder est bien censé s'afficher pour le produit actuellement visité
    // On avait stocké l'aspect coché / non coché dans une meta_data du produit ("yes" / "no")
    // C'est cette meta_data qu'on va tester :

    $enabled = $product -> get_meta(PB_META_KEY);

    if ($enabled == "yes") {
        
        $ingredients = get_the_terms($product -> get_id() , PB_TAX);
        // on obtient tous les termes (tous les ingrédients), rattachés au produit consulté, faisant partie de la taxonomie "ingrédients" (PB_TAX est la constante dans laquelle on avait sauvegardé l'identifiant donné à cette taxonomie)

        //var_dump($ingredients);

        echo "<ul>";

        foreach($ingredients as $ing) {

            $price = get_term_meta($ing -> term_id , PB_TERM_META_PRICE , true);

            $price_exp = "";

            if ($price > 0) {

                $price /= 100;

                $price = number_format($price , 2 , "," , " "); // Formattage : 2 nombres après la virgule, vraie virgule à la place du point, pas de ponctuation supplémentaire (pour la séparation des milliers etc)

                $price_exp = " (" . $price . "€)";

            }

            

            // Prix : à formatter un minimum -> convertir en Euros, écriture élégante (2 digits après virgule)

                echo "<li>" . $ing -> name . $price_exp . "</li>";
        }

        echo "</ul>";
    }
}
