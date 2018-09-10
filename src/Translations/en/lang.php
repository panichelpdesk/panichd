<?php

return [

 /*
  *  Constants
  */

  'nav-new-tickets'                  => 'New',
  'nav-new-tickets-title'            => 'New tickets',
  'nav-new-dd-list'                  => 'List',
  'nav-new-dd-list-title'            => 'New tickets list',
  'nav-new-dd-create'                => 'Create',
  'nav-create-ticket'                => 'Create new',
  'nav-create-ticket-title'          => 'Create new ticket',
  'nav-notices-number-title'         => 'There are :num notices',
  'nav-active-tickets-title'         => 'Active tickets',
  'nav-completed-tickets-title'      => 'Completed tickets',

  // Regular expressions
  'regex-text-inline'                => '/^(?=.*[A-Za-z]+[\'\-¡!¿?\s,;.:]*)[a-zA-Z\'0-9¡!¿?,;.:\-\s]*$/',

  // Tables
  'table-id'                         => '#',
  'table-subject'                    => 'Subject',
  'table-department'                 => 'Department',
  'table-description'                => 'Description',
  'table-intervention'               => 'Intevention',
  'table-owner'                      => 'Owner',
  'table-status'                     => 'Status',
  'table-last-updated'               => 'Upd.',
  'table-priority'                   => 'Priority',
  'table-agent'                      => 'Agent',
  'table-calendar'                   => 'Calendar',
  'table-completed_at'               => 'Completed at',
  'table-category'                   => 'Category',
  'table-tags'                       => 'Tags',

  'no-tickets-yet'                   => 'No tickets yet', # Pending to move old admin.index-empty-records in other languages
  'list-no-tickets'                  => 'There are no tickets in this list',
  'table-info-attachments-total'     => ':num attached files',
  'table-info-comments-total'        => ':num Total comments.',
  'table-info-comments-recent'       => ':num recent ones.',
  'table-info-notes-total'           => ':num internal notes',

        'calendar-active'            => 'Started :description',
  'calendar-active-today'      => 'Started :description',
  'calendar-active-future'     => 'Starts :description',
  'calendar-expired'           => 'Expired :description',
  'calendar-expired-today'     => 'Expired today at :time',
  'calendar-expiration'        => 'Expires :description',
  'calendar-expires-today'     => 'Will expire today at :hour',
  'calendar-scheduled'         => 'Scheduled on :date from :time1 to :time2H',
  'calendar-scheduled-today'   => 'Scheduled today from :time1 to :time2H',
  'calendar-scheduled-period'  => 'Scheduled from :date1 to :date2',

  // Agent related
  'table-change-agent'               => 'Change agent',
  'table-one-agent'                  => 'There is one agent in this category',
  'table-agent-status-check'         => 'Change status to ":status"',

  // Priority related
  'table-change-priority'            => 'Change priority',

  // Datatables
  'table-decimal'                    => '',
  'table-empty'                      => 'No data available in table',
  'table-info'                       => 'Showing _START_ to _END_ of _TOTAL_ entries',
  'table-info-empty'                 => 'Showing 0 to 0 of 0 entries',
  'table-info-filtered'              => '(filtered from _MAX_ total entries)',
  'table-info-postfix'               => '',
  'table-thousands'                  => ',',
  'table-length-menu'                => 'Show _MENU_ entries',
  'table-loading-results'            => 'Loading...',
  'table-processing'                 => 'Processing...',
  'table-search'                     => 'Search:',
  'table-zero-records'               => 'No matching records found',
  'table-paginate-first'             => 'First',
  'table-paginate-last'              => 'Last',
  'table-paginate-next'              => 'Next',
  'table-paginate-prev'              => 'Previous',
  'table-aria-sort-asc'              => ': activate to sort column ascending',
  'table-aria-sort-desc'             => ': activate to sort column descending',

  'filter-removeall-title'           => 'Remove all filters',
  'filter-pov'                       => 'View as',
  'filter-pov-member-title'          => 'View as a member',
  'filter-pov-agent-title'           => 'View as an agent',
  'filter-year-all'                  => 'All',
  'filter-calendar'                  => 'Calendar',
  'filter-calendar-all'              => 'All',
  'filter-calendar-expired'          => 'Expired',
  'filter-calendar-not-scheduled'    => 'Unscheduled',
  'filter-calendar-today'            => 'Expires today',
  'filter-calendar-tomorrow'         => 'Expires tomorrow',
  'filter-calendar-week'             => 'This week',
  'filter-calendar-month'            => 'This month',
  'filter-calendar-within-7-days'    => 'In 7 days',
  'filter-calendar-within-14-days'   => 'In 14 days',
  'filter-category'                  => 'Category',
  'filter-category-all'              => 'All',
  'filter-owner-all'                 => 'All',
  'filter-agent'                     => 'Agent',
  'filter-agent-all'                 => 'All',

  'btn-add'                          => 'Add',
  'btn-back'                         => 'Back',
  'btn-cancel'                       => 'Cancel',
  'btn-change'                       => 'Change',
  'btn-close'                        => 'Close',
  'btn-delete'                       => 'Delete',
  'btn-download'                     => 'Download',
  'btn-edit'                         => 'Edit',
  'btn-mark-complete'                => 'Complete',
  'btn-submit'                       => 'Submit',


  // Vocabulary
  'active-tickets-adjective'         => 'Active',
  'agent'                            => 'Agent',
  'agents'                           => 'Agents',
  'all-depts'                        => 'All',
  'attached-images'                  => 'Attached images',
  'attached-files'                   => 'Attached files',
  'attachments'                      => 'Attachments',
  'category'                         => 'Category',
  'closing-reason'                   => 'Closing reason',
  'closing-clarifications'           => 'Clarifications',
  'colon'                            => ': ',
  'comments'                         => 'Comments',
  'complete'                         => 'Complete',
  'complete-tickets-adjective'       => 'Completed',
  'created'                          => 'Created',
  'creation-date'                    => 'Created at :date',
  'crop-image'                       => 'Image crop',
  'date-format'                      => 'Y-m-d',
  'datetime-format'                  => 'Y-m-d H:i',
  'datetimepicker-format'            => 'YYYY-MM-DD HH:mm',
  'datetime-text'                    => ':date, :timeh',
  'deleted-member'                   => 'Deleted member',
  'department'                       => 'Department',
  'department-shortening'            => 'Dept.',
  'dept-descendant'                  => 'Subdepartment',
  'description'                      => 'Description',
  'discard'                          => 'Discard',
  'email'                            => 'E-mail',
  'email-resend-abbr'                => 'FW',
  'flash-x'                          => '×', // &times;
  'intervention'                     => 'Intervention',
  'last-update'                      => 'Last Update',
  'limit-date'                       => 'Limit date',
  'list'                             => 'List',
  'mark-complete'                    => 'Mark Complete',
  'member'                           => 'Member',
  'name'                             => 'Name',
  'newest-tickets-adjective'         => 'New',
  'no'                               => 'No',
  'no-replies'                       => 'No replies.',
  'owner'                            => 'Owner',
  'priority'                         => 'Priority',
  'reopen-ticket'                    => 'Reopen Ticket',
  'reply'                            => 'Reply',
  'responsible'                      => 'Responsible',
  'start-date'                       => 'Start date',
  'status'                           => 'Status',
  'subject'                          => 'Subject',
  'tags'                             => 'Tags',
  'ticket'                           => 'Ticket',
  'tickets'                          => 'Tickets',
  'today'                            => 'Today',
  'tomorrow'                         => 'Tomorrow',
  'update'                           => 'Update',
  'updated-date'                     => 'Updated :date',
  'user'                             => 'User',
  'year'                             => 'Year',
  'yes'                              => 'Yes',
  'yesterday'                        => 'Yesterday',

  // Days of week
  'day_1'                            => 'Monday',
  'day_2'                            => 'Tuesday',
  'day_3'                            => 'Wednesday',
  'day_4'                            => 'Thursday',
  'day_5'                            => 'Friday',
  'day_6'                            => 'Saturday',
  'day_7'                            => 'Sunday',
  'day_0'                            => 'Sunday',

    // Time units abbreviations
  'second-abbr'                      => 's.',
  'minute-abbr'                      => 'mi.',
  'hour-abbr'                        => 'h.',
  'day-abbr'                         => 'd.',
  'week-abbr'                        => 'wk.',
  'month-abbr'                       => 'mo.',


 /*
  *  Page specific
  */

// ____
  'index-title'                      => 'Helpdesk main page',

// tickets/____
  'index-my-tickets'                 => 'My Tickets',

  'btn-create-new-ticket'            => 'Create new',
  'index-complete-none'              => 'There are no complete tickets',
  'index-active-check'               => 'Be sure to check Active Tickets if you cannot find your ticket.',
  'index-active-none'                => 'There are no active tickets,',
  'index-create-new-ticket'          => 'create new ticket',
  'index-complete-check'             => 'Be sure to check Complete Tickets if you cannot find your ticket.',
  'ticket-notices-title'             => 'Notices',
  'ticket-notices-empty'             => 'There are no active notices',

// Newest tickets page reload Modal
  'page-reload-modal-title'          => 'Page reload',
  'page-reload-modal-countdown'      => 'This page will reload in <kbd><span id="counter">:num</span></kbd> seconds.',
  'page-reload-modal-reloading'      => 'This page is reloading... please wait',
  'page-reload-modal-button-now'     => 'Reload now!',

// Ticket forms messages
  'update-agent-same'                => 'Agent was not changed! Ticket <a href=":link" title=":title"><u>:name</u></a>',
  'update-agent-ok'                  => 'Agent was correctly updated. Ticket <a href=":link" title=":title"><u>:name</u></a>: :old_agent -> :new_agent',
  'update-priority-same'             => 'Priority was not changed! Ticket <a href=":link" title=":title"><u>:name</u></a>',
  'update-priority-ok'               => 'Priority was correctly updated. Ticket <a href=":link" title=":title"><u>:name</u></a>: :old -> :new',

// tickets/create
  'create-ticket-title'              => 'New Ticket Form',
  'create-new-ticket'                => 'Create New Ticket',
  'create-ticket-brief-issue'        => 'A brief of your issue ticket',
  'create-ticket-notices'            => 'Notices',
  'ticket-owner-deleted-warning'     => 'User is deleted. It won\'t appear in owner edition list',
  'ticket-owner-no-email'            => '(Has not e-mail)',
  'ticket-owner-no-email-warning'    => 'User has not e-mail: Will not receive any e-mail notification',
  'create-ticket-owner-help'         => 'You may choose from whom is the ticket or who does it affect',
  'create-ticket-visible'            => 'Visible',
  'create-ticket-visible-help'       => 'Choose ticket visibility for the assigned owner',
  'create-ticket-change-list'        => 'List change',
  'create-ticket-info-start-date'    => 'Default: Now',
  'create-ticket-info-limit-date'    => 'Default: No limit',
  'create-ticket-describe-issue'     => 'Describe your issue here in details',
  'create-ticket-intervention-help'  => 'Taken actions for ticket resolution',
  'attach-files'                     => 'Attach files',
  'pending-attachment'               => 'This file will be uploaded when the ticket is updated',
  'attachment-new-name'              => 'New name',

  'edit-ticket'                      => 'Edit Ticket',
  'attachment-edit'                  => 'Edit attachment',
  'attachment-edit-original-filename'=> 'Original filename',
  'attachment-edit-new-filename'     => 'New filename',
  'attachment-edit-crop-info'        => 'Select an area inside the image to crop it. It will be applied after the attachment fields are updated',

  'attachment-update-not-valid-name' => 'The new filename for ":file" must be at least 3 character long. HTML is not allowed',
  'attachment-error-equal-name'      => 'Name and description for file ":file" can\'t be the same',
  'attachment-update-not-valid-mime' => 'The file ":file" is not of any valid type',
  'attachment-update-crop-error'     => 'Image could not be cropped at specified sizes',

  'show-ticket-title'                => 'Ticket',
  'show-ticket-creator'              => 'Created by',
  'show-ticket-js-delete'            => 'Are you sure you want to delete: ',
  'show-ticket-modal-delete-title'   => 'Delete Ticket',
  'show-ticket-modal-delete-message' => 'Are you sure you want to delete ticket: :subject?',
  'show-ticket-modal-edit-fields'    => 'Edit more fields',

  'show-ticket-modal-complete-blank-intervention-check' => 'Leave blank intervention',
  'show-ticket-complete-blank-intervention-alert'       => 'To complete the ticket you must confirm that you leave intervention field blank',
  'show-ticket-modal-complete-blank-reason-alert'       => 'To complete the ticket you must indicate a closing reason',
  'show-ticket-complete-bad-status'                     => 'Ticket not completed: The specified status is not valid',
  'show-ticket-complete-bad-reason-id'                  => 'Ticket not completed: The specified reason is not valid',

  'complete-by-user'                 => 'Ticket completed by :user.',
  'reopened-by-user'                 => 'Ticket reopened by :user.',

  'ticket-error-not-valid-file'      => 'A no valid file was attached',
  'ticket-error-not-valid-object'    => 'This file can\'t be processed: :name',
  'ticket-error-max-size-reached'    => 'The file ":name" and following can\'t be attached as they exceed the max available space for this ticket, which is of :available_MB MB',
  'ticket-error-max-attachments-count-reached' => 'The file ":name" and following can\'t be attached as they exceed the max number of :max_count attached files per ticket',
  'ticket-error-delete-files'        => 'Some files could not be deleted',
  'ticket-error-file-not-found'      => 'The file ":name" could not be found',
  'ticket-error-file-not-deleted'    => 'The file ":name" could not be deleted',

   // Tiquet visible / no visible
  'ticket-visible'             => 'Visible ticket',
  'ticket-hidden'              => 'Hidden ticket',
  'ticket-hidden-button-title' => 'Switch user visibility',
  'ticket-visibility-changed'  => 'Ticket visibility has changed',
  'ticket-hidden-0-comment-title' => 'Changed to visible by <b>:agent</b>',
  'ticket-hidden-0-comment'    => 'Ticket is now <b>visible</b> for owner',
  'ticket-hidden-1-comment-title' => 'Hided by <b>:agent</b>',
  'ticket-hidden-1-comment'    => 'Ticket is now <b>hidden</b> for owner',

  // Comments
  'comment-reply-title'        => 'Messages between owner and agents',
  'comment-reply-from-owner'   => 'Reply from <b>:owner</b>',
  'reply-from-owner-to-owner'  => 'Reply from <b>:owner1</b> to <b>:owner2</b>',

  'comment-note-title'         => 'User hidden note',
  'comment-note-from-agent'    => 'Note from <b>:agent</b>',

  'comment-completetx-title'   => 'Ticket complete',
  'comment-complete-by'        => 'Tancat per <b>:owner</b>',

  'comment-reopen-title'       => 'Ticket reopened',
  'comment-reopen-by'          => 'Reopened by <b>:owner</b>',

  'show-ticket-add-comment'                => 'Add comment',
  'show-ticket-add-note'                   => 'Add internal note',
  'show-ticket-add-comment-type'           => 'Type',
  'show-ticket-add-comment-note'           => 'Internal note',
  'show-ticket-add-comment-reply'          => 'Reply to user',
  'show-ticket-add-com-check-email-text'   => 'Add text in the user notification',
  'show-ticket-add-com-check-intervention' => 'Append this text in intervention field (visible by user)',
  'show-ticket-add-com-check-resolve'      => 'Complete this ticket and apply the status',
  'add-comment-confirm-blank-intervention' => 'The "intervention" field is empty. Would you want to close ticket anyway?',

  'edit-internal-note-title'         => 'Edit internal note',
  'show-ticket-edit-com-check-int'   => 'Add text to the intervention field',
  'show-ticket-delete-comment'       => 'Delete comment',
  'show-ticket-delete-comment-msg'   => 'Are you sure you want to delete this comment?',
  'show-ticket-email-resend'         => 'Resend email',
  'show-ticket-email-resend-user'    => 'To user: ',
  'show-ticket-email-resend-agent'   => 'To agent: ',

  // Validations
  'validation-error'                 => 'This form has not been sent',
  'validate-ticket-subject.required' => 'A subject must be set. Please, point out in a few words what is it about',
  'validate-ticket-subject.min'      => 'The subject must be longer',
  'validate-ticket-content.required' => 'The description must be set. If you attach any image you\'d need to add a description text anyway',
  'validate-ticket-content.min'      => 'The description must be longer, although there is any attached image',
  'validate-ticket-start_date-format'=> 'The start date format is not valid. Correct is: ":format"',
  'validate-ticket-start_date'       => 'Start date year is not valid',
  'validate-ticket-limit_date-format'=> 'The limit date format is not valid. Correct is: ":format"',
  'validate-ticket-limit_date'       => 'Limit date year is not valid',
  'validate-ticket-limit_date-lower' => 'Limit date cannot be lower than start date',

  'ticket-destroy-error'             => 'The ticket could not be deleted: :error',
  'comment-destroy-error'            => 'The comment could not be deleted: :error',

 // Comment form
  'validate-comment-required'        => 'You must type the comment text',
  'validate-comment-min'             => 'You must type a longer text for the comment',


 /*
  *  Controllers
  */

// AdministratorsController
  'administrators-are-added-to-administrators'      => 'Administrators :names are added to administrators',
  'administrators-is-removed-from-team'             => 'Removed administrator\s :name from the administrators team',

// CategoriesController
  'category-name-has-been-created'   => 'The category :name has been created!',
  'category-name-has-been-modified'  => 'The category :name has been modified!',
  'category-name-has-been-deleted'   => 'The category :name has been deleted!',

// PrioritiesController
  'priority-name-has-been-created'   => 'The priority :name has been created!',
  'priority-name-has-been-modified'  => 'The priority :name has been modified!',
  'priority-name-has-been-deleted'   => 'The priority :name has been deleted!',
  'priority-all-tickets-here'        => 'All priority related tickets here',

// StatusesController
  'status-name-has-been-created'   => 'The status :name has been created!',
  'status-name-has-been-modified'  => 'The status :name has been modified!',
  'status-name-has-been-deleted'   => 'The status :name has been deleted!',
  'status-all-tickets-here'        => 'All status related tickets here',

// CommentsController
  'comment-has-been-added-ok'        => 'Comment has been added successfully',
  'comment-has-been-updated'         => 'Comment has been updated',
  'comment-has-been-deleted'         => 'Comment has been deleted',

// NotificationsController
  // E-mail translations located at email/globals.php

 // TicketsController
  'the-ticket-has-been-created'      => 'The ticket has been created! <a href=":link" title=":title"><u>:name</u></a>',
  'the-ticket-has-been-modified'     => 'The ticket has been modified!',
  'the-ticket-has-been-deleted'      => 'The ticket :name has been deleted!',
  'the-ticket-has-been-completed'    => 'The ticket has been completed! <a href=":link" title=":title"><u>:name</u></a>',
  'the-ticket-has-been-reopened'     => 'The ticket :name has been reopened! <a href=":link" title=":title"><u>:name</u></a>',
  'ticket-status-link-title'         => 'View ticket',

  'you-are-not-permitted-to-do-this' => 'You are not permitted to do this action!',

 /*
 *  Middlewares
 */

 // EnvironmentReadyMiddleware
 'environment-not-ready'                 => 'Administrator has not finished the required configuration to let tickets be created',

 //  IsAdminMiddleware IsAgentMiddleware UserAccessMiddleware
  'you-are-not-permitted-to-access'     => 'You are not permitted to access this page!',

];
