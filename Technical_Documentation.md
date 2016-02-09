# Technical Documentation #

## Assumptions ##
  * Installer is well versed in using UNIX terminal
  * Installer knows apache
  * Extra documentation for SOLR, Jetty, Apache, MySQL, and PHP 5.2  will not be contained in this document
  * Extra documentation for Codeigniter framework is contained in the Records Authority Software and not contained in this document ( **http://''yoursite''/ recordsAuthority/user\_guide/** )
## Requirements ##
  * ### Server  Requirements ###
    * Redhat Linux
    * Ubuntu
    * Apple OS X 10.6
    * Windows 7
  * ### Software Requirements ###
    * Apache
    * MySQL
    * PHP 5.2
    * Jetty http://jetty.codehaus.org/jetty/
    * Solr http://lucene.apache.org/solr/
  * ### Recommended Software ###
    * MAMP - For Apple users http://www.mamp.info/en/index.html
    * phpMyAdmin - MySQL web interface http://www.phpmyadmin.net/home_page/index.php
    * WAMP - For Windows users http://www.wampserver.com/en/
## Installation ##
  * ### Macintosh – Using MAMP ###
    * Download and install MAMP
    * Download the latest version of Records Authority
    * Extract the contents into your root web folder (e.g. **/Applications/MAMP/htdocs/**)
    * Move the folder **jetty-6.1.22**in the recordsAuthority folder to **/opt/jetty-6.1.22/**
    * Go to **/opt/jetty-6.1.22/** and enter the command "**sudo sh startJetty.sh**"
    * You will be prompted to enter your password, after press **Enter**.  Solr is now running
    * Access your **phpMyAdmin** web interface from **MAMP**
    * Create a database called **recordsManagementDB**
    * Select this database then click **Import**
    * Browse to **/recordsAuthority/ra\_schema.sql/**and click **Go**
    * The database is now set up
  * ### Unix ###
    * Install Apache
    * Install PHP 5.2 or above
    * Install MySQL
    * Extract the contents into your root web folder(e.g.**/var/www/html**)
    * You will prompted to enter your password, after press **Enter**.  Solr is now running
    * Login to mysql with **mysql –u ”username” –p ”password”**
    * Enter the command **create database recordsManagementDB**
    * Enter the command **quit**
    * Enter the command **mysql –u''”username” –p ”password” recordsManagementDB < ra\_schema.sql**
    * The database is now setup
  * ### Windows 7 ###
    * Install WAMP
    * Download the latest version of Records Authority
    * Extract recordsAuthority into the WAMP "www" folder (e.g. **c:/wamp/www/**)
    * Move the folder jetty-6.1.22 to **c:/jetty-6.1.22/**
    * goto **c:/jetty-6.1.22/bin/** and run **Jetty-Service.exe**
    * Access your phpMyAdmin web interface from WAMP
    * Create a database called **recordsManagementDB**
    * Select this database then click **Import**
    * Browse to **/recordsAuthority/ra\_schema.sql/**and click **Go**
    * The database is now set up
  * ### Codeigniter Configuration ###
    * browse to **/recordsAuthority/system/application/config/**
    * Edit the file **config.php** - This file contains many useful things such as site name,  site email,  and session handling
      * Under the Base Site URL heading change this line
        * `$config['base_url']` = "http://*_yourSiteNameHere_*/recordsAuthority";
      * Under the Prod Email heading change this line
        * `$config['prodEmail']` = **_yourEmailAddress_**;
      * Save the file
    * Edit the file **database.php** - This file contains your database login information and location
      * Edit these two lines to contain your database login and password
        * `$db['default]['username']` = "**_username_**";
        * `$db['default]['password']` = "**_password_**";
        * NOTEwe suggest using a different username and password than root
    * Browse to **/recordsAuthority/solr**
    * Edit the file **solrIndexer.php**
      * edit the line **`mysql_connect("localhost", "databaseLoginName", "databasePasswordName") or die(mysql_error)`** to contain your database information
  * ### Jetty Configuration ###
    * Move the folder jetty-6.1.22 in the recordsAuthority folder to **/usr/local/**
    * Changing Ports
      * Browse to **../jetty-6.1.22/etc/**
      * Edit the file **jetty.xml**
      * Go to the line that says **`“<Set name="port"><SystemProperty name="jetty.port" default="8983"/></Set>”`**
      * Change the default port to your desired port number E.G. Change the default port to your desired port number E.G. **`“<Set name="port"><SystemProperty name="jetty.port" default="8080"/></Set>”`**
  * Starting Jetty
    * Go to **/usr/local/jetty-6.1.22/** and enter the command **`“sudo sh startJetty.sh”`**
    * Stopping Jetty
    * Enter **`“ps aux | grep solr”`**
      * Find the _pid_ of the solr instance and enter `kill pid` (super user may be required)
  * ### Dashboard ###
    * Login into the dashboard to continue configuration by clicking the version number on any page.
    * Username default: admin
    * Password default: admin
## Records Management Database ##
  * The records management database is a MySQL based storage system for the RecordsAuthority application
> ### Table Descriptions ###
    * Rm\_associatedUnits – division and departments that associated with a record group
    * Rm\_associatedUnitsTemp – temporary $_POST information so it can be added via ajax into the database on the fly
    * Rm\_audit – logs any information that is changed in the record inventory or record groups along with the user that made the change and time it happened
    * Rm\_departmentContacts – department contact information for the surveys
    * Rm\_departments – department list with associated primary key to divisions
    * Rm\_disposition - DEPRICATED list of static retention rules for the use of a code. Left in the system in case future developers wish to use it
    * Rm\_divisions – division list
    * Rm\_docTypes – list of possible document types a record can be in for the survey form
    * Rm\_fieldTypes – list of field types for the survey creator/editor (e.g. radio buttons, text field)
    * Rm\_fullTextSearch – populated data from the record groups that is published.  This data is then tokenized by SOLR
    * Rm\_recordCategories – list of record categories
    * Rm\_recordType – record inventory information is stored in this table
    * Rm\_recordTypeDeleted – record inventories that have been deleted
    * Rm\_retentionSchedule – record group information is stored in this table
    * Rm\_retentionSchedule – record groups that have been deleted
    * Rm\_rsRelatedAuthorities - DEPRICATED storage of related authorities for usage in the record group.  Left in the system in case future developers wish to use it
    * Rm\_sessions – session data for the application
    * Rm\_surveyContactFields – dynamic contact question data for the survey
    * Rm\_surveyContactNotes – note data for contact information provided by the survey
    * Rm\_surveyContactQuestions – dynamic contact question data for the survey
    * Rm\_surveyContactResponses – survey contact response information
    * Rm\_surveyNotes – survey notes storage from the dashboard survey browser
    * Rm\_surveyQuestionResponses – survey question response information
    * Rm\_surveyQuestions – dynamic survey questions
    * Rm\_surveys – list of current surveys
    * Rm\_surveySubChoiceQuestionResponses – survey response information for sub questions based on radio or check box choices
    * Rm_ surveySubChoiceQuestion – dynamic subquestions for the survey based on radio or check box choices
    * Rm\_surveySubQuestionResponses – survey response information for sub questions
    * Rm\_surveySubQuestion – dynamic subquestions for the survey
    * Rm\_users – list of current users and encrypted passwords