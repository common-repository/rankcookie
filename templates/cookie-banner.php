<?php 
    /** 
     * The cookie banner template
     */

    $options = get_option('rankcookie_options'); 

    // Basic settings 
    $cookie_banner = array(
      'title' => __('Privacy settings', 'rankcookie'),  
      'default_text' => __('We use cookies on our website. Some of them are essential, while others help us to improve this website and your experience.', 'rankcookie'),
      'individual_settings_text' => __('Here you will find an overview of all cookies used. You can give your consent to all or only certain cookies.', 'rankcookie'),
      'label_accept_all' => __('Accept all', 'rankcookie'), 
      'label_custom_settings' => __('Individual settings', 'rankcookie'),
      'label_save_settings' => __('Save', 'rankcookie'),
      'label_close' => __('Hide this message', 'rankcookie'), 
      'label_back' => __('Back', 'rankcookie'), 
      'label_privacy' => __('Privacy', 'rankcookie'),
      'href_privacy' => $options['rankcookie_field_slug_privacy'] ?: __('/privacy', 'rankcookie'),
      'label_imprint' => __('Imprint', 'rankcookie'),
      'href_imprint' => $options['rankcookie_field_slug_imprint'] ?: __('/imprint', 'rankcookie')
    );

    // Default permissions 
    $default_permissions = array(
      array(
        'id' => 'statistics',
        'name' => __('Statistics', 'rankcookie'), 
        'description' => __('Statistics cookies collect information anonymously. This information helps us to understand how our visitors use our website.', 'rankcookie')
      ),
      array(
        'id' => 'essential',
        'name' => __('Essential', 'rankcookie'),
        'description' => __('Essential cookies enable basic functions and are necessary for the website to function properly.', 'rankcookie')
      )
    );

    // Add custom permission as specified by the user within the dashboard
    if ($options['rankcookie_field_custom_permission_name']) {
      $custom_permission = array(
        'id' => 'custom',
        'name' => $options['rankcookie_field_custom_permission_name'],
        'description' => $options['rankcookie_field_custom_permission_description'] ?: ''
      ); 
      $cookie_permissions = array(
        $default_permissions[0],
        $custom_permission,
        $default_permissions[1]
      );
    } else {
      $cookie_permissions = $default_permissions; // Or go with the default permissions only
    }
?>
<?php if ( rtrim($_SERVER['REQUEST_URI'],'/' ) !== $cookie_banner['href_privacy'] && rtrim($_SERVER['REQUEST_URI'],'/' ) !== $cookie_banner['href_imprint']) : # Don't show banner on privacy page and imprint ?> 
  <div class="rankcookie-overlay">
    <div class="rankcookie">
        <div class="rankcookie__title"><?php echo esc_html($cookie_banner['title']); ?></div>
        <p class="rankcookie__text"><?php echo esc_html($cookie_banner['default_text']); ?></p>
        <div class="rankcookie__custom-settings">
          <p class="rankcookie__text"><?php echo esc_html($cookie_banner['individual_settings_text']); ?></p>
          <?php foreach($cookie_permissions as $p) : ?> 
            <input type="checkbox" id="<?php echo esc_attr($p['id']); ?>" name="<?php echo esc_attr($p['id']); ?>" value="<?php echo esc_attr($p['id']); ?>" <?php if ($p['id'] === 'essential') { echo esc_attr('checked=checked'); } ?>><label class="rankcookie__label" for="<?php echo esc_attr($p['id']); ?>"><?php echo esc_attr($p['name']); ?></label>
          <p class="rankcookie__text"><?php echo esc_html($p['description']); ?></p>
          <?php endforeach; ?>
        </div>
        <div class="rankcookie__btns">
          <button class="rankcookie__btn rankcookie__btn--accept-all"><?php echo esc_html($cookie_banner['label_accept_all']); ?></button>
          <button class="rankcookie__btn rankcookie__btn--custom-settings"><?php echo esc_html($cookie_banner['label_custom_settings']); ?></button>
          <button class="rankcookie__btn rankcookie__btn--save-settings"><?php echo esc_html($cookie_banner['label_save_settings']); ?></button>
          <a class="rankcookie__link rankcookie__link--back"><?php echo esc_html($cookie_banner['label_back']); ?></a>
        </div>
        <div class="rankcookie__links">
          <a class="rankcookie__link rankcookie__link--close-banner"><?php echo esc_html($cookie_banner['label_close']); ?></a> |
          <a class="rankcookie__link" href="<?php echo esc_attr($cookie_banner['href_imprint']); ?>/"><?php echo esc_html($cookie_banner['label_imprint']); ?></a> |
          <a class="rankcookie__link" href="<?php echo esc_attr($cookie_banner['href_privacy']); ?>/"><?php echo esc_html($cookie_banner['label_privacy']); ?></a>
        </div>
    </div>
  </div>
<?php endif; ?>