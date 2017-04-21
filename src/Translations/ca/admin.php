<?php

return [

 /*
  *  Constants
  */
  'nav-settings'                  => 'Paràmetres',
  'nav-agents'                    => 'Agents',
  'nav-dashboard'                 => 'Panell admin.',
  'nav-categories'                => 'Categories',
  'nav-priorities'                => 'Prioritats',
  'nav-statuses'                  => 'Estats',
  'nav-configuration'             => 'Configuració',
  'nav-administrator'             => 'Administrador',

  'table-hash'                    => '#',
  'table-id'                      => 'Id',
  'table-name'                    => 'Nom',
  'table-action'                  => 'Acció',
  'table-categories'              => 'Categories',
  'table-join-category'           => 'Categories afegides',
  'table-remove-agent'            => 'Excloure d\'agents',
  'table-remove-administrator'    => 'Excloure d\'administrators',

  'table-slug'                    => 'Concatenat', // http://www.atcreativa.com/blog/que-es-slug-de-wordpress/  //Revisar (enllaçat, lligat ??)
  'table-default'                 => 'Valor predeterminat',
  'table-value'                   => 'Valor configurat',
  'table-lang'                    => 'Idioma',
  'table-edit'                    => 'Editar',

  'btn-back'                      => 'Endarrere',
  'btn-delete'                    => 'Eliminar',
  'btn-edit'                      => 'Editar',
  'btn-join'                      => 'Unir',
  'btn-remove'                    => 'Excloure',
  'btn-submit'                    => 'Enviar',
  'btn-save'                      => 'Desar',
  'btn-update'                    => 'Actualitzar',

  'colon'                         => ': ',

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
  'btn-create-new-agent'          => 'Crear nou agent',
  'agent-index-no-agents'         => 'No hi ha agents, ',
  'agent-index-create-new'        => 'Afegir agents',
  'agent-create-title'            => 'Afegir Agent',
  'agent-create-add-agents'       => 'Afegir Agents',
  'agent-create-no-users'         => 'No hi ha cap compte d\'usuari, primer cal crear-ne un.',
  'agent-create-select-user'      => 'Tria comptes d\'usuari per a utilitzar-se com agents',

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
  'category-create-title'         => 'Crear Nova Categoria',
  'category-create-name'          => 'Nom',
  'category-create-color'         => 'Color',
  'category-edit-title'           => 'Editar Categoria: :name',

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

];
