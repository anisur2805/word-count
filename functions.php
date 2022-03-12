<?php

use function Clue\StreamFilter\fun;

function wc_get_data($args = []) {
      global $wpdb;

      $defaults = [
            'number' => 20,
            'offset' => 0,
            'orderby' => 'title',
            'order' => 'ASC',
      ];

      $args = wp_parse_args($args, $defaults);

      $sql = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}table_name
            ORDER BY {$args['orderby']} {$args['order']}
            LIMIT %s %s",
            $args['offset'],
            $args['number']
      );

      $items = $wpdb->get_results($sql);

      return $items;
}


global $wpdb;
$dbname = $wpdb->prefix . 'dbname';
$query = "SELECT title, description, year, rating, director FROM $dbname";

if (!empty($search)) {
      $query .= " AND (title LIKE '%{$search}%' OR (name LIKE '%{$search}%' OR (description LIKE '%{$search}%' OR (year LIKE '%{$search}%' OR  (rating LIKE '%{$search}%' OR  (director LIKE '%{$search}%')";
}
