<?php 
  /** 
   * Template for the google tag manger snippet
   */

  $gtm_id = get_option('rankcookie_options')['rankcookie_field_gtm_id']; 
  if (!empty($gtm_id))  { 
    // Google Tag Manger Snippet
    echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','" . esc_html($gtm_id) . "');</script>"; 
    // End Google Tag Manger Snippet
  } 
?>