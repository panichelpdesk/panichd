<?php

return [

 /*
  *  Constants
  */

  'nav-active-tickets'               => 'Active Tickets',
  'nav-completed-tickets'            => 'Completed Tickets',
  
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
  'table-last-updated'               => 'Last Updated',
  'table-priority'                   => 'Priority',
  'table-agent'                      => 'Agent',
  'table-calendar'                   => 'Calendar',
  'table-category'                   => 'Category',
  'table-tags'                       => 'Tags',

  'calendar-active'            => 'Start date',
  'calendar-expired'           => 'Expired since this date',
  'calendar-expiration'        => 'Expires this date',
  'calendar-expires-today'     => 'Expires today',
  'calendar-scheduled'         => 'Scheduled for this date',
  
  // Agent related
  'table-change-agent'               => 'Change agent',
  'table-one-agent'                  => 'There is one agent in this category',
  
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
  
  'filter-pov'                       => 'View as',
  'filter-calendar'                  => 'Calendar',
  'filter-calendar-all'              => 'All',
  'filter-calendar-expired'          => 'Expired',
  'filter-calendar-today'            => 'Expires today',
  'filter-calendar-tomorrow'         => 'Expires tomorrow',
  'filter-calendar-week'             => 'This week',
  'filter-calendar-month'            => 'Aquest mes',
  'filter-category'                  => 'Category',
  'filter-category-all'              => 'All',
  'filter-agent'                     => 'Agent',
  'filter-agent-all'                 => 'All',
  'filter-on-total'                  => 'Count depends on active filters',
  'filter-off-total'                 => 'Total total',

  'btn-back'                         => 'Back',
  'btn-cancel'                       => 'Cancel',
  'btn-change'                       => 'Change',
  'btn-close'                        => 'Close',
  'btn-delete'                       => 'Delete',
  'btn-download'                     => 'Download',
  'btn-edit'                         => 'Edit',
  'btn-mark-complete'                => 'Mark Complete',
  'btn-submit'                       => 'Submit',
  

  // Vocabulary  
  'agent'                            => 'Agent',
  'agents'                           => 'Agents',
  'all-depts'                        => 'All',
  'attachments'                      => 'Attachments',
  'category'                         => 'Category',  
  'closing-reason'                   => 'Closing reason',
  'closing-clarifications'           => 'Clarifications',
  'colon'                            => ': ',
  'comments'                         => 'Comments',
  'created'                          => 'Created',
  'date-info-created'                => 'Creation date',
  'date-info-updated'                => 'Last update date',
  'department'                       => 'Department',
  'department-shortening'            => 'Dept.',
  'dept_sub1'                        => 'Subdepartment',
  'description'                      => 'Description',
  'flash-x'                          => '×', // &times;
  'intervention'                     => 'Intervention',
  'last-update'                      => 'Last Update',
  'limit-date'                       => 'Limit date',
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
  'today'                            => 'Today',
  'tomorrow'                         => 'Tomorrow',
  'user'                             => 'User',
  'yesterday'                        => 'Ahir',
  
  // Days of week
  'day_1'                            => 'Monday',
  'day_2'                            => 'Tuesday',
  'day_3'                            => 'Wednesday',
  'day_4'                            => 'Thursday',
  'day_5'                            => 'Friday',
  'day_6'                            => 'Saturday',
  'day_7'                            => 'Sunday',
  

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

  'ticket-notices-title'             => 'Active notices',  

  'create-ticket-title'              => 'New Ticket Form',
  'create-new-ticket'                => 'Create New Ticket',
  'create-ticket-owner'              => 'Sender',
  'create-ticket-notices'            => 'Notices',
  'create-ticket-owner-help'         => 'You may choose from whom is the ticket or who does it affect',
  'create-ticket-brief-issue'        => 'A brief of your issue ticket',
  'create-ticket-info-start-date'    => 'Default: Now',
  'create-ticket-info-limit-date'    => 'Default: No limit',
  'create-ticket-describe-issue'     => 'Describe your issue here in details',
  
  'edit-ticket'                      => 'Edit Ticket',

  'show-ticket-title'                => 'Ticket',
  'show-ticket-creator'              => 'Created by',
  'show-ticket-js-delete'            => 'Are you sure you want to delete: ',
  'show-ticket-modal-delete-title'   => 'Delete Ticket',
  'show-ticket-modal-delete-message' => 'Are you sure you want to delete ticket: :subject?',
  
  'show-ticket-modal-complete-blank-intervention-check' => 'Leave blank intervention',
  'show-ticket-complete-blank-intervention-alert'       => 'To complete the ticket you must confirm that you leave intervention field blank',
  'show-ticket-modal-complete-blank-reason-alert'       => 'To complete the ticket you must indicate a closing reason'
  'show-ticket-complete-bad-status'                     => 'Ticket not completed: The specified status is not valid',
  'show-ticket-complete-bad-reason-id'                  => 'Ticket not completed: The specified reason is not valid',
  
  'complete-by-user'                 => 'Ticket complete by :user',  
  'reopened-by-user'                 => 'Ticket reopened by :user',
  
  'ticket-comment-type-reply'        => 'Reply',
  'ticket-comment-type-note'         => 'Internal note (hidden for user)',
  'ticket-comment-type-complete'     => 'Ticket complete',
  'ticket-comment-type-reopen'       => 'Ticket reopened',
  
  'show-ticket-add-comment'                => 'Add comment',
  'show-ticket-add-comment-type'           => 'Type',
  'show-ticket-add-comment-note'           => 'Internal note',
  'show-ticket-add-comment-reply'          => 'Reply to user',
  'show-ticket-add-com-check-intervention' => 'Append this text in intervention field',
  'show-ticket-add-com-check-resolve'      => 'Resolve this ticket and apply the status',
  
   
  'show-ticket-edit-comment'         => 'Edit comment',
  'show-ticket-edit-com-check-int'   => 'Add text to the intervention field',
  'show-ticket-delete-comment'       => 'Delete comment',
  'show-ticket-delete-comment-msg'   => 'Are you sure you want to delete this comment?',
  'show-ticket-email-resend'         => 'Resend email',
  'show-ticket-email-resend-user'    => 'To user: ',
  'show-ticket-email-resend-agent'   => 'To agent: ',
  
  /*'validate-ticket-subject.required' => '',
  'validate-ticket-subject.min'      => '',
  'validate-ticket-content.required' => '',
  'validate-ticket-content.min'      => '',*/

 /*
  *  Controllers
  */

// AgentsController
  'agents-are-added-to-agents'                      => 'Agents :names are added to agents',
  'administrators-are-added-to-administrators'      => 'Administrators :names are added to administrators', //New
  'agents-joined-categories-ok'                     => 'Joined categories successfully',
  'agents-is-removed-from-team'                     => 'Removed agent\s :name from the agent team',
  'administrators-is-removed-from-team'             => 'Removed administrator\s :name from the administrators team', // New

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
  'notify-new-comment-from'          => 'New comment from ',
  'notify-on'                        => ' on ',
  'notify-status-to-complete'        => ' status to Complete',
  'notify-status-to'                 => ' status to ',
  'notify-transferred'               => ' transferred ',
  'notify-to-you'                    => ' to you',
  'notify-created-ticket'            => ' created ticket ',
  'notify-updated'                   => ' updated ',

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

 //  IsAdminMiddleware IsAgentMiddleware ResAccessMiddleware
  'you-are-not-permitted-to-access'     => 'You are not permitted to access this page!',

];
