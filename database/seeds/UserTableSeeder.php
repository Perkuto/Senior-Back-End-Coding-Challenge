<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 5/30/2015
 * Time: 12:23 PM
 */

namespace App\Seeds;
use Illuminate\Database\Seeder;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserTableSeeder extends Seeder
{

    public static $names = [
        'Kecia',
        'Danette',
        'Isidra',
        'Cindie',
        'Latisha',
        'Ross',
        'Chi',
        'Zulema',
        'Truman',
        'Jenice',
        'Kathi',
        'Karie',
        'Shelton',
        'Kathline',
        'Dorla',
        'Sue',
        'Rosemarie',
        'Erminia',
        'Shea',
        'Bernardina'
    ];


    public function run()
    {

        DB::table('users')->delete();
        $i = 0;
        foreach (self::$names as $name)
        {
            ++$i;
            $isImagePrivate = rand(0,1);

            $email = sprintf('%s@gmail.com', $name);
            $user = User::create(['email' => $email, 'name' => $name, 'password' => Hash::make('test123')]);
            Image::create(['user_id' => $user->id, 'name' => sprintf('%d.jpg', $i), 'is_private' => $isImagePrivate]);

        }
    }
}