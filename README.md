# installation Guide: 
1. Download the zip and extract it
2. Open your terminal or command prompt inside the project folder
3. Run "composer install" to install PHP dependencies
4. run "php artisan key:generate " to generate an application key
5. Duplicate the .env.example file and rename it to .env.
6. Open the .env file and set your database connection details
7. Open the .env file and set your database connection details as:
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
8. run "php artisan migrate"
9. run "php artisan serve" to run the project


# Rlevant File Location:
app\Http\Controllers\transaction\TransactionController.php
app\Http\Controllers\transaction\UserController.php
app\Http\Controllers\transaction\AuthController.php
app\Models\User.php
app\Models\Transaction.php
routes\api.php
database\migrations\2014_10_12_000000_create_users_table.php
database\migrations\2024_05_07_084241_create_transactions_table.php

# Testing Video Link:
https://drive.google.com/file/d/1y4KwFmOJTjDir5dq2mOr--71IMzMmHth/view?usp=sharing

