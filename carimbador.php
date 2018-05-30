<?php
/**
 * Plugin Name: Carimbador Maluco
 * Description: Plugin para carimbar PDFs com dados do comprador.
 * Version: 1.0.1
 * Author: Diego Perotto
 * Author URI: https://casalcroche.com.br
 * Requires at least: 4.5
 * Tested up to: 4.9.3
 *
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// definindo a constate com o caminho do plugin (vai saber quantas vezes vou usar isso no futuro :-)).
if ( ! defined( 'CARIMBADOR_PLUGIN_FILE' ) ) {
	define( 'CARIMBADOR_PLUGIN_FILE', __FILE__ );
}
define ("CARIMABDOR_MARGEM_PADRAO",2);
define ("CARIMABDOR_COR_PADRAO","#FF0000");
define ("CARIMABDOR_FONTE_PADRAO","Helvetica");
define ("CARIMABDOR_TAMANHO_PADRAO","10");
define ("CARIMABDOR_TEXTO_PADRAO","^^ Licenciado para {nome} :: {email} ^^");




include_once("includes/class_carimbador.php");
include_once("includes/class_download.php");
include_once("includes/pagina_configuracao.php");

// quando ativar o plugin, leva pra pagina de configuração
function carimbador_ativar( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'admin.php?page=carimbador' ) ) );
    }
}
add_action( 'activated_plugin', 'carimbador_ativar' );

function plugin_add_settings_link( $links ) {
    $settings_link = '<a href="'.admin_url( 'admin.php?page=carimbador' ).'">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );


?>