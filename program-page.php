<?php

namespace ColdTurkey\ProgramPage;

/*
 * Plugin Name: Program Page
 * Version: 1.5.1
 * Plugin URI: http://www.coldturkeygroup.com/
 * Description: Custom info pages for Platform ad campaigns
 * Author: Cold Turkey Group
 * Author URI: http://www.coldturkeygroup.com/
 * Requires at least: 4.0
 * Tested up to: 4.6
 *
 * @package Program Page
 * @author Aaron Huisinga
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'PROGRAM_PAGE_PLUGIN_PATH' ) )
	define( 'PROGRAM_PAGE_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

if ( ! defined( 'PROGRAM_PAGE_PLUGIN_VERSION' ) )
	define( 'PROGRAM_PAGE_PLUGIN_VERSION', '1.5' );

require_once( 'classes/class-program-page.php' );

global $program_page;
$program_page = new ProgramPage( __FILE__, new PlatformCRM() );

if ( is_admin() ) {
	require_once( 'classes/class-program-page-admin.php' );
	new ProgramPage_Admin( __FILE__ );
}
