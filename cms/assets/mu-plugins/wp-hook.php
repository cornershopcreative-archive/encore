<?php
/**
 * Add a 'hook' command to WP-CLI that lists the functions attached to a given
 * filter or action hook.
 *
 * Original command by Daniel Bachhuber <daniel@handbuilt.co>:
 * https://gist.github.com/danielbachhuber/0f991d150067d8a332ec#file-wp-hook-command-php
 *
 * Tweaked by Kenji Garland <kenji@cornershopcreative.com> to show where each
 * callback function is defined, and to distinguish between instance methods
 * and static methods, which plugins sometimes add like this:
 * `add_filter( 'the_content', array( 'My_Weird_Plugin', 'weird_method' ) );`
 */

if ( class_exists( 'WP_CLI' ) ) :

/**
 * List callbacks registered to a given action or filter.
 *
 * <hook>
 * : The key for the action or filter.
 *
 * [--format=<format>]
 * : List callbacks as a table, JSON, or CSV. Default: table.
 *
 * EXAMPLES
 *
 *     wp hook wp_enqueue_script
 */
$hook_command = function( $args, $assoc_args ) {
	global $wp_filter;

	$assoc_args = array_merge( array(
		'format'        => 'table',
		), $assoc_args );

	$hook = $args[0];
	if ( ! isset( $wp_filter[ $hook ] ) ) {
		WP_CLI::error( "No callbacks specified for {$hook}." );
	}

	$callbacks_output = array();
	foreach( $wp_filter[ $hook ] as $priority => $callbacks ) {
		foreach( $callbacks as $callback ) {
			if ( is_array( $callback['function'] ) ) {

				$method = $callback['function'][1];

				if ( is_object( $callback['function'][0] ) ) {
					$class = get_class( $callback['function'][0] );
					$function_name = "$class->$method";
				} else {
					$class = $callback['function'][0];
					$function_name = "$class::$method";
				}

				$reflection = new ReflectionClass( $class );
				$definition = $reflection->getFileName() . ':' . $reflection->getMethod( $method )->getStartLine();

			} else {

				$function_name = $callback['function'];
				$reflection = new ReflectionFunction( $function_name );
				$definition = $reflection->getFileName() . ':' . $reflection->getStartLine();

			}
			$callbacks_output[] = array(
				'function'        => $function_name,
				'priority'        => $priority,
				'accepted_args'   => $callback['accepted_args'],
				'definition'      => preg_replace( '#^' . ABSPATH . '#', '', $definition ),
				);
		}
	}
	WP_CLI\Utils\format_items( $assoc_args['format'], $callbacks_output, array( 'function', 'priority', 'accepted_args', 'definition' ) );
};
WP_CLI::add_command( 'hook', $hook_command );

endif;
