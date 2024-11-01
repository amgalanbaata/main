<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\AppUsers;
use Session;
use App\Models\Post;
use App\Models\Model\Location;
use Exception;
use Illuminate\Support\Facades\Storage;
class AdminController extends Controller
{
    public function index()
    {
        if (Session::get('admin_token') != '') {
            // return view('admin.dashboard');
            return redirect('/admin/dashboard');
        } else {
            Session::forget('admin_token');
            return view('admin.login', ['message' => '']);
        }
    }

    public function dashboard(Request $request)
    {
        $model = new Admin;
        $username = $request->input('username');
        $password = $request->input('password');
        $token = $request->input('_token');
        // $code = $request->sendCode();
        $data = $model->postSelect(false, false, false, false, false, false, 0);
        $image_path = $model->imagePathAll();
        $admin = $model->adminLogin($username, $password);
        $agreedCounts = $model->getAgreedCounts();
        $newCounts = $model->getNewCounts(Session::get('admin_is'));
        if (count($admin) > 0) {
            Session::put('admin_id', $admin[0]->id);
            Session::put('admin_token', $token);
            Session::put('admin_name', $admin[0]->username);
            $counts = $model->getCountsByStatus(Session::get('admin_is'));
            $typeCounts = $model->getTypeCount(Session::get('admin_is'));
            $admin_is = $admin;
            Session::put('admin_is', $admin_is[0]->type_code);
            return view('admin.dashboard', ['counts' => $counts, 'data' => $data, 'image_path' => $image_path, 'typeCounts' => $typeCounts, 'agreedCounts' => $agreedCounts, 'newCounts' => $newCounts]);
        } elseif (Session::get('admin_token') != '') {
            $counts = $model->getCountsByStatus(Session::get('admin_is'));
            $typeCounts = $model->getTypeCount(Session::get('admin_is'));
            return view('admin.dashboard', ['counts' => $counts, 'data' => $data, 'image_path' => $image_path, 'typeCounts' => $typeCounts, 'agreedCounts' => $agreedCounts, 'newCounts' => $newCounts]);
        } else {
            Session::forget('admin_token');
            Session::flush();
            // return redirect('admin');
            return view('admin.login', ['message' => 'Имэйл эсвэл нууц үг буруу байна.']);
        }
    }

    public function posts(Request $request)
    {
        if (Session::get('admin_token') != '') {
            $model = new Admin;
            $check1 = $request->input('check1');
            $check2 = $request->input('check2');
            $check3 = $request->input('check3');
            $check4 = $request->input('check4');
            $check5 = $request->input('check5');
            $check6 = $request->input('check6');
            $check7 = $request->input('check7');
            $check10 = $request->input('check10');
            if ($request->status == 1) {
                $check1 = true;
            }
            if ($request->status == 2) {
                $check2 = true;
            }
            if ($request->status == 3) {
                $check3 = true;
            }
            if ($request->status == 4) {
                $check4 = true;
            }
            if ($request->status == 5) {
                $check5 = true;
            }
            if ($request->status == 6) {
                $check6 = true;
            }
            if ($request->status == 10) {
                $check5 = true;
                $check6 = true;
            }

            $data = [];

            if ($check7) {
                $data = $model->postSelectType(1);
            } else {
                $data = $model->postSelect($check1, $check2, $check3, $check4, $check5, $check6, Session::get('admin_is'));
            }
            $condition = [
                "check1" => $check1,
                "check2" => $check2,
                "check3" => $check3,
                "check4" => $check4,
                "check5" => $check5,
                "check6" => $check6,
                "check7" => $check7,
                "check10" => $check10,
            ];
            return view('admin.posts', ['posts' => $data, 'condition' => $condition]);
        } else {
            Session::forget('admin_token');
            return redirect('admin');
        }
    }

    public function post(Request $request)
    {
        if (Session::get('admin_token') != '') {
            $model = new Admin;
            $data = $model->postSingleSelect($request->id);
            $image_path = $model->imagePathSelect($request->id);

            $model = new AppUsers;
            $userData = $model->appUserSelect($data->number);

            $model = new Location;
            $latitude = $data->latitude;
            $longitude = $data->longitude;
            $location = $model->locationSingleSelect($latitude, $longitude);
            // dd($location);
            // dd($location);
            if ($location != null) {
                return view('admin.post', ['post' => $data, 'image_path' => $image_path, 'message' => '', 'location' => $location, 'userData' => $userData]);
            }
            else {
                return view('admin.post', ['post' => $data, 'image_path' => $image_path, 'message' => '', 'userData' => $userData]);
            }
        } else {
            Session::forget('admin_token');
            return redirect('admin');
        }
    }

    public function addPost()
    {
        if (Session::get('admin_token') != '') {
            return view('admin.addPost');
        } else {
            Session::forget('admin_token');
            return redirect('admin');
        }
    }

    public function logout()
    {
        Session::forget('admin_token');
        Session::flush();
        return redirect('admin');
    }

    public function updatePost(Request $request)
    {
        if (Session::get('admin_token') != '') {
            $modelPost = new Post;
            $udata = [
                'status' => $request->status,
                'type' => $request->type,
                'admin_comment' => $request->admin_comment,
                'updated_at' => now(),
            ];
            if ($request->input('action_type') == 'locationAdd') {
                // $request->validate([
                //     'title' => 'required|string|max:255',
                //     'comment' => 'nullable|string',
                //     'latitude' => 'required|string',
                //     'longitude' => 'required|string',
                //     'color' => 'required|string',
                //     'pdfUpload' => 'nullable|file|mimes:pdf|max:10000',
                // ]);
                // dd('wtsf');

                $pdfPath = null;

                if ($request->hasFile('pdfUpload')) {
                    // $pdfPath = $request->file('pdfUpload')->store('uploads', 'public');
                    $file = $request->file('pdfUpload');

                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('uploads');
                    $file->move($destinationPath, $fileName);
                    $pdfPath = 'uploads/' . $fileName;
                }
                // dd($pdfPath);

                $locationData = [
                    'title' => $request->title,
                    'comment' => $request->comment,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'color' => $request->color,
                    'pdf_path' => $pdfPath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                // dd($pdfPath);

                try {
                    Location::create($locationData);
                    return redirect()->back()->with('message', 'success');
                } catch (Exception $e){
                    return redirect()->back()->with('message', 'error');
                }
                // return redirect()->route('locations.index')->with('success', 'Location added successfully.');
                // return redirect()->back()->with('message', 'Амжилттай байршил бүртгэгдлээ.');
            }

            if ($request->input('action_type') == 'resolve') {
                $udata['agreed'] = 'Зөвшөөрсөн';
            }
            if ($modelPost->postUpdate($request->id, $udata)){
                $message = 'success';
                return redirect()->back()->with('message', 'success1');
            } else {
                $message = 'error';
            }
            // print_r($request->input('action_type'));
            $model = new Admin;
            $data = $model->postSingleSelect($request->id);
            $image_path = $model->imagePathSelect($request->id);
            return view('admin.post', ['post' => $data, 'image_path' => $image_path, 'message' => $message]);
        } else {
            Session::forget('admin_token');
            return redirect('admin');
        }
    }
}
