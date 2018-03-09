<?php
/**
 *  Checklist Helper
 *
 * @author      John Gifter C Poja <gifter@gmail.com>
 * @copyright   Copyright (c) 2016 Genesis Software. <https://genesis.com>
 * @license     Commercial
 * @version     Release: 1.0.0
 * @since       File available since Release 1.0.0
 */

 
/** ----------------------------------------------------------------------------
 * Creates the HTML to display checklists
 *
 * @param   array    $checklistResults    Checklist details from database
 * @param   int      $jobId               The job id if printing checklists for a job
 * @return  string                        The HTML to use when displaying the checklists
 */
function print_checklist_forms( $checklistResults, $jobId = null ) {

   $newLine       = "\n";
   $count         = 0;        // Foreach loop counter
   $checklistId   = '';       // Used to check if we have changed to a new checklist

   // Closes the 2 divs of a checklist item
   $closeChecklistItem  = '               </div>' . $newLine
                         .'            </div>' . $newLine;
   // Closes the bootstrap row, checklist form container and the unordered list item
   $closeListItem       = '         </div>' . $newLine . $newLine
                         .'      </div>' . $newLine
                         .'   </li>' . $newLine;


   // Start: Checklist unordered list HTML
   $checklistHTML = '<ul class="list-unstyled sortable ui-sortable">' . $newLine;

   foreach ( $checklistResults as $row ) {

      // Print the title for any new checklists
      $checklistHTML .= _create_checklist_title( $jobId, $row, $checklistId, $count, $closeListItem );

      // Start a new row if needed (exclude the first item)
      if ( ( $row['newRow'] ) and ( $count != 0 ) ) {
         $checklistHTML .= '         </div>' . $newLine . $newLine
                          .'         <div class="row">' . $newLine;
      }

      // Start: Print checklist item
      // Open the containers with correct grid width
      $checklistHTML .= '            <div class="' . $row['fieldWidth'] . ' checklist-item-cell">' . $newLine
                       .'               <div class="form-group' . _print_type_class( $row['fieldType'] ) . '">' . $newLine;

      $itemId = $row['id'];
      $value  = '';

      // Add the HTML for the appropriate field types
      switch ( $row['fieldType'] ) {

         // Add the HTML for a date input field
         case 'Date':
            // Set Date value
            if ( isset( $row['dateData'] ) ) {
               list ( $year, $month, $day ) = explode( '-', $row['dateData'] );
               $value = ( $day . '/' . $month . '/' . $year );
            }

            $fieldId    = 'date-field-' . $itemId;
            $inputField = '<input type="text" name="' . $fieldId . '" id="' . $fieldId . '" data-id="' . $itemId . '"  data-url="' . ROOT_RELATIVE_PATH . 'job/create_job_checklistdata" class="form-control input-sm datepickers checklist-date-input" value="' . $value . '" readonly >';

            $checklistHTML .= _create_input_html( $fieldId, $inputField, $row['fieldTitle'], $row['fieldType'] );

            break;

         // Add the HTML for a checkbox input field
         case 'Checkbox':
            // Set Checkbox value
            if ( $row['checkboxData'] ) {
               $value = ' checked';
            }

            $checklistHTML .= '                  <div class="checkbox-custom checkbox-default">' . $newLine
                             .'                     <input type="checkbox" name="check-box-' . $itemId . '" id="check-box-' . $itemId . '" data-id="' . $itemId . '" data-url="' . ROOT_RELATIVE_PATH . 'job/create_job_checklistdata" class="checklist-checkbox-input"' . $value . '>' . $newLine
                             .'                     <label class="checkbox-title" for="check-box-' . $itemId . '">' . $newLine
                             .'                        ' . $row['fieldTitle'] . $newLine
                             .'                     </label>' . $newLine
                             .'                  </div>' . $newLine;
            break;

         // Add the HTML for a text input field
         case 'Input':
            // Set Text value
            $value = $row['textData'];

            $fieldId    = 'text-field-' . $itemId;
            $inputField = '<input type="text" name="' . $fieldId . '" id="' . $fieldId . '" data-id="' . $itemId . '" data-url="' . ROOT_RELATIVE_PATH . 'job/create_job_checklistdata" class="form-control input-sm checklist-text-input" value="' . $value . '">';

            $checklistHTML .= _create_input_html( $fieldId, $inputField, $row['fieldTitle'], $row['fieldType'] );

            break;
      }

      $checklistHTML .= $closeChecklistItem;
      // End: Print checklist item


      // Update for comparision
      $checklistId = $row['checklistId'];
      $count++;
   }

   // Close the final row, form container, list item and the unordered list
   $checklistHTML .= $closeListItem . '</ul>';
   // End: Checklist unordered list HTML

   // If we had no checklist items, don't return any html
   if ( $count == 0 ) { $checklistHTML = ''; }
   return $checklistHTML;

}


/** ----------------------------------------------------------------------------
* Prints a CSS class based on the field type.
* Currently only a class for checkboxes is used
*
* @param   string   $fieldtype    The field type
* @return  string                 The CSS class
*/
function _print_type_class( $fieldType ) {
   if ( $fieldType == 'Checkbox' ) {
      return ' checkbox-group';
   }
}


/** ----------------------------------------------------------------------------
* Creates the HTML for a checklist title
*
* It will also start the form container and close previous ones if needed
*
* @param   string    $jobId               The job id
* @param   array     $row                 Array with database values for this row
* @param   string    $checklistId         Previous checklist id, used to compare with the current id
* @param   int       $count               Counter with how many checklist items it has cycled through
* @param   string    $closeListItem       HTML that will close the 2 divs of the form container and bootstrap row
* @return  string                         The HTML to display the title of a checklist
*/
function _create_checklist_title( $jobId, $row, $checklistId, $count, $closeListItem ) {

   $newLine     = "\n";
   $headingHTML = '';

   if ( $checklistId != $row['checklistId'] ) {

      // Close a previous list item if we are printing multiple checklists
      if ( $count != 0 ) { $headingHTML .= $closeListItem; }

      $headingHTML .= '   <li data-url="' . ROOT_RELATIVE_PATH . 'job/" data-job-id="' . $jobId . '" data-error="' . lang( 'job_checklist_notif_sort_error' ) . '" data-error-notif="' . lang( 'system_notif_error' ) . '" class="p-sm">' . $newLine
         .'      <div class="checklist-form-title">' . $newLine
         .'         ' . $row['title'] . $newLine;

      // Add delete and reorder controls if it is printed out on a job
      if ( $jobId != null ) {
         $headingHTML .= '         <a class="delete-job-checklist text-default pull-right cursor checklist-control" data-row-id="' . $row['jobChecklistId'] . '" data-id="' . $row['checklistId'] . '" data-order="' . $row['jobChecklistDisplayOrder'] . '" data-job-id="' . $jobId . '"  data-container="body">' . $newLine
            .'            <i class="fa fa-times" aria-hidden="true"></i>' . $newLine
            .'         </a>' . $newLine
            .'         <a>' . $newLine
            .'            <i class="fa fa-bars handle pull-right mt-xs mr-xs ui-sortable-handle checklist-control cursor-move" role="button" aria-hidden="true"></i>' . $newLine
            .'         </a>'. $newLine;
      }

      // Close the heading and open container for the form
      $headingHTML .= '      </div>' . $newLine
         .'      <div class="checklist-form-container">' . $newLine . $newLine
         .'         <div class="row">' . $newLine;
   }

   return $headingHTML;
}


/** ----------------------------------------------------------------------------
* Creates the HTML for a checklist input field
*
* Adds a control to quickly select today on date fields
*
* @param   string    $fieldId             Unique id for the field
* @param   string    $inputField          HTML of the <input> element to be used
* @param   string    $fieldTitle          Title for the field
* @param   string    $readonly            Will mark the field as readonly if needed
* @return  string                         The HTML to display the input field
*/
function _create_input_html( $fieldId, $inputField, $fieldTitle, $fieldType ) {

   $newLine = "\n";

   // If there is no field title and no controls, we don't display as an input group
   $inputGroupOpen  = '';
   $inputGroupClose = '';
   if ( ( $fieldTitle != '' ) or ( $fieldType == 'Date' ) ) {
      $inputGroupOpen  = '                     <div class="input-group">' . $newLine;
      $inputGroupClose = '                     </div>' . $newLine;
   }

   // Create the HTML to display the field title in the input group
   $fieldTitleHTML = '';
   if ( $fieldTitle != '' ) {
      $fieldTitleHTML = '                        <span class="input-group-addon input-group-addon-title">' . $newLine
                       .'                           <label class="control-label" for="' . $fieldId . '">' . $fieldTitle . '</label>' . $newLine
                       .'                        </span>' . $newLine;
   }

   // Create the HTML for the button to select today's date if it's a date field
   $todayControl = '';
   if ( $fieldType == 'Date' ) {
      $todayControl = '                        <span class="input-group-btn">' . $newLine
                      .'                           <button class="btn btn-default input-sm input-btn-sm checklist-control select-today" data-field-id="' . $fieldId . '" type="button"><i class="fa fa-calendar"></i></button>' . $newLine
                      .'                        </span>' . $newLine;
   }

   // Put the required HTML together
   $inputHTML = '                  <div class="col-md-12 p-none">' . $newLine
               . $inputGroupOpen
               . $fieldTitleHTML
               .'                        ' . $inputField . $newLine
               . $todayControl
               . $inputGroupClose
               .'                  </div>' . $newLine;

   return $inputHTML;
}
?>