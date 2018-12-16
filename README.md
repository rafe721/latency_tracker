# latency_tracker
A Simple project in PHP (laravel) and VueJS to track latency of multiple hosts

## Assumptions and considerations
1. All blogs are **visible to any user** who interacts with the system regardless of their login status.
2. **A user can login** to the blog after which they can create blogs and publish they in the application.
3. A logged in user can **update and/or delete** only the blogs that they create.
4. It was decided to not implement the app in VueJS due to time constraints.

---

## Platform
1. Windows 7 Home Premium Service Pack 1 & MacOS Mojave
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

---

## Deployment instructions
Please ensure that you have all the components of the application stack in your computer. If any of the application stack or the tools mentioned in the below points are missing, please install the versions mentioned in the application stack or greater for your Operating system.

1. Ensure you have **php 7.20**; See [PHP 7.2 installation](http://php.net/manual/en/install.php).
2. Please follow the [Laravel Installation guide](https://laravel.com/docs/5.6/installation) and/or [mongoDB Installation guide](https://docs.mongodb.com/manual/administration/install-community/) either of these are not available in your computer.
3. Install **Composer** in your system. See [Composer - Getting Started](https://getcomposer.org/doc/00-intro.md).

Once you have the application stack in place:

1. **Clone the contents of this repository** to desired location of your computer.
2. To install all the **required dependencies**, run ``composer install``.
3. **Start** your application server. If it is the Laravel Artisan server, run the command ``sh php artisan serve`` from your project directory.

---

## Using the App
 To Do

---

## The way forward if there was more time
