<?php

/*
 * Setting descriptions
 *
 * See Seeds/SettingsTableSeeder.php
 */

$codemirrorVersion = PanicHD\PanicHD\Helpers\Cdn::CodeMirror;
$summernoteVersion = PanicHD\PanicHD\Helpers\Cdn::Summernote;

return array (
  'status_notification' => '			<p>
				<b>notification sur le statut</b>: envoyer des notifications par mel aux propriétaires et gestionnaires du ticket quand son statut change
			</p>

			<p>
				Par défaut, envoi de notifications: <code>1</code><br>
				Ne pas envoyer de notifications: <code>0</code>
			</p>',
  'comment_notification' => '			<p>
				<b>notification sur le commentaire</b>: Envoyer une notification quand un nouveau commentaire est publié
			</p>

			<p>
				Par défaut, envoi de notifications: <code>1</code><br>
                                Ne pas envoyer de notifications: <code>0</code>
			</p>',
  'admin_button_text' => 'Nom du menu de navigation de la cofiguration PanicHD',
  'admin_route' => 'La configuration de PanicHD met en avant le préfixe pour les noms de route Laravel (ex. route(\'<code>panichd</code>.status.index\')). Dashboard ne l\'utilise pas car il a son propre nom de route "dashboard".',
  'admin_route_path' => 'Préfixe URL pour le tableau de bord et les menus de configuration de PanicHD (ex. http://url/<code>panichd</code>/priority)',
  'agent_restrict' => 'Restreindre l\'accès des agents aux seuls tickets qui leur sont attribués<br /><code>0</code> : désactivé<br /><code>1</code> : activer l\'accès restreint',
  'assigned_notification' => 'Envoyer une notification à tout agent nouvellement affecté<br /><code>0</code> : Ne pas envoyer de notification<br /><code>1</code> : Envoyer une notification',
  'attachments_mimes' => 'Liste des extensions de fichiers joints autorisées, séparées par des virgules.',
  'attachments_path' => 'Sous-dossier à l\'intérieur de <b>stockage</b> où enregistrer les fichiers joints.',
  'attachments_ticket_max_files_num' => 'Nombre maximum de pièces jointes dans un seul ticket, y compris les commentaires',
  'attachments_ticket_max_size' => 'Taille maximale en <b>MB</b> pour toutes les pièces jointes d\'un même ticket, y compris les commentaires.',
  'calendar_month_filter' => 'Afficher les options disponibles du filtre de calendrier par périodes de calendrier (semaine, mois).<br /><code>0</code> : Afficher les options par nombre de jours (7 dies, 14 dies)<br /><code>1</code> : Afficher les options par périodes de calendrier (semaine, mois).',
  'check_last_update_seconds' => 'Intervalle en secondes dans lequel un contrôle AJAX de la dernière mise à jour du ticket sera effectué pour déclencher le rechargement des données.',
  'close_ticket_perm' => 'Tableau spécifiant quels types de membres peuvent <b>classer</b> un ticket.',
  'codemirror_theme' => '<p>Thème pour <b>codemirror</b> surligneur syntaxique</p><a target="_blank" href="https://cdnjs.com/libraries/codemirror/$codemirrorVersion">Voir les thèmes disponibles</a>.',
  'custom_recipients' => 'Afficher l\'option dans un formulaire de commentaire pour sélectionner un ou plusieurs destinataires personnalisés pour celui-ci',
  'default_close_status_id' => 'Le statut de fermeture du ticket par défaut',
  'default_priority_id' => 'La priorité par défaut pour les nouveaux tickets',
  'default_reopen_status_id' => 'Le statut de réouverture du ticket par défaut',
  'default_status_id' => 'Le statut par défaut des nouveaux tickets',
  'delete_modal_type' => 'Choisissez le type de message de confirmation à utiliser pour confirmer une suppression<br /><code>builtin</code> : confirmation javascript<br /><code>modal</code> : message modal jquery',
  'departments_feature' => 'Afficher les informations relatives au service des membres. Cette fonctionnalité est en cours de développement <a href="https://github.com/panichelpdesk/panichd/wiki/Under-development">comme décrit dans notre wiki</a><br /><code>0</code> : désactivé<br /><code>1</code> : activé',
  'departments_notices_feature' => 'Possibilité de lier des membres spécifiques à un département signalé. Si un ticket est créé avec l\'un de ces utilisateurs spéciaux comme propriétaire, tous les membres du département liés verront ce ticket comme un avis. Cette fonctionnalité est en cours de développement <a href="https://github.com/panichelpdesk/panichd/wiki/Under-development">comme décrit dans notre wiki</a><br /><code>0</code> : désactivé<br /><code>1</code> : activé

Traduit avec www.DeepL.com/Translator (version gratuite)',
  'editor_enabled' => 'Activer l\'éditeur summernote sur les zones de texte',
  'editor_html_highlighter' => 'Inclure ou non <a target="_blank" href="http://summernote.org/examples/#codemirror-as-codeview">surligneur de syntaxecodemirror</a><br /><code>0</code> : Ne pas inclure<br /><code>1</code> : Inclure',
  'email' => 
  array (
    'account' => 
    array (
      'mailbox' => 'L\'adresse e-mail pour toutes les notifications PanicHD<br /><code>default</code> : Utiliser les valeurs par défaut de Laravel',
      'name' => 'Le nom de l\'expéditeur de l\'email pour toutes les notifications PanicHD<br /><code>default</code> : Utiliser les valeurs par défaut de Laravel',
    ),
    'color_body_bg' => '<p><img src="http://i.imgur.com/KTF7rEJ.jpg"/></p>',
    'color_button_bg' => '<p><img src="http://i.imgur.com/0TbGIyt.jpg"/></p>',
    'color_content_bg' => '<p><img src="http://i.imgur.com/7r8dAFj.jpg"/></p>',
    'color_footer_bg' => '<p><img src="http://i.imgur.com/KTjkdSN.jpg"/></p>',
    'color_header_bg' => '<p><img src="http://i.imgur.com/wenw5H5.jpg"/></p>',
    'dashboard' => '<p><img src="http://i.imgur.com/qzNzJD4.jpg"/></p>',
    'facebook_link' => '<p><b>Toogle icon link</b>: empty or string</p><p><img src="http://i.imgur.com/FQQzr98.jpg"/></p>',
    'google_plus_link' => '<p><b>Toogle icon link</b>: empty or string</p><p><img src="http://i.imgur.com/fzyxfSg.jpg"/></p>',
    'header' => '<p><img src="http://i.imgur.com/5aJjuZL.jpg"/></p>',
    'owner' => 
    array (
      'newticket' => 
      array (
        'template' => 'La notification au propriétaire du ticket utilise le modèle de lame d\'email spécifié ici',
      ),
    ),
    'signature' => '<p><img src="http://i.imgur.com/coi3R63.jpg"/></p>',
    'signoff' => '<p><img src="http://i.imgur.com/jONMwgF.jpg"/></p>',
    'template' => 'Le modèle d\'email que toutes les notifications étendent',
    'twitter_link' => '<p><b>Toogle icon link</b>: empty or string</p><p><img src="http://i.imgur.com/5JmkrF1.jpg"/></p>',
  ),
  'embedded_image_prefix' => 'Les images incorporées dont la résolution dépasse la limite sont transformées en une pièce jointe nommée à l\'aide de ce préfixe et d\'une numérotation automatique.',
  'html_replacements' => 'Remplacement automatique des chaînes de caractères pour les champs HTML de contenu et d\'intervention',
  'length_menu' => 'Options pour le menu de pagination des tableaux',
  'list_owner_notification' => 'Notifier le propriétaire lorsque la liste de tickets passe d\'active à complète ou vice versa<br /><code>0</code> : désactivé<br /><code>1</code> : activé',
  'list_text_max_length' => 'Longueur maximale visible pour les champs de description et d\'intervention. Si l\'un de ces champs est plus grand que ce paramètre, le texte sera coupé à cette longueur et un bouton pour afficher le texte complet sera affiché<br /><code>0</code> : Désactiver',
  'main_route' => 'Préfixe PanicHD utilisé dans les noms de route Laravel (ex. route(\'<code>tickets</code>.index\'))',
  'main_route_path' => 'Préfixe URL pour voir les billets (ex. http://hostname/<code>tickets</code>/)',
  'master_template' => 'Le modèle que toutes les vues de PanicHD prolongeront.',
  'max_agent_buttons' => 'Nombre maximum d\'agents qui seront affichés comme des boutons séparés dans le panneau de filtre. S\'il y a plus d\'agents disponibles, ils seront affichés dans une liste de sélection.',
  'member_model_class' => 'Espace de noms du modèle PanicHD "Member" <b>full</b>. La valeur par défaut est <code>PanicHD\\PanicHD\\Models\\Member</code> et il est chargé comme <code>\\PanicHDMember</code>.',
  'oldest_year' => 'Année la plus ancienne autorisée pour la date de début ou la date limite du ticket',
  'paginate_items' => 'Nombre de lignes de la table par défaut',
  'purifier_config' => '<p>Set which HTML tags are allowed</p>
			<p>
				Configuring this parameter overrides the settings in <a target="_blank" href="https://github.com/mewebstudio/Purifier/blob/master/config/purifier.php">Purifier config file</a><br>
				The same config can be achived by running <code>php artisan vendor:publish</code> and modifying <code>config/purifier.php</code>
			</p>

			<p><a target="_blank" href="http://htmlpurifier.org/docs">Purifier documentation</a></p>',
  'queue_emails' => 'Utiliser la méthode Queue lors de l\'envoi des emails (Mail::queue).<br /><code>0</code> : Notifier via Mail::send<br /><code>1</code> : Notifier via Laravel Mail::queue. Notez qu\'il nécessite d\'être configuré <a target="_blank" href="http://laravel.com/docs/master/queues">dans Laravel</a>.',
  'reopen_ticket_perm' => 'Tableau spécifiant quels types de membres peuvent <b>réouvrir</b> un ticket.',
  'status_owner_notification' => 'Notifier le propriétaire lorsque le statut du ticket change<br /><code>0</code> : désactivé<br /><code>1</code> : activé. Nécessite que le paramètre <b>status_notification</b> soit également activé.',
  'subject_content_column' => 'Regroupement des colonnes sujet et contenu dans un tableau<br /><code>0</code> : désactivé<br /><code>1</code> : activé',
  'summernote_locale' => 'Quelle langue doit utiliser summernote js texteditor. Si la valeur est <code>laravel</code>, la locale définie dans <code>config/app.php</code> sera utilisée<br /><br />Exemple : <code>hu-HU</code> pour le hongrois. <a target="_blank" href="https://github.com/summernote/summernote/tree/master/lang">Voir les codes de langue disponibles</a>',
  'summernote_options_json_file' => 'App relative path for file that contains init values for summernote in JSON. <a target="_blank" href="http://summernote.org/deep-dive/#initialization-options">Voir les options disponibles</a><br /><code>default</code> : Charger les paramètres du fichier JSON\\summernote_init.json.',
  'summernote_options_user' => 'Le membre sans permissions actuelles utilise ses propres options summernote si elles sont spécifiées dans ce paramètre<br /><code>default</code> : Options par défaut de Summernote',
  'thumbnails_path' => 'Sous-dossier de "storageapp\\public" dans lequel vous pouvez enregistrer les vignettes des images jointes.',
  'ticket_attachments_feature' => 'Possibilité de joindre des fichiers aux tickets et/ou aux commentaires. <br /><code>0</code> : désactivé<br /><code>1</code> : activé.',
  'use_default_status_id' => 'Autoriser ou non l\'attribution de l\'identifiant d\'état par défaut dans un ticket.',
  'user_route' => 'Nom de la route pour les pages des membres. Si elle est configurée, tout nom de membre dans une vue aura un lien vers sa propre page<br />Le <b>paramètre utilisé</b> pour cette route est "<b>user</b>".',
);
