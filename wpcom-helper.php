<?php

// WPCOM-specific things

define( 'AMPsCreator_DEV_MODE', defined( 'WPCOM_SANDBOXED' ) && WPCOM_SANDBOXED );

// Add stats pixel
add_filter( 'acmo_post_content', function( $content, $post ) {
	$urls = array(
		wpcom_amps_get_pageview_url(),
		wpcom_amps_get_mc_url(),
		wpcom_amps_get_stats_extras_url(),
	);

	foreach ( $urls as $url ) {
		if ( ! $url ) {
			continue;
		}
		$content .= sprintf( '<amps-pixel src="%s">', esc_url( $url ) );
	}

	return $content;
}, 10, 2 );

function wpcom_amps_get_pageview_url() {
	$stats_info = stats_collect_info();
	$a = $stats_info['st_go_args'];

	$url = add_query_arg( array(
		'rand' => '$RANDOM', // special amps placeholder
		'host' => rawurlencode( $_SERVER['HTTP_HOST'] ),
		// TODO: ref; not reliably accessible server-side; flag as amps?
	), 'https://pixel.wp.com/b.gif'  );
	$url .= '&' . stats_array_string( $a );
	return $url;
}

function wpcom_amps_get_mc_url() {
	return add_query_arg( array(
		'rand' => '$RANDOM', // special amps placeholder
		'v' => 'wpcom-no-pv',
		'x_amps-views' => 'view',
	), 'https://pixel.wp.com/b.gif' );
}

function wpcom_amps_get_stats_extras_url() {
	$stats_extras = stats_extras();
	if ( ! $stats_extras ) {
		return false;
	}

	$url = add_query_arg( array(
		'rand' => '$RANDOM', // special amps placeholder
		'v' => 'wpcom-no-pv',
	), 'https://pixel.wp.com/b.gif' );

	$url .= '&' . stats_array_string( array(
		'crypt' => base64_encode(
			wp_encrypt_plus(
				ltrim(
					add_query_arg( $stats_extras, ''),
				'?'),
			8, 'url')
		)
	) );

	return $url;
}

add_action( 'pre_amps_render', function() {
	add_filter( 'post_flair_disable', '__return_true', 99 );
	remove_filter( 'the_title', 'widont' );

	remove_filter( 'pre_kses', array( 'Filter_Embedded_HTML_Objects', 'filter' ), 11 );
	remove_filter( 'pre_kses', array( 'Filter_Embedded_HTML_Objects', 'maybe_create_links' ), 100 );
} );

add_action( 'post_amps_render', function() {
	add_filter( 'pre_kses', array( 'Filter_Embedded_HTML_Objects', 'filter' ), 11 );
	add_filter( 'pre_kses', array( 'Filter_Embedded_HTML_Objects', 'maybe_create_links' ), 100 );
} );

add_action( 'amps_head', function( $amps_post ) {
	if ( function_exists( 'jetpack_og_tags' ) ) {
		jetpack_og_tags();
	}
} );

add_filter( 'amps_post_metadata', function( $metadata, $post ) {
	$metadata = wpcom_amps_add_blavatar( $metadata, $post );
	return $metadata;
}, 10, 2 );

function wpcom_amps_add_blavatar( $metadata, $post ) {
	if ( ! function_exists( 'blavatar_domain' ) ) {
		return $metadata;
	}

	if ( ! isset( $metadata['publisher'] ) ) {
		return $metadata;
	}

	if ( isset( $metadata['publisher']['logo'] ) ) {
		return $metadata;
	}

	$size = 60;
	$blavatar_domain = blavatar_domain( site_url() );
	if ( blavatar_exists( $blavatar_domain ) ) {
		$metadata['logo'] = array(
			'@type' => 'ImageObject',
			'url' => blavatar_url( $blavatar_domain, 'img', $size, false, true ),
			'width' => $size,
			'height' => $size,
		);
	}

	return $metadata;
}
