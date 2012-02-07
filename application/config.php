<?php
set_include_path( implode( PATH_SEPARATOR , array_unique(
	explode( PATH_SEPARATOR , __DIR__ . PATH_SEPARATOR . get_include_path() )
) ) );

spl_autoload_register( function( $class ) {
	$classFile = implode( DIRECTORY_SEPARATOR , explode( '\\' , $class ) );
	$classFile = stream_resolve_include_path( $classFile . '.php' );
	
	if ( is_file( $classFile ) ) {
		require $classFile;
	}
} );