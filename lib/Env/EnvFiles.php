<?php

namespace FirebdPHP\Env;

class EnvFiles
{

    public static function loadEnv($dir): ?bool
    {
       if(!file_exists($dir.'/.env')){
            return false;
       }

       $lines = file($dir.'/.env');
       foreach($lines as $line){
           putenv(trim($line));
       }
       
    }
}
