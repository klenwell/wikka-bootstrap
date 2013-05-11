<?php
  # TODO: remove this
  error_reporting(E_ALL);
  
  # init bootstrap helper
  require_once($this->GetThemePath('/') . '/bootstrap_helper.php');
  $bootstrap = new BootstrapHelper($this);
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $bootstrap->title_text; ?></title>
    <!-- Bootstrap Version -->
  
    <base href="<?php echo $bootstrap->site_base ?>" />
    
    <?php echo $bootstrap->meta_robots; ?>
  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="WikkaWiki" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="<?php echo
      $bootstrap->config_ent("meta_keywords"); ?>" />
    <meta name="description" content="<?php echo
      $bootstrap->config_ent("meta_description"); ?>" />
    
    <link rel="stylesheet" type="text/css" href="<?php sprintf('%s/%s',
      $bootstrap->theme_css_path, 'print.css'); ?>" media="print" />
    <link rel="icon" href="<?php sprintf('%s/%s', $bootstrap->theme_path,
      'images/favicon.ico'); ?>" media="image/x-icon" />
    <link rel="shortcut icon" href="<?php sprintf('%s/%s',
      $bootstrap->theme_path, 'images/favicon.ico'); ?>" media="image/x-icon" />
    
    <?php echo $bootstrap->rss_revisions_link; ?>
    <?php echo $bootstrap->rss_recent_changes_link; ?>
    <?php echo $bootstrap->universal_edit_button; ?>
    <?php $bootstrap->echo_additional_headers(); ?>
    
    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="<?php printf('%s/%s?%s',
      $bootstrap->theme_css_path, 'bootstrap.min.css',
      $bootstrap->theme_hash) ?>" />
    <link rel="stylesheet" type="text/css" href="<?php printf('%s/%s?%s',
      $bootstrap->theme_css_path, 'wikka-bootstrap.css',
      $bootstrap->theme_hash) ?>" />
  </head>
  
  <body>
	
    <!-- BEGIN PAGE WRAPPER -->
    <div id="page" class="container">
	  <?php
	    # display system messages
	    if ( isset($bootstrap->message) && strlen($bootstrap->message)>0 ) {
		  printf('<div class="alert alert-success">%s</div>',
		    $bootstrap->message);
	    }
	  ?>
    
      <!-- BEGIN MASTHEAD -->
      <div class="masthead">
        <h2 class="muted">
          <?php echo $bootstrap->masthead_html; ?>
        </h2>
        
        <div class="navbar">
          <div class="navbar-inner">
            <div class="container">
              <?php echo $bootstrap->menu('main_menu'); ?>
              <?php echo $bootstrap->search_form; ?>
            </div>
          </div>
        </div>
      </div>
      <!-- END MASTHEAD -->
	  
	<?php
      # TO BE CONTINUED IN footer.php
	?>