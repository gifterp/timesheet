<?php
/** 
 *  Model file that extends to CI model that hold database functions
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */
class MY_Model extends CI_Model {

	 protected $_table_name = '';
	 protected $_primary_key = '';
	 protected $_primary_filter= "intval";
	 protected $_order_by = '';
	 public    $rules = array();
	 public    $rules_admin = array();
	 protected $_timestamps = FALSE;
 
 	function __construct(){
		parent::__construct();
	}

		/** ----------------------------------------------------------------------------
	   * Function that get data with specific id or all the data as a result array 
	   *
	   * @param  $id           Holds the id for sql query
	   * @param  $single       Will determine if single row or array result query
	   * @param  $altMethod    What format the results should be returned as
	   */
		public function get($id = NULL, $single = FALSE, $altMethod = 'result'){

			if ($id != NULL) {
				$filter = $this->_primary_filter;
				$id = $filter($id);
				$this->db->where($this->_primary_key, $id);
				$method = 'row';
			}
			elseif($single == TRUE) {
				$method = 'row';
			}
			else {
				$method = $altMethod;
			}
			
			if(!count($this->db->qb_orderby)){
					$this->db->order_by($this->_order_by);
				}
			// $this->db->limit(10);
			return $this->db->get($this->_table_name)->$method();
		}

		/** ----------------------------------------------------------------------------
	   * Function that convert array from post
	   *
	   * @param  $field           Holds the field for each post
	   *          
	   */
		public function array_from_post($fields){
			foreach ($fields as $field) {

				$data[$field] = $this->input->post($field);
			}

			return $data;

		}

		/** ----------------------------------------------------------------------------
	   * Function that use via get, this works with a condition 
	   *
	   * @param  $where           data to be search for in the database
	   *          
	   */
		public function get_by($where, $single = FALSE){
			$this->db->where($where);
			return $this->get(NULL, $single);
		}

		/** ----------------------------------------------------------------------------
	   * Function that save and updates the data from the database
	   *
	   * @param  $data         Array post that holds the data
	   * @param  $id           If not null, it will update the database
	   *          
	   */
		public function save($data, $id = NULL){

			//Set timestamp
			if($this->_timestamps == TRUE){
				$now = date('Y-m-d H:i:s');
				$id || $data['created'] = $now;
				$data['modified'] = $now;
			}

			//insert here
			if($id === NULL){
				!isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
				$this->db->set($data);
				$this->db->insert($this->_table_name);
				$id = $this->db->insert_id();
			}

			//update here
			else{
				$filter = $this->_primary_filter;
				$id = $filter($id);
				$this->db->set($data);
				$this->db->where($this->_primary_key, $id);
				$this->db->update($this->_table_name);
			}

			return $id;
		}

	
		/** ----------------------------------------------------------------------------
	   * Function create an update in the database once user is logout
	   *
	   * @param  $data         Array post that holds the data
	   * @param  $id           If not null, it will update the database
	   *          
	   */
		public function logout_update($data, $id = NULL, $wh){

			//Set timestamp
			if($this->_timestamps == TRUE){
				$now = date('Y-m-d H:i:s');
				$id || $data['created'] = $now;
				$data['modified'] = $now;
			}

			//insert here
			if($id === NULL){
				!isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
				$this->db->set($data);
				$this->db->insert($this->_table_name);
				$id = $this->db->insert_id();
			}

			//update here
			else{
				$filter = $this->_primary_filter;
				//$id = $filter($id);
				$this->db->set($data);
				$this->db->where($wh, $id);
				$this->db->update($this->_table_name);
			}

			return $id;
		}

		/** ----------------------------------------------------------------------------
	   * Function that delete the data from the database
	   *
	   * @param  $id           If not null, it will delete the data with the specific id
	   *          
	   */
		public function delete($id){
			$filter = $this->_primary_filter;
			$id = $filter($id);

			if(!$id){
					return FALSE;
			}

			$this->db->where($this->_primary_key, $id);
			$this->db->limit(1);
			$this->db->delete($this->_table_name);
			
		}

		/** ----------------------------------------------------------------------------
	   * Function that delete the data from the database
	   *
	   * @param  $id           If not null, it will delete the data with the specific id
	   * @param  $where        Specific condition to what would be deleted
	   *
	   */
		public function delete_where($where,$id){
			$filter = $this->_primary_filter;
			$id = $filter($id);

			//if(!$id){
			//		return FALSE;
			//}

			if($where == NULL && $id == NULL){

			}else{
				$this->db->where($where, $id);
			}

			
			$this->db->delete($this->_table_name);

			
		}


      /** ----------------------------------------------------------------------------
	   * Reorders the display order when an item is moved via drag and drop
	   *
	   * @param  string  $fieldName     Database field to be reordered
	   * @param  string  $where         WHERE clause in SQL statement to select
	   * @param  int     $start         Where the reordered item originated
	   * @param  int     $end           Where the reordered item finished
	   * @param  int     $originalId    Original id of the item to exclude from being reordered
	   */
      public function reorder( $fieldName, $where, $start, $end, $originalId ) {
 
         // Increment or decrement
         $operator      = '-';
         $endOperator   = '<=';
         $startOperator = '>=';
         if ( $start > $end ) {
            $operator      = '+';
            $endOperator   = '>=';
            $startOperator = '<=';
         }

         // Build the query
         $sql = "UPDATE " . $this->_table_name . "
            SET " . $fieldName . " = " . $fieldName . " " . $operator . " 1
            WHERE (" . $fieldName . " " . $endOperator . " '" . $end . "'
               AND " . $fieldName . " " . $startOperator . " '" . $start . "'
               AND " . $where . "
               AND " . $this->_primary_key . " != '" . $originalId . "') ";

         $this->db->query($sql);

      }


      /** ----------------------------------------------------------------------------
	   * Get the last displayOrder value of the sorted row
	   *
	   * @param  string  $fieldName     Database field to be select
	   * @param  string  $where         WHERE clause in SQL statement to select
	   * @param  int     $id    			Id of the last item
	   */
      public function get_last_display_order( $fieldName, $where,  $id ) {
 			
 			$condition = '';
 			if ( $where != NULL || $id != NULL ) {
 				$condition = "WHERE " . $where . " = '" . $id . "'  ";
 			}
         $sql = " SELECT " . $fieldName . " 
                  FROM " . $this->_table_name . " 
                  " . $condition . "
                  ORDER BY " . $fieldName . " DESC LIMIT 1";
         return $this->db->query( $sql )->row();
      }

      /** ----------------------------------------------------------------------------
	    * Save the new value for changes in displayOrder 
	    *
	    * @param   int   	$id       		Id of the item to be updated
	    * @param   string 	$value    		The value of the new column
	    * @param   string   $fieldName     Database field to be select
	    */
	   public function update_single_field( $fieldName, $value, $id ) {
	      $sql = " UPDATE " . $this->_table_name . "
	               SET " . $fieldName . " = '" . $value . "'  
	               WHERE " . $this->_primary_key . " = '" . $id . "' ";
	      $this->db->query( $sql );
	   }

		
		public function datatable($start, $order, $length, $draw, $search, $tbl_columns = NULL, $join_tbl = NULL, $tbl_where = NULL ){

		if($join_tbl != NULL){
			foreach ($join_tbl as $jkey => $jvalue) {
				$this->db->join($jvalue['tbl'], $jvalue['tbl_on'], 'left');
			}
			
		}

	

		if($tbl_columns != NULL){
			
			if(count($tbl_columns) > 1){
				$a = "";
				foreach ($tbl_columns as $key => $value) {
					if($key < 0){
						$this->db->like($tbl_columns[0], $search, 'both');
					}else{
						if($search != NULL){

							$a .= $this->db->or_like($value, $search, 'both');
						}	
					}
				}

				if($a != ""){
					$this->db->where('('.$a.')');
				}

			}else{
				if($search != NULL){
					$this->db->like($tbl_columns[0], $search, 'both');
				}
			}

		}

		if($tbl_where != NULL){
			foreach ($tbl_where as $twkey => $twvalue) {
				$this->db->where($twkey, $twvalue, NULL);
				//$this->db->where('transaction.status', 5);
			}
		}


		$count = $this->db->count_all_results($this->_table_name);

		if($join_tbl != NULL){
			foreach ($join_tbl as $jkey => $jvalue) {
				$this->db->join($jvalue['tbl'], $jvalue['tbl_on'], 'left');
			}
			
		}

		if($tbl_where != NULL){
			 foreach ($tbl_where as $twkey => $twvalue) {
			 	$this->db->where($twkey, $twvalue, NULL);
				//$this->db->where('transaction.status', 5);
			 }
		}

		if($tbl_columns != NULL){
			if(count($tbl_columns) > 1){
				$a = "";
				foreach ($tbl_columns as $key => $value) {
					if($key < 0){
						$this->db->like($tbl_columns[0], $search, 'both');
					}else{
						if($search != NULL){

							$a .= $this->db->or_like($value, $search, 'both');
						}	
					}
				}

				if($a != ""){
					$this->db->where('('.$a.')');
				}

			}else{
				if($search != NULL){
					$this->db->like($tbl_columns[0], $search, 'both');
				}
			}
		}

		


		$this->db->order_by($this->_table_name.".id", "desc");

		$data = $this->db->get($this->_table_name, $length, $start)->result();

		$str = $this->db->last_query();
		
		return array( 'query' => $str,
					'count' => $count,
					  'data' => $data
					);

		
		}

		//DATA TABLE WHERE STARTS HERE
		public function datatable_where($start, $order, $length, $draw, $search, $tbl_columns = NULL, $join_tbl = NULL, $tbl_where = NULL ){

			//REFFERENCE  "SELECT * FROM (`transaction`) WHERE `affiliate_id` LIKE '%d%' ORDER BY `transaction`.`id` desc LIMIT 10, 10"
			
			//"SELECT transaction.* , customer.* , product.* FROM transaction LEFT JOIN customer ON customer.id = transaction.customer_id LEFT JOIN product ON product.id = transaction.product_id WHERE ( OR customer.first_name LIKE %je% OR customer.first_name LIKE %je% OR customer.first_name LIKE %je% ) AND AND transaction.status = 1 LIMIT10,0"


			$myquery = "SELECT ".$this->_table_name.".*  ";
			$tbl_join = "";

			$left_join = "";

			if($join_tbl != NULL){
				foreach ($join_tbl as $jkey => $jvalue) {
					//$this->db->join($jvalue['tbl'], $jvalue['tbl_on'], 'left');

					$tbl_join .= ", ".$jvalue['tbl'].".* ";

					$left_join .= " LEFT JOIN ".$jvalue['tbl']." ON ".$jvalue['tbl_on']." ";
				}
				
			}



		

			if($tbl_columns != NULL){

				$tbl_search = "(";
				
				if(count($tbl_columns) > 1){
					foreach ($tbl_columns as $key => $value) {
						if($key == 0){
							$this->db->like($tbl_columns[0], $search, 'both');
							$tbl_search .= " ".$tbl_columns[0]." LIKE '%".$search."%' ";
						}else{
							if($search != NULL){

							 $this->db->or_like($value, $search, 'both');
							}

							$tbl_search .= " OR ".$value." LIKE '%".$search."%' ";	
						}
					}


				}else{
					if($search != NULL){
						$this->db->like($tbl_columns[0], $search, 'both');
						$tbl_search .= " ".$tbl_columns[0]." LIKE '%".$search."%' ";
					}
				}

				$tbl_search .= ") AND ";

			}else{
				$tbl_search = "";
			}

			if($tbl_where != NULL){
				$sql_where = "";
				foreach ($tbl_where as $twkey => $twvalue) {
					$this->db->where($twkey, $twvalue, NULL);
					if($twkey == 0){
						$sql_where .= " ".$twkey." = ".$twvalue;
					}else{
						$sql_where .= " AND ".$twkey." = ".$twvalue;
					}
					//$this->db->where('transaction.status', 5);
				}
			}

			$all_query = $myquery.$tbl_join." FROM ".$this->_table_name.$left_join." WHERE ".$tbl_search.$sql_where." LIMIT ".$length.", ".$start;

			//$count = $this->db->count_all_results($this->_table_name);

			//$count = count($all_query);

			if($join_tbl != NULL){
				foreach ($join_tbl as $jkey => $jvalue) {
					$this->db->join($jvalue['tbl'], $jvalue['tbl_on'], 'left');
				}
				
			}

			if($tbl_where != NULL){
				 foreach ($tbl_where as $twkey => $twvalue) {
				 	$this->db->where($twkey, $twvalue, NULL);
					//$this->db->where('transaction.status', 5);
				 }
			}

			if($tbl_columns != NULL){
				if(count($tbl_columns) > 1){
					$a = "";
					foreach ($tbl_columns as $key => $value) {
						if($key < 0){
							$this->db->like($tbl_columns[0], $search, 'both');
						}else{
							if($search != NULL){

								$a .= $this->db->or_like($value, $search, 'both');
							}	
						}
					}

					if($a != ""){
						$this->db->where('('.$a.')');
					}

				}else{
					if($search != NULL){
						$this->db->like($tbl_columns[0], $search, 'both');
					}
				}
			}

			


			$this->db->order_by($this->_table_name.".id", "desc");

			$data = $this->db->get($this->_table_name, $length, $start)->result();

			$str = $this->db->last_query();

				$count = count($data);
			
			return array( 'query' => $all_query,
						'count' => $count,
						  'data' => $data
						);

			
		}


		// count the record in sql result.
		public function record_count() {
				return $this->db->count_all($this->_table_name);
		}

		// Fetch data according to per_page limit.
		public function fetch_data($page,$limit) {
			$sql = "SELECT * FROM ".$this->_table_name." WHERE softDelete  =  '0' LIMIT $page,$limit ";
			return $this->db->query($sql)->result();

		}
	//DATA TABLE WHERE ENDS HERE
}