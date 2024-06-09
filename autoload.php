<?php

spl_autoload_register(function($classe){
    $classe = str_replace("\\", DIRECTORY_SEPARATOR, $classe);
    $arquivo = __DIR__ . DIRECTORY_SEPARATOR . $classe . ".php";
    $arquivo = str_replace("\\App\\", DIRECTORY_SEPARATOR, $arquivo);

    if (file_exists($arquivo)) {
        require_once($arquivo);
        return true;
    }
    return false;
});