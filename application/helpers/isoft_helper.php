<?php
/**
 * Improved Software Helper
 *
 * General functions that will be reused in all our systems
 *
 * @author      John Gifter C Poja <gifter@gmail.com>
 * @copyright   Copyright (c) 2016 Genesis Software. <https://genesis.com>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */


/** ----------------------------------------------------------------------------
 * Changes a date from dd/mm/yyyy format to the international yyyy-mm-dd format
 *
 * @param   string   $date          String with date in format dd/mm/yyyy
 * @return  string                  String with date in format yyyy-mm-dd
 */
function create_int_date_format( $date ) {

   $intDate = null;

   if ( $date != '' || isset( $date ) ) {    // Only process if we have a date and not null
      $date = str_replace( '/', '-', $date );
      $intDate = date( 'Y-m-d', strtotime( $date ) );
   }

   return $intDate;
}


/** ----------------------------------------------------------------------------
 * Changes a date from MySQL into dd/mm/yyyy format
 *
 * @param   string   $date          String with date from MySQL
 * @return  string                  String with date in format dd/mm/yyyy
 */
function format_db_date( $date ) {

   if ( isset( $date ) ) { // format if not null or have value
      return date( 'd/m/Y', strtotime( $date ) );   
   }
   

}


/** ----------------------------------------------------------------------------
 * Replace empty values with null
 *
 * @param   string   $value         The string data to be used as the value
 * @return  null                    Set null into value
 */
function replace_empty_with_null( $value ) {

   if ( is_null( $value ) || empty( $value ) || $value == '' ) {
      $value = null;
   }
   return $value;
}


/** ----------------------------------------------------------------------------
 * Replace all empty values in an array with null
 *
 * @param   array   $array       Array to adjust
 * @return  void                 Array passed by reference and altered
 */
function array_replace_empty_with_null( &$array ) {

   foreach( $array as $key => $value ) {
      $array[ $key ] = replace_empty_with_null( $value );
   }
}


/** ----------------------------------------------------------------------------
 * Creates an associative array to be used in things such as the dropdown form helper
 *
 * Accepts an array like that returned by CodeIgniter result_array()
 *
 * @param   array    $array         Array with items to be put into an array
 * @param   string   $key           The array data to be used as the key
 * @param   string   $value         The array data to be used as the value
 * @param   boolean  $blank         If a blank key/value is needed at the start (Blank option in select)
 * @return  array                   Array of key => value pairs
 */
function create_key_value_array( $arrayToConvert, $key, $value, $blank = false ) {

   $array = [];

   // Blank value at the start if needed
   if ( $blank ) { $array[ '' ] = ''; }

   foreach ( $arrayToConvert as $row ) {
      $array[ $row[ $key ] ] = $row[ $value ];
   }

   return $array;
}


/** ----------------------------------------------------------------------------
 * Removes any trailing zeros after a decimal point
 *
 * Example output: 23.00 -> 23 | 23.50 -> 23.5
 *
 * @param   double   $number        The number to format
 * @return  double                  Formatted number
 */
function trim_trailing_zeros( $number ) {
   return ( strpos( $number, '.' ) !== false ? rtrim( rtrim( $number, '0' ), '.' ) : $number );
}
?>