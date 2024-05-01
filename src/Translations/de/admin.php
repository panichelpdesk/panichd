<?php

return [

	/*
  *  Constants
  */


	'nav-agents'          => 'Agent',
	'nav-dashboard'       => 'Dashboard',
	'nav-categories'      => 'Kategorien',
	'nav-priorities'      => 'Prioritäten',
	'nav-statuses'        => 'Status',
	'nav-configuration'   => 'Konfiguration',  // New
	'nav-administrators'  => 'Administratoren',
	'nav-dashboard-title' => 'Administrator Dashboard',
	'nav-members'         => 'Nutzer',
	'nav-notices'         => 'Benachrichtigungen',
	'nav-settings'        => 'Settings',

	'table-hash'          => '#', // New
	'table-id'            => 'ID',
	'table-name'          => 'Name',
	'table-action'        => 'Aktion',
	'table-categories'    => 'Kategorien',
	'table-join-category' => 'Zusammengefügte Kategorien',
	'table-remove-agent'  => 'Aus Agenten entfernen',

	'table-slug'    => 'Slug', // New
	'table-default' => 'Standardwert', // New
	'table-value'   => 'Mein Wert',  // New
	'table-lang'    => 'Sprache(n)', // New
	'table-edit'    => 'Bearbeiten', // New

	'btn-back'                         => 'Zurück',
	'btn-delete'                       => 'Löschen',
	'btn-edit'                         => 'Bearbeiten',
	'btn-join'                         => 'Join',
	'btn-remove'                       => 'Entfernen',
	'btn-submit'                       => 'Bestätigen',
	'btn-save'                         => 'Speichern',  // New
	'btn-update'                       => 'Aktualisieren',

	// Vocabulary
	'admin'                            => 'Administrator',
	'colon'                            => ': ',
	'role'                             => 'Rolle',

	/* Access Levels */
	'level-1'                          => 'Jeder',
	'level-2'                          => 'Zugewiesene Ticketmanger und Administratoren.',
	'level-3'                          => 'Administratoren.',

	/*
  *  Page specific
  */

	// $admin_route_path/dashboard
	'index-title'                      => 'Tickets System Dashboard',
	'index-empty-records'              => 'Keine Tickets vorhangen',
	'index-total-tickets'              => 'Alle Tickets',
	'index-newest-tickets'             => 'Neue Tickets',
	'index-active-tickets'             => 'Aktive Tickets',
	'index-complete-tickets'           => 'Geschlossene Tickets',
	'index-performance-indicator'      => 'Performanz Indikator',
	'index-periods'                    => 'Zeitabschnitte',
	'index-3-months'                   => '3 Monate',
	'index-6-months'                   => '6 Monate',
	'index-12-months'                  => '12 Monate',
	'index-tickets-share-per-category' => 'Anteile der Tickets pro Kategorie',
	'index-tickets-share-per-agent'    => 'Anteile der Tickets pro Ticketmanager',
	'index-categories'                 => 'Kategorien',
	'index-category'                   => 'Kategorie',
	'index-agents'                     => 'Ticketmanager',
	'index-agent'                      => 'Ticketmanager',
	'index-administrators'             => 'Administratoren',
	'index-administrator'              => 'Administrator',
	'index-users'                      => 'Benutzer',
	'index-user'                       => 'Benutzer',
	'index-tickets'                    => 'Tickets',
	'index-open'                       => 'Offen',
	'index-closed'                     => 'Geschlossen',
	'index-total'                      => 'Gesamt',
	'index-month'                      => 'Monat',
	'index-performance-chart'          => 'Durchschnitt der Anzahl der Tage um ein Ticket zu bearbeiten?',
	'index-categories-chart'           => 'Verteilung der Tickets pro Kategorie',
	'index-agents-chart'               => 'Verteilung der Tickets pro Ticketmanager',
	'index-view-category-tickets'      => 'Anzeigen :list Tickets pro :category category',
	'index-view-agent-tickets'         => 'Anzeigen den Ticketmanagern zugewiesene :list tickets',
	'index-view-user-tickets'          => 'Mir zugewiesene Tickets :list tickets',


	// $admin_route_path/agent/____
	'agent-index-title'                => 'Agenten Verwalten',
	'btn-create-new-agent'             => 'Neuen Agenten anlegen',
	'agent-index-no-agents'            => 'Es sind keine Agenten vorhanden, ',
	'agent-index-create-new'           => 'Agent hinzufügen',
	'agent-create-title'               => 'Agent hinzufügen',
	'agent-create-add-agents'          => 'Agent hinzufügen',
	'agent-create-no-users'            => 'Es sind keine Nutzeraccounts vorhanden. Erstellen Sie zuerst einen Nutzer.',
	'agent-create-select-user'         => 'Fügen Sie einen Nutzeraccount den Agenten hinzu.',
	'agent-store-ok'                   => 'Benutzer ":name" wurde den Ticketmanagern hinzugefügt',
	'agent-updated-ok'                 => 'Ticketmanager ":name" aktualisiert',
	'agent-excluded-ok'                => 'Ticketmanager ":name" exkludiert',

	'agent-store-error-no-category'           => 'Die müsssen dem Ticketemanager mindestens eine Kategorie zuweisen',

	//$admin_route_path/agent/edit
	'agent-edit-title'                        => 'Nutzer Berechtigungen :agent',
	'agent-edit-table-category'               => 'Kategorie',
	'agent-edit-table-agent'                  => 'Ticketmanger Berechtigungen',
	'agent-edit-table-autoassign'             => 'Neue Tickets automatisch.',


	// $admin_route_path/administrators/____
	'administrator-index-title'               => 'Verwaltung der Administratoren',
	'btn-create-new-administrator'            => 'Neuen Adminstrator anlegen',
	'administrator-index-no-administrators'   => 'Es sind keine Administratoren vorhanden, ',
	'administrator-index-create-new'          => 'Neuen Adminstrator anlegen',
	'administrator-create-title'              => 'Neuen Adminstrator anlegen',
	'administrator-create-add-administrators' => 'Neuen Adminstrator anlegen',
	'administrator-create-no-users'           => 'Es sind keine Nutzeraccounts vorhanden. Erstellen Sie zuerst einen Nutzer.',
	'administrator-create-select-user'        => 'Fügen Sie einen Nutzeraccount den Adminstratoren hinzu.',

	// $admin_route_path/category/____
	'category-index-title'                    => 'Kategorie Verwaltung',
	'btn-create-new-category'                 => 'Neue Kategorie erstellen',
	'category-index-no-categories'            => 'Es sind keine Kategorien vorhanden, ',
	'category-index-create-new'               => 'erstellen Sie jetzt eine neue Kategorie ',
	'category-index-js-delete'                => 'Sind Sie sich sicher, dass Sie diese Kategorie löschen wollen: ',
	'category-create-title'                   => 'Neue Kategorie anlegen',
	'category-create-name'                    => 'Name',
	'category-create-color'                   => 'Farbe',
	'category-edit-title'                     => 'Kategorie bearbeiten: :name',

	'category-index-email'   => 'Notifications email',
	'category-index-reasons' => 'Closing reasons',
	'category-index-tags'    => 'Tags',

	'category-create-email'          => 'Benachrichtigung E-Mail',
	'category-email-origin'          => 'Herkunft',
	'category-email-origin-website'  => 'Website',
	'category-email-origin-tickets'  => 'Allgemeine E-Mail für Tickets',
	'category-email-origin-category' => 'Diese Kategorie',
	'category-email-from'            => 'Von',
	'category-email-name'            => 'Name',
	'category-email'                 => 'E-Mail',
	'category-email-reply-to'        => 'Antworten an',
	'category-email-default'         => 'Standard',
	'category-email-this'            => 'Dieses Postfach',
	'category-email-from-info'       => 'Absender hat alle Benachrichtigungen aus dieser Kategorie benutzt.',
	'category-email-reply-to-info'   => 'Empfänger E-Mail für Antworten und Banchrichtigungen',
	'category-email-reply-this-info' => 'Wie unten beschrieben',

	'category-create-new-tickets'      => 'Wer neue Tickets erstellen kann',
	'category-create-new-tickets-help' => 'Minimale Berechtigung um Tickets dieser Kategorie erstellen zu können',

	'category-edit-closing-reasons'      => 'Gründe für das Schließen des Tickets',
	'category-edit-closing-reasons-help' => 'Optionen die Benutzer auswählen können, wenn das Ticket geschlossen wird',
	'category-edit-reason'               => 'Gründe für das Schließen',
	'category-edit-reason-label'         => 'Grund',
	'category-edit-reason-status'        => 'Status',
	'category-delete-reason'             => 'Gründe für das Löschen',

	'category-edit-new-tags'        => 'Neue Tags',
	'category-edit-current-tags'    => 'Aktueller Tags',
	'category-edit-new-tag-title'   => 'Neuen Tag erstellen',
	'category-edit-new-tag-default' => 'Neuer Tag',
	'category-edit-tag'             => 'Tag bearbeiten',
	'category-edit-tag-background'  => 'Hintergrund',
	'category-edit-tag-text'        => 'Text',

	'new-tag-validation-empty'    => 'Sie können keinen Tag mit leerem Namen erstellen.',
	'update-tag-validation-empty' => 'Sie können keinen Tag mit leerem Namen erstellen. Vorheriger Name ":name"',

	// Category form validation
	'category-reason-is-empty'    => 'Geschlossen :number hat keinen Text',
	'category-reason-too-short'   => 'Geschlossen :number mit dem Namen ":name" benötigt :min Zeichen',
	'category-reason-no-status'   => 'Geschlossen :number mit dem Namen ":name" benötigt einen definierten Status',

	'tag-regex'                         => '/^[A-Za-z0-9?@\/\-_\s]+$/',
	'category-tag-not-valid-format'     => 'Tag ":tag" ist kein gültiges Format',
	'tag-validation-two'                => 'Der Name des Tags existiert bereits ":name". Es sind keine duplizierten Tags erlaubt',


	// $admin_route_path/member/____
	'member-index-title'                => 'Nutzerverwaltung',
	'member-index-help'                 => 'Nutzer sind alle registrierten Nutzer in der Datenbank. Der Administrator hat die Liste eventuell gefiltert',
	'member-index-empty'                => 'Es wurden keine registrierten Nutzer gefunden',
	'member-table-own-tickets'          => 'Eigene Tickets',
	'member-table-assigned-tickets'     => 'Ihnen zugewiesene Tickets',
	'member-modal-update-title'         => 'Mitglieder aktualisieren',
	'member-modal-create-title'         => 'Mitglied erstellen',
	'member-delete-confirmation'        => 'Sind Sie sich sicher, dass Sie diesen Nutzer endgültig aus der Datenbank löschen möchten?',
	'member-password-label'             => 'Passwort',
	'member-new-password-label'         => 'Neues Passwort (optional)',
	'member-password-repeat-label'      => 'Passwort wiederholen',
	'member-added-ok'                   => 'Nutzer ":name" wurde erfolgreich erstellt',
	'member-updated-ok'                 => 'Nutzer ":name" wurde erfolgreich aktualisiert',
	'member-deleted'                    => 'Nutzer ":name" wurde erolgreich GELÖSCHT',
	'member-delete-own-user-error'      => 'Sie können Ihren eigenen Account nicht löschen',
	'member-delete-agent'               => 'Um diesen Nutzer löschen zu können müssen Sie zuerst die Rolle dem Ticketmanager entziehen',
	'member-with-tickets-delete'        => 'Sie können keinen Nutzer löschen dem ein Ticket zugewiesen ist.',


	// $admin_route_path/priority/____
	'priority-index-title'              => 'Prioritäten verwalten',
	'btn-create-new-priority'           => 'Neue Priorität erstellen',
	'priority-index-no-priorities'      => 'Es sind keine Prioritäten vorhanden, ',
	'priority-index-create-new'         => 'Neue Priorität erstellen',
	'priority-index-js-delete'          => 'Sind Sie sich sicher, dass Sie diese Priorität löschen wollen: ',
	'priority-create-title'             => 'Neue Priorität erstellen',
	'priority-create-name'              => 'Name',
	'priority-create-color'             => 'Farbe',
	'priority-edit-title'               => 'Priorität bearbeiten: :name',
	'priority-index-help'               => 'Sie können die Priorität ändern indem Sie die Tabellenzeilen per "Drag and Drop" verschieben . Diese Reihenfolge wird auch auf die Liste der Tickets angewendet, wenn Sie dieses Feld ankreuzen.',
	'priority-delete-title'             => 'Priorität löschen: :name',
	'priority-delete-warning'           => 'Es gibt <span class="modal-tickets-count"></span> Tickets mit dieser Priorität. Sie müssen diesen Tickets eine andere Priorität zuweisen.',
	'priority-delete-error-no-priority' => 'Sie müssen eine neue Priorität für ":name" anlegen. Und dementsprechend Tickets zuweisen',


	// $admin_route_path/status/____
	'status-index-title'                => 'Status verwalten',
	'btn-create-new-status'             => 'Neuen Status erstellen',
	'status-index-no-statuses'          => 'Es sind keine Status vorhanden,',
	'status-index-create-new'           => 'Neuen Status erstellen',
	'status-index-js-delete'            => 'Sind Sie sich sicher, dass Sie diesen Status löschen wollen: ',
	'status-create-title'               => 'Neuen Status erstellen',
	'status-create-name'                => 'Name',
	'status-create-color'               => 'Farbe',
	'status-edit-title'                 => 'Status bearbeiten: :name',


	// $admin_route_path/notice/____
	'notice-index-title'                => 'Notices to departments management',
	'btn-create-new-notice'             => 'Add notice',
	'notice-index-empty'                => 'There are no notices configured.',
	'notice-index-owner'                => 'Owner',
	'notice-index-email'                => 'Notice e-mail',
	'notice-index-department'           => 'Notice visible for',
	'notice-modal-title-create'         => 'Add a notice to department',
	'notice-modal-title-update'         => 'Update a notice to department',
	'notice-saved-ok'                   => 'Notice saved correctly',
	'notice-deleted-ok'                 => 'Notice deleted',
	'notice-index-js-delete'            => 'Are you sure you want to delete this notice?',
	'notice-index-help'                 => 'When a ticket set with one of the following owners is created, there will happen two things:<br /><br /><ol><li>An e-mail will be sent to ticket <b>owner</b>, with a specific e-mail template.</li><li>As long as the ticket is <b>open</b>, users in the same department will see the ticket as a <b>notice</b> in the create ticket menu.',
	'notice-index-owner-alert'          => 'A normal user, when creating a new ticket, will not be able to see any user listed here',

	// $admin_route_path/configuration/____
	'config-index-title'                => 'Konfigurationseinstellungen', // New
	'config-index-subtitle'             => 'Einstellungen', // New
	'btn-create-new-config'             => 'Neue Einstellung hinzufügen', // New
	'config-index-no-settings'          => 'Es sind keine Einstellungen vorhanen,', // New
	'config-edit-title'                 => 'Bearbeiten: Globale Konfiguration',
	'config-edit-subtitle'              => 'Einstellungen bearbeiten',
	'config-edit-id'                    => 'ID',
	'config-edit-slug'                  => 'Slug',
	'config-edit-default'               => 'Standardwert',
	'config-edit-value'                 => 'Mein Wert',
	'config-edit-language'              => 'Sprache',
	'config-index-initial'              => 'Initial',
	'config-index-features'             => 'Funktionen',
	'config-index-tickets'              => 'Tickets',
	'config-index-table'                => 'Tabelle',
	'config-index-notifications'        => 'Benachrichtigungen',
	'config-index-permissions'          => 'Berechtigungen',
	'config-index-editor'               => 'Editor', //Added: 2016.01.14
	'config-index-other'                => 'Sonstiges',
	'config-create-title'               => 'Erstellen: Neue Globale Einstellung',
	'config-create-subtitle'            => 'Einstellung erstellen',
	'config-edit-unserialize'           => 'Anzeigen und verändern der Werte des Arrays',
	'config-edit-serialize'             => 'Anzeigen der serialiesierten Zeichenkette der veränderten Werte (muss im dementsprechenden Feld eingetragen werden)',
	'config-edit-should-serialize'      => 'Serialisiert', //Added: 2016-01-16
	'config-edit-eval-warning'          => 'Wenn angekreuzt -> Server führt folgenden Befehl aus: eval()!
  									 Benutzen Sie diese Funktion nicht wenn eval() auf Ihrem Server deaktiviert ist oder Sie nicht mit der Funktion vertraut sind!
  									  Exact code executed:', //Added: 2016-01-16
	'config-edit-reenter-password'      => 'Geben Sie Ihr Passwort erneut ein', //Added: 2016-01-16
	'config-edit-auth-failed'           => 'Passwörter stimmen nicht überein', //Added: 2016-01-16
	'config-edit-eval-error'            => 'Ungültiger Wert', //Added: 2016-01-16
	'config-edit-tools'                 => 'Werkzeuge:',
	'config-update-confirm'             => 'Einstellung :name wurde aktualisiert',
	'config-delete-confirm'             => 'Einstellung :name wurde gelöscht',


];
