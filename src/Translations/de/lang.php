<?php

return [

	/*
	 *  Constants
	 */
	'nav-new-tickets'             => 'Neu',
	'nav-new-tickets-title'       => 'Neue Tickets',
	'nav-new-dd-list'             => 'Liste',
	'nav-new-dd-list-title'       => 'Neue Ticket Liste',
	'nav-new-dd-create'           => 'Erstellen',
	'nav-create-ticket'           => 'Erstellen',
	'nav-create-ticket-title'     => 'Neues Ticket erstellen',
	'nav-notices-number-title'    => ':num Benachrichtigungen ',
	'ticket-notices-empty'        => 'Keine Benachrichtigungen',
	'nav-active-tickets-title'    => 'Aktive Tickets',
	'nav-completed-tickets-title' => 'Abgeschlossene Tickets',

	'nav-active-tickets'    => 'Offene Tickets',
	'nav-completed-tickets' => 'Geschlossene Tickets',

	// Tables
	'table-id'              => '#',
	'table-subject'         => 'Betreff',
	'table-owner'           => 'Ersteller',
	'table-status'          => 'Status',
	'table-last-updated'    => 'Zuletzt aktualisiert',
	'table-priority'        => 'Priorität',
	'table-agent'           => 'Agent',
	'table-category'        => 'Kategorie',
	'table-department'      => 'Abteilung',
	'table-description'     => 'Beschreibung',
	'table-intervention'    => 'Klärung',
	'table-calendar'        => 'Kalender',
	'table-completed_at'    => 'Fertigestellt am',
	'table-tags'            => 'Tags',

	'no-tickets-yet'            => 'Keine Tickets vorhanden', // Pending to move old admin.index-empty-records in other languages
	'list-no-tickets'           => 'Es sind keine Tickets in dieser Liste',
	'updated-by-other'          => 'Durch anderen Benutzer aktualisiert',
	'mark-as-read'              => 'Als gelesen Markieren',
	'mark-as-unread'            => 'Als ungelesen markieren und sperren',
	'read-validation-error'     => 'Ticket konnte nicht als un-/gelesen markiert werden',
	'read-validation-ok-read'   => 'Ticket als gelesen markiert',
	'read-validation-ok-unread' => 'Ticket als ungelesen markiert',

	'table-info-attachments-total' => ':num Anhänge',
	'table-info-comments-total'    => ':num Kommentare.',
	'table-info-comments-recent'   => ':num Aktuelle Kommentare.',
	'table-info-notes-total'       => ':num Interne Kommentare',

	'calendar-active'           => 'Gestartet :description',
	'calendar-active-today'     => 'Gestartet :description',
	'calendar-active-future'    => 'Im Start :description',
	'calendar-expired'          => 'Abgelaufen :description',
	'calendar-expired-today'    => 'Abgelaufen heute um :time',
	'calendar-expiration'       => 'Abgelaufen :description',
	'calendar-expires-today'    => 'Wird heute um :hour ablaufen',
	'calendar-scheduled'        => 'Terminiert für :date von :time1 bis :time2H',
	'calendar-scheduled-today'  => 'Terminiert heute von :time1 bis :time2H',
	'calendar-scheduled-period' => 'Terminiert von :date1 bis :date2',

	// Agent related
	'table-change-agent'        => 'Ticketmanager ändern',
	'table-one-agent'           => 'In dieser Kategorie ist ein Ticketmanager vorhanden',
	'table-agent-status-check'  => 'Status ändern in ":status"',

	// list AJAX changes
	'table-change-priority'     => 'Priorität ändern',
	'table-change-status'       => 'Status ändern',

	// Datatables

	'table-decimal'         => '',
	'table-empty'           => 'Keine Daten in der Tabelle verfügbar',
	'table-info'            => 'Zeige _START_ bis _END_ von _TOTAL_ Einträgen',
	'table-info-empty'      => 'Zeige 0 bis 0 von 0 Einträgen',
	'table-info-filtered'   => '(gefiltert von _MAX_ total Einträgen)',
	'table-info-postfix'    => '',
	'table-thousands'       => ',',
	'table-length-menu'     => 'Zeige _MENU_ Einträge',
	'table-loading-results' => 'Lade...',
	'table-processing'      => 'Verarbeitung...',
	'table-search'          => 'Suche:',
	'table-zero-records'    => 'Keine passenden Einträge gefunden',
	'table-paginate-first'  => 'Start',
	'table-paginate-last'   => 'Ende',
	'table-paginate-next'   => 'Vor',
	'table-paginate-prev'   => 'Zurück',
	'table-aria-sort-asc'   => ': aktivieren um diese Spalte aufsteigend zu sortieren',
	'table-aria-sort-desc'  => ': aktivieren um diese Spalte absteigend zu sortieren',

	'filter-removeall-title'         => 'Filter entfernen',
	'filter-pov'                     => 'Anzeigen als',
	'filter-pov-member-title'        => 'Anzeigen als Benutzer',
	'filter-pov-agent-title'         => 'Anzeigen als Ticketmanager',
	'filter-year-all'                => 'Alle',
	'filter-calendar'                => 'Kalender',
	'filter-calendar-all'            => 'Alle',
	'filter-calendar-expired'        => 'Abgelaufen',
	'filter-calendar-not-scheduled'  => 'Nicht terminiert',
	'filter-calendar-today'          => 'Läuft heute ab',
	'filter-calendar-tomorrow'       => 'Läuft morgen ab',
	'filter-calendar-week'           => 'Diese Woche',
	'filter-calendar-month'          => 'Diesen Monat',
	'filter-calendar-within-7-days'  => 'In 7 Tagen',
	'filter-calendar-within-14-days' => 'In 14 Tagen',
	'filter-category'                => 'Kategorie',
	'filter-category-all'            => 'Alle',
	'filter-owner-all'               => 'Alle',
	'filter-agent'                   => 'Ticketmanager',
	'filter-agent-all'               => 'Alle',

	'btn-back'                   => 'Zurück',
	'btn-cancel'                 => 'Abbrechen', // NEW
	'btn-close'                  => 'Schliessen',
	'btn-delete'                 => 'Löschen',
	'btn-edit'                   => 'Bearbeiten',
	'btn-mark-complete'          => 'Als geschlossen markieren',
	'btn-submit'                 => 'Absenden',
	'btn-add'                    => 'Hinzufügen',
	'btn-change'                 => 'Ändern',
	'btn-download'               => 'Herunterladen',


	// Vocabulary
	'active-tickets-adjective'   => 'Active',
	'agent'                      => 'Ticketmanager',
	'agents'                     => 'Ticketmanager',
	'all-depts'                  => 'Alle',
	'attached-images'            => 'Angehängte Bilder',
	'attached-files'             => 'Angehängte Dateien',
	'attachments'                => 'Anhänge',
	'category'                   => 'Kategorie',
	'closing-reason'             => 'Grund für Schließung',
	'closing-clarifications'     => 'Erläuterungen',
	'colon'                      => ': ',
	'comments'                   => 'Kommentare',
	'complete'                   => 'Abschließen',
	'complete-tickets-adjective' => 'Abgeschlossen',
	'created'                    => 'Erstellt',
	'creation-date'              => 'Erstellt am :date',
	'crop-image'                 => 'Bild zuschneiden',
	'date-format'                => 'd-m-Y',
	'datetime-format'            => 'd-m-Y H:i',
	'datetimepicker-format'      => 'DD-MM-YYYY HH:mm',
	'datetime-text'              => ':date, :timeh',
	'deleted-member'             => 'Gelöschte Mitglieder',
	'department'                 => 'Abteilung',
	'department-shortening'      => 'Abteil.',
	'dept-descendant'            => 'Unterabteilung',
	'description'                => 'Beschreibung',
	'discard'                    => 'Verwerfen',
	'email'                      => 'E-Mail',
	'email-resend-abbr'          => 'FW',
	'flash-x'                    => '×', // &times;
	'intervention'               => 'Einspruch',
	'last-update'                => 'Zuletzt aktualisiert',
	'limit-date'                 => 'datum eingrenzen',
	'list'                       => 'Liste',
	'mark-complete'              => 'Als abgeschlossen markieren',
	'member'                     => 'Benutzer',
	'name'                       => 'Name',
	'newest-tickets-adjective'   => 'Neu',
	'no'                         => 'Nein',
	'no-replies'                 => 'Keine Antworten.',

	'owner'                   => 'Ersteller',
	'priority'                => 'Priorität',
	'reopen-ticket'           => 'Ticket wieder öffnen',
	'reply'                   => 'Antworten',
	'responsible'             => 'Verantwortlich',
	'start-date'              => 'Startdatum',
	'status'                  => 'Status',
	'subject'                 => 'Betreff',
	'tags'                    => 'Tags',
	'ticket'                  => 'Ticket',
	'tickets'                 => 'Tickets',
	'today'                   => 'Heute',
	'tomorrow'                => 'Morgen',
	'update'                  => 'Akutalisierung',
	'updated-date'            => 'Akutalisierung :date',
	'user'                    => 'Benutzer',
	'year'                    => 'Jahr',
	'yes'                     => 'Ja',
	'yesterday'               => 'Gestern',

	// Days of week
	'day_1'                   => 'Montag',
	'day_2'                   => 'Dienstag',
	'day_3'                   => 'Mittwoch',
	'day_4'                   => 'Donnerstag',
	'day_5'                   => 'Freitag',
	'day_6'                   => 'Samstag',
	'day_7'                   => 'Sonntag',
	'day_0'                   => 'Sonntag',

	// Time units abbreviations
	'second-abbr'             => 'S.',
	'minute-abbr'             => 'M.',
	'hour-abbr'               => 'H.',
	'day-abbr'                => 'D.',
	'week-abbr'               => 'W.',
	'month-abbr'              => 'Mo.',

	/*
	 *  Page specific
	 */

	// ____
	'index-title'             => 'Helpdesk Hauptseite',

	// tickets/____
	'index-my-tickets'        => 'Meine Tickets',
	'btn-create-new-ticket'   => 'Neues Ticket erstellen',
	'index-complete-none'     => 'Es gibt keine geschlossenen Tickets',
	'index-active-check'      => 'Bitte betrachte die Offenen Tickets wenn du dein Ticket nicht finden kannst.',
	'index-active-none'       => 'Es gibt keine aktiven Tickets,',
	'index-create-new-ticket' => 'neues Ticket erstellen',
	'index-complete-check'    => 'Bitte betrachte die Geschlossenen Tickets wenn du dein Ticket nicht finden kannst.',

	// Newest tickets page reload Modal
	'reload-countdown'        => 'Die Tabelle wird in <kbd class=":num_class"><span id="counter">:num</span></kbd> Sekunden aktualisiert.',
	'reload-reloading'        => 'Die Tabelle wird neu geladen. Bitte warten Sie.',

	// Ticket forms messages
	'update-agent-same'       => 'Der Ticketmanager wurde nicht geändert! Ticket <a href=":link" title=":title"><u>:name</u></a>',
	'update-agent-ok'         => 'Der Ticketmanager wurde zu ":new_agent" geändert. Ticket <a href=":link" title=":title"><u>:name</u></a>',
	'update-priority-same'    => 'Die Priorität wurde nicht geändert! Ticket <a href=":link" title=":title"><u>:name</u></a>',
	'update-priority-ok'      => 'Die Priorität wurde zu  ":new" geändert. Ticket <a href=":link" title=":title"><u>:name</u></a>',
	'update-status-same'      => 'Der Status wurde nicht geändert! Ticket <a href=":link" title=":title"><u>:name</u></a>',
	'update-status-ok'        => 'Der Status  wurde zu ":new" geändert. Tickett <a href=":link" title=":title"><u>:name</u></a>',


	// tickets/create

	'create-new-ticket'               => 'Neues Ticket erstellen',
	'create-ticket-brief-issue'       => 'Kurzbeschreibung',
	'create-ticket-notices'           => 'Anmerkungen',
	'ticket-owner-deleted-warning'    => 'Benutzer wurde nicht gelöscht. Das Ticket wird wird nocht merh in der Inhabertabelle erscheinen',
	'ticket-owner-no-email'           => '(Besitzt keine E-Mail)',
	'ticket-owner-no-email-warning'   => 'esitzt keine E-Mail: Der Empfänger wird keine Benachrichtigungen erhalten ',
	'create-ticket-owner-help'        => 'Sie können aus dem Inhaber oder dem Betroffenen auswählen',
	'create-ticket-visible'           => 'Sichtbar',
	'create-ticket-visible-help'      => 'Wählen Sie die Sichtbarkeit des Tickets für den zugewiesen Inhaber aus',
	'create-ticket-change-list'       => 'Liste wurde geändert',
	'create-ticket-info-start-date'   => 'Standard: Jetzt',
	'create-ticket-info-limit-date'   => 'Standard: Kein Limit',
	'create-ticket-describe-issue'    => 'Detaillierte Beschreibung',
	'create-ticket-intervention-help' => 'Eingriffe um das Ticket abzuschließen',
	'create-ticket-switch-to-note'    => 'Zur internen Anmerkung wechseln',
	'create-ticket-switch-to-comment' => 'Zur Nutzerasicht wechseln um Benutzer zu antworten',

	'attach-files'        => 'Anhänge hinzufügen',
	'pending-attachment'  => 'Diese Datei wird hinzugefügt, wenn das Ticket aktualisiert wird',
	'attachment-new-name' => 'Neuer Name',

	'edit-ticket'                       => 'Ticket bearbeiten',
	'attachment-edit'                   => 'Anhang bearbeiten',
	'attachment-edit-original-filename' => 'Originaler Dateiname',
	'attachment-edit-new-filename'      => 'Neuer Dateiname',
	'attachment-edit-crop-info'         => 'Wählen Sie einen Bereich innerhalb des Bildes aus, um es zuzuschneiden. Es wird nach der Aktualisierung der Felder angewendet',

	'attachment-update-not-valid-name' => 'Neuer Dateiname für ":file" muss mindestens 3 Zeichen lang sein. HTML ist nicht erlaubt',
	'attachment-error-equal-name'      => 'Name und Beschreibung der Datei ":file" dürfen nicht identisch sein',
	'attachment-update-not-valid-mime' => 'Die Datei ":file" besitzt keinen gültigen Dateityp',
	'attachment-update-crop-error'     => 'Bild konnte nicht zugeschnitten werden',


	'show-ticket-title'                => 'Ticket',
	'show-ticket-creator'              => 'Erstellt von',
	'show-ticket-js-delete'            => 'Möchtest du wirklich folgendes löschen: ',
	'show-ticket-modal-delete-title'   => 'Ticket Löschen',
	'show-ticket-modal-delete-message' => 'Möchtest du dieses Ticket wirklich löschen: :subject?',
	'show-ticket-modal-edit-fields'    => 'Edit more fields',

	'show-ticket-modal-complete-blank-intervention-check' => 'Einspruchsfeld leer lassen',
	'show-ticket-complete-blank-intervention-alert'       => 'Um das Ticket abschließen zu können, müssen Sie bestätigen, dass Sie das Einspruchsfeld leer lassen',
	'show-ticket-modal-complete-blank-reason-alert'       => 'Um das Ticket abschließen zu können, müssen Sie einen Grund für die Schließung des Tickets angeben',
	'show-ticket-complete-bad-status'                     => 'Ticket nicht abgeschlossen: Der angegebene Status ist nicht valide',
	'show-ticket-complete-bad-reason-id'                  => 'Ticket nicht abgeschlossen: Der angegebene Grund ist nicht valide',

	'complete-by-user' => 'Ticket abgeschlossen von :user.',
	'reopened-by-user' => 'Ticket erneut geöffnet von :user.',

	'ticket-error-not-valid-file'                => 'Eine invalide Datei wurde angehangen',
	'ticket-error-not-valid-object'              => 'Diese Datei kann nicht verarbeitet werden: :name',
	'ticket-error-max-size-reached'              => 'Die Datei ":name" und folgende Dateien können nicht angehangen werden, da Sie die maximale Größe des Tickets von :available_MB MB überschreiten',
	'ticket-error-max-attachments-count-reached' => 'Die Datei ":name" und folgende Dateien können nicht angehangen werden, da Sie die maximale Anzahl der Anhänge von :max_count pro Ticket überschreitet',
	'ticket-error-delete-files'                  => 'Einige Dateien konnten nicht gelöscht werden',
	'ticket-error-file-not-found'                => 'Die Datei ":name" konnte nicht gefunden werden',
	'ticket-error-file-not-deleted'              => 'Die Datei ":name" onnte nicht gelöscht werden',

	// Tiquet visible / no visible
	'ticket-visible'                             => 'Sichtbares Ticket',
	'ticket-hidden'                              => 'Nicht sichtbares Ticket',
	'ticket-hidden-button-title'                 => 'Sichtbarkeit für Nutzer ändern',
	'ticket-visibility-changed'                  => 'Sichtbarkeit für Nutzer geändert',
	'ticket-hidden-0-comment-title'              => 'Sichtbarkeit geändert. Sichtbar von <b>:agent</b>',
	'ticket-hidden-0-comment'                    => 'Das Ticket ist nun <b>sichtbar</b> für den Inhaber',
	'ticket-hidden-1-comment-title'              => 'Geändert in nicht sichtbar durch  <b>:agent</b>',
	'ticket-hidden-1-comment'                    => 'Das Ticket ist nun <b>unsichtbar</b> für den Inhaber',

	// Comments
	'comment'                                    => 'Kommentar',
	'note'                                       => 'Interne Anmerkung',
	'comment-reply-title'                        => 'Nachricht sichtbar für Nutzer',
	'comment-reply-from-owner'                   => 'Antwort von <b>:owner</b>',
	'reply-from-owner-to'                        => 'Antwort von <b>:owner</b> an <b>:recipients</b>',

	'comment-note-title'         => 'Vom Nutzer nicht sichtaber Notiz',
	'comment-note-from-agent'    => 'Notiz von <b>:agent</b>',
	'comment-note-from-agent-to' => 'Notiz von <b>:agent</b> an <b>:recipients</b>',

	'comment-completetx-title' => 'Ticket abgeschlossen',
	'comment-complete-by'      => 'Geschlossen durch <b>:owner</b>',

	'comment-reopen-title' => 'Ticket erneut geönffet',
	'comment-reopen-by'    => 'Ticket erneut geönffet con <b>:owner</b>',

	'show-ticket-add-comment'                => 'Kommentar hinzufügen',
	'show-ticket-add-note'                   => 'Interne Anmerkung hinzufügen',
	'show-ticket-add-comment-type'           => 'Typ',
	'show-ticket-add-comment-note'           => 'Interne Anmerkung',
	'show-ticket-add-comment-reply'          => 'Antwort an Nutzer',
	'show-ticket-add-comment-notificate'     => 'Benachrichtigen',
	'show-ticket-add-com-check-email-text'   => 'Text der Nutzerbenachrichtigung hinzufügen',
	'show-ticket-add-com-check-intervention' => 'Diesen Text im Einspruchsfeld anhängen (sichtbar von Nutzer)',
	'show-ticket-add-com-check-resolve'      => 'Dieses Ticket abschließen und Status zuweisen.',
	'add-comment-confirm-blank-intervention' => 'Das Feld "Einspruch" ist leer. Möchten Sie das Ticket sofort schließen?',

	'edit-internal-note-title'          => 'Interne Anmerkung bearbeiten',
	'show-ticket-edit-com-check-int'    => 'Text dem Einsoruchsfeld hinzufügen',
	'show-ticket-delete-comment'        => 'Kommentar löschen',
	'show-ticket-delete-comment-msg'    => 'Sind Sie sich sicher, dass Sie diesen Kommentar löschen wollen?',
	'show-ticket-email-resend'          => 'E-Mail erneut versenden',
	'show-ticket-email-resend-agent'    => '(Ticketmanager)',
	'show-ticket-email-resend-owner'    => '(Ticketinhaber)',
	'notification-resend-confirmation'  => 'Benachrichtigungen wurd erneut versandt',
	'notification-resend-no-recipients' => 'Es wurden keine Empfänger ausgewählt',

	// Validations
	'validation-error'                  => 'Das Formular wurde nicht versandt',
	'validate-ticket-subject.required'  => 'Ein Betreff muss gesetzt sein. Bitte fügen Sie einen Betreff hinzu.',
	'validate-ticket-subject.min'       => 'Der Betreff sollte etwas länger sein',
	'validate-ticket-content.required'  => 'Die Beschreibung muss gesetzt sein. Wenn Sie ein Bild Anhängen müssen Sie sowieso einen Text hinzufügen.',
	'validate-ticket-content.min'       => 'Die Beschreibun sollte etwas länger sein, obwohl bereits ein Bild angehängt wurde.',
	'validate-ticket-start_date-format' => 'Das Format des Startdatums ist nicht korrekt. Ein valides Format ist: ":format"',
	'validate-ticket-start_date'        => 'Jahr des Startdatums ungültig',
	'validate-ticket-limit_date-format' => 'Das Format des Enddatum ist nicht korrekt. Ein valides Format ist: ":format"',
	'validate-ticket-limit_date'        => 'Jahr des Enddatum ungültig',
	'validate-ticket-limit_date-lower'  => 'Das Enddatum darf nicht vor dem Startdatum liegen',

	'ticket-destroy-error'      => 'Das Ticket konnte nicht gelöscht werden: :error',
	'comment-destroy-error'     => 'Der Kommentar konnte nicht gelöscht werden: :error',

	// Comment form
	'validate-comment-required' => 'Sie müssen einen Kommentar hinzufügen',
	'validate-comment-min'      => 'Der Kommentar muss etwas länger sein',

	// Ticket search form
	'searchform-nav-text'       => 'Suche',
	'searchform-title'          => 'Nach Tickets suchen',

	'searchform-creator'         => 'Ersteller',
	'searchform-department'      => 'Abteilung',
	'searchform-comments'        => 'Kommentar Text',
	'searchform-attachment_text' => 'Anhang Text',
	'searchform-any_text_field'  => 'Textfeld für Volltextsuche',
	'searchform-created_at'      => 'Erstellt am',
	'searchform-completed_at'    => 'Abgeschlossen am',
	'searchform-updated_at'      => 'Zuletzt aktualisiert',
	'searchform-read_by_agent'   => 'Gelesen durch Ticketmanager',

	'searchform-help-creator'        => 'Der Ersteller des Tickets (Manchmal durch Ticketmanager in Namen des Nutzers angelegt)',
	'searchform-help-owner'          => 'Nutzer der Inhaber des Tickets ist',
	'searchform-help-department'     => 'Inhaber Abteilung',
	'searchform-help-any_text_field' => 'Volltextsuche in den Feldern: Betreff, Beschreibung, Einspruch, Kommentar oder Anhang',

	'searchform-creator-none'    => '- keiner -',
	'searchform-owner-none'      => '- keiner -',
	'searchform-department-none' => '- kein -',
	'searchform-list-none'       => '- keine -',

	'searchform-status-none'      => '- niemand -',
	'searchform-status-rule-any'  => 'Ein der ausgewählten',
	'searchform-status-rule-none' => 'Kein der ausgewählten',

	'searchform-priority-none'      => '- keine -',
	'searchform-priority-rule-any'  => 'Eines der ausgewählten',
	'searchform-priority-rule-none' => 'Keines der ausgewählten',

	'searchform-category-none' => '- keine -',
	'searchform-agent-none'    => '- kein -',

	'searchform-tags-rule-no-filter'    => 'Nicht filtern',
	'searchform-tags-rule-has_not_tags' => 'Ohne Tags',
	'searchform-tags-rule-has_any_tag'  => 'Mit einem Tage',
	'searchform-tags-rule-any'          => 'Einer der Ausgewählten',
	'searchform-tags-rule-all'          => 'All selected',
	'searchform-tags-rule-none'         => 'None of selected',

	'searchform-date-type-from'        => 'Aus Vorgabe',
	'searchform-date-type-until'       => 'Bis Vorgabe',
	'searchform-date-type-exact_year'  => 'Exaktes Jahr',
	'searchform-date-type-exact_month' => 'Exakter Monat',
	'searchform-date-type-exact_day'   => 'Exakter Tag',

	'searchform-read_by_agent-none' => 'Nicht filtern',
	'searchform-read_by_agent-yes'  => 'Ja',
	'searchform-read_by_agent-no'   => 'Nein',

	'searchform-btn-submit' => 'Suchen',

	'searchform-validation-no-field' => 'Kein Feld wurde hinzugefügt',
	'searchform-validation-success'  => ':num Felder gefunden',

	'searchform-results-title'         => 'Ergebnisse',
	'searchform-btn-edit'              => 'Suche bearbeiten',
	'searchform-btn-web'               => 'Nach URL im Internet suchen',
	'searchform-help-btn-web'          => 'Das ist ein Permalink zu dieser Suche',


	/*
	 *  Controllers
	 */

	// AgentsController
	'agents-are-added-to-agents'       => 'Agenten :names wurden hinzugefügt',
	'agents-joined-categories-ok'      => 'Erfolgreich den Kategorien zugewiesen',
	'agents-is-removed-from-team'      => 'Agent(en) :name wurden aus dem Agenten Team entfernt',

	// CategoriesController
	'category-name-has-been-created'   => 'Die Kategorie :name wurde erstellt!',
	'category-name-has-been-modified'  => 'Die Kategorie :name wurde bearbeitet!',
	'category-name-has-been-deleted'   => 'Die Kategorie :name wurde gelöscht!',

	// PrioritiesController
	'priority-name-has-been-created'   => 'Die Priorität :name wurde erstellt!',
	'priority-name-has-been-modified'  => 'Die Priorität :name wurde bearbeitet!',
	'priority-name-has-been-deleted'   => 'Die Priorität :name wurde gelöscht!',
	'priority-all-tickets-here'        => 'Alle Prioritäts Tickets hierher',

	// StatusesController
	'status-name-has-been-created'     => 'Der Status :name wurde erstellt!',
	'status-name-has-been-modified'    => 'Der Status :name wurde bearbeitet!',
	'status-name-has-been-deleted'     => 'Der Status :name wurde gelöscht!',
	'status-all-tickets-here'          => 'Alle Status Tickets hierher',

	// CommentsController
	'comment-has-been-added-ok'        => 'Kommentar erfolgreich hinzugefügt',

	// NotificationsController
	'notify-new-comment-from'          => 'Neuer Kommentar von ',
	'notify-on'                        => ' bei ',
	'notify-status-to-complete'        => ' Status auf Geschlossen',
	'notify-status-to'                 => ' Status auf ',
	'notify-transferred'               => ' verschoben ',
	'notify-to-you'                    => ' zu Dir',
	'notify-created-ticket'            => ' Ticket erstellt ',
	'notify-updated'                   => ' aktualisiert ',

	// TicketsController
	'the-ticket-has-been-created'      => 'Das Ticket wurde erstellt!',
	'the-ticket-has-been-modified'     => 'Das Ticket wurde bearbeitet!',
	'the-ticket-has-been-deleted'      => 'Das Ticket :name wurde gelöscht!',
	'the-ticket-has-been-completed'    => 'Das Ticket :name wurde geschlossen!',
	'the-ticket-has-been-reopened'     => 'Das Ticket :name wurde erneut geöffnet!',
	'you-are-not-permitted-to-do-this' => 'Du bist nicht berechtigt diese Aktion auszuführen!',


];
