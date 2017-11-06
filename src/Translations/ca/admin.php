<?php

return [

 /*
  *  Constants
  */
  'nav-settings'                  => 'Paràmetres',
  'nav-agents'                    => 'Agents',
  'nav-dashboard'                 => 'Panell admin.',
  'nav-dashboard-title'           => 'Panell d\'administrador',
  'nav-categories'                => 'Categories',
  'nav-priorities'                => 'Prioritats',
  'nav-statuses'                  => 'Estats',
  'nav-dept-users'                => 'Usuaris departamentals',
  'nav-configuration'             => 'Configuració',
  'nav-administrator'             => 'Administrador',

  'table-hash'                    => '#',
  'table-id'                      => 'Id',
  'table-name'                    => 'Nom',
  'table-create-level'            => 'Tiquets nous',
  'table-action'                  => 'Acció',
  'table-categories'              => 'Categories',
  'table-categories-autoasg-title'=> 'Assignació automàtica a nous tiquets',
  'table-remove-agent'            => 'Excloure d\'agents',
  'table-remove-administrator'    => 'Excloure d\'administrators',

  'table-slug'                    => 'Concatenat', // http://www.atcreativa.com/blog/que-es-slug-de-wordpress/  //Revisar (enllaçat, lligat ??)
  'table-default'                 => 'Valor predeterminat',
  'table-value'                   => 'Valor configurat',
  'table-lang'                    => 'Idioma',
  'table-edit'                    => 'Editar',

  'btn-back'                      => 'Endarrere',
  'btn-change'                    => 'Canviar',
  'btn-create'                    => 'Crear',
  'btn-delete'                    => 'Eliminar',
  'btn-edit'                      => 'Editar',
  'btn-join'                      => 'Unir',
  'btn-remove'                    => 'Excloure',
  'btn-submit'                    => 'Enviar',
  'btn-save'                      => 'Desar',
  'btn-update'                    => 'Actualitzar',

  'colon'                         => ': ',

  /* Access Levels */
  'level-1'                       => 'Tothom',
  'level-2'                       => 'agents assignats + admins.',
  'level-3'                       => 'admins.',
  
 /*
  *  Page specific
  */

// tickets-admin/____
  'index-title'                         => 'Panell d\'Administració de Tiquets',
  'index-empty-records'                 => 'Encara no hi ha tiquets',
  'index-total-tickets'                 => 'Tiquets totals',
  'index-open-tickets'                  => 'Tiquets oberts',
  'index-closed-tickets'                => 'Tiquets tancats',
  'index-performance-indicator'         => 'Indicador de rendiment',
  'index-periods'                       => 'Períodes',
  'index-3-months'                      => '3 mesos',
  'index-6-months'                      => '6 mesos',
  'index-12-months'                     => '12 mesos',
  'index-tickets-share-per-category'    => 'Proporció de tiquets per categoria',
  'index-tickets-share-per-agent'       => 'Proporció de tiquets per agent',
  'index-categories'                    => 'Categories',
  'index-category'                      => 'Categoria',
  'index-agents'                        => 'Agents',
  'index-agent'                         => 'Agent',
  'index-administrators'                => 'Administradors',
  'index-administrator'                 => 'Administrador',
  'index-users'                         => 'Usuaris',
  'index-user'                          => 'Usuari',
  'index-tickets'                       => 'Tiquets',
  'index-open'                          => 'Oberts',
  'index-closed'                        => 'Tancat',
  'index-total'                         => 'Total',
  'index-month'                         => 'Mes',
  'index-performance-chart'             => 'Quants dies de promig per resoldre un tiquet?',
  'index-categories-chart'              => 'Distribució de tickets per Categoria',
  'index-agents-chart'                  => 'Distribució de tickets per Agent',

// tickets-admin/agent/____
  'agent-index-title'             => 'Gestió d\'Agents',
  'agent-index-no-agents'         => 'No hi ha agents',
  'agent-index-create-new'        => 'Afegir agent',
  'agent-create-form-agent'       => 'Usuari',
  'agent-create-add-agents'       => 'Afegir Agents',
  'agent-create-no-users'         => 'No hi ha cap compte d\'usuari, primer cal crear-ne un.',
  'agent-store-ok'                => 'L\'usuari ":name" s\'ha afegit com a agent',
  'agent-updated-ok'              => 'L\'agent ":name" s\'ha actualitzat correctament',
  'agent-excluded-ok'            => 'L\'agent ":name" s\'ha exclòs',
  
  'agent-store-error-no-category' => 'Per afegir un agent cal marcar com a mínim una categoria',

  // tickets-admin/agent/edit
  'agent-edit-title'                 => 'Permisos d\'usuari per a :agent',
  'agent-edit-table-category'        => 'Categoria',
  'agent-edit-table-agent'           => 'Permisos d\'agent',
  'agent-edit-table-autoassign'      => 'Tiquets nous auto.',
  
// tickets-admin/administrators/____
  'administrator-index-title'                   => 'Gestió d\'Administradors',
  'btn-create-new-administrator'                => 'Crear nou administrador',
  'administrator-index-no-administrators'       => 'No hi ha administradors, ',
  'administrator-index-create-new'              => 'Afegir administradors',
  'administrator-create-title'                  => 'Afegir Administrador',
  'administrator-create-add-administrators'     => 'Afegir Administradors',
  'administrator-create-no-users'               => 'No hi ha cap compte d\'usuari, primer cal crear-ne un.',
  'administrator-create-select-user'            => 'Tria comptes d\'usuari per a utilitzar-se com administradors',

// tickets-admin/category/____
  'category-index-title'          => 'Gestió de Categories',
  'btn-create-new-category'       => 'Crear nova categoria',
  'category-index-no-categories'  => 'No hi ha categories, ',
  'category-index-create-new'     => 'crear nova categoria',
  'category-index-js-delete'      => 'Estàs segur/a que vols eliminar la categoria: ',
  'category-index-email'          => 'Email de notificacions',
  'category-index-reasons'        => 'Raons tancament',
  'category-index-tags'           => 'Etiquetes',
  'category-create-title'         => 'Crear Nova Categoria',
  'category-create-name'          => 'Nom',
  'category-create-email'         => 'Email de notificacions',
  'category-email-default'        => 'Adreça d\'email',
  'category-email-info'           => 'Remitent utilitzat a totes les notificacions en aquesta categoria. Si està en blanc, s\'utilitzarà l\'email predeterminat de cada notificació',
  'category-create-color'         => 'Color',
  'category-create-new-tickets'   => 'Tiquets nous',
  'category-create-new-tickets-help'   => 'Nivell mínim a la categoria per a crear tiquets',
  'category-edit-title'                => 'Editar Categoria: :name',  
  'category-edit-closing-reasons'      => 'Tancaments de tiquet',
  'category-edit-closing-reasons-help' => 'Opcions que l\'usuari haurà de triar quan tanqui un tiquet',
  'category-edit-reason'          => 'Raó de tancament',
  'category-edit-reason-label'    => 'Raó',
  'category-edit-reason-status'   => 'Estat',
  'category-delete-reason'        => 'Eliminar raó',
  'category-edit-new-tags'        => 'Etiq. noves',
  'category-edit-current-tags'    => 'Etiq. actuals',
  'category-edit-tag'             => 'Editar etiqueta',
  'category-edit-tag-background'  => 'Fons',
  'category-edit-tag-text'        => 'Text',

// tickets-admin/priority/____
  'priority-index-title'          => 'Gestió de prioritats',
  'btn-create-new-priority'       => 'Crear nova prioritat',
  'priority-index-no-priorities'  => 'No hu ha prioritats, ',
  'priority-index-create-new'     => 'crear nova prioritat',
  'priority-index-js-delete'      => 'Segur que vols eliminar la prioritat?: ',
  'priority-create-title'         => 'Create New Priority',
  'priority-create-name'          => 'Nom',
  'priority-create-color'         => 'Color',
  'priority-edit-title'           => 'Editar Prioritat: :name',

// tickets-admin/status/____
  'status-index-title'            => 'Gestió d\'estats',
  'btn-create-new-status'         => 'Crear nou estat',
  'status-index-no-statuses'      => 'No hi ha estats,',
  'status-index-create-new'       => 'Crear nou estat',
  'status-index-js-delete'        => 'Esteu segur que voleu eliminar l\'estat?: ',
  'status-create-title'           => 'Crear nou estat',
  'status-create-name'            => 'Nom',
  'status-create-color'           => 'Color',
  'status-edit-title'             => 'Editar Estat: :name',

// tickets-admin/deptsuser/____
  'deptsuser-index-title'         => 'Gestió d\'usuaris departamentals',
  'btn-create-new-deptsuser'      => 'Assignar usuari',
  'deptsuser-index-empty'         => 'No hi ha relacions configurades,',
  'deptsuser-index-definition'    => 'Al menú de crear tiquet, un usuari qualsevol hi veurà avisos dels tiquets oberts dels usuaris aquí llistats que es trobin explícitament al seu departament o als relacionats',  
  'deptsuser-index-user'          => 'Usuari',
  'deptsuser-index-email'         => 'E-mail',
  'deptsuser-index-department'    => 'Departament',
  'deptsuser-modal-title-create'   => 'Assignar usuari a un departament',
  'deptsuser-modal-title-update'   => 'Actualitzar usuari departamental',
  'deptsuser-saved-ok'             => 'Associació desada correctament',
  'deptsuser-deleted-ok'           => 'Associació eliminada',
  'deptsuser-index-js-delete'      => 'Estàs segur/a que vols eliminar aquesta associació per a',
  
// tickets-admin/configuration/____
  'config-index-title'            => 'Paràmetres de configuració',
  'config-index-subtitle'         => 'configuració',
  'btn-create-new-config'         => 'Afegir nova configuració',
  'config-index-no-settings'      => 'No hi ha configuracions,',
  'config-index-initial'          => 'Inicial',
  'config-index-tickets'          => 'Tiquets',
  'config-index-notifications'    => 'Notificacions',
  'config-index-permissions'      => 'Permisos',
  'config-index-editor'           => 'Editor',
  'config-index-other'            => 'Altres',
  'config-create-title'           => 'Crear: Nou Configuració Global',
  'config-create-subtitle'        => 'Crear Configuració',
  'config-edit-title'             => 'Editar: Configuració Global',
  'config-edit-subtitle'          => 'Editar configuració',
  'config-edit-id'                => 'Id',
  'config-edit-slug'              => 'Concatenat', //Revisar
  'config-edit-default'           => 'Valor per defecte',
  'config-edit-value'             => 'Valor configurat',
  'config-edit-language'          => 'Idioma',
  'config-edit-unserialize'       => 'Obtenir els valors de la matriu, i canviar els valors',
  'config-edit-serialize'         => 'Obtenir la cadena serialitzada dels valors modificats (per ser introduït en el camp)',  //Get the serialized string of the changed values (to be entered in the field)
  'config-edit-should-serialize'  => 'Cadena serialitzada', //Revisar Serialize
  'config-edit-eval-warning'      => 'When checked, the server will run eval()!
  									  Don\'t use this if eval() is disabled on your server or if you don\'t exactly know what you are doing! 
  									  Exact code executed:', //Revisar
  'config-edit-reenter-password'  => 'Torneu a introduir la contrasenya',
  'config-edit-auth-failed'       => 'La contrasenya no coincideix',
  'config-edit-eval-error'        => 'Valor no vàlid',
  'config-edit-tools'             => 'Eines:',
  'config-update-confirm'         => 'El paràmetre :name s\'ha actualitzat',

];
