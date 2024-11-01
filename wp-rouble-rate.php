<?php
/*
Plugin Name: Rouble Rate 
Plugin URI: http://nebster.net
Description: Плагин получает курс рубля ко всем доступным валютам в ЦБР РФ
Version: 1.0
Author: Игорь Тронь
Author URI: http://nebster.net
*/
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
	define( WPRR, 'wp-rouble-rate' );
	global $wprr;
	$wprr = array(
		'url'	=> 'http://www.cbr.ru/scripts/XML_daily.asp',
	);

	
	#	Расписание обновления курсов	
	if( defined( 'DOING_CRON' ) && DOING_CRON ){
		add_action( 'wprr_loader_exrate', '_wprr_loader_exrate' );
		add_action( 'wprr_loader_exrate__repeat', '_wprr_loader_exrate' );
	}
	
	function _wprr_loader_exrate() {
		global $wprr;
		wp_clear_scheduled_hook( 'wprr_loader_exrate__repeat' );
		$data = simplexml_load_string( file_get_contents( $wprr['url'] ) );
		$rates = array();
		if ( empty ( $data ) ) :
			wp_schedule_event( time(), 'hourly', 'wprr_loader_exrate__repeat' );
			exit;
		endif;
		
		foreach( $data->Valute as $k => $v ) :
			$val = ( array )$v;
			$rates[ $val['CharCode'] ] = $val;
			unset( $rates[ $val['CharCode'] ][ '@attributes' ] );
			$rates[ $val['CharCode'] ]['ID'] = $val['@attributes']['ID'];
		endforeach;
		
		update_option( '_wprr_rate', $rates );
	}
		
	register_activation_hook( __FILE__, 'wprr_activation' );
	function wprr_activation(){
		wp_clear_scheduled_hook( 'wprr_loader_exrate' );
		wp_clear_scheduled_hook( 'wprr_loader_exrate__repeat' );
		wp_schedule_event( time(), 'twicedaily', 'wprr_loader_exrate' );
		_wprr_loader_exrate();
	}
	
	register_deactivation_hook( __FILE__, 'wprr_deactivation' );
	function wprr_deactivation() {
		wp_clear_scheduled_hook( 'wprr_loader_exrate' );
		wp_clear_scheduled_hook( 'wprr_loader_exrate__repeat' );
	}
	
	#	Информация о курсе в админ-панель	
	add_action( 'admin_bar_menu', 'wprr_admin_bar_menu', 30 );
	function wprr_admin_bar_menu( $wp_admin_bar ) {
		$rates = get_option( '_wprr_rate' );
		$wp_admin_bar->add_node( array(
			'id'    => 'wprr_exrate_usd',
			'title' => $rates['USD']['Nominal'] . ' USD :: ' . $rates['USD']['Value'] ,
		) );
		$wp_admin_bar->add_node( array(
			'id'    => 'wprr_exrate_eur',
			'title' => $rates['EUR']['Nominal'] . ' EUR :: ' . $rates['EUR']['Value'] ,
		) );
	}