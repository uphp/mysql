<?php
    namespace src;

    use Doctrine\Common\Inflector\Inflector;
    /**
    * UPhp Inflection has static methods for inflecting text and extends of doctrine/inflector.
    *
    * The methods of this class facilitate the use of the functions contained in doctrine/inflector and are adapted to be used in UPhp.
    * For more information of usage go to http://www.doctrine-project.org/api/inflector/1.0/class-Doctrine.Common.Inflector.Inflector.html
    *
    * @link uphp.io
    * @since 1.0
    * @author Diego Bentes <diegopbentes@gmail.com>
    */
    class Inflection extends Inflector{
        /**
        * Adds irregular words to the array that contain differentiation between plural and singular.
        *
        * @param array $array_inflection_irregular The array with irregular words.
        * @example Inflection::irregular(['person' => 'people']);
        * @return void Words are added to intelligence.
        */
        public static function irregular(Array $array_inflection_irregular){
            parent::rules( 'plural', [ 'irregular' => $array_inflection_irregular ] );
        }
        /**
        * Adds uninflected words to the array.
        *
        * @param array $array_inflection_uninflected The array with uninflected words.
        * @example Inflection::uninflected(['login']);
        * @return void Words are added to intelligence.
        */
        public static function uninflected(Array $array_inflection_uninflected){
            parent::rules( 'plural', [ 'uninflected' => $array_inflection_uninflected ] );
        }
    }