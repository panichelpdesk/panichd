# Panic Help Desk
This package is a ticketing system for [Laravel 5](https://laravel.com/) PHP framework based on [Kordy/Ticketit](https://github.com/thekordy/ticketit). We have kept almost [all ticketit features](https://github.com/thekordy/ticketit/wiki/v0.2-Features) and includes many other functionalities to bring you closer to any production environment, like file attachments, ticket tags, calendar fields and a filters panel. It may work in any Laravel app from version 5.1 to 5.8. It has it's own routes, so it shouldn't affect other packages.

## Table of contents

* [Description](#description)
  + [Overview](#overview)
  + [Translations](#translations)
  + [A ticket step by step example](#a-ticket-step-by-step-example)
  + [Feature synopsis](#features)
  + [Features in detail (link to the Wiki)](https://github.com/panichelpdesk/panichd/wiki/Current-features)
* [Installing](#installing)
  + [Requirements](#requirements)
  + [If Kordy/Ticketit is installed](#if-kordyticketit-is-installed)
  + [Installation steps](#installation-steps)
  + [Complete installation](#complete-installation)
    + [With the web installer](#option-1-web-installer)
    + [With command line](#option-2-command-line-for-advanced-users)
* [Configurations and Laravel integration](#configurations-and-laravel-integration)
* [Contributing](#contributing)
* [Built with](#built-with)
* [Acknowledgements](#acknowledgements)

## Description
#### Overview
Panic Help Desk is a ticketing system that may be integrated on any Laravel app. A "ticket" can be any specific issue, thread, bug or whatever you need. It includes some general fields that may be useful to classify it and has some interaction possibilities between the user that creates it and the ticket managers.

[![General view](https://raw.githubusercontent.com/panichelpdesk/panichd-wiki/master/screenshots/full_page/ticket_index_small.png)](https://raw.githubusercontent.com/panichelpdesk/panichd-wiki/master/screenshots/full_page/ticket_index.png)

This ticketing system is actually mean to be used in a corporate support environment, by these reasons:
* Only registered users or ticket managers are able to add tickets. For any manager, a ticket can only be assigned to an owner which is registered.
* We have included all required asset files in the package structure because we want it to be usable in our LAN even without working internet connection (think about IT corporate support)

#### Translations
This package comes with Catalan, English and Brazillian Portuguese language files. For any of them you don't need to do anything: PanicHD will get your Laravel configuration.

You may also you may create your own language files. There are some outdated language files that come from Ticketit and can be a good starting point. We encourage you to make your own language files and create a pull request in PanicHD to share with other speakers.

#### A ticket step by step example
1. A user registers a new ticket for a specific issue
2. The ticket gets automatically assigned to an agent (a ticket manager) that receives an e-mail notification. 
3. The agent contacts the user to give support. After the support tasks, leaves the ticket opened in "User pending" status because a confirmation that the issue is solved is needed
4. The user confirms within the ticket that it is solved. He can do it by directly completing the ticket or making a new comment
5. In any case, the agent will receive an e-mail notification with the user update
5. If the ticket was left active, the agent completes it
6. Both the agent and the user will see the ticket in the Complete list for future reference.

### Features

This is a synopsis of the main PanicHD features. For detailed descriptions, example screenshots and general reference, please read our [Current features page in the wiki](https://github.com/panichelpdesk/panichd/wiki/Current-features) 

* Three user roles: Member, Agent and Admin
* PanicHD ticket fields
  - Basic set that any member can fill up when registering a new ticket:
    - Subject
    - Description: It may contain text, html or even directly pasted screenshots
    - Category
    - Attached files
  - classification fields, like: Priority, status, tags
  - Time related fields:
    - Start date: When the ticket activity may start. By default it is the creation date
    - Limit date: When the ticket expires. It is used for schedule ticket filtering
    
  - Any of the ticket managers may view / edit all the ticket fields
  - A manager may add a ticket assigning it to any owner (any registered user)
  - A manager may add a user hidden ticket (or switch a visible ticket to hidden):
    - It may be also assigned to any owner
    - The owner (if it's not also an agent on the ticket category) won't be able to view the ticket
    - This kind of owner will not receive any e-mail notification
* User / Managers communication
  - By comments added from both of them within a ticket card
  - By e-mail notifications: All of them will receive notifications relative to relevant changes in the ticket made by any of them
  - Manager may add user hidden messages called "internal notes". If the ticket is assigned to another agent, he will receive a related e-mail notification also

* Ticket filters
  - All ticket lists include a filter panel that lets you use a specific criteria in some relevant fields (Calendar, category, agent)
  - These filters are kept in user session until you change or deletes them
  - Only members with permissions to manage at least one category will be able to use the filter panel
  - You may activate a field by URL, [as we explain in the wiki](https://github.com/panichelpdesk/panichd/wiki/Laravel-integrations#load-list-with-specified-filters)
  
* Ticket search
  - There is a dedicated search form where you may specify it by any of the ticket related fields
  - We have added some advanced search options, like search text in any field, text in comments, text in attachment fields, search by specific date criteria...
  - After executing the search, you will have a button with a permanent link to it, which has all specified parameters and values in URL
  - It is enabled for all members with permissions
  
* For Admins
  - Any classification element may be edited
  - They can easily manage categories and assigned Agents
  
* For developers
  - There is a [configuration settings menu](https://github.com/panichelpdesk/panichd/wiki/Configuration-settings) that directly comes from Ticketit. It is so useful and flexible to configure the package at your own desire even without altering the package files
  - We have added [some useful Artisan commands](https://github.com/panichelpdesk/panichd/wiki/Command-line-toolbox#artisan-commands) to help you make your own local tests. Click the link or type in the Laravel console the following command:
    
    `php artisan panichd`

## Installing
### Requirements
* [Laravel](https://laravel.com/) 5.1 or higher including:
  + [Laravel auth](https://laravel.com/docs/master/authentication#authentication-quickstart) with at least one user registered
  + Model App\User.php that uses users table. It is added with Laravel auth by default and PanicHD requires it to be there. It seems that some admin panels change it to App\Models\User.php or maybe other routes. 
  + Valid email configuration (Needed for PanicHD notification emails)
  
 * [Composer](https://getcomposer.org/) (the PHP dependency manager)

### If Kordy/Ticketit is installed
If it's installed in the same Laravel project you want to install Panic Help Desk, Panic Help Desk will replace it, reusing it's database tables and keeping registered tickets. Before installing PanicHD, you will have to uninstall Kordy/Ticketit following these steps:

 1. Open composer.json file at laravel root folder. Remove the line that reffers to kordy/ticketit in the "require" section
 2. Open config/app.php. Remove the line that contains "TicketitServiceProvider"
 3. Via command line in laravel root, execute:
     `composer update kordy/ticketit`
 4. Delete all possible remaining refferences and files that you may have in your Laravel project (Published files? Refferences in Laravel files?)

### Installation steps
1. Open a command line in the Laravel folder and type:
    `composer require panichd/panichd`
2. If you are using Laravel 5.4 or lower, you will have to add the service provider. In this case, Open config/app.php. In the "Providers" section, add:

    `PanicHD\PanicHD\PanicHDServiceProvider::class,`

### Complete installation
At this point, if you think you typed enough commands, the [web installer](#option-1-web-installer) comes to rescue you ;) But if you're a tough and experienced Laravel coder, please forget this and jump to [Complete installation with command line](#option-2-command-line-for-advanced-users) section.

#### Option 1: Web installer
To access the web installer you just have to:

1. Log in the Laravel app via web browser
2. access URL http://your-laravel-app-URL/panichd
3. Read and follow the installation steps

#### Option 2: Command line (for advanced users)
Create the attachments folders:
1. Access "storage" folder inside Laravel root and create the subfolder:
`panichd_attachments`
2. Access storage\app\public and create the subfolder:
`panichd_thumbnails`

Execute these commands:
1. Publish and install migrations

   1.1 Publish migrations:
   `php artisan vendor:publish --tag=panichd-db`

   1.2 Execute migrations:
   `php artisan migrate`

2. Fill up "panichd_settings" table with the required defaults seeder:

   `php artisan db:seed --class=PanicHD\\PanicHD\\Seeds\\SettingsTableSeeder`

3. If Kordy/Ticketit was installed, Patch settings table with:

  `php artisan db:seed --class=PanicHD\\PanicHD\\Seeds\\SettingsPatch`

4. Enable "panichd_thumbnails" folder access:

   `php artisan storage:link`
5. Publish included assets:

   `php artisan vendor:publish --tag=panichd-public`
   
6. If you didn't have Kordy/Ticketit, you will have to enable your user account (or any other) as an admin in Panic Help Desk. In the "users" table, just find your account and set "panichd_admin" value to 1.

7. Test functionality with generated demo content
If you want to test the package first, you can install some demo contents from command line in your Laravel app:
   - To install the demo contents:
     `php artisan db:seed --class=PanicHD\\PanicHD\\Seeds\\DemoDataSeeder`
  
   - To delete the contents addded by the demo, read the [panichd:demo-rollback command dedicated wiki section](https://github.com/panichelpdesk/panichd/wiki/Command-line-toolbox#panichddemo-rollback).
   

8. Configure the package for real environment usage
   - If you want to add the included default priorities, statuses and category:
   `php artisan db:seed --class=PanicHD\\PanicHD\\Seeds\\Basic`
   - If you want to create only  your custom parameters, you can skip to the next installation step

9. Access http://your-laravel-app-URL/panichd in your browser

## Configurations and Laravel integration
   * Ticket Parameters: All ticket classification fields are customizable, like priorities, statuses... and within their own menues in the Package.
   * Settings:
     - The package comes with a big set of [configurable settings](https://github.com/panichelpdesk/panichd/wiki/Configuration-settings), which allow you to change many aspects of it's behavior, like:
       - Package routes for tickets and admin menues
       - Tickets workflow (default priority, status...) and default limits
       - Enable / disable different kind of email notifications
     - We have created a wiki page specifying a group of [essential settings to be aware of](https://github.com/panichelpdesk/panichd/wiki/Configuration-settings#essential-settings) in any usage environment. It's a very basic set, but it may be a good starting point with them.
     - You can also create your own configuration settings [to use anywhere you may need](https://github.com/panichelpdesk/panichd/wiki/Laravel-integrations/#use-any-panichd-configuration-setting-in-any-laravel-file)
   * [Laravel integrations detailed in our wiki](https://github.com/panichelpdesk/panichd/wiki/Laravel-integrations):
     - [Create tickets from your Laravel app](https://github.com/panichelpdesk/panichd/wiki/Laravel-integrations#create-tickets-from-your-laravel-app)
     - You can [create a custom Member model](https://github.com/panichelpdesk/panichd/wiki/Laravel-integrations#custom-member-model), for example to allow using a different table than "users" for PanicHD Members.
     - [Use any configuration setting in your app](https://github.com/panichelpdesk/panichd/wiki/Laravel-integrations/#use-any-panichd-configuration-setting-in-any-laravel-file)

## Contributing

Please read our [contributing reference](CONTRIBUTING.md).


## Built with
* [Laravel](https://laravel.com/): This is our basis PHP framework
* [jQuery](https://jquery.com/): The javascript framework. Please dont tell me you don't know
* [Bootstrap](https://getbootstrap.com/): The CSS framework
* [TheKordy/Ticketit](https://github.com/thekordy/ticketit): The ticketing Laravel package that PanicHD is built on. It includes also:
  * [Yajra Datatables](https://github.com/yajra/laravel-datatables). A Laravel package to use [Datatables](https://datatables.net/)
  * [Codemirror](https://codemirror.net/)
  * [Summernote editor](https://summernote.org/)
* [Select2](https://select2.org/): jQuery script that replaces HTML select elements for much more functional ones
* [Photoswipe](http://photoswipe.com/): The best free javascript image gallery we found outside there
* [Bootstrap Colorpicker Plus](https://github.com/zzzhan/bootstrap-colorpicker-plus): The javascript color picker for every customizable color in PanicHD
* [Bootstrap Datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/): A great javascript calendar selector
* Used Google fonts:
  - [Lato Light](https://fonts.google.com/specimen/Lato)
  - [Raleway](https://fonts.google.com/specimen/Raleway)
* [jCrop](http://deepliquid.com/content/Jcrop.html): An oldie but useful image cropping javascript library

If some day this package is as useful to someone as other ones like these have been to us, our debt will for sure still be enormous, but also we'd be very happy!

## Acknowledgements

Thanks to [Kordy](https://github.com/thekordy) and his collaborators for building up [Kordy/Ticketit](https://github.com/thekordy/ticketit) and sharing it on GitHub. Without it, Panic Help Desk simply won't exist.

A big Thank You also to all the guys at online communities like [StackOverflow](https://stackoverflow.com) that do their best everyday to help others like me on their daily coding headaches.
