<?php
require_once 'class.widgets.php';

function wordcount_register_widgets() {
      register_widget('DemoWidget');
}
add_action('widgets_init', 'wordcount_register_widgets');