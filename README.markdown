FRAMEWORK OVERVIEW
==================

The default framework comes with the following directories.

_docs
-----

This is the frameworks documentation.  PDF files are handy for
searching without requiring internet connection.  The HTML
folder contains web links to the online documentation. You can
delete this folder without any issues.

_install
--------

This contains the files needed to setup the framework using
the instructions provided below. You can delete this folder
without any issues.

application
-----------

This is the core of the framework where all code should
be written. This folder cannot be deleted.

assets
------

This folder is used for any content that will be uploaded
through the framework by external users. This folder cannot
be deleted.

media
-----

This is where all CSS, Images and Javascript files should be
contained. There should be a folder for each device you want to
support.  This folder cannot be deleted.

system
------

This is the main CodeIgniter application.  No changes should be
made to this directory unless it is a direct upgrade to
CodeIgniter itself. This folder cannot be deleted.

web
---

This should be the only web accessible folder of the bunch.
This folder cannot be deleted.


INSTALLING THE FRAMEWORK FOR LOCAL DEVELOPMENT
==============================================

Step 1
------

Create a directory for this project in the root of your web server.
For the sake of this tutorial we will assume you are developing
locally, and you have created a folder named "framework" and
copied the framework files to this folder.  So you would be
accessing this framework via http://localhost/framework/web/

Step 2
------

Now you will need to add the apache configuration code.  You can do
that by either editing your apache httpd.conf file, or creating an
.htaccess file in the web folder.  The code can be found in the
htaccess.txt file found in the _install folder.

There are two Environmental Variables that you will need to take
notice of.

FRAMEWORK_APPLICATION_ENV

This should be either: development, staging or production. If you
are running this locally, more than likely it should be set to
development. If this is not defined, it will default to production.

FRAMEWORK_CONFIG_INI

This is the backbone of the entire framework.  Make sure you set this
correctly to the absolute path to where this file can be located.
You will find a sample framework.ini file in the _install folder.
Copy this ini file somewhere that is not publicly accessible
on your server.  You can rename it to whatever you want. Just make
sure that your also use that same name in the apache path.

Make sure to modify all the paths in the default htaccess.txt file to
reflect where you have installed everything.

Once you get the code in place, restart apache.

Step 3
------

Now that apache is setup, it's time to configure that framework itself.
To do this all you need to do is open the framework.ini file you copied
in the previous step.  There are only a few variables that you need to
update to get everything up and running. If you wish to learn more
about what these variables do, just look in the /application/config/
folder.  The name of the PHP file is the exact same as the section
in the framework.ini file.  i.e. [config] = config.php

The variables you need to pay special attention to are:

    [config]
        base_url            ( this should be the full URL to your installation )
        log_threshold       ( 0 = none, 4 = verbose )
        log_path            ( absolute path to log directory )
        cache_path          ( absolute path to cache directory )

    [database]
        hostname
        username
        password
        database
		memcache_port

    [auth]
        website_name        ( this should be the name of the web site )
        webmaster_email     ( this should be YOUR email address )
		ipinfodb_apikey		( register for this at http://www.ipinfodb.com/register.php )

    [floodblocker]
        logs_path           ( absolute path to log directory )

    [paths]
        media_url           ( this should be the full URL to the media directory )
        media_abs_path      ( absolute path to media directory )
        assets_url          ( this should be the full URL to the assets directory )
        assets_abs_path     ( absolute path to assets directory )

Step 4
------

Once you have finished setting everything up, you can easily install the core
database, tables and default fixtures by pointing your browser to:

	http://localhost/framework/web/system/check

You will need to adjust the above URL, or course, to where you actually placed your
installation of this framework.  This System Check will also tell you if there were
any problem preventing a successful install and suggest actions to take to correct
your installation.

Step 5
------

There are a few optional tools enabled for you to develop easily that are not
already built into either Doctrine of CodeIgniter (CI).

To use the custom code profiler to profile both CI and Doctrine, use the
following code in your CI Controller:

    $this->output->enable_profiler(TRUE);

FirePHP is also installed and enabled by default if your FRAMEWORK_APPLICATION_ENV
is set to development.  To use it in your controllers, use any of the following:

NOTE: You can pass things like integers, strings or arrays.

    $this->firephp->log($var);
    $this->firephp->warn($var);
    $this->firephp->error($var);
    $this->firephp->debug($var);

There is also a built in API with full REST support. Your API Controller can be
located here:

    /application/controllers/ApiController.php

To test the GET method of this API you can use the default enabled API Key and
point your browser to:

    http://localhost/framework/web/api/index/apikey/00038A21-814F-1A94-A593-FECBB8BAF7F2

If everything is working you should correctly get the notice:

Invalid GET Request. Refer to API Documentation.

This is because the API controller triggered the index method in ApiController.php and
there was nothing to do, so it showed an error.  Read the ApiController.php for more
details on how to write your own controller to integrate with your existing models.

The API Keys are stored in the `apikey` table in your database.  By default, the first
key is enabled and assigned to our company.  You can write your own code so assign
API keys to other users however you wish.


SETTING UP GEO LOCATION SERVICE
===============================

This framework uses http://www.ipinfodb.com to pull accurate Geo Location information.
You will need to register with their API service in order to gain access to their
services.  You can do that here:

	http://www.ipinfodb.com/register.php
	
Once you get an API Key from them, update your framework.ini files ipinfodb_apikey variable.
You do not need to do this, but your you will lose capturing this information in the 
database if it is not setup.


WORKING WITH SYSTEM SECTION
===========================

After you have completed the installation, you can access the system section by going to:

	http://localhost/framework/web/system/
	
To login use the default administrator login info:

	username: admin
	password: password

The system section is more for the systems admin to review and manage the inner workings
of the application.  There are six main sections in the system admin area:

dashboard
---------

The dashboard is available at http://localhost/framework/web/system/ and provides quick
access to the other secions of the site.  This page is also handy as if there are any PHP
errors, there will be a red badge on the Error Logs icon indicating the number of errors.

user management
---------------

The user management section is available at http://localhost/framework/web/system/users/
and is, as it sounds, where you will manage your users for your application.

database management
-------------------

There are three subsections inside the database management section.  The first is a place
to view the database schema.  This is an image that can be panned and zoomed that is an
exported PNG from ORM Designer.

The next sections is where the meat of your work will come into play.  Local Migration Manager
is broken down into five main tabs that are in the order that they will be most likely used.
You will want to view the following page for more information about how to use these sections:

	http://localhost/framework/web/system/database/migration#help
	
The last subsection allows you to update the database.  This is done by using the migrations
that are created from the migration tab in the local migration manager subsection.

php error logs
--------------

From time to time you may generate some errors while working on some code.  This section will
capture all PHP errors and log them for easy viewing.  You can configured the level of what
gets logged by altering the log_threshold variable in the framework.ini file.

	0 = Disables logging, Error logging TURNED OFF
	1 = Error Messages (including PHP errors)
	2 = Debug Messages
	3 = Informational Messages
	4 = All Messages
  
For a live site you'll usually only enable Errors (1) to be logged otherwise your log files 
will fill up very fast.

settings
--------

Here you can manage the Cache for the site.  Check the boxes of the active Cache
Types you want to Flush then select "Flush Cache" from the drop down list.  Once you're
ready, click the "Apply to selected" to flush the selected Cache Types.

system check
------------

This System Check will tell you if there are any problems preventing a successful install 
and suggest actions to take to correct your installation.  If everything is configured 
correctly the system check will also see if it can go ahead and perform a clean install
of the system if it detects it is not already installed.  This section will more than 
likely be the first area of the site you access once you have finished setting up your
system as outlined in INSTALLING THE FRAMEWORK FOR LOCAL DEVELOPMENT.

WORKING WITH ADMIN SECTION
==========================

After you have completed the installation, you can access the admin section by going to:

	http://localhost/framework/web/admin/
	
To login use the default administrator login info:

	username: admin
	password: password
	

WORKING WITH ORM DESIGNER
=========================

In order to work with the /application/doctrine/orm_designer/Doctrine.ormdesigner ORM Designer 
file you will need to install ORM Designer located at:

	http://www.orm-designer.com/download-orm-designer
	
Once you have this program installed, you can manage the ORM file with the manual available on
the ORM Designer Website located here:

	http://www.orm-designer.com/tag/doctrine
	
When creating new modules, you will want to check how the other modules were setup first and
duplicate the paths to the YML and XML files so the system will work properly for you.