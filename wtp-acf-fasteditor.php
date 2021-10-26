<?php
/**
 * Webto.pro plugin for ACF
 *
 * @copyright Copyright (C) 2021 to this day, Webto.pro - support@webto.pro
 *
 * @wordpress-plugin
 * Plugin Name: ACF FastEditor by WebTo.Pro
 * Version:     1.0
 * Description: Addition for ACF plugin.
 * Author:      Unbywyd
 * Plugin URI:  https://webto.pro
 * Author URI:  https://unbywyd.com
 * Text Domain: webto.pro
 */

define('ACF_FR_DIR', plugin_dir_path(__FILE__));
define('ACF_FR_URL', plugin_dir_url(__FILE__));

define('ACF_FR_PLUGIN_DEV_MODE', false);

class ACF_FASTEDITOR {
	function __construct() {    
	add_action('init', [$this, 'init']);
    add_action( 'admin_notices', [$this, 'admin_notices']);    
	  add_action('wp_footer', [$this, 'wp_footer']);
    add_filter( 'wp_loaded', [$this, 'flush_rules']);
    add_filter( 'template_include', [$this, 'template_include'],  99);
    add_filter( 'generate_rewrite_rules', [$this, 'generate_rewrite_rules'], 10, 1);
    add_filter( 'query_vars', [$this, 'query_vars'], 10, 1);
    add_action('wp_enqueue_scripts', [$this, 'wp_enqueue_scripts']);
    add_action( 'wp_print_scripts', [$this, 'deregister_javascript'], 99 );

  }
  public function generate_rewrite_rules($wp_rewrite) {
    if(current_user_can('manage_options')) {      
      $wp_rewrite->rules = array_merge(
          ['wtp-acf-fasteditor/(.+)/(.+)/?$' => 'index.php?pagename=wtp-acf-fasteditor&pid=$matches[1]&slugs=$matches[2]'],
          $wp_rewrite->rules
      );
    }
  }
  public function deregister_javascript() {
    global $wp;
    if($wp->query_vars['pagename'] == 'wtp-acf-fasteditor') {
      wp_dequeue_script('wp-color-picker');
      wp_deregister_script( 'jquery-ui-datepicker' );
      wp_deregister_script( 'wp-color-picker-js-extra' );
      wp_deregister_script( 'wp-color-picker' );
    }
  }
  public function wp_enqueue_scripts() {
    wp_deregister_script('wp-color-picker');
    global $post;
    if(current_user_can('manage_options') && $post) {
      wp_enqueue_script('wtp-acf-fasteditor');      
      wp_enqueue_style('wtp-acf-fasteditor');      
    }
  }
  function flush_rules() {
    $rules = get_option( 'rewrite_rules' );
    if(!isset($rules['wtp-acf-fasteditor/(.+)/(.+)/?$'])) {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }    
  }  
  function query_vars($vars) {
    if(current_user_can('manage_options')) {
        if(!in_array('slugs', $vars)) {
        array_push($vars, 'slugs');
        }
        if(!in_array('pid', $vars)) {
        array_push($vars, 'pid');
        }
    }
    return $vars;
  }
  function template_include($template) {
     if(current_user_can('manage_options')) {
      global $wp;
      if(!empty($wp->query_vars) && $wp->query_vars['pagename'] == 'wtp-acf-fasteditor') {
        status_header(200);
        return $template = ACF_FR_DIR . '/incs/acf-fasteditor-page.php';
      }
    }
    return $template;
  }
  public function acf_check() {
    /*
    * Check if the ACF plugin is exists
    */
    return class_exists( 'acf' );
  }
  public function admin_notices() {
    /*
    * Check if the ACF plugin is exists
    */
    if (!$this->acf_check() && current_user_can( 'manage_options' )) {
          $class   = 'notice notice-error';
          $message = sprintf( __( 'Uh-oh. ACF not installed. Please install the <a href="%s" class="thickbox">Advanced Custom Fields plugin.</a>', 'sample-text-domain' ), '/wp-admin/plugin-install.php?tab=plugin-information&plugin=advanced-custom-fields&TB_iframe=true&width=600&height=550' );
          printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
      }
  }
  public function init() {
    wp_register_script( 'wtp-acf-fasteditor', ACF_FR_URL . '/client/dist/js/index.min.js' , array('jquery'), filemtime(ACF_FR_DIR . '/client/dist/js/index.min.js'), true);   
    wp_localize_script('wtp-acf-fasteditor', 'wtp_fasteditor', array(
      'ajax_url' => admin_url( 'admin-ajax.php' )
    ));
    if(ACF_FR_PLUGIN_DEV_MODE) {
      wp_register_style( 'wtp-acf-fasteditor', ACF_FR_URL . '/client/dist/css/app.css' , array(), filemtime(ACF_FR_DIR . '/client/dist/css/app.css'));   
    } else {
      wp_register_style( 'wtp-acf-fasteditor', ACF_FR_URL . '/client/dist/releases/app-v1/css/app.min.css' , array(), filemtime(ACF_FR_DIR . '/client/dist/releases/app-v1/css/app.min.css'));   
    }
  }

  public function getFields() {
        global $post;
        if(isset($post) && !empty($post)) {
            $fields = get_fields($post->ID);         
            if(!empty($fields)) {
                $output = '';
                foreach($fields as $key => $field) {
                    $output .= $key . ",";
                }
                return preg_replace("/,$/", '', $output);
            }
        } else {
            return '';
        }
    }
  public function wp_footer() {
    global $post;
    $fields = false;
  
    if($post):  
    $fields = get_fields($post->ID);  
    $hasFields = !empty($fields) ? 'true' : 'false';
  
    ?>
    <div class="app-acf-fasteditor" id="app_acf_fasteditor">
      <div hidden ref="container">
      <div ref="storage" data-pid='<?php print $post->ID; ?>' data-hasforms="<?php print $hasFields ?>"></div>
        <button class="app-acf-fasteditor-btn" @click.prevent="onEditMode" v-if="!editMode"><svg class="ungic-icon" role="img" aria-labelledby="un_75mojbfz0" width="2em" height="2em">
                <title id="un_75mojbfz0">Edit mode</title>
                <use xlink:href="#svg-icon-settings_alt"></use>
            </svg></button>
        <div class="app-acf-fasteditor-overlay" @click.prevent="editMode = false" aria-label="Close overlay" tabindex="0" v-if="editMode"></div>
       
        <div class="app-acf-fasteditor-panel" role="modal" tabindex="0" ref="modal" aria-label="Fast editor" :class="{'__open': editMode}">
            <h2 class="app-acf-fasteditor-panel-heading"><span>ACF FastEditor</span>
                <a @click.prevent="editMode = false" ref="close" href="#" class="app-acf-fasteditor-panel-close"><svg class="ungic-icon" role="img" aria-labelledby="un_40p8l90wo" width="2em" height="2em">
                        <title id="un_40p8l90wo">Close</title>
                        <use xlink:href="#svg-icon-close_m"></use>
                    </svg></a>
            </h2>
            <div class="app-acf-fasteditor-tabs" v-if="!hasWidgetEditor">
                <p class="app-acf-fasteditor-alert info"><b>Note!</b>  In order to see the result of your changes, you need to manually reload the page</p>
                <nav class="app-acf-fasteditor-tabs-nav">
                    <ul>
                        <li><a @click.prevent="activeTab = 1" :aria-disabled="activeTab == 1" role="tab" id="acf_fasteditor_tab1" :aria-selected="activeTab == 1" aria-controls="acf_fasteditor_tab_widgets" :class="{'active': activeTab == 1 }" href="#acf_fasteditor_tab_forms">Current page</a></li>
                        <li><a @click.prevent="activeTab = 2" :aria-disabled="activeTab == 2" href="#acf_fasteditor_tab_widgets" :aria-selected="activeTab == 2" aria-controls="acf_fasteditor_tab_forms" :class="{'active': activeTab == 2 }" id="acf_fasteditor_tab2">Widgets</a></li>
                    </ul>
                </nav>
                <div class="app-acf-fasteditor-tabs-container">
                    <div id="acf_fasteditor_tab_widgets" class="app-acf-fasteditor-tab-region" v-show="activeTab == 1" role="tabpanel" tabindex="0" aria-labelledby="acf_fasteditor_tab1">
                        <div class="app-acf-fasteditor-alert warning" v-if="!hasForms">
                            No editable fields found
                        </div>
                        <div v-if="hasForms">
                            <iframe @load="onIframeLoad" aria-label="Current page settings" :src="getIframeFormsUrl" frameborder="0"></iframe>
                        </div>
                    </div>
                    <div id="acf_fasteditor_tab_forms" class="app-acf-fasteditor-tab-region"  v-show="activeTab == 2" role="tabpanel" tabindex="0" aria-labelledby="acf_fasteditor_tab2">
                        <div class="app-acf-fasteditor-alert warning" v-if="!widgets.length">
                            No editable widgets found
                        </div>                      
                        <div class="app-acf-fasteditor-widget" v-for="widget in widgets" :v-key="widget.id" v-else>
                          <h4 class="app-acf-fasteditor-widget-title" v-html="widget.title"></h4>
                          <iframe @load="onIframeLoad" :aria-label="widget.title + ' settings'" :src="widgetIframeURL(widget)" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>            
            <div class="app-acf-fasteditor-widget-editor" v-if="hasWidgetEditor">
              <p class="app-acf-fasteditor-alert info"><b>Note!</b>  In order to see the result of your changes, you need to manually reload the page</p>
              <h3 v-html="currentWidget.title"></h3>
              <iframe @load="onIframeLoad" :src="currentWidgetUrl" :aria-label="currentWidget.title" frameborder="0"></iframe>
            </div>
            <div class="app-acf-fasteditor-footer-controls">
              <button class="app-acf-fasteditor-footer-control app-acf-fasteditor-cancel" ref="cancel" @click.prevent="editMode = false">Cancel</button>
            </div>
        </div>
      
      </div>
    </div>  
    <svg class="ungic-svg-sprite" style="display:none">
        <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentcolor" id="svg-icon-check">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M18.03 7.97a.75.75 0 0 1 0 1.06l-7 7a.75.75 0 0 1-1.06 0l-4-4a.75.75 0 1 1 1.06-1.06l3.47 3.47 6.47-6.47a.75.75 0 0 1 1.06 0z"></path>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentcolor" id="svg-icon-close_m">
            <path d="M12 10.586L5.636 4.222 4.222 5.636 10.586 12l-6.364 6.364 1.414 1.414L12 13.414l6.364 6.364 1.414-1.414L13.414 12l6.364-6.364-1.414-1.414L12 10.586z"></path>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentcolor" id="svg-icon-pencil_m">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.45 1.893l5.656 5.657-13.02 13.021H3.427v-5.657L16.45 1.894zm0 2.829l-1.424 1.423 2.829 2.828 1.423-1.423-2.829-2.828zm-.01 5.666l-2.828-2.829-8.184 8.184v2.828h2.829l8.183-8.183z"></path>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentcolor" id="svg-icon-refresh">
            <path d="M6.545 8.163a.75.75 0 0 1-.487-1.044l1.66-3.535a.75.75 0 0 1 1.36.002l.732 1.57a.76.76 0 0 1 .08-.028 8.15 8.15 0 1 1-5.8 5.903.75.75 0 0 1 1.456.364 6.65 6.65 0 1 0 4.907-4.862l.74 1.583a.75.75 0 0 1-.872 1.043l-3.776-.996z"></path>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentcolor" id="svg-icon-settings_alt">
            <path d="M8.75 12a3.25 3.25 0 1 1 6.5 0 3.25 3.25 0 0 1-6.5 0z"></path>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.46 1.839a.75.75 0 0 1 1.08 0L15.111 4.5h3.638a.75.75 0 0 1 .75.75v3.638l2.662 2.573a.75.75 0 0 1 0 1.078L19.5 15.112v3.638a.75.75 0 0 1-.75.75h-3.638l-2.573 2.662a.75.75 0 0 1-1.078 0L8.888 19.5H5.25a.75.75 0 0 1-.75-.75v-3.638l-2.661-2.573a.75.75 0 0 1 0-1.078L4.5 8.888V5.25a.75.75 0 0 1 .75-.75h3.638l2.573-2.661zM12 7.25a4.75 4.75 0 1 0 0 9.5 4.75 4.75 0 0 0 0-9.5z"></path>
        </symbol>
    </svg>
    <?php
    endif;
  }
}

$ACF_FASTEDITOR =  new ACF_FASTEDITOR();