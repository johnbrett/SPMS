## Student Progress Monitoring System ##

This application started out as a fourth year student in University but as the authors have progressed a bit since then it is being re-written from scratch as an exercise to explore new technologies and frameworks, to reaffirm the skills learnt from day to day, and to create a much better system that is more performant, scalable and extendable.

Currently the system is built with PHP, using the [Slim framework](http://www.slimframework.com/), and the front end built with [Twitter Bootstrap](http://twitter.github.io/bootstrap/index.html), and [AngularJS](http://angularjs.org/), and using a MySQL Database.

Future plans are to swap out the PHP/MySQL backend for one built on NodeJS and MongoDB.


## Setting Up ##

1. Create a MySQL database name "spms".
2. Import spms.sql, this will give you the initial student, lab, result, admin tables with some sample data, and the all_results, and all_students views.
3. You can then test out the REST api by hitting <SERVER>/SPMS/router.php/student (Don't use file:// - open by localhost!!)
4. You should be able to hit the UI by opening <SERVER>/SPMS and presented with the login screen.

Note: Authentication is disabled by default, to enable change $GLOBALS['debug'] to be false.

If you are interested in getting involved in this project, have any questions, or suggested technologies we'd be happy to hear from you.