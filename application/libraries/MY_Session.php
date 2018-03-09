<?php
/**
 *  Controller file that extends to my controller that hold sessions
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class MY_Session extends CI_Session{

   function sess_update(){
      //listen to  HTTP_X_REQUESTED_WITH
      if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest'){
         parent::sess_update();
      }


   }


}