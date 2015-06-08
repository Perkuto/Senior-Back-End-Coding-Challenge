<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 5/28/2015
 * Time: 11:09 PM
 */

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    /**
     * Show the profile for the given user.
     *
     * @param  int $id
     * @return Response
     */
    public function listImages($id)
    {

        $images = Image::findAllForUser($id);

        return response()->json($images->toJson());
    }

    public function addImage(Request $request)
    {
        $file = $request->file('image');
        $user = $request->user();
        $name = md5(uniqid());
        $isPrivate = (bool)$request->input('isPrivate');

        if ($user) {
            if ($this->isValidUpload(array('image' => $file))) {
                $ext  = $file->getClientOriginalExtension();
                $name = sprintf('%s.%s', $name, $ext);
                $file->move(public_path() . '/uploads', $name);

                $image = Image::create(['user_id' => $user->id, 'name' => $name, 'is_private' => $isPrivate]);

                return response()->json($image->toJson());
            }

            return response()->json(array('error' => 'Invalid file type'));

        }

        return response('unAuthorized', '403');
    }


    public function deleteImage($id, Request $request)
    {

        if ($user = $request->user()) {
            $deletedImage = Image::deleteForUser($id, $user->id);

            if (!empty($deletedImage)) {
                $disk = Storage::disk('uploads');
                $disk->delete($deletedImage);

                return response()->json(array('success' => true));
            }

            return response('Not Found', 404);
        }

        return response('unAuthorized', '403');


    }

    public function updateImage($imageId, Request $request)
    {
        $file = $request->file('image');
        $user = $request->user();
        $isPrivate = (bool)$request->input('isPrivate');
        if ($user) {
            if ($this->isValidUpload(array('image' => $file))) {
                if ($image = Image::findByIdForUser($imageId, $user->id)) {

                    //delete old image
                    $disk = Storage::disk('uploads');
                    $disk->delete($image->name);

                    //remove file extension in the filename
                    $imageParts = explode('.', $image->name);
                    array_pop($imageParts);
                    $imageName = implode('.', $imageParts);
                    $ext = $file->getClientOriginalExtension();
                    $name = sprintf('%s.%s', $imageName, $ext);

                    $file->move(public_path() . '/uploads', $name);


                    $image->name = $name;
                    $image->is_private = $isPrivate;
                    $image->save();

                    return response()->json($image->toJson());
                }

                return response('Not Found', 404);

            }

            return response('Invalid Request', 400);
        }

        return response('unAuthorized', '403');
    }

    public function mockAuthenticate($email, $password)
    {
        if ($user = Auth::attempt(['email' => $email, 'password' => $password])) {

            return redirect()->route('user_pictures', ['user_id' => Auth::user()->id]);
        }
        return response('unAuthorized', '403');
    }

    private function isValidUpload($file, array $rule = array('image' => 'image'))
    {
        //no file uploaded
        if (empty($file['image'])) {

            return false;
        }
        $validator = Validator::make($file, $rule);

        return !$validator->fails();
    }

}