<?php
/*
   Plugin Name: Curriculum Manager
   Plugin URI: http://wordpress.org/extend/plugins/curriculum-manager/
   Version: 0.1
   Author: Eric McNiece
   Description: Allows management of curriculum-based content
   Text Domain: curriculum-manager
   License: GPLv3
  */

/*
    "WordPress Plugin Template" Copyright (C) 2014 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This following part of this file is part of WordPress Plugin Template for WordPress.

    WordPress Plugin Template is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/

$CurriculumManager_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function CurriculumManager_noticePhpVersionWrong() {
    global $CurriculumManager_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "Curriculum Manager" requires a newer version of PHP to be running.',  'curriculum-manager').
            '<br/>' . __('Minimal version of PHP required: ', 'curriculum-manager') . '<strong>' . $CurriculumManager_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'curriculum-manager') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function CurriculumManager_PhpVersionCheck() {
    global $CurriculumManager_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $CurriculumManager_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'CurriculumManager_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function CurriculumManager_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('curriculum-manager', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// First initialize i18n
CurriculumManager_i18n_init();


// Next, run the version check.
// If it is successful, continue with initialization for this plugin
if (CurriculumManager_PhpVersionCheck()) {

    if ( !class_exists( 'ReduxFramework' ) ) {
      require_once( dirname( __FILE__ ) . '/redux-framework/ReduxCore/framework.php' );
      require_once( dirname( __FILE__ ) . '/CurriculumManager_ReduxInit.php' );
    }

    // Only load and run the init function if we know PHP version can parse it
    include_once('curriculum-manager_init.php');
    CurriculumManager_init(__FILE__);
}

add_action('init', 'removeDemoModeLink');
function removeDemoModeLink() { // Be sure to rename this function to something more unique
    if ( class_exists('ReduxFrameworkPlugin') ) {
        remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
    }
    if ( class_exists('ReduxFrameworkPlugin') ) {
        remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );
    }
}
