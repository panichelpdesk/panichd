# Panic Help Desk
This package is a ticketing system for [Laravel 5](https://laravel.com/) based on [Ticketit](https://github.com/thekordy/ticketit), a very polish written ticketing system package that we fell in love with, so we used it as a base of our project. If you need to make a very customized ticketing project you should check it out, as you won't find any better starting point.

#### About Ticketit
Ticketit has three user roles: Users (from Laravel integrated auth), agents and admins. Admins can create categories for tickets and assign agents to them. You can create tickets via form and attach screenshots to them. Users can list and view their open tickets and communicate with agent through a comment form. It also has statistics and a very flexible configuration system.

Ticketit developers currently have stopped major changes in current release and, from many moths ago, they're planning a new version of the package with more focus on developers.

## PanicHD additional features
We've added the following features to the package:
* Tag system: Each category can have an own list of tags. Each ticket can have many tags that an agent can manage.
* File attachments: For ticket or comment. List images separately and view them in a jquery gallery. Images can also be cropped.
