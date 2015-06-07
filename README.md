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

`GET /user/{user_id}/pictures` to list a user's public photos
`GET|POST (any) mock/authenticate/{email}/{password}` to authenticate as any user in the db.
The password is set to `test123`.

Routes for delete and Update Image and its controller have been setup but the code is not yet final.

Please note this is prototype quality code, to be improved.




