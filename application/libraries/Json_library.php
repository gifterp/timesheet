<?php
/**
 * JSON Function Library
 *
 * @author      John Gifter C Poja <gifterphpdev@gmail.com>
 * @copyright   Copyright (c) 2016 Genesis Software. <https://genesis.com>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Json_library {


   /** ----------------------------------------------------------------------------
    * Prints out an array as valid JSON
    *
    * @param   array    $array     Items to prints as JSON
    * @return  void
    */
   public function print_array_json( $array, $nestTitle = null ) {
      header( 'Content-Type: application/json' );

      // Add a title if the JSON output should be nested
      if ( $nestTitle ) {
         print '{ "' . $nestTitle . '": ' . json_encode( $array ) . ' }';
      } else {
         print json_encode( $array );
      }
   }


   /** ----------------------------------------------------------------------------
    * Prints out an array as valid JSON unless it is an empty array
    *
    * If the array is empty it sends no response data back
    * This will cause the AJAX call to fail and call the error method
    *
    * @param   array    $array     Data to print in JSON format
    * @return  void
    */
   public function print_array_json_unless_empty( $array ) {

      // Only print a JSON response if not an empty array
      if ( !empty( $array ) ) { $this->print_array_json( $array ); }

   }

}