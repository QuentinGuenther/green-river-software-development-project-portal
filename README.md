# Green River College Software Development Project Portal

<div style="text-align:center"><img src ="./images/Green_River_College_logo.png" /></div>

## Project Summary

This project creates a tool for the faculty of Green River College to more effectively manage students projects. When the faculty learns about a new project, they need a way to log it and record a description of the project along with the company and client information. Then, once a project is used in class, they need a way to keep track of the URL, login credentials, GitHub repository, and Trello link.

Many projects go through several iterations. So, They also need a way to view a projects history; including what class it was worked on, who was the teacher, and what changes did they make?

## Requirements
1. Separates all database/business logic using the MVC pattern.
    * The database/business logic is contained under `/models` with all class files contained under `/models/classes` and all classes that interact with the database are contained under `models/classes/Database`.
2. Routes all URLs and leverages a templating language using the Fat-Free framework.
    * All routes are handled in `index.php` and templates that are called are contained in `/views`. Some URLs have parameters which is passed to business logic for processing further data needed to view the page.
3. Has a clearly defined database layer using PDO and prepared statements.
    * Classes which interact with the database are under `models/classes/Database`. These are static classes, each getting their functionality to interact with the database from `models/classes/Database/db.php` which contains the RestDB class. The RestDB class is an abstract class containing generic functions for CRUD interactions using PDO. Each of RestDB's child classes can then call one of RestDB's methods with a SQL statement and applicable prams used in the SQL statement as a multi-dimensional array *(`':parameter' => array(value => dataType)`)*.
4. Data can be viewed, added, updated, and deleted.
5. Has a history of commits from both team members to a Git repository.
6. Uses OOP, and defines multiple classes, including at least one inheritance relationship.
7. Contains full Docblocks for all PHP files.
    * Each PHP file has Docblocks
8. Has full validation on the client side through JavaScript and server side through PHP.
9. Incorporates jQuery and Ajax.
### Languages and Frameworks
* PHP
* Fat-Free Framework
* HTML
* CSS
* MySQL
* JavaScript, JQuery/Ajax
### Setup Requirements
* `git clone` this repository to the server.
* A new MySQL database must be made on the server, along with a user for the database with `SELECT`, `INSERT`, `UPDATE`, and `DELETE` privileges. The tables can be found under `tables.sql`
* Outside of `/public_html` on the Apache Server, a db_config.php needs to be created.
```
// db_config.php
<?php
    define( "DB_DSN", "mysql:dbname=DATABASE_NAME" );
    define( "DB_USERNAME", "DATABASE_USERNAME" );
    define( "DB_PASSWORD", "DATABASE_PASSWORD" );
```
* At the top of db.php there is a require_once statement that links to db_config.php.

# Diagrams
## [MySQL Entity–Relationship Model](./uml/er-diagram.png)
<div style="text-align:center"><img src ="./uml/er-diagram.png" /></div>

## [Class Diagram](./uml/class-diagram.svg)
<div style="text-align:center"><img src ="./uml/class-diagram.svg" /></div>

## Authors
* Quentin Guenther
* Nathan Corbin
