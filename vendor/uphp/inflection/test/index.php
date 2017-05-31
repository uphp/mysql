<?php
    namespace test;

    require("../vendor/autoload.php");

    use src\Inflection;

    echo Inflection::pluralize("test");
    echo "<br>";
    echo Inflection::singularize("tests");