=== WP Rouble Rate ===
Contributors: hokku
Donate link: https://www.paypal.me/hokku
Tags: rouble rate,rouble rating,exchange rouble,rouble exchanging,курс рубля
Requires at least: 4.0
Tested up to: 4.7.2
Stable tag: trunc
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 WP Rouble Rate - плагин для ежедневного обновления курса рубля ко всем доступным для ЦБР валютам. 

== Description ==
 Rating provided [http://www.cbr.ru/scripts/XML_daily.asp ](http://www.cbr.ru/scripts/XML_daily.asp  "").
 Курсы валют предоставлены сайтом ЦБР [http://www.cbr.ru/scripts/XML_daily.asp ](http://www.cbr.ru/scripts/XML_daily.asp  "").
 
 WP Rouble Rate - плагин для ежедневного обновления курса рубля ко всем доступным для ЦБР валютам. Данные доступны в глобальной опции '_wprr_rate'.
 
 Use in the theme
 <pre>
	$rates = get_option( '_wprr_rate' );
	echo $rates['USD']['Nominal'] . ' USD = ' . $rates['USD']['Value'];
 </pre>
 
 Чтобы увидеть полный список доступных валют, выполните функцию ниже.
 
 Full rate list
 <pre>
        $rates = get_option( '_wprr_rate' );
        print_r( $rates );
 </pre>

== Installation ==

1. Just setup and activate the plugin through the 'Plugins - Add' menu in WordPress