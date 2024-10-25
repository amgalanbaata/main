<?php

namespace App\Http\Controllers;

use App\Models\Model\User;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Http\Request;
use Session;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::get('admin_token') != '') {
            if (Session::get('admin_is') == 0) {
                $model = new User;
                $users = $model->userSelect();
                $type = [
                    ['type_code' => 0, 'name' => 'Админ'],
                    ['type_code' => 2, 'name' => 'Хог хягдал'],
                    ['type_code' => 3, 'name' => 'эвдрэл доройтол'],
                    ['type_code' => 4, 'name' => 'Бохир'],
                ];

                $data = [
                    'title' => 'Users',
                    'users' => $users,
                    'types' => $type
                ];
                return view('admin.user.users', $data);
            } else {
                return redirect('admin/dashboard');
            }
        } else {
            Session::forget('admin_token');
            return redirect('admin');
        }
    }

    public function userIndex()
    {
        if (Session::get('admin_token') != '') {
            if (Session::get('admin_is') != 0) {
                $model = new User;
                $users = $model->userSelect();
                $data = [
                    'title' => 'Users',
                    'users' => $users,
                ];
                return view('admin.user.changePassword', $data);
            } else {
                return redirect('admin/dashboard');
            }
        } else {
            Session::forget('admin_token');
            return redirect('admin');
        }
    }

    public function editPassword(Request $request)
    {
        if (Session::get('admin_token') != '') {
            $userId = Session::get('admin_id');
            $user = User::find($userId);

            $request->validate([
                'password' => [
                    'required',
                    'string',
                    'min:6',
                    'confirmed',
                ],
            ]);

            if ($user) {
                $user->password = $request->password;
                $user->updated_at = now();

                $user->save();

                return redirect()->route('admin.user.settings')->with('success', 'Password updated successfully.');
            } else {
                return redirect()->back()->with('error', 'User not found.');
            }
        } else {
            Session::forget('admin_token');
            return redirect('admin');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.addUser');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        // $request->validate([
        //     'username' => 'required|string|max:255',
        //     'phone' => 'required|string|max:255',
        //     'email' => 'required|string|max:255',
        //     'password' => 'required|string|min:6',
        //     'type_code' => 'required|integer',
        // ]);

        // Create a new user
        $user = new User([
            'username' => $request->username,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => ($request->password),
            'type_code' => $request->type_code,
        ]);

        try {
            $user->save();
            return redirect()->back()->with('message', 'success');
            dd('success');
        } catch (Exception $e){
            print_r($e);
            return redirect()->back()->with('message', 'error');
            dd('failed');
        }
        // $user->save();
        // dd('successaa');

        // return redirect()->route('users.index')->with('success', 'User added successfully.');
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('admin.user.userEdit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if (Session::get('admin_token') != '') {
            // Find the user by ID
            $user = User::find($id);

            if ($user) {
                // Update user data
                $user->username = $request->username;
                $user->phone = $request->phone;
                $user->email = $request->email;
                $user->password = $request->password;
                $user->type_code = $request->type_code;
                $user->updated_at = now();

                $user->save();

                // return redirect()->route('users.index')->with('success', 'User updated successfully.');
                return redirect()->back()->with('message', 'successUserEdit');
            } else {
                return redirect()->back()->with('message', 'errorUserEdit');
            }
        } else {
            Session::forget('admin_token');
            return redirect('admin');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
    }
}
