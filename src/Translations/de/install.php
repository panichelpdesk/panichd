<?php

return [
	'main-title' => 'Panic Help Desk',

	'not-ready-to-install'     => 'Paket ist noch nicht verfügbar!',
	'ticketit-still-installed' => 'Die Abhängigkeit Kordy/Ticketit ist immernoch installiert. Bitte folgen Sie den Schritten der Deinstallation unter <a href=":link">:link</a>',

	'not-yet-installed' => '<b>Aktueller Status:</b> Die Installation des Pakets <b>wurde noch nicht beendet</b>.',
	'welcome'           => 'Welcome to the <b>Panic Help Desk installation menu!</b>',
	'setup-list'        => 'Wenn Sie den Knopf "Jetz installieren" drücken werden folgende Schritte durchgeführt:',
	'initial-setup'     => 'Initiale Einrichtung',

	'setup-list-migrations' => '<b>:num</b> Datenbankmigrationsdateien werden in Ihrer Laravel-App veröffentlicht und installiert',
	'setup-more-info'       => 'Details einblenden',
	'setup-less-info'       => 'Details ausblenden',

	'setup-list-ticketit-settings'    => 'Es werden Änderungen durchgeführt, die zu Kompatibilitätsproblemen mit Kordy/Ticketit führen',
	'setup-list-ticketit-admin_route' => 'Die Parameter für die Weiterleitung der Administration werden auf folgende Standardwerte gesetzt: "admin_route" and "admin_route_path" ',
	'setup-list-ticketit-template'    => 'Die "main template" wird auf folgenden Standardwert gesetzt: "master_template"',
	'setup-list-ticketit-routes'      => 'Die "routes" Einstellung wird gelöscht',
	'setup-list-settings'             => 'Die PanicHD "settings"-Tabelle wird mit den Standardwerten aus SettingsTableSeeder.php gefüllt',
	'setup-list-folders'              => 'Die benötigten Ordner für Speicherung der Tickets und Anhänge werden erstellt',
	'setup-list-admin'                => 'Ihr Benutzeraccount (:name &lt;:email>) wird dem Panic Help Desk als Administrator hinzugefügt. Sie können zu einem späteren Zeitpunkt weitere Administratoren hinzufügen.',
	'setup-list-public-assets'        => 'Die ganzen css, js... Dateien werden zum Laravel-Ordner  "public\vendor\panichd" kopiert.',
	'configurable-parameters'         => 'Einstellbare Parameter',
	'existent-parameters'             => 'Es sind bereits Voreisntellungen von Kordy/Ticketit vorhanden. Es werden hier deshalb keine weiteren Schnellstartoptionen angezeigt.<br />Sie können diese Einstellungen nach der Installation von Panic Help Desk bearbeiten.',
	'optional-quickstart-data'        => 'Schnellstartkonfiguration hinzufügen:<ul><li>Fügen Sie essenzielle Status, Prioritäten und Kategorien hinzu.</li><li> Der aktuelle Benutzer wird dieser Kategorie als Ticketmanager hinzugefügt.</li><li>Nach der Installation können Sie alles bearbeiten.</li></ul>',
	'install-now'                     => 'Jetzt installieren!',

	'just-installed'                => 'Glückwunsch! Sie haben Panic Help Desk <b>erfolgreich installiert und konfiguriert</b> .',
	'installed-package-description' => 'Ab jetzt können Sie auf <a href=":panichd">:panichd</a> zugreifen um den Status des Pakets zu kontrollieren.',
	'continue-to-main-menu'         => 'Weiter zum Hauptmenü',

	'package-requires-update'      => 'Panic Help Desk benötigt eine <b> Konfiguration der Aktualisierung</b>',
	'package-requires-update-info' => 'Der Administrator hat Panic Help Desk installiert. Die Kanfiguration ist zurzeit nicht angeschlossen. Bitte gedulden Sie sich einen Moment',

	'status-updated'              => 'Ihre Version ist aktuell und gerade <b>online</b>',
	'status-out-of-date'          => '<b>Aktueller Status: offline</b>  bis sie das Upgrade konfiguriert haben',
	'about-to-update'             => 'Menü aktualisieren',
	'about-to-update-description' => 'Wenn Sie den Knopf "Jetzt aktualisieren" drücken werden folgende Schritte durchgeführt:',
	'all-tables-migrated'         => 'Datenbanksänderungen bereits durchgeführt. Keine weiteren Änderungen mehr nötig',

	'optional-config'             => 'Optionale Einstellung',
	'choose-public-folder-action' => 'Möchten Sie ein Backup/Sicherung von dem Ordner "public/vendor/panichd" erstellen bevor dieser erneut installiert wird? (Wird benötigt, wenn Sie bereits Veränderungen an den Dateien vorgenommen haben.)',
	'public-folder-destroy'       => 'Nein',
	'public-folder-backup'        => 'Ja (Es wird ein Backup- bzw. Sicherungordner unter "public/vendor" erstellt)',

	'upgrade-now' => 'Jetzt aktualisieren!',

	'upgrade-done' => 'Panic Help Desk wurde erfolgreich aktualisiert.',

	'pending-settings'             => 'Es wurden noch nicht alle <b>Einstellungen korrekt durchgeführt</b>',
	'pending-settings-description' => 'Um neue Tickets erstellen zu können, müssen Sie mindestens eine Priorität, eine Kategorie und einen Status angelegt haben. Diese müssen mindestens einem Ticketmanager zugewiesen sein.',

	'master-template-file'           => 'Hauptvorlage (Datei)',
	'master-template-other-path'     => 'Witere Pfade zur Hauptvorlage',
	'master-template-other-path-ex'  => 'zum Beispiel views/layouts/app.blade.php',
	'migrations-to-be-installed'     => 'Folgende Migrationen werden installiert:',
	'another-file'                   => 'Weitere Datei:',
	'upgrade'                        => 'Panic Help Desk Version aktualisieren',
	'settings-to-be-installed'       => 'Folgende Einstellungen werden übernommen:',
	'all-settings-installed'         => 'Einstellungen wurden wurden übernommen. Keine weiteren Änderungen mehr nötig.',
	'public-folder-will-be-replaced' => 'Der Inhalt des Ordners "public/vendor/panichd" wird <b>gelöscht und neu installiert</b>',
];
