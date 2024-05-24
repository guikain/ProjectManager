<?php

spl_autoload_register(function($classe){
    //echo "<pre>"; var_dump($classe); echo "</pre>";
    $classe = str_replace("\\", DIRECTORY_SEPARATOR, $classe);
    //echo "<pre>"; var_dump(__DIR__); echo "</pre>";
    $arquivo = __DIR__ . DIRECTORY_SEPARATOR . $classe . ".php";
    $arquivo = str_replace("\\App\\", DIRECTORY_SEPARATOR, $arquivo);
    //echo "<pre>"; var_dump($arquivo); echo "</pre>";

    if (file_exists($arquivo)) {
        require_once($arquivo);
        return true;
    }
    return false;
});