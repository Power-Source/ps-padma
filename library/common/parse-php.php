<?php

/**
 * Parses PHP using eval.
 *
 * @param string $content PHP to be parsed.
 * 
 * @return mixed PHP that has been parsed.
 **/
function padma_parse_php($content) {

	/* If Padma PHP parsing is disabled, then return the content now. */
	if ( defined('PADMA_DISABLE_PHP_PARSING') && PADMA_DISABLE_PHP_PARSING === true )
		return $content;

	/* If it's a WordPress Network setup and the current site being viewed isn't the main site, 
	   then don't parse unless PADMA_ALLOW_NETWORK_PHP_PARSING is true. */
	if ( !is_main_site() && (!defined('PADMA_ALLOW_NETWORK_PHP_PARSING') || PADMA_ALLOW_NETWORK_PHP_PARSING === false) )
		return $content;

	if ( empty( $content ))
		return $content;

	ob_start();

	// Improved error handling for eval() - SECURITY HARDENING
	try {
		$eval = eval("?>$content<?php ;");
	} catch (\ParseError $e) {
		$error = $e;
		$eval = false;
	} catch (\Throwable $e) {
		// Catch all exceptions from eval
		error_log('Parse error in theme: ' . $e->getMessage());
		$eval = false;
		$error = $e;
	}

	if ( $eval === null ) {

		$parsed = ob_get_contents();		

	} else {

		$error 	= error_get_last();
		$parsed = '<p>' . sprintf( __('<strong>Error while parsing PHP:</strong> %s', 'padma'), $error['message']) . '</p>';

	}

	ob_end_clean();

	return $parsed;

}
