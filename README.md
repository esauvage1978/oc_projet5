# Welcome on my blog
#### Project number 5 of OpenClassrooms "Developpeur d'application php / Symfony" cursus. 
#### An exemple is available on : https://mylostuniver.com

##### 1. Aim :

The aim of this project, is to create a blog. Anyone can read articles and write comments.

The administration part is resrved for registred users. The administrator can restict actions.


Three cumulative profiles are available. The redactor to manage the articles. The moderator to handle comments. The administrator to manage users.


It use some libraries :
 1. Php unit
 2. php mailer
     
All are included by composer.

##### 2. How to install :

###### 2.1 Download :

Download `my_little_blog` file on gitHub. You can download, or clone it on : https://github.com/Aeltus/my_little_blog.

###### 2.2 Installing :

   1. Put my_little_blog file on root of your web server.
   2. If it is not already done, install composer : https://getcomposer.org/
   3. In root file, open a new terminal windows and type in command line : ```composer install --no-dev``` (next times, to update you wil type : ```composer update --no-dev```) "--no-dev if it is a production server"
   4. If not set, create your database.
   5. open ```./Application/Config/Private```, set your parameters in .cnf files and rename them in .yml (you can also create a copy of file in .yml)
   6. Pointed your URL on : ```./index.php```
   7. In your terminal (in root of your project) type : ```php vendor/doctrine/orm/bin/doctrine orm:schema-tool:create```

Your web site is up !!!

##### 3. Testing :

You can do some basic tests on this application automatically in dev mode by using phpunit.

For tests, you just have to open a terminal in the root directory of the project, and type : ```php vendor/phpunit/phpunit/phpunit```

#### 4. Notice :

The statement did not ask for an identification, the administration is therefore accessible to all, the link of access to the administration is in the footer
