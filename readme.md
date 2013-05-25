## PHP-Based RESTful services for a Student Progress Monitoring System ##

I started this as a fourth year student in University but having learned quite a bit since then would like to write it from scratch.

I'm using this project as a basis of reaffriming the skills I pick up from the day to day work but also to explore new technologies and plan to swap out the PHP backend for one built on NodeJS and possibly MongoDB.

Currently I'm starting with a PHP and MySQL backend, using the [Slim framework](http://www.slimframework.com/).

## Setting Up ##

1. Create a MySQL database name "spms".
2. Import spms.sql, this will give you the initial student, lab, result, admin tables with some sample data, and the all_results, and all_students views.
3. You can then test out the REST api by hitting <SERVER>/<PROJECT ROOT>/index.php/students

Note: Authentication is disabled by default, to enable change $GLOBALS['debug'] to be false.

If you are interested in getting involved in this project or have any questions don't hesitate to ask.

Many thanks to [ccoenraets](https://github.com/ccoenraets) whose work has proved to be an excellent learning resource.