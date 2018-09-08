This application is a simple demo CMS built with [Silex 2.0](https://silex.symfony.com/).

The user interface is built with [Bootstrap 4](http://getbootstrap.com).


## Requirements

- PHP >= 7.1.3
- PDO PHP Extension

## Installation

1. Clone project

   ```
   git clone https://github.com/Coderberg/SilexCMS.git mywebsite
   ```
2. Enter the newly created folder

   ```
   cd mywebsite
   ```
3. Install dependencies with [Composer](https://getcomposer.org/doc/00-intro.md)

   ```
   composer install
   ```

4. After installing Silex, you should configure your web server's document / web root to be the ```public``` directory.

5. Create an empty MySQL database

6. Import db_dump.sql (using phpMyAdmin or other administrative tools)

7. Fill in your database credentials /config/database.php

8. Go to http://mysite.loc/admin and log in.

   ```
   login: admin
   password: admin
   ```


## License

This application is an open-source software licensed under the [MIT license](http://opensource.org/licenses/MIT).
