# Flashcards backend source

This is the backend for the flashcards application. Running on PHP8 and Laravel framework. 

## Setup

### Download libraries

Run `composer install` to download all the required libraries in the `vendor` folder.

### Environment config

Before running, create a .env file and include your database connection information:

```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=flashcards_db
DB_USERNAME=root
DB_PASSWORD=password
```

Database has to be created beforehand.

### Database setup

Run the migrations to create the tables:
`php artisan migrate`

Next, create the admin user:
`php artisan db:seed --class InitialSeeder`

### Running

`php artisan serve`

or

`php artisan serve --host 0.0.0.0 --port YOUR_PORT`

## Admin dashboard

Go to `localhost:8000` where if everything works you should see a login screen.