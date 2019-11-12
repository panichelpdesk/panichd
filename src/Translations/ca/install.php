<?php

return [
    'main-title'                      => 'Panic Help Desk',

    'not-ready-to-install'            => 'Encara no es pot instal·lar la llibreria!',
    'ticketit-still-installed'        => 'La llibreria Kordy/Ticketit encara està instal·lada. Per a continuar, si us plau segueix els passos per a desinstalar-la indicats a <a href=":link">:link</a>',

    'not-yet-installed'               => '<b>Estat actual:</b> La instal·lació de la llibreria encara <b>no s\'ha acabat</b>.',
    'welcome'                         => 'Benvingut al menú d\'<b>instal·lació de Panic Help Desk!</b>',
    'setup-list'                      => 'Quan premis el botó "Instal·lar ara!" es farà les següents accions:',
    'initial-setup'                   => 'Configuració inicial',

    'setup-list-migrations'           => '<b>:num</b> migracions de la base de dades es publicarà i s\'instal·larà a l\'aplicació Laravel',
    'setup-more-info'                 => 'Veure detalls',
    'setup-less-info'                 => 'Ocultar detalls',

    'setup-list-ticketit-settings'    => 'Es farà canvis a varis paràmetres incompatibles de Kordy/Ticketit',
    'setup-list-ticketit-admin_route' => 'Es reiniciarà els paràmetres referents a la ruta d\'administració: "admin_route" i "admin_route_path"',
    'setup-list-ticketit-template'    => 'Es reiniciarà el paràmetre de la plantilla principal "master_template"',
    'setup-list-ticketit-routes'      => 'S\'eliminarà el paràmetre "routes"',
    'setup-list-settings'             => 'La taula settings de PanicHD s\'omplirà amb els valors per defecte del fitxer SettingsTableSeeder.php',
    'setup-list-folders'              => 'Es crearà els directoris necessaris per a desar els fitxers adjunts dels tiquets',
    'setup-list-admin'                => 'El teu compte d\'usuari s\'afegirà com a administrador de Panic Help Desk. Després de la instal·lació podràs afegir-ne altres.',
    'setup-list-public-assets'        => 'Es copiarà al directori de Laravel "public\vendor\panichd" tots els fitxers css, js... necessaris de la llibreria.',
    'configurable-parameters'         => 'Paràmetres configurables',
    'existent-parameters'             => 'Ja existeixen paràmetres configurats de Kordy/Ticketit, així que aquí no es mostrarà opcions d\'inici ràpid.<br />Per a editar-los, si us plau accedeix als menús d\'administració després d\'instal·lar Panic Help Desk.',
    'optional-quickstart-data'        => 'Afegir paràmetres d\'inici ràpid:<ul><li>Inclou prioritats i estats bàsics i una categoria de tiquets.</li><li>El teu compte d\'usuari s\'afegirà com a agent a la categoria creada.</li><li>Tots son modificables després de la instal·lació.</li></ul>',
    'install-now'                     => 'Instal·lar ara!',

    'just-installed'                  => 'Felicitats! Ja tens Panic Help Desk <b>instal·lat i configurat</b>.',
    'installed-package-description'   => 'A partir d\'ara pots accedir quan vulguis a <a href=":panichd">:panichd</a> per controlar l\'estat de la llibreria.',
    'continue-to-main-menu'           => 'Continuar al menú principal',

    'package-requires-update'         => 'Panic Help Desk requereix <b>configurar una actualització</b>',
    'package-requires-update-info'    => 'L\'Administrador ha instal·lat una actualització de Panic Help Desk però no ha acabat de configurar-la. Si us plau espera que el procés hagi acabat',

    'status-updated'                  => 'La llibreria està actualitzada i <b>En línia</b> ara mateix',
    'status-out-of-date'              => '<b>Estat actual: Fora de línia</b> fins que s\'instal·li la darrera actualització',
    'about-to-update'                 => 'Aquest és el <b>menú d\'actualització</b> de Panic Help Desk',
    'about-to-update-description'     => 'Un cop facis clic al botó "Actualitzar ara", es farà els següents canvis:',
    'all-tables-migrated'             => 'Totes les modificacions de la base de dades ja estan instal·lades. No es farà canvis.',

    'optional-config'                 => 'Configuració opcional',
    'choose-public-folder-action'     => 'Vols fer una còpia de seguretat del directori "public/vendor/panichd" abans de reinstal·lar-lo? (Necessari només si has fet modificacions als fitxers que hi ha)',
    'public-folder-destroy'           => 'No',
    'public-folder-backup'            => 'Si (Es crearà una còpia de seguretat a "public/vendor")',

    'upgrade-now'                     => 'Actualitzar ara!',

    'upgrade-done'                    => 'L\'actualització ha acabat correctament.',

    'pending-settings'                => 'Encara hi ha alguna <b>configuració pendent</b>',
    'pending-settings-description'    => 'Abans de poder afegir nous tiquets, t\'has d\'assegurar que tens almenys un estat, prioritat, categoria i un agent que hi estigui assignat.',

    'master-template-file'            => 'fitxer de plantilla mestra',
    'master-template-other-path'      => 'Una altra ruta d\'accés al fitxer de plantilla mestra',
    'master-template-other-path-ex'   => 'ex. views/layouts/app.blade.php',
    'migrations-to-be-installed'      => 'S\'instal·laran aquestes migracions:',
    'another-file'                    => 'un altre arxiu',
    'upgrade'                         => 'Actualització de la versió de Panic Help Desk',
    'settings-to-be-installed'        => 'S\'instal·laran aquests ajustos:',
    'all-settings-installed'          => 'Ja hi ha registrats tots els paràmetres de configuració. No es farà canvis',
    'public-folder-will-be-replaced'  => '<b>S\'eliminarà i es reinstal·larà</b> el contingut del directori public/vendor/panichd',
];
