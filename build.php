<?php
/**
 * Build phar
 */
$srcRoot   = __DIR__ . '/src';
$buildRoot = __DIR__ . '/build';

$phar = new Phar( $buildRoot . '/phpfmt.phar',
	FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, 'myapp.phar' );

$phar['phpfmt.php'] = file_get_contents( $srcRoot . '/phpfmt.php' );

$phar->setStub( $phar->createDefaultStub( 'phpfmt.php' ) );

/**
 * Generate READNE
 */
$readme_src = file_get_contents( __DIR__ . '/README.src.md' );
$readme_out = __DIR__ . '/README.md';

$testEnv = true;
require $srcRoot . '/phpfmt.php';

$usage = '';
foreach ( getOptions( false ) as $k => $v ) {
	$usage .= '| ' . $k . ' | ' . $v . " |\n";
}

$cmd    = sprintf( 'php %s/phpfmt.php --list-simple', $srcRoot );
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
