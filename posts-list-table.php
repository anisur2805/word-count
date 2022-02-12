<?php
add_filter('manage_posts_columns', 'word_count_set_custom_edit_book_columns');
add_action('manage_posts_custom_column', 'word_count_custom_book_column', 10, 2);

function word_count_set_custom_edit_book_columns($columns) {
      unset($columns['author']);
      $columns['wordcount'] = __('Word Count', 'word-count');
      $columns['word_count_thumbnail'] = __('Thumbnail', 'word-count');

      return $columns;
}

function word_count_custom_book_column($column, $post_id) {
      if ('wordcount' == $column) {
            // $_post = get_post( $post_id );
            // $content = $_post->post_content;
            // $wordn = str_word_count( strip_tags( $content ) );

            $wordn = get_post_meta($post_id, 'wordn', true);
            echo $wordn;
      } else if ('word_count_thumbnail' == $column) {
            echo get_the_post_thumbnail($post_id, 'thumbnail');
      }
}

function word_count_manage_edit_post_sortable_columns() {
      $columns['wordcount'] = 'wordn';
      return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'word_count_manage_edit_post_sortable_columns');

/**
 * This function will run only once
 *
 * @return void
 */
// function word_count_set_word_count() {
//       $posts = get_posts( array(
//             'posts_per_page' => -1,
//             'post_type' => 'post',
//             'post_status' => 'any',
//       ));

//       foreach( $posts as $p ) {
//             $content = $p->post_content;
//             $wordn = str_word_count( strip_tags( $content ) );
//             update_post_meta( $p->ID, 'wordn', $wordn );
//       }
// }
// add_action('init', 'word_count_set_word_count');

/**
 * Sort the posts by the number of post contents 
 *
 * @param [type] $wpquery
 * @return void
 */
function word_count_sort_column_data($wpquery) {
      if (!is_admin()) {
            return;
      }

      $orderby = $wpquery->get('orderby');
      if ('wordn' == $orderby) {
            $wpquery->set('meta_key', 'wordn');
            $wpquery->set('orderby', 'meta_value_num');
      }
}
add_action('pre_get_posts', 'word_count_sort_column_data');

function word_count_update_wordcount_on_post_save($post_id) {
      $p = get_post($post_id);
      $content = $p->post_content;
      $wordn = str_word_count(strip_tags($content));
      update_post_meta($p->ID, 'wordn', $wordn);
}
add_action('save_post', 'word_count_update_wordcount_on_post_save');

function word_count_filter_posts() {
      if (isset($_GET['post_type']) &&  $_GET['post_type'] != 'post') {
            return;
      }

      $filter_value = isset($_GET['wcfilter']) ? $_GET['wcfilter'] : '';
      $values = array(
            '0' => __('Select Status', 'word-count'),
            '1' => __('Some Posts', 'word-count'),
            '2' => __('Some Posts ++', 'word-count'),
      )
?>

      <select name="wcfilter">
            <?php
            foreach ($values as $key => $val) {
                  printf(
                        "<option value='%s' %s>%s</option>",
                        $key,
                        $key == $filter_value ? 'selected="selected"' : '',
                        $val
                  );
            }
            ?>
      </select>
<?php
}
add_action('restrict_manage_posts', 'word_count_filter_posts');

/**
 * Filter Query based on custom options
 *
 * @param [type] $wpquery
 * @return void
 */
function word_count_filter_data($wpquery) {
      if (!is_admin()) {
            return;
      }

      $filter_value = isset($_GET['wcfilter']) ? $_GET['wcfilter'] : '';
      if ('1' == $filter_value) {
            $wpquery->set('post__in', array(4100, 4174));
      } else if ('2' == $filter_value) {
            $wpquery->set('post__in', array(4173, 4100));
      }
}
add_action('pre_get_posts', 'word_count_filter_data');

/**
 * Filter Query based on post thumbnail
 */
function word_count_filter_thumbnail_posts() {
      if (isset($_GET['post_type']) &&  $_GET['post_type'] != 'post') {
            return;
      }

      $filter_value = isset($_GET['thumbnail_filter']) ? $_GET['thumbnail_filter'] : '';
      $values = array(
            '0' => __('Thumbnail Status', 'word-count'),
            '1' => __('Has Thumbnail', 'word-count'),
            '2' => __('No Thumbnail', 'word-count'),
      )
?>

      <select name="thumbnail_filter">
            <?php
            foreach ($values as $key => $val) {
                  printf(
                        "<option value='%s' %s>%s</option>",
                        $key,
                        $key == $filter_value ? 'selected="selected"' : '',
                        $val
                  );
            }
            ?>
      </select>
<?php
}
add_action('restrict_manage_posts', 'word_count_filter_thumbnail_posts');


function word_count_thumbnail_filter_data($wpquery) {
      if (!is_admin()) {
            return;
      }

      $filter_value = isset($_GET['thumbnail_filter']) ? $_GET['thumbnail_filter'] : '';
      if ('1' == $filter_value) {
            $wpquery->set('meta_query', array(
                  array(
                        'key' => '_thumbnail_id',
                        'compare' => 'EXISTS',
                  )
            ));
      } else if ('2' == $filter_value) {
            $wpquery->set('meta_query', array(
                  array(
                        'key' => '_thumbnail_id',
                        'compare' => 'NOT EXISTS',
                  )
            ));
      }
}
add_action('pre_get_posts', 'word_count_thumbnail_filter_data');


































/**
 * Filter Query based on word count
 */
function word_count_filter_word_length() {
      if (isset($_GET['post_type']) &&  $_GET['post_type'] != 'post') {
            return;
      }

      $filter_value = isset($_GET['word_length']) ? $_GET['word_length'] : '';
      $values = array(
            '0' => __('Word Count', 'word-count'),
            '1' => __('Above 400', 'word-count'),
            '2' => __('Between 200 to 400', 'word-count'),
            '3' => __('Below 200', 'word-count'),
      )
?>

      <select name="word_length">
            <?php
            foreach ($values as $key => $val) {
                  printf(
                        "<option value='%s' %s>%s</option>",
                        $key,
                        $key == $filter_value ? 'selected="selected"' : '',
                        $val
                  );
            }
            ?>
      </select>
<?php
}
add_action('restrict_manage_posts', 'word_count_filter_word_length');


function word_count_filter_data_word_length($wpquery) {
      if (!is_admin()) {
            return;
      }

      $filter_value = isset($_GET['word_length']) ? $_GET['word_length'] : '';
      if ('1' == $filter_value) {
            $wpquery->set('meta_query', array(
                  array(
                        'key' => 'wordn',
                        'value' => 400,
                        'compare' => '>=',
                        'type' => 'NUMERIC',
                  )
            ));
      } else if ('2' == $filter_value) {
            $wpquery->set('meta_query', array(
                  array(
                        'key' => 'wordn',
                        'value' => array(200,400),
                        'compare' => 'BETWEEN',
                        'type' => 'NUMERIC',
                  )
            ));
      } else if ('3' == $filter_value) {
            $wpquery->set('meta_query', array(
                  array(
                        'key' => 'wordn',
                        'value' => 200,
                        'compare' => '<=',
                        'type' => 'NUMERIC',
                  )
            ));
      }
}
add_action('pre_get_posts', 'word_count_filter_data_word_length');
