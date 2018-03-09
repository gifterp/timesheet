<?php 
/**
 *  Model file that will handle the database connection and function of  job cadastral section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class Job_cadastral_model extends MY_Model { 

      protected $_table_name = "time_cadastral";
      protected $_order_by = 'cadastralId';
      protected $_primary_key = "cadastralId";
 
      function __construct(){
         parent::__construct();
      }


      /** ----------------------------------------------------------------------------
       * Get cadastral details for specific cadastral id
       *
       * @param  int    $cadastralId  Cadastral id
       * @return object               Cadastral details
       *
       */
      public function get_cadastral_row( $cadastralId ) {

         $sql = " SELECT *
                  FROM time_cadastral AS cad
                     INNER JOIN time_council AS con ON cad.councilId = con.councilId
                  WHERE  cad.cadastralId =  '" . $cadastralId . "' ";
         return $this->db->query( $sql )->row();
      }  

}