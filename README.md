# Panic Help Desk
This package is a ticketing system for [Laravel 5](https://laravel.com/) PHP framework based on [thekordy/ticketit](https://github.com/thekordy/ticketit) ticketing system. It of course has [all ticketit features](https://github.com/thekordy/ticketit/wiki/v0.2-Features) and some new and useful ones: File attachments, ticket tags, calendar fields and a filters panel. It may be installed in any project based on Laravel 5.1 or later. It has it's own routes, so it shouldn't affect other packages.

## Table of contents

* [Description](#description)
  + [Ticketit features](#ticketit-heritage-features)
  + [Panic Help Desk features](#panic-help-desk-features)
* [Installing](#installing)
  + [Requirements](#requirements)
  + [If thekordy/ticketit is installed](#if-thekordyticketit-is-installed)
  + [Installation steps](#installation-steps)
  + [Complete installation](#complete-installation)
    + [With the web installer](#with-the-web-installer)
    + [With command line](#with-command-line-for-advanced-users)
  + 
* [Contributing](#contributing)

## Description
### Ticketit heritage features
TheKordy/Ticketit is a very polish written ticketing system package that we fell in love with, so we used it as a base of our project. It has three user roles: Users, agents and admins:
 - Users may be registered in the Laravel app through it's default auth system. They may create and view their own tickets. They may attach screenshots in them. They see only a ticket fields basic set. They can also communicate with the ticket managers via the comments section in the ticket page.
 - Agents can edit and manage tickets in the categories they are allowed to. They manage all ticket fields.
 - Admins are the administrators of the ticketing system. They have to configure and maintain all the ticketing system functionality. They may assign the "agent" role to the users at the categories they determine.

It also has many statistics, a very flexible configuration system and many translations including German, Spanish, Russian, Arabic...

If you want to read more about the original kordy/ticketit features, please go to their github project page [https://github.com/thekordy/ticketit/wiki/v0.2-Features](https://github.com/thekordy/ticketit/wiki/v0.2-Features)

### Panic Help Desk features
Panic Help Desk keeps all Ticketit functionality, plus some additional features including:

* Ticket creation visible fields change deppending on the category specific user role: For a configuration with some categories, a specific agent may have this role only in some of these, and in some others he'd be a normal user. In this scenario, when this user account is in the ticket creation form and does many changes in the category field, he will see that the visible fields change deppending on his role on the selected category.

* File attachments for tickets and comments: List images separately and view them in a javascript gallery. Images can also be cropped. All attached files can be renamed and have a description.

* New "Intervention" field: It's designed to contain a taken actions resume for a ticket. It may be filled up automatically with information related on some ticket changes

* Description and intervention fields are also visible and searchable in ticket list.

* Ticket tags: Each category has an independent tag list from each other. Agents and admins may assign them to tickets, and view and search them in the ticket list.  Tags can be colored.

* Ticket calendar: A ticket agent or an admin may specify a start date and a limit date for a ticket. With a combination of this fields, you bring tickets to many different statuses. The following statuses may be filtered in ticket list:
  + Tickets that are scheduled to some day in the future (this thurdsay? this month?)
  + Tickets that end today or tomorrow
  + Tickets that already expired
  + Tickets that were just added and started some weeks ago
* Ticket filters: You can filter the ticket list by many different criteria in a specific filters panel: Calendar, category and agent.
* Agent as a user: Any agent is able to switch his point of view, from agent to user and vice-versa:
  + As a agent: He will see all tickets in categories where he has agent permissions
  + As a user: He will see the tickets he created in categories where he has no agent permissions

## Installing
### Requirements
* Laravel 5.1 or higher including:
  + Laravel auth with at least one user registered
  + Model App\User.php that uses users table. It is added with Laravel auth by default and PanicHD requires it to be there. It seems that some admin panels change it to App\Models\User.php or maybe other routes. 
  + Valid email configuration (Needed for PanicHD notification emails)
  
 * Composer (the PHP dependency manager)

### If thekordy/ticketit is installed
If it is installed in the same Laravel project, Panic Help Desk will replace it, reusing it's database tables and keeping registered tickets.

In this case, you will have to uninstall Kordy/Ticketit firstly doing these steps:

 1. Open composer.json file at laravel root folder. Remove the line that reffers to kordy/ticketit in the "require" section
 2. Open config/app.php. Remove the line that contains "TicketitServiceProvider"
 3. Via command line in laravel root, execute:
     `composer update kordy/ticketit`
 4. Delete all possible remaining refferences and files that you may have in your Laravel project (Published files? Refferences in Laravel files?)

### Installation steps
1. Open a command line in the Laravel folder and type:
    `composer require panichd/panichd`
2. Open config/app.php and in the "Providers" section, add:
    `PanicHD\PanicHD\PanicHDServiceProvider::class,`

### Complete installation
At this point, if you had enough with typing commands, the [web installer](#with-the-web-installer) comes to rescue you ;) But if you're a rough and experienced Laravel coder, please forget this and jump to [Complete installation with command line](#with-command-line-for-advanced-users) section.

#### With the web installer
To access the web installer you just have to:

1. Log in the Laravel app via web browser
2. access URL http://your-laravel-app-URL/panichd
3. Read and follow the installation steps

#### With command line (for advanced users)
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
3. Enable "panichd_thumbnails" folder access:

   `php artisan storage:link`
4. Publish included assets:

   `php artisan vendor:publish --tag=panichd-public`


If you want to include the included priorities, statuses and category:

   `php artisan db:seed --class=PanicHD\\PanicHD\\Seeds\\Basic`

Now, you will have to enable your user account as an admin in Panic Help Desk. In the "users" table, just find your account and set "panichd_admin" value to 1.

If you're all done, access http://your-laravel-app-URL/panichd in your browser.

## Contributing

Please read our [contributing reference](CONTRIBUTING.md).
