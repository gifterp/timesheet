<?php
/**
 *  Model file that will handle the database connection and function of  job section
 *
 * 
 *
 * @author      John Gifter C Poja <gifter@improvedsoftware.com.au>
 * @author      Matt Batten C Poja <matt@improvedsoftware.com.au>
 * @copyright   Copyright (c) 2016 Improved Software. <https://improvedsoftware.com.au>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */ 
class Job_model extends MY_Model { 
 
      protected $_table_name  = "time_job";
      protected $_order_by    = 'jobId';
      protected $_primary_key = "jobId";
 
      function __construct() {
         parent::__construct(); 
      }

 
      /** ----------------------------------------------------------------------------
       * Get all job details in job table join customer,cadastral and council table with specific job id
       *
       * @param  int    $jobId        Job id
       * @return object               Job details, Job cadastral details
       */
      public function get_job_details( $jobId ) {

         $sql = " SELECT *,
                     j.jobId AS jobId,
                     c.name AS customerName,
                     c.address1 AS customerAddress,
                     c.city AS customerCity,
                     c.region AS customerRegion,
                     c.postCode AS customerPostCode,
                     c.phone AS customerPhone,
                     c.email AS customerEmail,
                     concat(u.firstName,  ' ', u.surname) AS `manager`,
                     d.name AS departmentName,
                     jc.jobCustomFieldsId AS jobCustomFieldsId,
                     jc.budget AS jcbudget,
                     jc.purchaseOrderNo AS jcpurchaseOrderNo,
                     jc.crownAllotment AS jccrownAllotment,
                     jc.section AS jcsection,
                     jc.parish AS jcparish,
                     jc.area AS jcarea,
                     j.jobType AS jobType,
                     j.streetNo AS street,
                     j.streetName AS streetName,
                     j.suburb AS suburb,
                     j.easting AS easting,
                     j.northing AS northing,
                     j.zone AS zone,
                     j.tender AS tender,
                     j.received AS received,
                     j.start AS start,
                     j.finish AS finish,
                     j.postCode AS jobPostCode,
                     j.archived AS archived,
                     cc.name AS councilName,
                     j.parentJobId AS parentJobId,
                     j.jobReferenceNo AS jobReferenceNo,
                     j.jobName AS jobName,
                     j.archived AS archived,
                     child.jobReferenceNo AS childRef,
                     child.jobName AS childName
                  FROM time_job  AS j
                      LEFT JOIN time_job AS child ON j.parentJobId = child.jobId
                     INNER JOIN time_customer AS c ON j.customerId = c.customerId
                     LEFT JOIN time_user AS u ON j.userId = u.userId
                     LEFT JOIN time_department AS d ON j.departmentId = d.departmentId
                     LEFT JOIN time_job_customfields AS jc ON j.jobCustomFieldsId = jc.jobCustomFieldsId
                     LEFT JOIN time_cadastral AS tc ON j.cadastralId = tc.cadastralId
                     LEFT JOIN time_council AS cc ON tc.councilId = cc.councilId
                  WHERE j.jobId =  '" . $jobId . "' ";
         return $this->db->query( $sql )->row();
      } 


      /** ----------------------------------------------------------------------------
       * Get all child job details in job table based on parent Id
       *
       * @param  int    $parentId     Parent id
       * @return object               Child Job details
       */
      public function get_child_job_details( $parentId ) {

         $sql = " SELECT *,
                  j.jobReferenceNo AS jobReferenceNo,
                  j.jobName AS jobName,
                  j.jobId AS jobId,
                  child.jobReferenceNo AS childRef,
                  child.jobName AS childName
                  FROM time_job  AS j
                  LEFT JOIN time_job AS child ON j.parentJobId = child.jobId
                  WHERE j.parentJobId =  '" . $parentId . "' ";
         return $this->db->query( $sql )->result();
      }


      /** ----------------------------------------------------------------------------
      * Gets a list of jobs with the basic details
      *
      * Jobs may have children, we use a LEFT JOIN to get these
      * We exclude any jobs or child jobs that have been archived
      *
      * @return  array (of objects)          Job and child job details
      */
      public function get_list() {

         $sql = " SELECT j.jobId AS jobId,
                        j.jobReferenceNo AS jobReferenceNo,
                        j.jobName AS jobName,
                        child.jobId AS childJobId,
                        child.jobReferenceNo AS childReferenceNo,
                        child.jobName AS childJobName
                  FROM time_job AS j
                     LEFT JOIN time_job AS child ON j.jobId = child.parentJobId
                  WHERE j.parentJobId IS NULL
                     AND ( child.jobId IS NULL AND NOT j.archived )
                     OR ( child.jobId IS NOT NULL AND NOT j.archived AND NOT child.archived )
                  ORDER BY jobReferenceNo desc, childReferenceNo desc";
         $query = $this->db->query( $sql );
         return $query->result();
      }

      /** ----------------------------------------------------------------------------
      * Gets a list of jobs saved by the user
      *
      * Saved jobs are from a personal list that the user can drag onto their timesheets
      *
      * We exclude any jobs or child jobs that have been archived
      *
      * @return array           Task details
      */
      public function get_user_jobs( $userId ) {

         $sql = " SELECT userSavedJobId AS draggableId,
                        j.jobId AS jobId,
                        j.jobReferenceNo AS jobReferenceNo,
                        j.jobName AS jobName,
                        parent.jobId AS parentJobId,
                        parent.jobReferenceNo AS parentReferenceNo,
                        parent.jobName AS parentJobName
                  FROM time_job AS j
                     INNER JOIN time_user_savedjob AS usj ON j.jobId = usj.jobId
                     LEFT JOIN time_job AS parent ON j.parentJobId = parent.jobId
                  WHERE usj.userId = " . $userId;
         $sql .= " AND NOT j.archived
               ORDER BY jobReferenceNo desc";
         $query = $this->db->query( $sql );
         return $query->result_array();
      }


      /** ----------------------------------------------------------------------------
       * Get search job row(s) having the search word
       *
       * @param  bool      $joblist     Call from job section
       * @param  string    $query       Query string from adv search
       * @param  string    $searchItem  Search Word from basic search
       * @return object                 List of job details
       *
       */
      public function get_job_list_or_search( $joblist = TRUE, $archived, $searchItem, $query = null, $childQuery = null ) {
        
         $condition            = 1;
         $archivedQuery        = 1;
         $childCondition       = 1;
         $childArchivedQuery   = 1;

         if ( $joblist == FALSE ) { 
            if ( $query != null ) {
               $condition = $query;
               $childCondition = $childQuery;
            } else {
               $condition = "( c.name like '%" . $searchItem . "%' 
                        OR j.jobName like '%" . $searchItem . "%' 
                        OR j.jobReferenceNo like '%" . $searchItem . "%' OR  j.parentJobId IN  
                        (  SELECT jobId FROM time_job j 
                              INNER JOIN time_customer AS c ON j.customerId = c.customerId
                           WHERE
                              c.name like '%" . $searchItem . "%' 
                              OR j.jobName like '%" . $searchItem . "%' 
                              OR j.jobReferenceNo like '%" . $searchItem . "%' ) )";
               $childCondition = "( c.name like '%" . $searchItem . "%' 
                        OR child.jobName like '%" . $searchItem . "%' 
                        OR child.jobReferenceNo like '%" . $searchItem . "%' OR  child.parentJobId IN  
                        (  SELECT jobId FROM time_job child 
                              INNER JOIN time_customer AS c ON child.customerId = c.customerId
                           WHERE
                              c.name like '%" . $searchItem . "%' 
                              OR child.jobName like '%" . $searchItem . "%' 
                              OR child.jobReferenceNo like '%" . $searchItem . "%' ) )";
            }
         }

         if( $archived == 0 ) {
            $archivedQuery = "( j.archived = 0 )";
            $childArchivedQuery = "( child.archived = 0 )";
         }
             

         $sql = " SELECT jobs.*
                  FROM (
                     SELECT j.jobReferenceNo AS jobReferenceNo,
                        j.jobName AS jobName,
                        j.suburb AS suburb,
                        j.jobType AS jobType,
                        j.jobId AS jobId,
                        j.parentJobId AS parentJobId,
                        c.name AS name,
                        child.jobId AS cJobId,
                        child.jobType AS cjobType,
                        child.parentJobId AS childJobId,
                        child.jobReferenceNo AS childRef,
                        child.jobName AS childName
                     FROM time_job AS j
                        LEFT JOIN time_job AS child ON j.parentJobId = child.jobId
                        INNER JOIN time_customer AS c ON j.customerId = c.customerId
                        LEFT JOIN time_cadastral AS tc ON j.cadastralId = tc.cadastralId
                     WHERE j.parentJobId IS NULL AND " . $archivedQuery . " AND " . $condition . "
                     UNION ALL
                     SELECT j.jobReferenceNo AS jobReferenceNo,
                        j.jobName AS jobName,
                        j.suburb AS suburb,
                        j.jobType AS jobType,
                        j.jobId AS jobId,
                        j.parentJobId AS parentJobId,
                        c.name AS name,
                        child.jobId AS cJobId,
                        child.jobType AS cjobType,
                        child.parentJobId AS childJobId,
                        child.jobReferenceNo AS childRef,
                        child.jobName AS childName
                     FROM time_job AS child
                        INNER JOIN time_job AS j ON child.parentJobId = j.jobId
                        INNER JOIN time_customer AS c ON child.customerId = c.customerId
                        LEFT JOIN time_cadastral AS tc ON child.cadastralId = tc.cadastralId
                     WHERE " . $childArchivedQuery . " AND " . $childCondition . "
                     ) jobs
                     ORDER BY jobs.jobReferenceNo DESC, jobs.childRef ASC
                     ";
         return $this->db->query( $sql )->result();
      }
}