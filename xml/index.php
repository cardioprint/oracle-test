<?php
const ROOT_DIR = __DIR__;

//TODO Замечание: Автоматическая загрузка недоступна при использовании PHP в интерактивном режиме командной строки.

spl_autoload_register( function ($class_name) {
    $CLASSES_DIR = ROOT_DIR . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    $file = $CLASSES_DIR . $class_name . '.php';
    if( file_exists( $file ) ) include $file;
} );

print_mem();
$parser = new XmlLibParser('input.xml');
$result = $parser->processFile();
print_mem();
print_r($result);


function print_mem()
{
    /* Peak memory usage */
    $mem_peak = memory_get_peak_usage();

    echo 'Peak usage: <strong>' . round($mem_peak / 1024) . 'KB</strong> of memory.<br><br>';
}