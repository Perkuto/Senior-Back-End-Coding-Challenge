# Koto
Koto is a simple photo sharing REST API.
* Requires PHP, MariaDB (PDO)
* API Demo: https://koto.spaquito.ca/rest-api/
* Front-end Demo: https://koto.spaquito.ca

### Generate Token /auth
* POST: Accepts "username" and "password"
* Demo: https://koto.spaquito.ca/rest-api/auth (username: demo, password: test)

Returns Token
````
[
  {"Token":"6974740f6d7f2cc35db04ae0ceded6aa3db18c29daca818be09136ee005050b7de5796559199e3478f560ee2c0365104395b8b6957884740c50077ef4ec9b9fa"}
  
]
````

### Add or Update Photo /photo
* POST: Accepts "token", "photo", "privacy", "caption", "title", "photo_id"
* POST "photo" must be allowed image format.
* Leave "photo_id" empty to upload a new photo.
* Specify "photo_id" to update existing photo.
* Demo: https://koto.spaquito.ca/rest-api/photo

Returns Message
````
[
{"Message":"Photo successfully uploaded","Photo ID":"c904b813-6805-4074-b8c6-8b45e09a20ad","Title":"Relaxing","Caption":"This is a photo of bench in the woods by the river.","Privacy":"0"}  
]
````

### Delete Token (logout) /auth
* DELETE: Accepts "token"
* Demo: https://koto.spaquito.ca/rest-api/auth

Returns Message
````
[
  {"Message":"Token has been deleted"}
]
````

