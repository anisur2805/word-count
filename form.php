<h1>Options Demo Admin Page </h1>
<form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
      <?php 
      $latitude = get_option('optionsdemo_latitude');
      
      wp_nonce_field('optionsdemo'); ?>
      <label for="optionsdemo_latitude"><?php _e('Latitude', 'options-demo'); ?></label>
      <input type="text" name="optionsdemo_latitude" id="optionsdemo_latitude" value="<?php echo esc_attr( $latitude );?>" />
      <input type="hidden" name="action" value="optionsdemo_admin_page" />
      <?php submit_button('Save'); ?>
</form>