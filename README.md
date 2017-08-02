# PartizanTest
User Auth test for Partizan


Create the docker virtualised envionment using docker-compose up

Use Composer to install the required packages

run php artisan migrate && php artisan db:seed to seed the database with a default user and organisation.

Make sure to create a new .env file with appropriate SMTP settings to allow the application to send emails.
