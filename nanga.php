<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Mozaik & VG web things
 * Plugin URI:        https://github.com/VGwebthings/nanga-mz
 * GitHub Plugin URI: https://github.com/VGwebthings/nanga-mz
 * Description:       Functions that don't belong to the theme.
 * Version:           1.1.5
 * Author:            Mozaik & VG web things
 * Author URI:        https://github.com/VGwebthings/nanga-mz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nanga
 * Domain Path:       /languages
 */
if ( ! defined( 'WPINC' ) ) {
    die;
}
//if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
//    require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
//}
function activate_nanga() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-nanga-activator.php';
    Nanga_Activator::activate();
}

function deactivate_nanga() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-nanga-deactivator.php';
    Nanga_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nanga' );
register_deactivation_hook( __FILE__, 'deactivate_nanga' );
require plugin_dir_path( __FILE__ ) . 'includes/nanga-helpers.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-nanga.php';
function run_nanga() {
    $plugin = new Nanga();
    $plugin->run();
}

run_nanga();
