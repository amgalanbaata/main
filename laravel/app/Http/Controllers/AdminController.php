<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Session;
use App\Models\Post;
use App\Models\Model\Location;
use Exception;

class AdminController extends Controller
{
    public function index()
    {
        if (Session::get('admin_token') != '') {
            return view('admin.dashboard');
        } else {
            Session::forget('admin_token');
            return view('admin.login');
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
        if (count($model->adminLogin($username, $password)) > 0) {
            Session::put('admin_id', $admin[0]->id);
            Session::put('admin_token', $token);
            $counts = $model->getCountsByStatus(Session::get('admin_is'));
            $typeCounts = $model->getTypeCount(Session::get('admin_is'));
            $admin_is = $model->adminLogin($username, $password);
            Session::put('admin_is', $admin_is[0]->type_code);
            return view('admin.dashboard', ['counts' => $counts, 'data' => $data, 'image_path' => $image_path, 'typeCounts' => $typeCounts]);
        } elseif (Session::get('admin_token') != '') {
            $counts = $model->getCountsByStatus(Session::get('admin_is'));
            $typeCounts = $model->getTypeCount(Session::get('admin_is'));
            return view('admin.dashboard', ['counts' => $counts, 'data' => $data, 'image_path' => $image_path, 'typeCounts' => $typeCounts]);
        } else {
            Session::forget('admin_token');
            return redirect('admin');
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
            return view('admin.post', ['post' => $data, 'image_path' => $image_path, 'message' => '']);
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
                $request->validate([
                    'title' => 'required|string|max:255',
                    'comment' => 'nullable|string',
                    'latitude' => 'required|string',
                    'longitude' => 'required|string',
                    'color' => 'required|string',
                ]);

                $locationData = [
                    'title' => $request->title,
                    'comment' => $request->comment,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'color' => $request->color,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                try {
                    Location::create($locationData);
                } catch (Exception $e){
                    dd($e);
                }


                return redirect()->route('locations.index')->with('success', 'Location added successfully.');
            }

            if ($request->input('action_type') == 'resolve') {
                $udata['agreed'] = 'Зөвшөөрсөн';
            }
            if ($modelPost->postUpdate($request->id, $udata)){
                $message = 'success';
                return redirect()->back();
            } else {
                $message = 'error';
            }
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
