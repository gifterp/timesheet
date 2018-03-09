<?php
$lang['report_name']                            = 'Reports';

// WIP report form
$lang['report_wip_name']                        = 'WIP Report';
$lang['report_wip_section_title']               = 'Work in Progress Report';
$lang['report_wip_fm_pnl_title']                = 'Generate a work in progress report';
$lang['report_wip_fm_pnl_subtitle']             = 'The WIP report allows you to prepare work for invoicing';
$lang['report_wip_fm_lbl_date']                 = 'Date range';
$lang['report_wip_fm_lbl_date_range']           = 'to';
$lang['report_wip_fm_lbl_job']                  = 'Select jobs';
$lang['report_wip_fm_ph_job']                   = 'Show all jobs';
$lang['report_wip_fm_lbl_user']                 = 'Select staff';
$lang['report_wip_fm_ph_staff']                 = 'Show all staff';
$lang['report_wip_fm_lbl_sort']                 = 'Sort order';
$lang['report_wip_fm_sort_options']             = array( 'entries.startDateTime, entries.name, entries.taskGroup, entries.taskName' => 'Date, Staff, Task',
                                                         'entries.startDateTime, entries.taskGroup, entries.taskName, entries.name' => 'Date, Task, Staff',
                                                         'entries.name, entries.startDateTime, entries.taskGroup, entries.taskName' => 'Staff, Date, Task',
                                                         'entries.name, entries.taskGroup, entries.taskName, entries.startDateTime' => 'Staff, Task, Date',
                                                         'entries.taskGroup, entries.taskName, entries.startDateTime, entries.name' => 'Task, Date, Staff',
                                                         'entries.taskGroup, entries.taskName, entries.name, entries.startDateTime' => 'Task, Staff, Date'  );
$lang['report_wip_fm_lbl_filter']               = 'Filter';
$lang['report_wip_fm_filter_options']           = array( 'current' => 'Current Entries',
                                                         'archived' => 'Archived entries',
                                                         'written-off' => 'Written off entries' );

// Mock invoice panel
$lang['report_minv_fm_pnl_title']               = 'Ready for invoicing';
$lang['report_minv_fm_pnl_subtitle']            = 'Mock invoices ready to be processed';

// WIP report page
$lang['report_wip_fm_sdbr_title']               = 'Report form';
$lang['report_wip_fm_sdbr_subtitle']            = 'You ran the report with these parameters';
$lang['report_wip_ttip_archived_col']           = 'Archive items once they have been invoiced';
$lang['report_wip_ttip_written_off_col']        = 'Write off items that are not to be invoiced';
$lang['report_wip_ttip_invoice_col']            = 'Select items below and use the add to invoice button to add to a mock invoice';
$lang['report_wip_notif_archive_success']       = 'The entry has been archived';
$lang['report_wip_notif_archive_error']         = 'There was a problem archiving the entry';
$lang['report_wip_notif_unarchive_success']     = 'The entry is no longer archived';
$lang['report_wip_notif_unarchive_error']       = 'There was a problem restoring the entry from the archive';
$lang['report_wip_notif_wo_success']            = 'The entry has been written off';
$lang['report_wip_notif_wo_error']              = 'There was a problem writing off the entry';
$lang['report_wip_notif_restore_wo_success']    = 'The entry is no longer written off';
$lang['report_wip_notif_restore_wo_error']      = 'There was a problem restoring the entry from being written off';
$lang['report_wip_notif_job_totals_error']      = 'There was a problem creating the job totals';
$lang['report_wip_notif_add_invoice_success']   = 'Items were successfully added to a mock invoice';
$lang['report_wip_notif_add_invoice_error']     = 'There was a problem adding the items to a mock invoice';
$lang['report_wip_notif_add_invoice_none']      = 'You must select at least one item to add to the invoice';
$lang['report_wip_notif_upd_invoice_error']     = 'There was a problem updating the invoice data for the job';

// Mock invoice report page
$lang['report_minv_name']                       = 'Mock Invoice Report';
$lang['report_minv_section_title']              = 'Mock Invoice Report';
$lang['report_minv_add_invoice_pnl_title']      = 'Add invoice details';
$lang['report_minv_mock_invoice_pnl_title']     = 'Mock invoice #<span id="invoice-number"></span>';
$lang['report_minv_empty']                      = '<b>Please enter details for the invoice below</b>';
$lang['report_minv_ctrl_pnl_title']             = 'Report controls';
$lang['report_minv_ttip_copy_description']      = 'Copy this entry comment to the invoice description field';
$lang['report_minv_ttip_remove_col']            = 'Remove items from the invoice';
$lang['report_minv_ttip_remove_item']           = 'Remove this item from the invoice';
$lang['report_minv_ttip_remove_task_items']     = 'Remove this task and all items from the invoice';
$lang['report_minv_notif_control_error']        = 'There was a problem loading the report control settings';
$lang['report_minv_notif_delete_row_success']   = 'The details were deleted from the invoice';
$lang['report_minv_notif_delete_row_error']     = 'There was a problem deleting the details';
$lang['report_minv_notif_add_row_success']      = 'A new row was added';
$lang['report_minv_notif_add_row_error']        = 'There was a problem adding a new row';
$lang['report_minv_notif_add_row_already']      = 'There is already an empty row<br>A new one was not created';
$lang['report_minv_notif_delete_item_success']  = 'The entry was removed from the invoice';
$lang['report_minv_notif_delete_item_error']    = 'There was a problem deleting the entry';
$lang['report_minv_notif_upd_category_success'] = 'The category was updated';
$lang['report_minv_notif_upd_category_error']   = 'There was a problem updating the category';
$lang['report_minv_notif_upd_desc_success']     = 'The description was updated';
$lang['report_minv_notif_upd_desc_error']       = 'There was a problem updating the description';
$lang['report_minv_notif_upd_amount_success']   = 'The amount was updated';
$lang['report_minv_notif_upd_amount_error']     = 'There was a problem updating the amount';
$lang['report_minv_notif_display_error']        = 'There was a problem displaying the mock invoice details';
$lang['report_minv_notif_sort_order_error']     = 'There was a problem updating the sort order';
$lang['report_minv_notif_entries_error']        = 'There was a problem displaying the entries for the mock invoice';

// Mock invoice details form
$lang['report_minv_fm_category_ph']             = 'No category chosen';
$lang['report_minv_fm_description_ph']          = 'Add a description';
$lang['report_minv_fm_amount_ph']               = 'Add amount';
$lang['report_minv_details_load_notif_error']   = 'The mock invoice content could not be loaded';
$lang['report_minv_fm_load_notif_error']        = 'The form to add invoice details could not be loaded';

// Mock invoice report control panel
$lang['report_minv_fm_ready_lbl']               = 'Ready for invoicing?';
$lang['report_minv_fm_ready_ttip']              = 'Invoices that are ready will be shown as waiting for entry into your accounting software';
$lang['report_minv_fm_complete_lbl']            = 'Completed?';
$lang['report_minv_fm_complete_ttip']           = 'Mark an invoice as complete after it has been entered into the system. All items from the invoice will be archived automatically.';
$lang['report_minv_fm_action_lbl']              = 'Actions';
$lang['report_minv_fm_open_all_btn']            = 'Open all';
$lang['report_minv_fm_close_all_btn']           = 'Close all';
$lang['report_minv_fm_delete_btn']              = 'Delete invoice';
$lang['report_minv_fm_close_btn']               = 'Close invoice';
$lang['report_minv_notif_ready_success']        = 'Ready to invoice setting was successfully updated';
$lang['report_minv_notif_ready_error']          = 'There was a problem changing the ready to invoice setting';
$lang['report_minv_notif_complete_success']     = 'Completed setting was successfully updated';
$lang['report_minv_notif_complete_error']       = 'There was a problem changing the completed setting';
$lang['report_minv_notif_delete_success']       = 'This invoice was successfully deleted<br><br>This window will close in 2 seconds';
$lang['report_minv_notif_delete_error']         = 'There was a problem deleting the invoice';

// Report table headings
$lang['report_th_minv_total']                   = 'Invoice Total (Calc)';
$lang['report_th_task']                         = 'Task';
$lang['report_th_description']                  = 'Description';
$lang['report_th_comments']                     = 'Comments';
$lang['report_th_hrs']                          = 'Hrs';
$lang['report_th_total']                        = 'Total';
$lang['report_th_total_calc']                   = 'Total (Calc)';
$lang['report_th_total_adj']                    = 'Total (Adj)';
$lang['report_th_user']                         = 'Staff';
$lang['report_th_date']                         = 'Date';
$lang['report_th_actions']                      = 'Actions';
$lang['report_th_category']                     = 'Category';
$lang['report_th_amount']                       = 'Amount';

// Report buttons
$lang['report_btn_print_report']                = '<i class="fa fa-print mr-xs"></i>Print Report';
$lang['report_btn_collapse_expand']             = 'Collapse/expand';
$lang['report_btn_show_entries']                = 'Show entries';
$lang['report_btn_hide_entries']                = 'Hide entries';