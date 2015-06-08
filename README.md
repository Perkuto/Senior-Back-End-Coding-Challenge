## Installation

This repository holds the whole Laravel install, in the future it will rely on composer to install this dependency.

1. Clone this repository
2. Create a database called `perkuto` in your mysql server
3. Give all rights except grants (or at least all CRUD) to the user `perkuto` on the database mentioned above

If you want other mysql settings, feel free to edit the .env file at the root of the project.

run the following command: `php artisan migrate:refresh --seed`
If your mysql parameters are correct this will generate a few users and some photos, creating the tables from scratch.
This is the fixtures data, this command can be re-run any number of times,
and will generate some 'random' data for public and private pictures.

for now you can access the following routes:

`GET /user/{user_id}/images` to list a user's public photos
`GET|POST (any) mock/authenticate/{email}/{password}` to authenticate as any user in the db.
`DELETE user/image/{id}` to delete a user's image, you must mbe authenticated to do so.
`PUT user/image/{id}` to update a user's image. You must be authenticated.
`POST user/image` to add an image to a user. You must be authenticated.

The password for all users is set to `test123`. The email will be found in the users table.

The controllers could be improved by putting the logic for the PUT and POST images into the image model.

## API Usage
`GET /user/{user_id}/images` returns a jsone formed with all public images for the `user_id` in the url.

Successful example return:
````
[
                 {"id":28,"name":"28a47f9b2e592309876d5e387bcd6a28.","created_at":"2015-06-07 22:19:52","updated_at":"2015-06-07 22:19:52"},
                 {"id":29,"name":"6c3bf012b61961e03dc4d9ada6952824.jpeg","created_at":"2015-06-07 22:21:26","updated_at":"2015-06-07 23:38:45"}
]
````
If the user does not exist, the endpoint returns a 404.


`DELETE user/image/{id}`
Successful example return:
{"success": true}

- If the image does not exist, returns 404
- If the user is not authorized, returns 403

`PUT user/image/{id}`
Expects an image field. If the PUT verb cannot be sent, an optional _method parameter can be passed with value PUT.
- Successful example return:
````{"id":28,"name":"28a47f9b2e592309876d5e387bcd6a28.","created_at":"2015-06-07 22:19:52","updated_at":"2015-06-07 22:19:52"}````
- Invalid file type:
````{"error": "Invalid file type}````
- If the user is not authorized, returns 403
- If the image does not exist, returns 404

`POST user/image`
Expects an image field.
- Successful example return:
````{"id":28,"name":"28a47f9b2e592309876d5e387bcd6a28.","created_at":"2015-06-07 22:19:52","updated_at":"2015-06-07 22:19:52"}````
- Invalid file type:
````{"error": "Invalid file type}````
- If the user is not authorized, returns 403

## How to test
The endpoints were tested using the POSTMAN chrome extension, but any HTTP console should be able to ease accessing all urls.
If using POSTMAN, beware it does not seem to be able to POST date with a PUT request, please try with a POST, and the optional `_method=PUT` parameter