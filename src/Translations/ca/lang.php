<?php

return [

 /*
  *  Constants
  */

  'nav-active-tickets'               => 'Oberts',
  'nav-completed-tickets'            => 'Tancats',
  
  // Regular expressions
  'regex-text-inline'                => '/^(?=.*[A-Za-z]+[\'\-¡!¿?\s,;.:]*)[a-zA-ZçÇáéíóúàèòÁÉÍÓÚÀÈÒ\'0-9¡!¿?,;.:\-\s]*$/',
  

  // Tables
  'table-id'                         => '#',
  'table-subject'                    => 'Tema',
  'table-department'                 => 'Departament',
  'table-description'                => 'Descripció',
  'table-intervention'               => 'Actuació',
  'table-owner'                      => 'Propietari',
  'table-status'                     => 'Estat',
  'table-last-updated'               => 'Última actualització',
  'table-priority'                   => 'Prioritat',
  'table-agent'                      => 'Tècnic',
  'table-calendar'                   => 'Agenda',
  'table-category'                   => 'Categoria',
  'table-tags'                       => 'Etiquetes',
  
  'calendar-active'            => 'Data d\'inici',
  'calendar-expired'           => 'Caducat des d\'aquest dia',
  'calendar-expiration'        => 'Caduca aquest dia',
  'calendar-expires-today'     => 'Caduca avui',
  'calendar-scheduled'         => 'Programat en data',
  
  // Agent related
  'table-change-agent'               => 'Canviar tècnic',
  'table-one-agent'                  => 'Només hi ha un tècnic en aquesta categoria',

  // Datatables
  'table-decimal'                    => '',
  'table-empty'                      => 'No hi ha dades disponibles a la taula',
  'table-info'                       => '_TOTAL_ Registres. Mostrant _START_ a _END_',
  'table-info-empty'                 => 'Mostra 0 a 0 de 0 registres',
  'table-info-filtered'              => '(filtrat _MAX_ total de registres)',
  'table-info-postfix'               => '',
  'table-thousands'                  => ',',
  'table-length-menu'                => 'Mostra _MENU_ registres',
  'table-loading-results'            => 'Carregant...',
  'table-processing'                 => 'En procés...',
  'table-search'                     => 'Cerca: ',
  'table-zero-records'               => 'No s\'han trobat coincidències',
  'table-paginate-first'             => 'Primer',
  'table-paginate-last'              => 'Últim',
  'table-paginate-next'              => 'Següent',
  'table-paginate-prev'              => 'Anterior',
  'table-aria-sort-asc'              => ': activar per ordenar ordre ascendent',
  'table-aria-sort-desc'             => ': activar per ordenar ordre descendent',
  
  'filter-pov'                       => 'Vista',
  'filter-calendar'                  => 'Agenda',
  'filter-calendar-all'              => 'Tot',
  'filter-calendar-expired'          => 'Caducats',
  'filter-calendar-today'            => 'Per avui',
  'filter-calendar-tomorrow'         => 'Per demà',
  'filter-calendar-week'             => 'Aquesta setmana',
  'filter-calendar-month'            => 'Aquest mes',
  'filter-category'                  => 'Categoria',
  'filter-category-all'              => 'Totes',
  'filter-agent'                     => 'Agent',
  'filter-agent-all'                 => 'Tots',
  'filter-on-total'                  => 'Compte segons filtre',
  'filter-off-total'                 => 'Compte total',

  'btn-back'                         => 'Enrere',
  'btn-cancel'                       => 'Cancel·lar',
  'btn-change'                       => 'Canviar',
  'btn-close'                        => 'Tancar',
  'btn-delete'                       => 'Esborrar',
  'btn-download'                     => 'Descarregar',
  'btn-edit'                         => 'Editar',
  'btn-mark-complete'                => 'Tancar Tiquet',
  'btn-submit'                       => 'Enviar',

  // Vocabulary  
  'agent'                            => 'Tècnic',
  'agents'                           => 'Tècnics',
  'all-depts'                        => 'Tots',
  'attachments'                      => 'Adjunts',
  'category'                         => 'Categoria',
  'closing-reason'                   => 'Raó de tancament',
  'closing-clarifications'           => 'Aclariments',
  'colon'                            => ': ',
  'comments'                         => 'Comentaris',
  'created'                          => 'Creat',
  'date-info-created'                => 'Data de creació',
  'date-info-updated'                => 'Data de darrer canvi',
  'department'                       => 'Departament',
  'department-shortening'            => 'Dep.',
  'dept_sub1'                        => 'Area',
  'description'                      => 'Descripció',
  'flash-x'                          => '×', // &times;
  'intervention'                     => 'Actuació',
  'last-update'                      => 'Última actualització',
  'limit-date'                       => 'Data límit',
  'no-replies'                       => 'Sense respostes.',
  'owner'                            => 'Propietari',
  'priority'                         => 'Prioritat',
  'reopen-ticket'                    => 'Reobrir Tiquet',
  'reply'                            => 'Respondre',
  'responsible'                      => 'Tècnic',
  'start-date'                       => 'Data inici',
  'status'                           => 'Estat',
  'subject'                          => 'Tema',
  'tags'                             => 'Etiquetes',
  'ticket'                           => 'Tiquet',
  'today'                            => 'Avui',
  'tomorrow'                         => 'Demà',
  'user'                             => 'Usuari',
  'yesterday'                        => 'Ahir',
  
  // Days of week
  'day_1'                            => 'Dilluns',
  'day_2'                            => 'Dimarts',
  'day_3'                            => 'Dimecres',
  'day_4'                            => 'Dijous',
  'day_5'                            => 'Divendres',
  'day_6'                            => 'Dissabte',
  'day_7'                            => 'Diumenge',

 /*
  *  Page specific
  */

// ____
  'index-title'                      => 'Tiquets',

// tickets/____
  'index-my-tickets'                 => 'Els meus Tiquets',
  
  'btn-create-new-ticket'            => 'Crear nou',
  'index-complete-none'              => 'No hi han tiquets tancats',
  'index-active-check'               => 'Assegureu-vos de revisar els tiquets oberts si no pot trobar el seu tiquet.',
  'index-active-none'                => 'No hi ha tiquets oberts,',
  'index-create-new-ticket'          => 'crear nou tiquet',
  'index-complete-check'             => 'Assegureu-vos de revisar els tiquets tancats si no pot trobar el seu tiquet.',

  'ticket-notices-title'             => 'Avisos actius', 
  
  'create-ticket-title'              => 'Nou formulari de Tiquet',
  'create-new-ticket'                => 'Crear Nou Tiquet',
  'create-ticket-owner'              => 'Remitent',
  'create-ticket-notices'            => 'Avís per',
  'create-ticket-owner-help'         => 'Aquí cal indicar de qui és el tiquet o a qui afecta',
  'create-ticket-brief-issue'        => 'Tema del tiquet',
  'create-ticket-info-start-date'    => 'Predeterminat: Ara',
  'create-ticket-info-limit-date'    => 'Predeterminat: Sense límit',
  'create-ticket-describe-issue'     => 'Descriu els detalls del problema',
  'attach-files'                     => 'Adjuntar fitxers',
  'pending-attachment'               => 'Aquest fitxer s\'afegirà quan s\'actualitzi el tiquet',
  'attachment-new-name'              => 'Nom nou',
  
  'edit-ticket'                      => 'Editar Tiquet',
  'attachment-edit'                  => 'Editar adjunt',
  'attachment-edit-original-filename'=> 'Nom original',
  'attachment-edit-new-filename'     => 'Nom nou',
  
  'attachment-update-not-valid-name' => 'El nom nou per a ":file" ha de tenir almenys 3 caràcters. No es permet HTML',

  'show-ticket-title'                => 'Tiquet',
  'show-ticket-creator'              => 'Creat per',
  'show-ticket-js-delete'            => 'Esteu segur que voleu esborrar?: ',
  'show-ticket-modal-delete-title'   => 'Esborrar Tiquet',
  'show-ticket-modal-delete-message' => 'Esteu segur que voleu esborrar el tiquet?: :subject?',
  
  'show-ticket-modal-complete-blank-intervention-check' => 'Deixar actuació en blanc',
  'show-ticket-complete-blank-intervention-alert'       => 'Per a tancar el tiquet cal que confirmis que deixes el camp actuació en blanc',
  'show-ticket-modal-complete-blank-reason-alert'       => 'Per a tancar el tiquet cal que indiquis una raó de tancament',
  'show-ticket-complete-bad-status'                     => 'Tiquet no completat: L\'estat indicat no és vàlid',
  'show-ticket-complete-bad-reason-id'                  => 'Tiquet no completat: La raó indicada no és vàlida',
  
  'complete-by-user'                 => 'Tiquet tancat per :user',
  'reopened-by-user'                 => 'Tiquet reobert per :user',
  
  'ticket-error-not-valid-file'      => 'S\'ha adjuntat un fitxer no vàlid',
  'ticket-error-not-valid-object'    => 'Aquest fitxer no es pot processar: :name',
  'ticket-error-max-size-reached'    => 'El fitxer ":name" i els següents no es poden adjuntar ja que sobrepassen l\'espai disponible per aquest tiquet que és de :available_MB MB',
  'ticket-error-max-attachments-count-reached' => 'El fitxer ":name" i els següents no es poden adjuntar ja que sobrepassen el número màxim de :max_count fitxers adjunts que pot contenir cada tiquet',
  'ticket-error-delete-files'        => 'No s\'ha pogut eliminar alguns fitxers',
  'ticket-error-file-not-found'      => 'No s\'ha localitzat el fitxer ":name"',
  'ticket-error-file-not-deleted'    => 'El fitxer ":name" no s\'ha pogut eliminar',
  
  'ticket-comment-type-reply'        => 'Resposta',
  'ticket-comment-type-note'         => 'Nota interna (oculta per a l\'usuari)',
  'ticket-comment-type-complete'     => 'Tiquet tancat',
  'ticket-comment-type-reopen'       => 'Tiquet reobert',
  
  'show-ticket-add-comment'                => 'Afegir comentari',
  'show-ticket-add-comment-type'           => 'Tipus',
  'show-ticket-add-comment-note'           => 'Nota interna',
  'show-ticket-add-comment-reply'          => 'Resposta a usuari', 
  'show-ticket-add-com-check-intervention' => 'Afegir aquesta resposta al camp actuació',
  'show-ticket-add-com-check-resolve'      => 'Resoldre el tiquet amb estat',
  
  'show-ticket-edit-comment'         => 'Editar comentari',
  'show-ticket-edit-com-check-int'   => 'Afegir el text al camp actuació',
  'show-ticket-delete-comment'       => 'Eliminar comentari',
  'show-ticket-delete-comment-msg'   => 'Estàs segur que vols eliminar aquest comentari?',
  'show-ticket-email-resend'         => 'Reenviar notificacions',
  'show-ticket-email-resend-user'    => 'A l\'usuari: ',
  'show-ticket-email-resend-agent'   => 'Al tècnic: ',
  
  'validate-ticket-subject.required' => 'Cal indicar un tema. Si us plau, indica en poques paraules de què es tracta',
  'validate-ticket-subject.min'      => 'Cal que indiquis un tema més llarg',
  'validate-ticket-content.required' => 'Cal indicar una descripció. Si adjuntes alguna imatge és necessari que també afegeixis un text explicatiu',
  'validate-ticket-content.min'      => 'Cal indicar una descripció més llarga, encara que ja hi hagi imatges adjuntes',
  

 /*
  *  Controllers
  */

// AgentsController
  'agents-are-added-to-agents'                      => 'Agents :names S\'ha\n afegit com Agent\s',
  'administrators-are-added-to-administrators'      => 'Administradors :names S\'ha\n agegit com Administrador\s',
  'agents-joined-categories-ok'                     => 'Categories triades amb èxit',
  'agents-is-removed-from-team'                     => 'Agent\s :name eliminat\s del grup d\'agents',
  'administrators-is-removed-from-team'             => 'Administrator\s :name eliminat\s del grup d\'administradors',

// CategoriesController
  'category-name-has-been-created'   => 'La categoria :name s\'ha creat!',
  'category-name-has-been-modified'  => 'La categoria :name s\'ha modificat!',
  'category-name-has-been-deleted'   => 'La categoria :name s\'ha esborrat!',

// PrioritiesController
  'priority-name-has-been-created'   => 'La prioritat :name s\'ha creat!',
  'priority-name-has-been-modified'  => 'La prioritat :name s\'ha modificat!',
  'priority-name-has-been-deleted'   => 'La prioritat :name s\'ha esborrat!',
  'priority-all-tickets-here'        => 'Totes les prioritats relacionades amb els tiquets',

// StatusesController
  'status-name-has-been-created'     => 'The status :name s\'ha creat!',
  'status-name-has-been-modified'    => 'The status :name s\'ha modificat!',
  'status-name-has-been-deleted'     => 'The status :name s\'ha esborrat!',
  'status-all-tickets-here'          => 'Tots els registres relacionats amb l\'estat',

// CommentsController
  'comment-has-been-added-ok'        => 'El comentari s\'ha afegit correctament',
  'comment-has-been-updated'         => 'El comentari s\'ha actualitzat correctament',
  'comment-has-been-deleted'         => 'El comentari s\'ha eliminat correctament',

// NotificationsController
  'notify-new-comment-from'          => 'Nou comentari de ',
  'notify-on'                        => ' Conectat ',
  'notify-status-to-complete'        => ' Estat per completear',
  'notify-status-to'                 => ' Estat per ',
  'notify-transferred'               => ' enviat ',
  'notify-to-you'                    => ' per tu',
  'notify-created-ticket'            => ' crear tiquet ',
  'notify-updated'                   => ' actualitzat ',

 // TicketsController
  'the-ticket-has-been-created'      => 'S\'ha creat el tiquet <a href=":link" title=":title"><u>:name</u></a>!',
  'the-ticket-has-been-modified'     => 'El tiquet :name s\'ha modificat!', //revisar no tenia :name
  'the-ticket-has-been-deleted'      => 'El tiquet :name s\'ha esborrat!',
  'the-ticket-has-been-completed'    => 'S\'ha tancat el tiquet <a href=":link" title=":title"><u>:name</u></a>!',
  'the-ticket-has-been-reopened'     => 'S\'ha reobert el tiquet <a href=":link" title=":title"><u>:name</u></a>!',
  'ticket-status-link-title'         => 'Veure tiquet',
  
  'you-are-not-permitted-to-do-this' => 'No tens permisos per fer aquesta acció!',

 /*
 *  Middlewares
 */

 //  IsAdminMiddleware IsAgentMiddleware ResAccessMiddleware
  'you-are-not-permitted-to-access'     => 'No tens permisos per accedir a aquesta pàgina!',

];
