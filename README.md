# Project Defshop

This project is about a small online shop. Which have following features:

## For User
1. User can browse all products
2. user can filter products by category and color
3. user can add products to shopping cart
4. user can browse Shopping cart and update content
5. user can select a payment method Paypal or cash on delivery
6. User can login to their account.
7. user can see order history
8. User can register and need to activate account from email


## for Admin
1. Admin can login 
2. Admin can manage content of defshop
3. Admin can see order history 


## Setup
 - Clone the repo from ```$ git clone git@github.com:napestershine/defshop.git```
 - Install dependencies by running ```$ composer install```
 - Import database into your mysql db from ```database```
 - specify db settings in `inc/config.php` 
 - specify email settings in `inc/config.php` 
 - specify other settings as per needs in `inc/config.php`
 - run application ```$ php -S localhost:8000 -t index.php``` 
 - for login details see `details.txt`
 
 ## Limitations:
 
 1. Paypal have been integrated as payment options, which is using SDK and currently not finished testing
 2. Unfortunately, no test cases preset as looking for solution for browser testing with PHPUnit
 3. Code is for development and demo purpose only. Not meant to be deployed in production.
 
 ## Future Tasks
 1. Some places values are hard coded, so that can moved to configuration file
 2. Configs are hard coded, can be moved to some .emv file to provide more flexibility
 3. Design is in basic way, can be improved designe
 