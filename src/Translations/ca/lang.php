<?php

return [

 /*
  *  Constants
  */

  'nav-new-tickets'                  => 'Nous',
  'nav-new-tickets-title'            => 'Tiquets nous',
  'nav-new-dd-list'                  => 'Llista',
  'nav-new-dd-list-title'            => 'Llista de tiquets nous',
  'nav-new-dd-create'                => 'Crear',
  'nav-create-ticket'                => 'Crear nou',
  'nav-create-ticket-title'          => 'Crear nou tiquet',
  'nav-notices-number-title'         => 'Hi ha :num avisos',
  'nav-active-tickets-title'         => 'Tiquets oberts',  
  'nav-completed-tickets-title'      => 'Tiquets tancats',
  
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
  'table-last-updated'               => 'Act.',
  'table-priority'                   => 'Prioritat',
  'table-agent'                      => 'Tècnic',
  'table-calendar'                   => 'Agenda',
  'table-category'                   => 'Categoria',
  'table-tags'                       => 'Etiquetes',
  
  'no-tickets-yet'                   => 'Encara no hi ha tiquets',
  'list-no-tickets'                  => 'En aquesta llista no hi ha tiquets',
  'table-info-attachments-total'     => ':num fitxers adjunts',
  'table-info-comments-total'        => ':num comentaris totals.',
  'table-info-comments-recent'       => ':num recents.',
  
  'calendar-active'            => 'Va començar :description',
  'calendar-active-today'      => 'Ha començat :description',
  'calendar-active-future'     => 'Comença :description',
  'calendar-expired'           => 'Caducat des de :description',
  'calendar-expired-today'     => 'Caducat avui a les :time',
  'calendar-expiration'        => 'Caduca :description',
  'calendar-expires-today'     => 'Caducarà avui a les :hour',
  'calendar-scheduled'         => 'Programat el :date de :time1 a :time2H',
  'calendar-scheduled-today'   => 'Programat avui de :time1 a :time2H',
  'calendar-scheduled-period'  => 'Programat del :date1 al :date2',
  
  // Agent related
  'table-change-agent'               => 'Canviar tècnic',
  'table-one-agent'                  => 'Només hi ha un tècnic en aquesta categoria',
  
  // Priority related
  'table-change-priority'            => 'Canviar la prioritat',

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
  'filter-year-all'                  => 'Tots',
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

  'btn-add'                          => 'Afegir',
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
  'active-tickets-adjective'         => 'Oberts',
  'agent'                            => 'Tècnic',
  'agents'                           => 'Tècnics',
  'all-depts'                        => 'Tots',
  'attached-images'                  => 'Imatges adjuntes',
  'attached-files'                   => 'Fitxers adjunts',
  'attachments'                      => 'Adjunts',
  'category'                         => 'Categoria',
  'closing-reason'                   => 'Raó de tancament',
  'closing-clarifications'           => 'Aclariments',
  'colon'                            => ': ',
  'comments'                         => 'Comentaris', 
  'complete'                         => 'Tancat',
  'complete-tickets-adjective'       => 'Tancats',
  'created'                          => 'Creat',
  'crop-image'                       => 'Retallar imatge',
  'date-format'                      => 'd-m-Y',
  'datetimepicker-format'            => 'DD-MM-YYYY HH:mm',
  'datetimepicker-validation'        => 'd-m-Y H:i',
  'date-info-created'                => 'Data de creació',
  'date-info-updated'                => 'Data de darrer canvi',
  'department'                       => 'Departament',
  'department-shortening'            => 'Dep.',
  'dept_sub1'                        => 'Area',
  'description'                      => 'Descripció',
  'discard'                          => 'Descartar',
  'email-resend-abbr'                => 'RV',
  'flash-x'                          => '×', // &times;
  'intervention'                     => 'Actuació',
  'last-update'                      => 'Última actualització',
  'limit-date'                       => 'Data límit',
  'list'                             => 'Llista',
  'no'                               => 'No',
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
  'update'                           => 'Actualitzar',
  'user'                             => 'Usuari',
  'year'                             => 'Any',
  'yes'                              => 'Sí',
  'yesterday'                        => 'Ahir',
  
  // Days of week
  'day_1'                            => 'Dilluns',
  'day_2'                            => 'Dimarts',
  'day_3'                            => 'Dimecres',
  'day_4'                            => 'Dijous',
  'day_5'                            => 'Divendres',
  'day_6'                            => 'Dissabte',
  'day_7'                            => 'Diumenge',
  
  // Time units abbreviations
  'second-abbr'                      => 's.',
  'minute-abbr'                      => 'mi.',
  'hour-abbr'                        => 'h.',
  'day-abbr'                         => 'd.',
  'week-abbr'                        => 'st.',
  'month-abbr'                       => 'ms.',

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
  'ticket-notices-title'             => 'Avisos',
  'ticket-notices-empty'             => 'No hi ha cap avís actiu',

// Ticket forms messages
  'update-agent-same'                => 'No has canviat l\'agent! Tiquet <a href=":link" title=":title"><u>:name</u></a>',
  'update-agent-ok'                  => 'Agent canviat correctament. Tiquet <a href=":link" title=":title"><u>:name</u></a>: :old_agent -> :new_agent',
  'update-priority-same'             => 'No has canviat la prioritat! Tiquet <a href=":link" title=":title"><u>:name</u></a>',
  'update-priority-ok'               => 'Prioritat canviada correctament. Tiquet <a href=":link" title=":title"><u>:name</u></a>: :old -> :new',
  
// tickets/create
  'create-ticket-title'              => 'Nou formulari de Tiquet',
  'create-new-ticket'                => 'Crear Nou Tiquet',
  'create-ticket-brief-issue'        => 'Tema del tiquet',
  'create-ticket-notices'            => 'Avís per',
  'create-ticket-owner-help'         => 'Aquí cal indicar de qui és el tiquet o a qui afecta',
  'create-ticket-visible'            => 'Visible',
  'create-ticket-visible-help'       => 'Escull la visibilitat del tiquet per al propietari',
  'create-ticket-change-list'        => 'Canviar de llista',
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
  'attachment-edit-crop-info'        => 'Selecciona un requadre dins la imatge per a retallar-la. S\'aplicarà després d\'actualitzar els camps de l\'adjunt',
  
  'attachment-update-not-valid-name' => 'El nom nou per a ":file" ha de tenir almenys 3 caràcters. No es permet HTML',
  'attachment-error-equal-name'      => 'El nom i la descripció per a ":file" no poden ser iguals',
  'attachment-update-not-valid-mime' => 'El fitxer ":file" no és de cap tipus vàlid',
  'attachment-update-crop-error'     => 'La imatge no s\'ha pogut retallar amb les mides indicades',

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
  
  // Tiquet visible / no visible
  'ticket-visible'             => 'Tiquet visible',
  'ticket-hidden'              => 'Tiquet ocult',
  'ticket-hidden-button-title' => 'Canviar visibilitat per a l\'usuari',
  'ticket-visibility-changed'  => 'S\'ha canviat la visibilitat del tiquet',
  'ticket-hidden-0-comment-title' => 'Canviat a visible per <b>:agent</b>',
  'ticket-hidden-0-comment'       => 'El tiquet s\'ha fet <b>visible</b> per a l\'usuari',
  'ticket-hidden-1-comment-title' => 'Ocultat per <b>:agent</b>',
  'ticket-hidden-1-comment'       => 'El tiquet s\'ha <b>ocultat</b> per a l\'usuari',
  
  // Comentaris
  'comment-reply-title'        => 'Missatges entre el propietari i els tècnics',
  'comment-reply-from-owner'   => 'Resposta de <b>:owner</b>',
  'reply-from-owner-to-owner'  => 'Resposta de <b>:owner1</b> a <b>:owner2</b>',
  
  'comment-note-title'         => 'Nota oculta per a l\'usuari',
  'comment-note-from-agent'    => 'Nota de <b>:agent</b>',
  
  'comment-complete-title'     => 'Tiquet tancat',
  'comment-complete-by'        => 'Tancat per <b>:owner</b>',
  
  'comment-reopen-title'       => 'Tiquet reobert',
  'comment-reopen-by'          => 'Reobert per <b>:owner</b>',
  
  'show-ticket-add-comment'                => 'Afegir comentari',
  'show-ticket-add-note'                   => 'Afegir nota interna',
  'show-ticket-add-comment-type'           => 'Tipus',
  'show-ticket-add-comment-note'           => 'Nota interna',
  'show-ticket-add-comment-reply'          => 'Resposta a usuari', 
  'show-ticket-add-com-check-intervention' => 'Afegir aquesta resposta al camp actuació',
  'show-ticket-add-com-check-resolve'      => 'Resoldre el tiquet amb estat',
  'add-comment-confirm-blank-intervention' => 'El camp "actuació" està en blanc. Vols tancar el tiquet igualment?',
  
  'edit-internal-note-title'         => 'Editar nota interna',
  'show-ticket-edit-com-check-int'   => 'Afegir el text al camp actuació',
  'show-ticket-delete-comment'       => 'Eliminar comentari',
  'show-ticket-delete-comment-msg'   => 'Estàs segur que vols eliminar aquest comentari?',
  'show-ticket-email-resend'         => 'Reenviar notificacions',
  'show-ticket-email-resend-user'    => 'A l\'usuari: ',
  'show-ticket-email-resend-agent'   => 'Al tècnic: ',
  
  // Validacions
  'validation-error'                 => 'Aquest formulari no s\'ha enviat',
  'validate-ticket-subject.required' => 'Cal indicar un tema. Si us plau, indica en poques paraules de què es tracta',
  'validate-ticket-subject.min'      => 'Cal que indiquis un tema més llarg',
  'validate-ticket-content.required' => 'Cal indicar una descripció. Si adjuntes alguna imatge és necessari que també afegeixis un text explicatiu',
  'validate-ticket-content.min'      => 'Cal indicar una descripció més llarga, encara que ja hi hagi imatges adjuntes',
  'validate-ticket-start_date-format'=> 'La data d\'inici té un format incorrecte. El correcte és: ":format"',
  'validate-ticket-start_date'       => 'L\'any de la data d\'inici no és vàlid',
  'validate-ticket-limit_date-format'=> 'La data límit té un format incorrecte. El correcte és: ":format"',
  'validate-ticket-limit_date'       => 'L\'any de la data límit no és vàlid',
  'validate-ticket-limit_date-lower' => 'La data límit no pot ser inferior a la d\'inici',
  
  'ticket-destroy-error'             => 'El tiquet no s\'ha pogut eliminar: :error',
  'comment-destroy-error'            => 'El comentari no s\'ha pogut eliminar: :error',
  
 // Comment form
  'validate-comment-required'        => 'Cal que escriguis el text del comentari',
  'validate-comment-min'             => 'Cal posar un text més llarg al comentari',

 /*
  *  Controllers
  */

// AdministratorsController
  'administrators-are-added-to-administrators'      => 'Administradors :names S\'ha\n agegit com Administrador\s',
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
  // Les traduccions d'e-mail es troben a email/globals.php

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

 // EnvironmentReadyMiddleware
 'environment-not-ready'                 => 'L\'Administrador no ha finalitzat la configuració per poder afegir tiquets',
 
 //  IsAdminMiddleware IsAgentMiddleware UserAccessMiddleware
  'you-are-not-permitted-to-access'     => 'No tens permisos per accedir a aquesta pàgina!',

];
