##Saladoo Assignment
I used for this project **Lumen/Laravel** framework, the system built by using MVC with services and repositories layers pattern.

###Requirments 
to run the application the at the server/pc you has to have the following:

* php 7
* Composer
* Mysql Database V8 or higher

###setup

run the following commands through command prompt : 

* **composer update** to install the required dependencies
* rename **.env.example** to **.env** and fill your database host, name, user name and password information
* run the following command to install the database: **php artisan migrate**
* run the following command to seed the customer information to the database: **php artisan db:seed** 
* run the following command to run the server: php -S localhost:1984 -t public 

###code layers
* **Models**: exists at **app/** folder
* **Controllers**: exists at **app/Http/Controllers** folder
* **Repositories**:  exists at **app/Http/repoistories** folder
* **Services**: exist at **app/Http/Services/** folder
* **DTO (Data Transfer Objects)**: exist at **app/Http/Helpers/** folder

###API documentation

For API documentation and testing I usually use postman app, you can download it from here: 
[postman app](https://www.getpostman.com/apps)

Import the api routes from "ecommerce.postman_collection.json" file, it exists at the root folder at this repository.

add global variable at the postman app: **{{e_url}}** to can implements the APIs

The APIs at the system are as the following:
####Products apis
* **post api/v1/products/add**: the system will add new product after check that the product name unique, price exist, currency is exist and its one of the following list **(EUR, USD)**
the api.
```javascript
{
	"product_name": "my second product",
	"product_price": "50",
	"price_currency": "EUR"
}
```
* **get api/v1/products/:id**: get the selected after check that the product exist at the database
* **delete api/v1/products/:id** : the system will check if the product exist at the database and remove it if found.
* **put api/v1/products/:id** :  the system will edit selected product after check that the product is exist at the database, name unique, price exist, currency is exist and its one of the following list **(EUR, USD)**
```javascript
{
"product_name": "my second product",
"product_price": "50",
"price_currency": "EUR"
}
```
* **post api/v1/products/search** : search for products depends on the filters at the request body
```javascript
{
	"product_name": "my second product",
	"product_price": "50",
	"price_currency": "EUR",
	"discount_amount": "10",
	"discount_type": "number"
}
```

####Discount APIs
* **post api/v1/discounts/add**: the system will add new discunt after check that the product exist, discount amount exist, discount type one of the following **(number, percentage)**, discount amount between 1 to 99 if percentage type and discount amount less than product price if discount type is number 

```javascript
{
	"discount_type": "percentage",
	"discount_amount": 10,
	"product_id":1
}
```
* **delete api/v1/discounts/:id** : the system will check if the discount exist at the database and remove it if found.
* **put api/v1/discounts/:id**: the system will add edit discount after check that the discount exist, the product exist, discount amount exist, discount type one of the following **(number, percentage)**, discount amount between 1 to 99 if percentage type and discount amount less than product price if discount type is number 
```javascript
{
	"discount_type": "percentage",
	"discount_amount": 10,
	"product_id":1
}
```

###order (cart) APIs
**All carts API will be related to one customer selected by default by the code**
* **get api/v1/cart/add**: the system will add new item to cart after check that the product exist
* **delete api/v1/cart/:id**: the system will delete selected cart after check that the cart exist
* **delete api/v1/cart/:product/id**: the system will delete selected item from the cart after check that the product exist and exist at the customer cart
* **get /api/v1/cart**: get the customer cart if exist

#####if there are any errors, it will return the error messages in 404 headers
