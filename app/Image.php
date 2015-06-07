<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['is_private', 'user_id', 'name'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['is_private', 'user_id'];

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function scopePublicImage($query)
    {
        return $query->where('is_private', '=', 0);
    }

    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', '=', $userId);
    }

    public static function findAllForUser($id)
    {
        $images = Image::publicImage()->where('user_id', '=', $id)->get();

        return $images;

    }

    public static function findByIdForUser($id, $userId)
    {
        $image = Image::OfUser($userId)->find($id);

        return $image;
    }

    public static function deleteForUser($id, $userId)
    {
        $imageName = Image::findByIdForUser($id, $userId)->name;

        Image::OfUser($userId)->where('id', '=', $id)->delete();

        return $imageName;
    }

    public static function updateForUser($id, $userId)
    {
        Image::OfUser($userId)->where('id', '=', $id)->touch();
    }

    public static function addForUser()
    {

    }





}
