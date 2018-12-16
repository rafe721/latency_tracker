# latency_tracker
A Simple project in PHP (laravel) and VueJS to track latency of multiple hosts

## Assumptions and considerations
1. There is no authentication layer to manage users in the application.
2. **Only Valid host names and IPv4** addresses can be pinged by the server application. Hence only they can be accepted by the front end.

---

## Platform
1. Windows 7 Home Premium Service Pack 1
2. PHP aritsan developmental web application server.

## Application Stack
1. Laravel 5.6.38 & dependancies
2. PHP7.2.0 (with php_mongodb.dll v1.5.3 x86 non thread safe)

## Resources & tools
1. jquery 3.3.1
2. Bootstrap 4.1.3, Semantics (for UI styles)
3. fontawesome-free 5.3.1
4. Jetbrains PhpStorm 2018.2.4 (IDE)
5. Composer 1.7.2
6. NPM 6.41

---

## Setting up the environment.
Please find below links to installation instructions for the critical components of this application's runtime environment.

1. Ensure you have **php 7.20**; See [PHP 7.2 installation](http://php.net/manual/en/install.php).
2. Install **Composer** in your system. See [Composer - Getting Started](https://getcomposer.org/doc/00-intro.md).
3. Install **npm** for your flavour of operating system. See[Install NPM](https://www.npmjs.com/get-npm) for more.
4. Install **Laravel Globally**. See [](https://laravel.com/docs/5.7/installation) for instructions. In Unix-like systems, if the **laravel** command is still not found, please ensure that the path to composer is exported in the ~/.bashrc file.

Once you have the application stack in place:

1. **Clone the contents of this repository** to desired location of your computer.
2. To install all the **required dependencies**, run ``composer install``. Then run ``npm install``. If you are going to make further changes, especialy to front end Javascript, run ``npm run watch`` on a seperate terminal.
3. **Start** your application server. If it is the Laravel Artisan server, run the command ``php artisan serve`` from your project directory.

---

## Using the App
1. It is a single page app. All routes of the app redirect to this page.
2. On this page, there is a table containing a default list of hostnames and/or IPv4 addresses (hosts).
3. Each of these hosts can be selected/unselected through a checkbox at the begining of their row.
4. A host can be deleted individually through the remove button at the end of the row.
5. At the top left corner of the table there is a **Ping now** button that will reset the timer and ping the server with the list of selected hosts.
6. At the top right conner of the table, there is a **Remove All Selected** button that will remove selected hosts from the list.
7. The last row of the table is used for adding new hosts into the list. It contains a textbox that will accept only host names and IPv4 addresses and buttons for cnacelling and adding the entered host.
8. There is a running counter at the top of the table. A new request would be sent to the server when the counter reaches 0. The counter will wait for the server to respond before reseting and resuming.

---

## The way forward if there was more time
I researched extensively regarding asynchronous processing in PHP. I even spent some time trying to implement it. If there was more time, I would finish implementing it.
1. Perhaps a history of the last ten pings and a table containing their average latency would be a nice addition.
2. I would have implemented this project in node for async processing (I have not worked on node projects from initialisation).
3. I would have implemented sone form of persistence and that would serve as the default list of hosts the next time the application is used.
