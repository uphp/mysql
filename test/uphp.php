<?php
require("../vendor/autoload.php");

require("../src/ActiveRecord.php");

use UPhp\Model\ActiveRecord;

function uphpVersion()
{
    echo "UPhp Framework\n";
    echo "Version: 0.1 BETA\n";
    echo "Page: uphp.io\n";
}

if (! empty($argv[1])) {
    
    switch ($argv[1]) {

        case "db:dump":
            echo "Verificando banco de dados utilizado ...\n";
            $banco = require("config/database.php");
            if ($banco["type"] == "nosql") {
                echo "Detectado: " . $banco["driver"] . "\n";
                echo "Lendo db_dump.json ...\n";
                $db_dump_json = file_get_contents("db/db_dump.json");
                echo "Gerando db_dump.lock ...\n";
                $db_lock = fopen("db/db_dump.lock", "w");
                fwrite($db_lock, $db_dump_json);
                fclose($db_lock);
                echo "db_dump.lock gerado com sucesso";
            } elseif ($banco["type"] == "relational") {
                echo "Detectado: " . $banco["driver"] . "\n";
                echo "Lendo banco de dados ...\n";
                $dbDesc = ActiveRecord::descDatabase();
                echo "Gerando db_dump.lock ...\n";
                $dbDesc = serialize($dbDesc);
                $db_lock = fopen("db_dump.lock", "w");
                fwrite($db_lock, $dbDesc);
                fclose($db_lock);
                echo "db_dump.lock gerado com sucesso";
            }
            break;

        case "-v":
        case "--version":
            uphpVersion();
            break;


        case "generate":
            
            if (isset($argv[2])) {
                echo $argv[2];
            }
            break;
        
        default:

            echo $argv[1] . " is invalid command";

    }

} //else {
//    uphpVersion();
//}