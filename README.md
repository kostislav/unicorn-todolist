# An example To-Do List app written in a PHP framework that (kind of) does not exist

This started out as a thought experiment. If I were to write a simple To-Do List example app in a framework that was made exactly for me, what would it look like? It was code-for-show that could not be run, using classes that did not exist.

The next (il)logical step was, of course, to start writing that framework!

It is still very much a work-in-progress, but it has enough functionality to run the To-Do app in almost the exact shape as it was designed.

What are the next steps? The declarative nature of the framework should allow generating nice and readable optimized code for production - almost as if it was hand-written. It's definitely something I would like to explore next.

## How to run #

The project is divided into the `app` and `framework` directories. They are separate Composer projects and the `app` project has a dev (symlink) dependency on the `framework` project. So, to run this thing:

1) `composer install` in the `framework` directory
2) `composer install` in the `app` directory
3) Point your Apache web server at the `app/www` directory
4) Write some To-Dos!