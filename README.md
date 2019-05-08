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

Download `oc_projet5` file on gitHub. You can download, or clone it on : https://github.com/esauvage1978/oc_projet5.

###### 2.2 Installing :

   1. Put oc_projet5 file on root of your web server.
   2. If not set, create your database. Files are present in directory 'bdd'
   3. open ```./config/bdd.php ```, replace XXX by your parameters
   4. open ```./config/config.php ```, replace XXX by your parameters
   5. Pointed your URL on : ```./Public/index.php```

Your web site is up !!!

   6. Create an user 
   7. in database, on table ocp5_user, change value of ``` u_user_role ``` by ``` 1 ```


