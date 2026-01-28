<?php if (!defined("ABSPATH")) { exit; }

/* TAXONOMIE ingredient */

define('PB_TAX', 'pizza_ingredient');


add_action('init', 'pb_register_ingredient_taxonomy');

function pb_register_ingredient_taxonomy() {

  register_taxonomy(PB_TAX, ['product'], [
    'labels'       => [
        'name'          => 'Ingrédients',
        'singular_name' => 'Ingrédient'
    ],
    'public'       => false,
    'show_ui'      => true,
    'show_in_quick_edit' => true,
    'show_in_menu' => true,
    'show_admin_column' => false,
    'hierarchical' => false,
    'rewrite'      => false,
    'meta_box_cb'  => 'post_tags_meta_box',
    'show_in_rest' => true
  ]);
}

/* Champ “prix” dans l’admin des ingrédients */

define('PB_TERM_META_PRICE', 'pb_price_cents'); // prix en centimes

add_action(PB_TAX . '_add_form_fields', 'pb_term_add_price_field');
add_action(PB_TAX . '_edit_form_fields', 'pb_term_edit_price_field');
add_action('created_' . PB_TAX, 'pb_term_save_price_field');
add_action('edited_' . PB_TAX, 'pb_term_save_price_field');

function pb_term_add_price_field() {
  ?>
  <div class="form-field">
    <label for="pb_price_cents">Prix supplément (centimes)</label>
    <input type="number" name="pb_price_cents" id="pb_price_cents" min="0" step="1" value="0">
    <p class="description">Ex : 150 = 1,50€</p>
  </div>
  <?php
}

function pb_term_edit_price_field($term) {
  $value = (int) get_term_meta($term->term_id, PB_TERM_META_PRICE, true);
  ?>
  <tr class="form-field">
    <th scope="row"><label for="pb_price_cents">Prix supplément (centimes)</label></th>
    <td>
      <input type="number" name="pb_price_cents" id="pb_price_cents" min="0" step="1" value="<?php echo esc_attr($value); ?>">
      <p class="description">Ex : 150 = 1,50€</p>
    </td>
  </tr>
  <?php
}

function pb_term_save_price_field($term_id) {
  if (!isset($_POST['pb_price_cents'])) return;
  $price = max(0, (int) $_POST['pb_price_cents']);
  update_term_meta($term_id, PB_TERM_META_PRICE, $price);
}

/* TODO :
- Groupes d'ingrédients ? (Fromages, Protéines, Légumes, etc.) voir brief
- Pâtes et base : à intégrer au plugin
*/

?>