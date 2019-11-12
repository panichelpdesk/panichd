<?php

/*
 * Setting descriptions
 *
 * See Seeds/SettingsTableSeeder.php
 */

$codemirrorVersion = PanicHD\PanicHD\Helpers\Cdn::CodeMirror;
$summernoteVersion = PanicHD\PanicHD\Helpers\Cdn::Summernote;

return [
    // inicial
    'main_route'         => 'Prefix de PanicHD que s\'utilitza als noms de ruta de Laravel (p.ex.: route(\'<code>tiquets</code>.index\'))',
    'main_route_path'    => 'Prefix URL per veure els tiquets (p.ex.: http://hostname/<code>tiquets</code>/)',
    'admin_route'        => 'Prefix per als menús de configuració de PanicHD a les rutes de Laravel (p.ex.: route(\'<code>panichd</code>.status.index\')). El panell d\'admin te el seu propi nom de ruta "dashboard"',
    'admin_route_path'   => 'Prefix URL per als menús de configuració i el panell d\'administració de PanicHD (p.ex.: http://url/<code>panichd</code>/priority)',
    'master_template'    => 'La plantilla blade que totes les vistes de PanicHD extendran',
    'user_route'         => 'Nom de ruta per a pàgines de Membres. Si es configura, qualsevol nom de Membre en una vista contindrà un enllaç a la seva pròpia pàgina<br />El <b>paràmetre utilitzat</b> per la ruta és "<b>user</b>"',
    'member_model_class' => 'Namespace <b>complet</b> per el model "Member" de PanicHD. Per defecte és <code>PanicHD\PanicHD\Models\Member</code>',
    'admin_button_text'  => 'Nom del menú nav de configuració de PanicHD',

    // característiques
    'departments_feature'         => 'Veure informació relacionada amb el departament dels Membres. Aquesta característica està en desenvolupament <a href="https://github.com/panichelpdesk/panichd/wiki/Under-development">tal com descrivim a la wiki (en anglès)</a><br /><code>0</code>: deshabilitat<br /><code>1</code>: habilitat',
    'departments_notices_feature' => 'Habilitat per enllaçar un Membre específic a un departament d\'avís. Si es crea un tiquet amb qualsevol d\'aquests usuaris com a propietari, tots els membres relacionats amb el departament veuran el tiquet com un Avís. Aquesta característica està en desenvolupament <a href="https://github.com/panichelpdesk/panichd/wiki/Under-development">tal com descrivim a la wiki (en anglès)</a><br /><code>0</code>: deshabilitat<br /><code>1</code>: habilitat',
    'ticket_attachments_feature'  => 'Ability to attach files to tickets and/or comments<br /><code>0</code>: deshabilitat<br /><code>1</code>: habilitat',

    // taula
    'paginate_items'            => 'Número predeterminat de files a la taula',
    'length_menu'               => 'Opcions per al menú de paginació de la taula',
    'max_agent_buttons'         => 'Número màxim d\'agents que es mostrarà en botons separats al panell de filtre. Si hi ha més agents disponibles, es mostrarà en una llista desplegable',
    'subject_content_column'    => 'Agrupar columnes d\'assumpte i contingut a la taula<br /><code>0</code>: deshabilitat<br /><code>1</code>: habilitat',
    'calendar_month_filter'     => 'Veure opcions del filtre d\'agenda per períodes de calendari (setmana, mes).<br /><code>0</code>: Veure opcions de comptes de dies (7 dies, 14 dies)<br /><code>1</code>: Veure opcions de períodes de calendari (setmana, mes)',
    'list_text_max_length'      => 'Longitud visible màxima per als camps de descripció i actuació. Si qualsevol dels dos és més gran que aquest paràmetre, el text es retallarà a aquesta longitud i es mostrarà un botó per a veure el text sencer<br /><code>0</code>: Deshabilitar',
    'check_last_update_seconds' => 'Interval en segons en què es farà una comprovació AJAX de la darrera actualització de tiquets per a executar recàrrega de dades',

    // tiquets
    'default_status_id'                => 'L\'estat per defecte dels tiquets nous',
    'default_close_status_id'          => 'L\'estat per defecte quan es tanca un tiquet',
    'default_reopen_status_id'         => 'L\'estat per defecte al reobrir un tiquet',
    'delete_modal_type'                => 'Escollir quin tipus de missatge de confirmació utilitzar per a confirmar una eliminació.<br /><code>builtin</code>: confirmació javascript<br /><code>modal</code>: missatge modal de jquery',
    'attachments_path'                 => 'Subdirectori dins de <b>storage</b> on desar els fitxers adjunts',
    'attachments_ticket_max_size'      => 'Mida màxima en <b>MB</b> per tots els adjunts en un únic tiquet, incloent els comentaris',
    'attachments_ticket_max_files_num' => 'Número màxim de fitxers adjunts en un únic tiquet, incloent els comentaris',
    'attachments_mimes'                => 'Llista d\'extensions de fitxer permeses separades amb coma',
    'thumbnails_path'                  => 'Subdirectori dins de "storage\\app\\public" on desar les miniatures d\'imatges adjuntes',
    'oldest_year'                      => 'Any més antic permès per als camps de data d\'inici o data límit',
    'html_replacements'                => 'Reemplaços automàtics per als camps de contingut i actuació',
    'default_priority_id'              => 'La prioritat per defecte dels tiquets nous',
    'use_default_status_id'            => 'Permetre o no d\'assignar l\'estat default_status_id a qualsevol tiquet',

    // notificacions
    'email.template'                 => 'La plantilla blade d\'email que totes les notificacions extenen',
    'comment_notification'           => 'Enviar notificació quan es publica un nou comentari<br /><code>1</code>: Enviar notificació<br /><code>0</code>: No enviar notificació',
    'queue_emails'                   => 'Utilitzar mètode Queue quan s\'enviï emails (Mail::queue).<br /><code>0</code>: Notificar via Mail::send<br /><code>1</code>: Notificar via Laravel Mail::queue. Requereix configuració <a target="_blank" href="http://laravel.com/docs/master/queues">a Laravel</a>.',
    'assigned_notification'          => 'Enviar notificació a qualsevol nou agent assignat<br /><code>1</code>: Enviar notificació<br /><code>0</code>: No enviar notificació',
    'email.account.name'             => 'El nom de remitent d\'email per a totes les notificacions de PanicHD<br /><code>default</code>: Utilitzar predeterminats de Laravel',
    'email.account.mailbox'          => 'L\'adreça d\'email de remitent per a totes les notificacions de PanicHD',
    'list_owner_notification'        => 'Notificar el propietari quan canvia la llista del tiquet entre obert i tancat<br /><code>0</code>: deshabilitat<br /><code>1</code>: habilitat',
    'status_owner_notification'      => 'Notificar el propietari quan canvia l\'estat del tiquet<br /><code>0</code>: deshabilitat<br /><code>1</code>: habilitat. Requereix tenir habilitat el paràmetre <b>status_notification</b>',
    'custom_recipients'              => 'Mostrar opció al formulari de comentari per a seleccionar-ne un o varis destinataris personalitzats',
    'status_notification'            => 'Notificar el propietari/agent quan canvia l\'estat del tiquet<br /><code>1</code>: Enviar notificació<br /><code>0</code>: No enviar notificació',
    'email.owner.newticket.template' => 'La notificació d\'avisos al propietari del tiquet utilitza la plantilla blade d\'email que s\'indica aquí',

    // TODO: Eliminar paràmetres obsolets email.*
    'email.header' => '<p><img src="http://i.imgur.com/5aJjuZL.jpg"/></p>', 'email.signoff' => '<p><img src="http://i.imgur.com/jONMwgF.jpg"/></p>', 'email.signature' => '<p><img src="http://i.imgur.com/coi3R63.jpg"/></p>', 'email.dashboard' => '<p><img src="http://i.imgur.com/qzNzJD4.jpg"/></p>', 'email.google_plus_link' => '<p><b>Toogle icon link</b>: empty or string</p><p><img src="http://i.imgur.com/fzyxfSg.jpg"/></p>', 'email.facebook_link' => '<p><b>Toogle icon link</b>: empty or string</p><p><img src="http://i.imgur.com/FQQzr98.jpg"/></p>', 'email.twitter_link' => '<p><b>Toogle icon link</b>: empty or string</p><p><img src="http://i.imgur.com/5JmkrF1.jpg"/></p>', 'email.footer' => '', 'email.footer_link' => '', 'email.color_body_bg' => '<p><img src="http://i.imgur.com/KTF7rEJ.jpg"/></p>', 'email.color_header_bg' => '<p><img src="http://i.imgur.com/wenw5H5.jpg"/></p>', 'email.color_content_bg' => '<p><img src="http://i.imgur.com/7r8dAFj.jpg"/></p>', 'email.color_footer_bg' => '<p><img src="http://i.imgur.com/KTjkdSN.jpg"/></p>', 'email.color_button_bg' => '<p><img src="http://i.imgur.com/0TbGIyt.jpg"/></p>',

    // permisos
    'agent_restrict'     => 'Restringir l\'accés dels agents a només els seus tiquets assignats<br /><code>0</code>: deshabilitat<br /><code>1</code>: habilitar accés restringit',
    'close_ticket_perm'  => 'Array que especifica quins tipus de membres poden <b>tancar</b> un tiquet',
    'reopen_ticket_perm' => 'Array que especifica quins tipus de membres poden <b>reobrir</b> un tiquet',

    //editor
    'editor_enabled'               => 'Habilitar editor summernote als requadres de text',
    'summernote_locale'            => 'Especificar quin idioma ha d\'utilitzar l\'editor summernote. Si el valor és <code>laravel</code>, s\'utilitzarà la configuració de <code>config/app.php</code>.<br /><br />Exemple: <code>ca-ES</code> per a Català. <a target="_blank" href="https://github.com/summernote/summernote/tree/master/lang">Veure codis d\'idioma disponibles</a>',
    'editor_html_highlighter'      => 'Si s\'inclou <a target="_blank" href="http://summernote.org/examples/#codemirror-as-codeview">el ressaltador de sintaxi de codemirror</a> o no<br /><code>0</code>: No incloure<br /><code>1</code>: Incloure',
    'codemirror_theme'             => '<p>Tema per a ressaltador de sintaxi de <b>codemirror</b></p><a target="_blank" href="https://cdnjs.com/libraries/codemirror/$codemirrorVersion">Veure temes disponibles</a>',
    'summernote_options_json_file' => 'Directori relatiu per al fitxer que conté els valors d\'inici de summernote en format JSON. <a target="_blank" href="http://summernote.org/deep-dive/#initialization-options">Veure opcions disponibles</a><br /><code>default</code>: Utilitzar opcions predeterminades',
    'purifier_config'              => <<<'ENDHTML'
			<p>Configurar quines etiquetes HTML estan permeses</p>
			<p>
				Configurar aquest paràmetre substitueix els que s'indiqui a <a target="_blank" href="https://github.com/mewebstudio/Purifier/blob/master/config/purifier.php">la configuració de Purifier</a><br>
				Es pot fer la mateixa cofniguració executant <code>php artisan vendor:publish</code> i modificant el fitxer <code>config/purifier.php</code>
			</p>

			<p><a target="_blank" href="http://htmlpurifier.org/docs">Documentació de Purifier</a></p>
ENDHTML
    , 'summernote_options_user' => 'Un membre sense permisos actuals utilitzarà les opcions de summernote especificades en aquest paràmetre<br /><code>default</code>: Opcions per defecte de summernote',
];
