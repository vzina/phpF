<?php
/**
 * Variables
 */
$srcRoot   = __DIR__ . '/src';
$buildRoot = __DIR__ . '/build';

/**
 * Bump version
 */

if ( ! isset( $argv[1] ) ) {
	die( 'Provide a version number as argument' . PHP_EOL );
}

$version = $argv[1];

$file = file_get_contents( $srcRoot . '/phpf.php' );

file_put_contents( $srcRoot . '/phpf.php', preg_replace( "/define\( 'VERSION'\, '(.*?)' \)\;/", "define( 'VERSION', '$version' );", $file ) );

/**
 * Build PHAR
 */

$phar = new Phar( $buildRoot . '/phpf.phar',
	FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, 'myapp.phar' );

$phar['phpf.php'] = file_get_contents( $srcRoot . '/phpf.php' );

$phar->setStub( $phar->createDefaultStub( 'phpf.php' ) );

/**
 * Generate READNE
 */
$readme_src = file_get_contents( __DIR__ . '/README.src.md' );
$readme_out = __DIR__ . '/README.md';

$testEnv = true;
require $srcRoot . '/phpf.php';

$usage = '';
foreach ( getOptions( false ) as $k => $v ) {
	$usage .= '| ' . $k . ' | ' . $v . " |\n";
}

$cmd    = sprintf( 'php %s/phpf.php --list-simple', $srcRoot );
$passes = explode( PHP_EOL, trim( `$cmd` ) );
$passes = implode( PHP_EOL,
	array_map( function ( $v ) {
		return ' * ' . $v;
	}, $passes )
 );

file_put_contents( $readme_out,
	strtr( $readme_src, [
		'%USAGE%' => $usage,
		'%PASSES%' => $passes,
	] )
 );
