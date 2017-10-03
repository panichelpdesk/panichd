# Panic Help Desk
This package is a ticketing system for [Laravel 5](https://laravel.com/) based on [Ticketit](https://github.com/thekordy/ticketit), a very polish written ticketing system package that we fell in love with, so we used it as a base of our project. If you need to make a very customized ticketing project you should check it out; you probably won't find any better starting point.

## Ticketit features
Ticketit has three user roles: Users (from Laravel integrated auth), agents and admins. Admins can create categories for tickets and assign agents to them. You can create tickets via form and attach screenshots to them. Users can list and view their open tickets and communicate with agent through a comment form. It also has statistics and a very flexible configuration system.

Ticketit developers currently have stopped major changes in current release and, from many moths ago, they're planning a new version of the package with more focus on developers.

## PanicHD current features
We've added the following features to ticketit package:
* Tag system: Each category can have it's own tag list. Agents can assign tags to tickets.
* File attachments: For ticket or comment. List images separately and view them in a javascript gallery. Images can also be cropped. All attached files can be renamed and have a description.
* Intervention field that can interact with comments. Description and intervention are also visible and searchable on ticket list.
* Interactive ticket creation form: Admin, of course, always sees all fields in ticket form, but for user and agent roles, any user sees user or agent fields depending on chosen category in the form.
* Department notices: It deppends on an externally managed department structure (not managed by the package). Users are related to persons that have an assigned department. You can assign a "department user" to a specific department (or to all departments option), so you can make all real users that are on the same department view open tickets from the "department user" just over the new ticket form.
