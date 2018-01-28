# Panic Help Desk
This package is a ticketing system for [Laravel 5](https://laravel.com/) PHP framework based on [Kordy/Ticketit](https://github.com/thekordy/ticketit) ticketing system. It of course has [all ticketit features](https://github.com/thekordy/ticketit/wiki/v0.2-Features) and some new and useful ones: File attachments, ticket tags, calendar fields and a filters panel. It may be installed in any project based on Laravel 5.1 or later. It has it's own routes, so it shouldn't affect other packages.

## Table of contents

* [Description](#description)
  + [Features inherited from Ticketit](#features-inherited-from-kordyticketit)
  + [Original Panic Help Desk features](#original-panic-help-desk-features)
* [Installing](#installing)
  + [Requirements](#requirements)
  + [If Kordy/Ticketit is installed](#if-kordyticketit-is-installed)
  + [Installation steps](#installation-steps)
  + [Complete installation](#complete-installation)
    + [With the web installer](#option-1-web-installer)
    + [With command line](#option-2-command-line-for-advanced-users)
* [Contributing](#contributing)
* [Credits](#credits)

## Description
Panic Help Desk is a ticketing system. It consists in these basic functionalities:
- Three user roles: Common Laravel User, Agent and Admin
 - Users may be registered in the Laravel app through it's default auth system. They may create and view their own tickets, wich can be set to any of the existent categories in the system. They may attach screenshots in them. They see only a ticket fields basic set. They can also communicate with the ticket managers via the comments section in the ticket page.
 - Agents can edit and manage tickets in the categories they are allowed to. They manage all ticket fields.
 - Admins are the administrators of the ticketing system. They have to configure and maintain all the ticketing system functionality. They may assign the "agent" role to the users at the categories they want.
- Each category may be managed by an idependent support team. Maybe for different departments in a company or for different usages that you want to be listable independently.
- Any support "agent" may have assigned several tickets and may comunicate with every ticket owner within the system to resolve the issue.

 We have kept the most of Ticketit's functionality and we've improved what we considered necessary. We've also added other features that we need for own usage and thought to be useful for others, so they were included in the package.

### Features inherited from Kordy/Ticketit
Panic Help Desk is just a customized and expanded version of Kordy/Ticketit.
Ticketit includes most of the functionality you will see in Panic Help Desk, including:
- The three user roles: Users, agents and admins
- Interaction between them through tickets, comments and ticket statuses / completion
- Installation and upgrade menu
- Ticket and users stats
- A very complete and customizable configuration settings management
- Many translations including German, Spanish, Russian, Arabic...

If you want to read more about the original kordy/ticketit features, please go to their github project page [https://github.com/thekordy/ticketit/wiki/v0.2-Features](https://github.com/thekordy/ticketit/wiki/v0.2-Features)

### Original Panic Help Desk features
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
* [Laravel](https://laravel.com/) 5.1 or higher including:
  + [Laravel auth](https://laravel.com/docs/5.3/authentication#introduction) with at least one user registered
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
2. Open config/app.php and in the "Providers" section, add:
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
3. Enable "panichd_thumbnails" folder access:

   `php artisan storage:link`
4. Publish included assets:

   `php artisan vendor:publish --tag=panichd-public`



If you want to add the included default priorities, statuses and category:

   `php artisan db:seed --class=PanicHD\\PanicHD\\Seeds\\Basic`

Now, you will have to enable your user account as an admin in Panic Help Desk. In the "users" table, just find your account and set "panichd_admin" value to 1.

If you're all done, access http://your-laravel-app-URL/panichd in your browser.

## Contributing

Please read our [contributing reference](CONTRIBUTING.md).

## Credits

Thanks to [Kordy](https://github.com/thekordy) and his collaborators for building up that great project [Kordy/Ticketit](https://github.com/thekordy/ticketit) and shared it in GitHub. Without it, Panic Help Desk simply won't exist.

I'm sure that without the [StackOverflow](https://stackoverflow.com) community that solves our everyday coding headaches, our development would be much more slower and many times stopped. Google knows that.

Also a big thank you to all the people that are sharing their works as Open Source and let other people like us include their packages in our code. If some day this one is as useful to someone as other packages have been to us, our debt will for sure still be enormous, but also we'll be very happy!
