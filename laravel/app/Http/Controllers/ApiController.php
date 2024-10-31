<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\AppUsers;
use Exception;
// use Intervention\Image\ImageManagerStatic as Image;
// use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use DB;

class ApiController extends Controller
{
    public function insert(Request $request)
    {
        $model = new Post();
        $imagePaths = [];
        if ($request->_token) {
            $imagePaths[] = $this->saveImage($request->image1, 'posts');
            $imagePaths[] = $this->saveImage($request->image2, 'posts');
            $imagePaths[] = $this->saveImage($request->image3, 'posts');
        } else {
            foreach ($request->image as $image) {
                $imagePaths[] = $this->saveImage($image, 'posts');
            }
        }

        $post = [
            'name' => $request->name,
            'number' => $request->number,
            'comment' => $request->comment,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->_token ? $request->address : '',
            'status' => 1,
            'admin_comment' => '',
            'type' => $request->type,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $insertedId = $model->postInsert($post);
        for ($i = 0; $i < count($imagePaths); $i++) {
            $data = [
                    'post_id' => $insertedId,
                    'image_name' => $imagePaths[$i]
            ];
            $model->postImageInsert($data);
        }
        if ($request->_token) {
            return redirect('admin/posts');
        }
        return response([
            'message' => 'Post created.',
            'post' => $post,
            'id' => $insertedId,
            'created_at' => now(),
            'updated_at' => now()
        ], 200);
    }

    public function myPosts(Request $request)
    {
        $model = new Post();

        $data = $model->postsSelect($request->id);

        return response()->json($data);
    }

    public function count(Request $request)
    {
        $model = new Post();
        $allPost = $model->allPostsCount();

        return response()->json($allPost);
    }

    public function privacy() {
        return view('admin.privacy');
    }

    // public function location(Request $request)
    // {
    //     $request->validate([
    //         'latitute' => 'required|numeric',
    //         'longitute' => 'required|numeric',
    //     ]);

    //     $location = [
    //         'latitute' => $request->latitute,
    //         'longitute' => $request->longitute
    //     ];

    //     if (!$location) {
    //         // return response()->json(['error' => 'Phone number is required'], 400);
    //     }

    //     return view('admin.staticUrls.map', ['location' => $location]);

    //     return response()->json(['url' => 'success'], 200);
    // }

    public function sendCode(Request $request)
    {
        $phone = $request->input('phone');
        $code = $request->input('code');

        if (!$phone) {
            return response()->json(['error' => 'Phone number is required'], 400);
        }

        // $data = array('phone' => $phone, 'sms' => $code);
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, "https://ask-techno.com/mail/sms.php");
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        // curl_setopt($ch, CURLOPT_HTTPHEADER,
        //     array(
        //         'Content-Type: application/json',
        //         'Cache-Control: no-cache'
        //     )
        // );
        // $output = curl_exec($ch);
        // if (curl_error($ch)) {
        //     $error_msg = curl_error($ch);
        //     print_r($error_msg);
        // }
        // curl_close($ch);
        // return $output;
        return response()->json(['message' => 'OK'], 200);
        // try {
        //     $model = new Post();
        //     $model->phone = $phone;
        //     $model->code = $code;
        //     // $model->save();

        //     return response()->json(['message' => 'Code sent successfully'], 200);
        // } catch (Exception $e) {
        //     return response()->json(['error' => 'Failed to send code', 'details' => $e->getMessage()], 500);
        // }
    }
    public function email(Request $request) {

        $email = $request->input('email');
        $user = AppUsers::where('email', $email)->first();

        if($user) {
            return response()->json(['message' => 'OK'], 200);
        } else {
            return response()->json(['message' => 'NO'], 400);
        }
    }

    public function getSentPosts(Request $request) {
        $email = $request->input('email');
        if($email) {
            $posts = Post::where('number', $email)
                        ->orderBy('created_at', 'desc')
                        ->get();
            $arr = array();
            $i = 0;
            foreach($posts as $post) {
                $arr[$i]['id'] = $post['id'];
                $arr[$i]['name'] = $post['name'];
                $arr[$i]['number'] = $post['number'];
                $arr[$i]['comment'] = $post['comment'];
                $arr[$i]['type'] = $post['type'];
                $arr[$i]['status'] = $post['status'];
                $arr[$i]['latitude'] = $post['latitude'];
                $arr[$i]['longitude'] = $post['longitude'];
                $arr[$i]['admin_comment'] = $post['admin_comment'];
                $arr[$i]['date'] = substr($post['created_at'],0,10);
                $arr[$i]['image1'] = null;
                $arr[$i]['image2'] = null;
                $arr[$i]['image3'] = null;
                $images = DB::table('post_images')->where('post_id', $post['id'])->get();
                for($j = 1; $j <= count($images); $j++) {
                    $arr[$i]['image'.$j] = url('/')."/images/posts/".$images[$j - 1]->image_name;
                }
                $i++;
            }

            if (count($arr) == 0) {
                return response()->json(['message' => 'No Posts Foind for this email'], 400);
            }

            return response()->json([
                'message' => 'OK',
                'posts' => $arr,
            ], 200);
        } else {
            return response()->json(['message' => 'Email not provided'], 400);
        }
    }

    public function password(Request $request) {
        $password = $request->input('password');
        $email = $request->input('email');

        $user = AppUsers::where('email', $email)->first();


        if ($user && $user->password === $password) {
            return response()->json([
                'message' => 'OK',
                'district' => $user->district,
                'committee' => $user->committee
            ], 200);
        } else {
            return response()->json(['message' => 'NO'], 400);
        }
    }

    public function typeName() {
        // Query the database for the 'name' field
        $typeNames = DB::table('type')->get(['id', 'type_code', 'name']);

        // Check if there is data
        if ($typeNames->isNotEmpty()) {
            // Return the names as a JSON response with UTF-8 encoding
            return response()->json($typeNames, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        } else {
            // Return an empty array with a 404 status code
            return response()->json([], 400);
        }
    }

    public function statusName() {
        // Query the database for the 'name' field
        $statusNames = DB::table('status')->get(['id', 'status_code', 'name']);
        $arr = array();
        $i = 0;
        foreach($statusNames as $statusName) {
            $arr[$i]['id'] = (int) $statusName->id;
            $arr[$i]['type_code'] = $statusName->status_code;
            $arr[$i]['name'] = $statusName->name;
            $i++;
        }

        // Check if there is data
        if (count($arr) > 0) {
            // Return the names as a JSON response with UTF-8 encoding
            return response()->json($arr, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        } else {
            // Return an empty array with a 404 status code
            return response()->json([], 400);
        }
    }

    public function saveImage($image, $path = 'public')
    {
        if(!$image)
        {
            return null;
        }

        try {
            $filename = round(microtime(true) * 1000).'.png';
            // save image
            $fileData = base64_decode($image);
            // $img = Image::make($fileData);
            $filePath = public_path('images/posts/'.$filename);
            file_put_contents($filePath, $fileData);
            // $img->save($filePath, 90);
            // $manager = new ImageManager(new Driver());
            // // read image from file system
            // $image = $manager->read($filePath);
            // // resize image proportionally to 300px width
            // // $image->scale(width: 400);
            // // save modified image in new format
            // $image->toPng()->save(public_path('storage/posts/test.png'));
            // $image = Image::make($filePath)->resize(400, 200);
            /*list($width, $height) = getimagesize($filename);
            $new_width = 400;
            $new_height = 600;

            // Resample
            $image_p = imagecreatetruecolor($new_width, $new_height);
            $image = imagecreatefromjpeg($filename);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

            // Output
            imagejpeg($image_p, $filePath, 100);*/
            return $filename;
        } catch(Exception $e) {
            print_r($e);
            echo $e;
            return null;
        }
    }
    // public function saveImage($image, $path = 'public')
    // {
    //     if (!$image) {
    //         return null;
    //     }

    //     try {
    //         ini_set('memory_limit', '4G');
    //         $filename = round(microtime(true) * 1000) . '.png';
    //         $fileData = base64_decode($image);

    //         // Initialize ImageManager without drivers (it will auto-select)
    //         $manager = new ImageManager();

    //         // Create image instance directly from the base64 data
    //         $img = $manager->make($fileData);

    //         // Resize the image before saving to reduce memory footprint
    //         $img->resize(400, null, function ($constraint) {
    //             $constraint->aspectRatio();
    //             $constraint->upsize();
    //         });

    //         // Save the image to the file path
    //         $filePath = public_path('storage/posts/' . $filename);
    //         $img->save($filePath, 90); // Save with quality parameter

    //         return $filename;
    //     } catch (Exception $e) {
    //         print_r($e);
    //         echo $e;
    //         return null;
    //     }
    // }
}
