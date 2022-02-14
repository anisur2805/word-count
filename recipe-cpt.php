<?php
// Register Custom Post Type
function recipe_cpt() {

      $labels = array(
            'name'                  => _x('Recipes', 'Post Type General Name', 'recipe_cpt'),
            'singular_name'         => _x('Recipe', 'Post Type Singular Name', 'recipe_cpt'),
            'all_items'             => __('All Items', 'recipe_cpt'),
            'add_new_item'          => __('Add New Item', 'recipe_cpt'),
            'add_new'               => __('Add New', 'recipe_cpt'),
      );
      $args = array(
            'label'                 => __('Recipe', 'recipe_cpt'),
            'description'           => __('Post Type Description', 'recipe_cpt'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor'),
            'taxonomies'            => array('category', 'post_tag'),
            'hierarchical'          => false,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'capability_type'       => 'page',
      );
      register_post_type('recipe', $args);
}
add_action('init', 'recipe_cpt', 0);

function recipe_single_template($file) {
      global $post;
      if ('recipe' == $post->post_type) {
            $file_path = plugin_dir_path(__FILE__) . 'single-recipe.php';
            $file = $file_path;
      }

      return $file;
}
add_filter('single_template', 'recipe_single_template');
