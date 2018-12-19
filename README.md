# Koto
Koto is a simple photo sharing platform with an open source REST API.

### Installation
* Requires PHP, MariaDB (PDO).
* Import database from koto.sql (includes demo user).
* Configure database credentials in connect.php
* API Demo: https://koto.spaquito.ca/rest-api/ (returns JSON)
* Front-end Demo: https://koto.spaquito.ca (responseive HTML5, jQuery)

### Generate Token /auth
* POST: Accepts "username" and "password".
* Demo: https://koto.spaquito.ca/rest-api/auth (username: demo, password: test)

Returns Token:
````
{"Token":"6974740f6d7f2cc35db04ae0ceded6aa3db18c29daca818be09136ee005050b7de5796559199e3478f560ee2c0365104395b8b6957884740c50077ef4ec9b9fa"}
````

### Add or Update Photo /photo
* POST: Accepts "token", "photo", "title", "caption", "privacy", "photo_id".
* POST "photo" must be "allowed" format (jpg, png, gif).
* Leave "photo_id" empty to upload a new photo.
* Specify "photo_id" to update existing photo.
* Set "privacy" = "1" to set photo as private.
* Demo: https://koto.spaquito.ca/rest-api/photo

Returns:
````
{"Message":"Photo successfully uploaded","Photo ID":"c904b813-6805-4074-b8c6-8b45e09a20ad","Title":"Relaxing","Caption":"This is a photo of bench in the woods by the river.","Privacy":"0"}  
````

### Delete Photo /photo
* DELETE: Accepts "token", "photo_id".
* A photo can only be deleted by the user that uploaded the photo.
* Demo: https://koto.spaquito.ca/rest-api/photo

Returns JSON Message:
````
{"Message":"Photo successfully deleted"}
````

### Delete Token (logout) /auth
* DELETE: Accepts "token".
* Demo: https://koto.spaquito.ca/rest-api/auth

Returns JSON Message:
````
  {"Message":"Token has been deleted"}
````

### Response Codes
All API calls return appropriate HTTP response codes (200, 400, 401, 405).
