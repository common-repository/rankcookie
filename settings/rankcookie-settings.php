<?php
/**
 * Settings page for the rankcookie plugin, accessible via the dashboard
 */

// Custom option and settings
function rankcookie_settings_init() {
  
  // Register a new setting for the 'rankcookie' page
  register_setting('rankcookie', 'rankcookie_options');
  
  /**
   * Add sections
   */
  
  // Add section google tag manager
  add_settings_section('rankcookie_section_gtm', __('Google Tag Manager', 'rankcookie'), 'rankcookie_section_gtm_cb', 'rankcookie');

  // Add section custom permissons
  add_settings_section('rankcookie_section_custom_permission', __('Additional permissions', 'rankcookie'), 'rankcookie_section_custom_permission_cb', 'rankcookie');
  
  // Add section url slugs
  add_settings_section('rankcookie_section_slugs', __('Change URL slugs', 'rankcookie'), 'rankcookie_section_slugs_cb', 'rankcookie');

  // Add section cookie lifetime 
  add_settings_section('rankcookie_section_lifetime', __('Cookie lifetime', 'rankcookie'), 'rankcookie_section_lifetime_cb', 'rankcookie');

  /**
   * Add fields 
   */
  
  // Add field google tag manager id
  add_settings_field('rankcookie_field_gtm_id', __('Google Tag Manger ID', 'rankcookie'), 'rankcookie_field_default_cb_input', 'rankcookie', 'rankcookie_section_gtm', array(
    'label_for' => 'rankcookie_field_gtm_id',
    'class' => 'rankcookie-row',
    'rankcookie_custom_data' => array(
      'description' => __('Id of the form GTM-12334567', 'rankcookie')
    )
  ));

  // Add field custom permission name
  add_settings_field('rankcookie_field_custom_permission_name', __('Name', 'rankcookie'), 'rankcookie_field_default_cb_input', 'rankcookie', 'rankcookie_section_custom_permission', array(
    'label_for' => 'rankcookie_field_custom_permission_name',
    'class' => 'rankcookie-row',
    'rankcookie_custom_data' => array(
      'description' => __('Name of the additional permission, e.g. "External media"', 'rankcookie')
    )
  ));

  // Add field custom permission description
  add_settings_field('rankcookie_field_custom_permission_description', __('Description', 'rankcookie'), 'rankcookie_field_default_cb_textarea', 'rankcookie', 'rankcookie_section_custom_permission', array(
    'label_for' => 'rankcookie_field_custom_permission_description',
    'class' => 'rankcookie-row',
    'rankcookie_custom_data' => array(
      'description' => __('Description of the additional consent, e.g. "If cookies are accepted from external media, access to this content no longer requires manual consent."', 'rankcookie')
    )
  ));

  // Add field privacy slug
  add_settings_field('rankcookie_field_slug_privacy', __('Privacy', 'rankcookie'), 'rankcookie_field_default_cb_input', 'rankcookie', 'rankcookie_section_slugs', array(
    'label_for' => 'rankcookie_field_slug_privacy',
    'class' => 'rankcookie-row',
    'rankcookie_custom_data' => array(
      'description' => __('URL slug for the privacy page. Default: /privacy', 'rankcookie')
    )
  ));

  // Add field google tag manager id
  add_settings_field('rankcookie_field_slug_imprint', __('Imprint', 'rankcookie'), 'rankcookie_field_default_cb_input', 'rankcookie', 'rankcookie_section_slugs', array(
    'label_for' => 'rankcookie_field_slug_imprint',
    'class' => 'rankcookie-row',
    'rankcookie_custom_data' => array(
      'description' => __('URL slug for the imprint page. Default: /imprint', 'rankcookie')
    )
  ));

  // Add field cookie lifetime
  add_settings_field('rankcookie_field_lifetime', __('Lifetime', 'rankcookie'), 'rankcookie_field_default_cb_input', 'rankcookie', 'rankcookie_section_lifetime', array(
    'label_for' => 'rankcookie_field_lifetime',
    'class' => 'rankcookie-row',
    'rankcookie_custom_data' => array(
      'description' => __('Lifetime of the cookie in days. Default: 365 days', 'rankcookie')
    )
  ));
}

/**
 * Register our rankcookie_settings_init to the admin_init action hook
 */
add_action('admin_init', 'rankcookie_settings_init');

/**
 * Custom option and settings:
 * Callback functions
 */

// Section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// The values are defined at the add_settings_section() function.

// Callback for section google tag manager
function rankcookie_section_gtm_cb($args) {
?>
  <p id="<?php
  echo esc_attr($args['id']);
?>"><?php
  _e('The GTM snippet is automatically inserted when the "Google Tag Manger ID" field is filled in and the required cookie consent of the user ("statistics") is available.', 'rankcookie');
?></p>
  <?php
}

// Callback for section custom permissions
function rankcookie_section_custom_permission_cb($args) {
?>
  <p id="<?php
  echo esc_attr($args['id']);
?>"><?php
  _e('With this option you can obtain additional permissions via the cookie banner. Include the name and a brief description of the permissions.', 'rankcookie');
?></p>
  <?php
}

// Callback for section url slugs
function rankcookie_section_slugs_cb($args) {
  ?>
    <p id="<?php
    echo esc_attr($args['id']);
  ?>"><?php
    _e('The URL slugs of the pages "privacy" and "imprint", which are linked from the cookie banner, can be changed here.', 'rankcookie');
  ?></p>
    <?php
}

// Callback for section cookie lifetime
function rankcookie_section_lifetime_cb($args) {
  ?>
    <p id="<?php
    echo esc_attr($args['id']);
  ?>"><?php
    _e('Here you can change the lifetime of the cookie (time until the cookie expires).', 'rankcookie');
  ?></p>
    <?php
}

// Callbacks for fields
 
// Default callback for input fields
function rankcookie_field_default_cb_input($args) {
  $options = get_option('rankcookie_options');
?>
  <input id="<?php echo esc_attr($args['label_for']); ?>" name="rankcookie_options[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo esc_attr($options[$args['label_for']]);?>">
  <p class="description"><?php echo esc_attr($args['rankcookie_custom_data']['description']); ?></p>
<?php
}

// Default callback for textarea fields
function rankcookie_field_default_cb_textarea($args) {
  $options = get_option('rankcookie_options');
?>
  <textarea id="<?php echo esc_attr($args['label_for']); ?>" name="rankcookie_options[<?php echo esc_attr($args['label_for']); ?>]" rows="4" cols="50"><?php echo esc_textarea($options[$args['label_for']]);?></textarea>
  <p class="description"><?php echo esc_attr($args['rankcookie_custom_data']['description']); ?></p>
<?php
}

/**
 * Add top level menu page
 */
function rankcookie_options_page() {
  
  add_menu_page('rankcookie Settings', 'rankcookie', 'manage_options', 'setup-rankcookie', 'rankcookie_options_page_html', 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE5LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPg0KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTI7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxnPg0KCQk8Zz4NCgkJCTxwYXRoIGQ9Ik01MDcuNDQsMjA4LjY0Yy0xLjI5Ni02Ljg4LTYuODgtMTIuMDk2LTEzLjgyNC0xMi45MjhjLTYuOTYtMC44MzItMTMuNiwyLjkyOC0xNi40OCw5LjMxMg0KCQkJCWMtNS4wNzIsMTEuMi0xNi4yMDgsMTguOTkyLTI5LjEyLDE4Ljk3NmMtMTQuMzIsMC4wMzItMjYuNDE2LTkuNjMyLTMwLjQ0OC0yMi44OTZjLTIuNDMyLTguMDk2LTEwLjc1Mi0xMi44OTYtMTguOTc2LTEwLjk3Ng0KCQkJCUMzOTMuNTM2LDE5MS4zMTIsMzg4Ljc1MiwxOTIsMzg0LDE5MmMtMzUuMjQ4LTAuMDY0LTYzLjkzNi0yOC43NTItNjQtNjRjMC00Ljc1MiwwLjY4OC05LjUzNiwxLjg3Mi0xNC41NzYNCgkJCQljMS45MzYtOC4yMjQtMi44OC0xNi41Ni0xMC45NzYtMTguOTkyQzI5Ny42MzIsOTAuNDE2LDI4Ny45NjgsNzguMzIsMjg4LDY0Yy0wLjAxNi0xMi45MjgsNy43NzYtMjQuMDQ4LDE4Ljk3Ni0yOS4xMg0KCQkJCWM2LjM4NC0yLjg4LDEwLjE0NC05LjUzNiw5LjMxMi0xNi40OGMtMC44MzItNi45Ni02LjA0OC0xMi41NDQtMTIuOTI4LTEzLjg0QzI4OC4wOTYsMS42OTYsMjcyLjI4OCwwLDI1NiwwDQoJCQkJQzExNC43ODQsMC4wMzIsMC4wMzIsMTE0Ljc4NCwwLDI1NmMwLjAzMiwxNDEuMjE2LDExNC43ODQsMjU1Ljk2OCwyNTYsMjU2YzE0MS4yMTYtMC4wMzIsMjU1Ljk2OC0xMTQuNzg0LDI1Ni0yNTYNCgkJCQlDNTEyLDIzOS43MTIsNTEwLjMwNCwyMjMuOTA0LDUwNy40NCwyMDguNjR6IE00MTQuMzIsNDE0LjMyQzM3My42OTYsNDU0LjkxMiwzMTcuNzkyLDQ4MCwyNTYsNDgwcy0xMTcuNjk2LTI1LjA4OC0xNTguMzItNjUuNjgNCgkJCQlDNTcuMDg4LDM3My42OTYsMzIsMzE3Ljc5MiwzMiwyNTZTNTcuMDg4LDEzOC4zMDQsOTcuNjgsOTcuNjhDMTM4LjMwNCw1Ny4wODgsMTk0LjIwOCwzMiwyNTYsMzJjMi44OCwwLDUuNjk2LDAuMzA0LDguNTYsMC40MzINCgkJCQlDMjU5LjIxNiw0MS43NDQsMjU2LjAxNiw1Mi40NjQsMjU2LDY0YzAuMDMyLDIzLjg4OCwxMy4yOCw0NC4zNjgsMzIuNTkyLDU1LjI5NkMyODguMjg4LDEyMi4xNDQsMjg4LDEyNC45OTIsMjg4LDEyOA0KCQkJCWMwLjAzMiw1Mi45NzYsNDMuMDI0LDk1Ljk2OCw5Niw5NmMzLjAwOCwwLDUuODU2LTAuMjg4LDguNzA0LTAuNTkyQzQwMy42MzIsMjQyLjcwNCw0MjQuMDk2LDI1NS45NjgsNDQ4LDI1Ng0KCQkJCWMxMS41MzYtMC4wMTYsMjIuMjU2LTMuMjE2LDMxLjU2OC04LjU2YzAuMTI4LDIuODQ4LDAuNDMyLDUuNjgsMC40MzIsOC41NkM0ODAsMzE3Ljc5Miw0NTQuOTEyLDM3My42OTYsNDE0LjMyLDQxNC4zMnoiLz4NCgkJCTxjaXJjbGUgY3g9IjE5MiIgY3k9IjEyOCIgcj0iMzIiLz4NCgkJCTxjaXJjbGUgY3g9IjEyOCIgY3k9IjI1NiIgcj0iMzIiLz4NCgkJCTxjaXJjbGUgY3g9IjI4OCIgY3k9IjM4NCIgcj0iMzIiLz4NCgkJCTxjaXJjbGUgY3g9IjI3MiIgY3k9IjI3MiIgcj0iMTYiLz4NCgkJCTxjaXJjbGUgY3g9IjQwMCIgY3k9IjMzNiIgcj0iMTYiLz4NCgkJCTxjaXJjbGUgY3g9IjE3NiIgY3k9IjM2OCIgcj0iMTYiLz4NCgkJPC9nPg0KCTwvZz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjwvc3ZnPg0K');
}

/**
 * Register our rankcookie_options_page to the admin_menu action hook
 */
add_action('admin_menu', 'rankcookie_options_page', 999);

/**
 * Top level menu:
 * Callback functions
 */
function rankcookie_options_page_html() {
  // Check user capabilities
  if (!current_user_can('manage_options')) {
    return;
  }
  
  // Add error/update messages
  
  // Check if the user have submitted the settings
  // Wordpress will add the "settings-updated" $_GET parameter to the url
  if (isset($_GET['settings-updated'])) {
    // add settings saved message with the class of "updated"
    add_settings_error('rankcookie_messages', 'rankcookie_message', __('Settings saved', 'rankcookie'), 'updated');
  }
  
  // Show error/update messages
  settings_errors('rankcookie_messages');
?>
 <div class="wrap">
   <style>
     input:not(#submit), textarea { width: 330px; }
     #rankcookie_field_gtm_id { height: 30px; font-size: 16px; border: 2px solid black; padding-left: 5px; }
   </style>
 <h1><?php echo esc_html(get_admin_page_title());?></h1>
 <form action="options.php" method="post">
 <?php
  // Output security fields for the registered setting "rankcookie"
  settings_fields('rankcookie');
  // Output setting sections and their fields
  // (Sections are registered for "rankcookie", each field is registered to a specific section)
  do_settings_sections('rankcookie');
  
  // Output save settings button
  submit_button(__('Save settings', 'rankcookie'));
?>
 </form>
 </div>
 <?php
}