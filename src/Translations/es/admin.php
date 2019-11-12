<?php

return [

 /*
  *  Constants
  */
  'nav-settings'                  => 'Configuración',
  'nav-agents'                    => 'Agentes',
  'nav-dashboard'                 => 'Dashboard',
  'nav-categories'                => 'Categorías',
  'nav-priorities'                => 'Prioridades',
  'nav-statuses'                  => 'Estados',
  'nav-configuration'             => 'Configuración',
  'nav-administrator'             => 'Administrador',  //new

  'table-hash'                    => '#',
  'table-id'                      => 'ID',
  'table-name'                    => 'Nombre',
  'table-action'                  => 'Acción',
  'table-categories'              => 'Categorías',
  'table-join-category'           => 'Categorías Unidas',
  'table-remove-agent'            => 'Eliminar de agentes',
  'table-remove-administrator'    => 'Eliminar de administradores', // New

  'table-slug'                    => 'Código',
  'table-default'                 => 'Valor Predeterminado',
  'table-value'                   => 'Mi Valor',
  'table-lang'                    => 'Idioma',
  'table-description'             => 'Descripción',
  'table-edit'                    => 'Editar',

  'btn-back'                      => 'Atrás',
  'btn-delete'                    => 'Borrar',
  'btn-edit'                      => 'Editar',
  'btn-join'                      => 'Unir',
  'btn-remove'                    => 'Eliminar',
  'btn-submit'                    => 'Enviar',
  'btn-save'                      => 'Salvar',
  'btn-update'                    => 'Actualizar',

  'colon'                         => ': ',

 /*
  *  Page specific
  */

// $admin_route_path/dashboard
  'index-title'                         => 'Tickets System Dashboard',
  'index-empty-records'                 => 'No tickets yet',
  'index-total-tickets'                 => 'Total tickets',
  'index-open-tickets'                  => 'Open tickets',
  'index-closed-tickets'                => 'Closed tickets',
  'index-performance-indicator'         => 'Performance Indicator',
  'index-periods'                       => 'Periods',
  'index-3-months'                      => '3 months',
  'index-6-months'                      => '6 months',
  'index-12-months'                     => '12 months',
  'index-tickets-share-per-category'    => 'Tickets share per category',
  'index-tickets-share-per-agent'       => 'Tickets share per agent',
  'index-categories'                    => 'Categories',
  'index-category'                      => 'Categoría',
  'index-agents'                        => 'Agentes',
  'index-agent'                         => 'Agente',
  'index-administrators'                => 'Administradores',  //new
  'index-administrator'                 => 'Adinistrador',  //new
  'index-users'                         => 'Usuarios',
  'index-user'                          => 'Usuario',
  'index-tickets'                       => 'Tiques',
  'index-open'                          => 'Abierto',
  'index-closed'                        => 'Cerrado',
  'index-total'                         => 'Total',
  'index-month'                         => 'Mes',
  'index-performance-chart'             => '¿Cuántos días en promedio toma resolver un tique?',
  'index-categories-chart'              => 'Distribución de Tiques por Categoría',
  'index-agents-chart'                  => 'Distribución de Tiques por Agente',

// $admin_route_path/agent/____
  'agent-index-title'             => 'Administración de Agentes',
  'btn-create-new-agent'          => 'Crear un nuevo agente',
  'agent-index-no-agents'         => 'No existen agentes, ',
  'agent-index-create-new'        => 'Añadir agentes',
  'agent-create-title'            => 'Añadir Agente',
  'agent-create-add-agents'       => 'Añadir Agentes',
  'agent-create-no-users'         => 'No existen cuentas de usuarios, crea cuentas de usuarios primero.',
  'agent-create-select-user'      => 'Selecciona las cuentas de usuario para añadirlas como agentes',

// $admin_route_path/administrators/____
  'administrator-index-title'                   => 'Administración de Administradores',  //new
  'btn-create-new-administrator'                => 'Crear un nuevo administrador',  //new
  'administrator-index-no-administrators'       => 'No existen administradores, ',  //new
  'administrator-index-create-new'              => 'Añadir administradores',  //new
  'administrator-create-title'                  => 'Añadir Administrador',  //new
  'administrator-create-add-administrators'     => 'Añadir Administradores',  //new
  'administrator-create-no-users'               => 'No existen cuentas de usuario, crea cuentas de usuario primero.',  //new
  'administrator-create-select-user'            => 'Selecciona las cuentas de usuario para añadirlas como administradores',  //new

// $admin_route_path/category/____
  'category-index-title'          => 'Administración de Categorías',
  'btn-create-new-category'       => 'Crear categoría nueva',
  'category-index-no-categories'  => 'No existen categorías, ',
  'category-index-create-new'     => 'crear nueva categoría',
  'category-index-js-delete'      => '¿Estás seguro que desea borrar la categoría?: ',
  'category-create-title'         => 'Crear Categoría Nueva',
  'category-create-name'          => 'Nombre',
  'category-create-color'         => 'Color',
  'category-edit-title'           => 'Editar Categoria: :name',

  'category-edit-new-tags'        => 'Etiq. nuevas',
  'category-edit-current-tags'    => 'Etiq. actuales',
  'category-edit-new-tag-title'   => 'Crear etiqueta nueva',
  'category-edit-new-tag-default' => 'Etiqueta nueva',
  'category-edit-tag'             => 'Editar etiqueta',
  'category-edit-tag-background'  => 'Fondo',
  'category-edit-tag-text'        => 'Texto',

  'tag-regex'                     => '/^[A-Za-z\x{00C0}-\x{017F}0-9?@\/\-_\s]+$/u',
  'new-tag-validation-empty'      => 'No puedes registrar una etiqueta con el nombre en blanco',
  'update-tag-validation-empty'   => 'No puedes dejar en blanco el nombre de la etiqueta con nombre anterior ":name"',
  'tag-validation-two'            => 'Has indicado dos etiquetas con el mismo nombre ":name"',

// $admin_route_path/priority/____
  'priority-index-title'          => 'Administración de Prioridades',
  'btn-create-new-priority'       => 'Crear prioridad nueva',
  'priority-index-no-priorities'  => 'No existen prioridades, ',
  'priority-index-create-new'     => 'crear nueva prioridad',
  'priority-index-js-delete'      => '¿Estás seguro que desea borrar la prioridad?: ',
  'priority-create-title'         => 'Crear Prioridad Nueva',
  'priority-create-name'          => 'Nombre',
  'priority-create-color'         => 'Color',
  'priority-edit-title'           => 'Editar Prioridad: :name',

// $admin_route_path/status/____
  'status-index-title'            => 'Administración de Estados',
  'btn-create-new-status'         => 'Crear estado nuevo',
  'status-index-no-statuses'      => 'No existen estados,',
  'status-index-create-new'       => 'crear estado nuevo',
  'status-index-js-delete'        => '¿Estás seguro de que desea borrar el estado?: ',
  'status-create-title'           => 'Crear Estado Nuevo',
  'status-create-name'            => 'Nombre',
  'status-create-color'           => 'Color',
  'status-edit-title'             => 'Editar Estado: :name',

// $admin_route_path/configuration/____
  'config-index-title'            => 'Administración de Configuraciones',
  'config-index-subtitle'         => 'Configuraciones',
  'btn-create-new-config'         => 'Añadir configuración nueva',
  'config-index-no-settings'      => 'No existen configuraciones,',
  'config-index-initial'          => 'Inicial',
  'config-index-tickets'          => 'Tiques',
  'config-index-table'            => 'Tabla',
  'config-index-features'         => 'Características',
  'config-index-notifications'    => 'Notificaciones',
  'config-index-permissions'      => 'Permisos',
  'config-index-editor'           => 'Editor', //Added: 2016.01.14
  'config-index-other'            => 'Otro',
  'config-create-title'           => 'Crear: Nueva Configuración Global',
  'config-create-subtitle'        => 'Crear Configuración',
  'config-edit-title'             => 'Editar: Configuración Global',
  'config-edit-subtitle'          => 'Editar Configuraciones',
  'config-edit-id'                => 'ID',
  'config-edit-slug'              => 'Código',
  'config-edit-default'           => 'Valor predeterminado',
  'config-edit-value'             => 'Mi valor',
  'config-edit-language'          => 'Idioma',
  'config-edit-unserialize'       => 'Obtener los valores del array, y cambiar los valores',
  'config-edit-serialize'         => 'Get the serialized string of the changed values (to be entered in the field)',
  'config-edit-should-serialize'  => 'Serializar', //Added: 2016-01-16
  'config-edit-eval-warning'      => 'Si está seleccionado, el servidor usará eval()!
  									  No usar esto si eval() está desactivado en el servidor o si no tiene los conocimientos al respecto!
  									  Código específico ejecutado:', //Added: 2016-01-16
  'config-edit-reenter-password'  => 'Re-ingrese su clave', //Added: 2016-01-16
  'config-edit-auth-failed'       => 'Las claves no son iguales', //Added: 2016-01-16
  'config-edit-eval-error'        => 'Valor inválido', //Added: 2016-01-16
  'config-edit-tools'             => 'Herramientas:',
  'config-update-confirm'         => 'El parámetro :name ha sido actualizado',
  'config-delete-confirm'         => 'El parámetro :name ha sido eliminado',
];
