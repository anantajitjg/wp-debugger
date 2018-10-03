<?php
/**
 * WP-Debugger
 * 
 * Better debugging in WordPress
 * 
 * @author Anantajit JG
 * 
 */

/**
 * Plugin Name: WP Debugger
 * Description: Better debugging in WordPress
 * Author: Anantajit JG
 * Author URI: https://anantajitjg.github.io
 * Version: 1.0
*/

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin Constants
if( ! defined( 'WP_DEBUGGER_PLUGIN_BASENAME' ) ) {
    define( 'WP_DEBUGGER_PLUGIN_BASENAME', plugin_basename(__FILE__) );
}
if( ! defined( 'WP_DEBUGGER_PLUGIN_DIR' ) ) {
    define( 'WP_DEBUGGER_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}
if( ! defined( 'WP_DEBUGGER_PLUGIN_URL' ) ) {
    define( 'WP_DEBUGGER_PLUGIN_URL', untrailingslashit( plugin_dir_url(__FILE__) ) );
}
if( ! defined( 'WP_DEBUGGER_PLUGIN_VERSION' ) ) {
    define( 'WP_DEBUGGER_PLUGIN_VERSION', '1.0' );
}

function wp_debugger_enqueue_scripts() {
    wp_enqueue_style( 'wp-debugger-style', WP_DEBUGGER_PLUGIN_URL . '/assets/style.css', array(), WP_DEBUGGER_PLUGIN_VERSION );
}
add_action( 'wp_enqueue_scripts', 'wp_debugger_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'wp_debugger_enqueue_scripts' );

/**
 * Core functions
 */

function highlight_str( $str ) {
    $str = trim( $str );
    $str = highlight_string( "<?php " . $str . " ?>", true );
    $str = str_replace( array( '&lt;?php&nbsp;$results&nbsp;', '?&gt;' ), '', $str );
    $str = str_replace( ');&nbsp;', ')', $str );
    $str = preg_replace( '|\\=&nbsp;|', '', $str, 1 );
    // replace default colors
    $str = str_replace( '#DD0000', '#9E2727', $str );
    return $str;
}

/**
 * var_dump with <pre> tag wrapped to the output
 * @param mixed $expression The variable you want to dump.
*/
function sdump( $expression ) {
    echo '<pre>';
    var_dump( $expression );
    echo'</pre>';
}

/**
 * dump the variable and highlight it
 * @param mixed $expression The variable you want to dump.
*/
function dump( $expression ) {
    printf( '<div class="wp-debugger-dump-container">%s</div>', highlight_str( "\$results = " . var_export( $expression, true ) . ";" ) );
}

/**
 * Prints information about a variable with <pre> tag wrapped to the output.
 * @param mixed $expression The expression to be printed.
*/
function print_sr( $expression ) {
    echo '<pre>';
    print_r( $expression );
    echo '</pre>';
}

/**
 * Prints information about a variable in the browser console in table format.
 * @param mixed $expression The expression to be printed.
*/
function print_rc( $expression, $table = true ) {
    $json = wp_json_encode( $expression );
    printf( '<script>console.%2$s(%1$s);</script>', $json, $table === true ? 'table' : 'log' );
}