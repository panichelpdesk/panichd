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
  'table-completed_at'               => 'Tancament',
  'table-category'                   => 'Categoria',
  'table-tags'                       => 'Etiquetes',

  'no-tickets-yet'                   => 'Encara no hi ha tiquets',
  'list-no-tickets'                  => 'En aquesta llista no hi ha tiquets',
  'updated-by-other'                 => 'Actualitzat per un altre membre',
  'mark-as-read'                     => 'Marcar aquest tiquet com a llegit',
  'mark-as-unread'                   => 'Marcar i bloquejar aquest tiquet com a no llegit',
  'read-validation-error'            => 'No s\'ha pogut marcar aquest tiquet com a llegit / no llegit',
  'read-validation-ok-read'          => 'Tiquet marcat com a llegit',
  'read-validation-ok-unread'        => 'Tiquet marcat com a no llegit',

  'table-info-attachments-total'     => ':num fitxers adjunts',
  'table-info-comments-total'        => ':num comentaris totals.',
  'table-info-comments-recent'       => ':num recents.',
  'table-info-notes-total'           => ':num notes internes',

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
  'table-agent-status-check'         => 'Canviar estat a ":status"',

  // Canvis AJAX a la llista
  'table-change-priority'            => 'Canviar la prioritat',
  'table-change-status'              => 'Canviar l\'estat',

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

  'filter-removeall-title'           => 'Elimina tots els filtres',
  'filter-pov'                       => 'Vista',
  'filter-pov-member-title'          => 'Veure com a membre',
  'filter-pov-agent-title'           => 'Veure com a agent',
  'filter-year-all'                  => 'Tots',
  'filter-calendar'                  => 'Agenda',
  'filter-calendar-all'              => 'Tot',
  'filter-calendar-expired'          => 'Caducats',
  'filter-calendar-not-scheduled'    => 'No prog.',
  'filter-calendar-today'            => 'Per avui',
  'filter-calendar-tomorrow'         => 'Per demà',
  'filter-calendar-week'             => 'Aquesta setmana',
  'filter-calendar-month'            => 'Aquest mes',
  'filter-calendar-within-7-days'    => 'Fins 7 dies',
  'filter-calendar-within-14-days'   => 'Fins 14 dies',
  'filter-category'                  => 'Categoria',
  'filter-category-all'              => 'Totes',
  'filter-owner-all'                 => 'Tots',
  'filter-agent'                     => 'Agent',
  'filter-agent-all'                 => 'Tots',

  'btn-add'                          => 'Afegir',
  'btn-back'                         => 'Enrere',
  'btn-cancel'                       => 'Cancel·lar',
  'btn-change'                       => 'Canviar',
  'btn-close'                        => 'Tancar',
  'btn-delete'                       => 'Esborrar',
  'btn-download'                     => 'Descarregar',
  'btn-edit'                         => 'Editar',
  'btn-mark-complete'                => 'Tancar',
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
  'creation-date'                    => 'Creat el :date',
  'crop-image'                       => 'Retallar imatge',
  'date-format'                      => 'd-m-Y',
  'datetime-format'                  => 'd-m-Y H:i',
  'datetimepicker-format'            => 'DD-MM-YYYY HH:mm',
  'datetime-text'                    => ':date, :timeh',
  'deleted-member'                   => 'Usuari eliminat',
  'department'                       => 'Area',
  'department-shortening'            => 'Dep.',
  'dept-descendant'                  => 'Departament',
  'description'                      => 'Descripció',
  'discard'                          => 'Descartar',
  'email'                            => 'E-mail',
  'email-resend-abbr'                => 'RV',
  'flash-x'                          => '×', // &times;
  'intervention'                     => 'Actuació',
  'last-update'                      => 'Última actualització',
  'limit-date'                       => 'Data límit',
  'list'                             => 'Llista',
  'mark-complete'                    => 'Tancar tiquet',
  'member'                           => 'Membre',
  'name'                             => 'Nom',
  'newest-tickets-adjective'         => 'Nous',
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
  'tickets'                          => 'Tiquets',
  'today'                            => 'Avui',
  'tomorrow'                         => 'Demà',
  'update'                           => 'Actualitzar',
  'updated-date'                     => 'Actualitzat :date',
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
  'day_0'                            => 'Diumenge',

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

// Newest tickets page reload Modal
  'reload-countdown'                 => 'La llista de tiquets es recarregarà en <kbd class=":num_class"><span id="counter">:num</span></kbd> segons.',
  'reload-reloading'                 => 'La llista de tiquets està recarregant... si us plau, espera',

// Ticket forms messages
  'update-agent-same'                => 'No has canviat l\'agent! Tiquet <a href=":link" title=":title"><u>:name</u></a>',
  'update-agent-ok'                  => 'Agent canviat a ":new_agent" al tiquet <a href=":link" title=":title"><u>:name</u></a>',
  'update-priority-same'             => 'No has canviat la prioritat! Tiquet <a href=":link" title=":title"><u>:name</u></a>',
  'update-priority-ok'               => 'Prioritat canviada a ":new" al tiquet <a href=":link" title=":title"><u>:name</u></a>',
  'update-status-same'               => 'No has canviat l\'estat! Tiquet <a href=":link" title=":title"><u>:name</u></a>',
  'update-status-ok'                 => 'Estat canviat a ":new" al tiquet <a href=":link" title=":title"><u>:name</u></a>',

// tickets/create
  'create-new-ticket'                => 'Crear Nou Tiquet',
  'create-ticket-brief-issue'        => 'Tema del tiquet',
  'create-ticket-notices'            => 'Avís per',
  'ticket-owner-deleted-warning'     => 'L\'usuari està eliminat. No es veurà a l\'edició de propietaris',
  'ticket-owner-no-email'            => '(No té e-mail)',
  'ticket-owner-no-email-warning'    => 'L\'usuari no té email: No rebrà cap notificació per correu electrònic',
  'create-ticket-owner-help'         => 'Aquí cal indicar de qui és el tiquet o a qui afecta',
  'create-ticket-visible'            => 'Visible',
  'create-ticket-visible-help'       => 'Escull la visibilitat del tiquet per al propietari',
  'create-ticket-change-list'        => 'Canviar de llista',
  'create-ticket-info-start-date'    => 'Predeterminat: Ara',
  'create-ticket-info-limit-date'    => 'Predeterminat: Sense límit',
  'create-ticket-describe-issue'     => 'Descriu els detalls del problema',
  'create-ticket-intervention-help'  => 'Accions realitzades per a la resolució del tiquet',
  'create-ticket-switch-to-note'     => 'Canviar a nota interna',
  'create-ticket-switch-to-comment'  => 'Canviar a resposta a usuari',

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
  'show-ticket-modal-edit-fields'    => 'Editar més camps',

  'show-ticket-modal-complete-blank-intervention-check' => 'Deixar actuació en blanc',
  'show-ticket-complete-blank-intervention-alert'       => 'Per a tancar el tiquet cal que confirmis que deixes el camp actuació en blanc',
  'show-ticket-modal-complete-blank-reason-alert'       => 'Per a tancar el tiquet cal que indiquis una raó de tancament',
  'show-ticket-complete-bad-status'                     => 'Tiquet no completat: L\'estat indicat no és vàlid',
  'show-ticket-complete-bad-reason-id'                  => 'Tiquet no completat: La raó indicada no és vàlida',

  'complete-by-user'                 => 'Tiquet tancat per :user.',
  'reopened-by-user'                 => 'Tiquet reobert per :user.',

  'ticket-error-not-valid-file'                => 'S\'ha adjuntat un fitxer no vàlid',
  'ticket-error-not-valid-object'              => 'Aquest fitxer no es pot processar: :name',
  'ticket-error-max-size-reached'              => 'El fitxer ":name" i els següents no es poden adjuntar ja que sobrepassen l\'espai disponible per aquest tiquet que és de :available_MB MB',
  'ticket-error-max-attachments-count-reached' => 'El fitxer ":name" i els següents no es poden adjuntar ja que sobrepassen el número màxim de :max_count fitxers adjunts que pot contenir cada tiquet',
  'ticket-error-delete-files'                  => 'No s\'ha pogut eliminar alguns fitxers',
  'ticket-error-file-not-found'                => 'No s\'ha localitzat el fitxer ":name"',
  'ticket-error-file-not-deleted'              => 'El fitxer ":name" no s\'ha pogut eliminar',

  // Tiquet visible / no visible
  'ticket-visible'                => 'Tiquet visible',
  'ticket-hidden'                 => 'Tiquet ocult',
  'ticket-hidden-button-title'    => 'Canviar visibilitat per a l\'usuari',
  'ticket-visibility-changed'     => 'S\'ha canviat la visibilitat del tiquet',
  'ticket-hidden-0-comment-title' => 'Canviat a visible per <b>:agent</b>',
  'ticket-hidden-0-comment'       => 'El tiquet s\'ha fet <b>visible</b> per a l\'usuari',
  'ticket-hidden-1-comment-title' => 'Ocultat per <b>:agent</b>',
  'ticket-hidden-1-comment'       => 'El tiquet s\'ha <b>ocultat</b> per a l\'usuari',

  // Comentaris
  'comment'                    => 'Comentari',
  'note'                       => 'Nota interna',
  'comment-reply-title'        => 'Missatge visibles pels usuaris',
  'comment-reply-from-owner'   => 'Resposta de <b>:owner</b>',
  'reply-from-owner-to'        => 'Resposta de <b>:owner</b> a <b>:recipients</b>',

  'comment-note-title'         => 'Nota oculta per a l\'usuari',
  'comment-note-from-agent'    => 'Nota de <b>:agent</b>',
  'comment-note-from-agent-to' => 'Nota de <b>:agent</b> a <b>:recipients</b>',

  'comment-completetx-title'   => 'Tiquet tancat',
  'comment-complete-by'        => 'Tancat per <b>:owner</b>',

  'comment-reopen-title'       => 'Tiquet reobert',
  'comment-reopen-by'          => 'Reobert per <b>:owner</b>',

  'show-ticket-add-comment'                => 'Afegir comentari',
  'show-ticket-add-note'                   => 'Afegir nota interna',
  'show-ticket-add-comment-type'           => 'Tipus',
  'show-ticket-add-comment-note'           => 'Nota interna',
  'show-ticket-add-comment-reply'          => 'Resposta a usuari',
  'show-ticket-add-comment-notificate'     => 'Notificar',
  'show-ticket-add-com-check-email-text'   => 'Afegir text a la notificació per a l\'usuari',
  'show-ticket-add-com-check-intervention' => 'Afegir aquesta resposta al camp actuació (visible per l\'usuari)',
  'show-ticket-add-com-check-resolve'      => 'Tancar el tiquet amb estat',
  'add-comment-confirm-blank-intervention' => 'El camp "actuació" està en blanc. Vols tancar el tiquet igualment?',

  'edit-internal-note-title'         => 'Editar nota interna',
  'show-ticket-edit-com-check-int'   => 'Afegir el text al camp actuació',
  'show-ticket-delete-comment'       => 'Eliminar comentari',
  'show-ticket-delete-comment-msg'   => 'Estàs segur que vols eliminar aquest comentari?',
  'show-ticket-email-resend'         => 'Reenviar notificacions',
  'show-ticket-email-resend-agent'   => '(Tècnic del tiquet)',
  'show-ticket-email-resend-owner'   => '(Propietari del tiquet)',
  'notification-resend-confirmation' => 'Notificacions reenviades correctament',
  'notification-resend-no-recipients'=> 'No s\'ha marcat cap destinatari',

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

// Cercador de tiquets
  'searchform-nav-text'             => 'Cercar',
  'searchform-title'                => 'Cercar tiquets',

  'searchform-creator'              => 'Creador',
  'searchform-department'           => 'Departament',
  'searchform-comments'             => 'Text als comentaris',
  'searchform-attachment_filename'  => 'Nom d\'adjunt',
  'searchform-any_text_field'       => 'Qualsevol camp de text',
  'searchform-created_at'           => 'Creació',
  'searchform-completed_at'         => 'Tancament',
  'searchform-updated_at'           => 'Darrera actualització',
  'searchform-read_by_agent'        => 'Llegit pel tècnic assignat',

  'searchform-help-creator'         => 'Qui va crear el tiquet (A vegades és un tècnic en nom d\'un Membre)',
  'searchform-help-owner'           => 'A qui pertany el tiquet',
  'searchform-help-department'      => 'Departament del propietari del tiquet',
  'searchform-help-any_text_field'  => 'Cercar a qualsevol camp de text entre el tema, descripció, actuació, comentaris o camps de fitxers adjunts',

  'searchform-creator-none'         => '- cap -',
  'searchform-owner-none'           => '- cap -',
  'searchform-department-none'      => '- cap -',
  'searchform-list-none'            => '- cap -',

  'searchform-status-none'          => '- cap -',
  'searchform-status-rule-any'      => 'Qualsevol dels triats',
  'searchform-status-rule-none'     => 'Cap dels triats',

  'searchform-priority-none'        => '- cap -',
  'searchform-priority-rule-any'    => 'Qualsevol de les triades',
  'searchform-priority-rule-none'   => 'Cap de les triades',

  'searchform-category-none'        => '- cap -',
  'searchform-agent-none'           => '- cap -',

  'searchform-tags-rule-no-filter'   => 'No filtrar',
  'searchform-tags-rule-has_not_tags'=> 'Sense etiquetes',
  'searchform-tags-rule-has_any_tag' => 'Amb qualsevol etiqueta',
  'searchform-tags-rule-any'         => 'Qualsevol de les triades',
  'searchform-tags-rule-all'         => 'Totes les triades',
  'searchform-tags-rule-none'        => 'Cap de les triades',

  'searchform-date-type-from'       => 'En endavant',
  'searchform-date-type-until'      => 'Més antics',
  'searchform-date-type-exact_year' => 'Any exacte',
  'searchform-date-type-exact_month'=> 'Any i mes',
  'searchform-date-type-exact_day'  => 'Dia exacte',

  'searchform-read_by_agent-none'   => 'No filtrar',
  'searchform-read_by_agent-yes'    => 'Sí',
  'searchform-read_by_agent-no'     => 'No',

  'searchform-btn-submit'           => 'Cercar',

  'searchform-validation-no-field'  => 'No s\'ha indicat cap camp de cerca',
  'searchform-validation-success'   => 'S\'ha registrat :num camps de cerca',

  'searchform-results-title'        => 'Resultats de la cerca',
  'searchform-btn-edit'             => 'Editar cerca',
  'searchform-btn-web'              => 'Adreça web de cerca',
  'searchform-help-btn-web'         => 'Aquest és un enllaç permanent per a aquesta cerca',

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
