<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppUsers;
use Session;


class AppUserController extends Controller
{
    public function index()
    {
        if (Session::get('admin_token') != '') {
            $model = new AppUsers();
            $users = $model->appUsers();

            return view('admin.appUsers.appUsers', ['users' => $users]);
        } else {
            Session::forget('admin_token');
            return redirect('admin');
        }
    }

    public function create()
    {
        return view('admin.appUsers.appUserAdd');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|string|max:255|email',
            'password' => 'required|string|min:6',
            'district' => 'required|string|max:255',
            'committee' => 'required|string|max:255',
        ]);

        // Create a new user
        $appUser = new AppUsers([
            'email' => $request->email,
            'password' => ($request->password),
            'district' => $request->district,
            'committee' => $request->committee,
        ]);

        $appUser->save();

        return redirect()->route('app-users.index')->with('success', 'User added successfully.');
    }

    public function edit($id)
    {
        $user = AppUsers::find($id);

        return view('admin.appUsers.appUserEdit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if (Session::get('admin_token') != '') {
            // Find the user by ID
            $user = AppUsers::find($id);

            if ($user) {
                // Update user data
                $user->email = $request->email;
                $user->password = $request->password;
                $user->committee = $request->committee;
                $user->district = $request->district;
                $user->updated_at = now();

                $user->save();

                return redirect()->route('app-users.index')->with('success', 'User updated successfully.');
            } else {
                return redirect()->route('app-users.index')->with('error', 'User not found.');
            }
            return view('admin.appUsers.appUserEdit', compact('user'));
        } else {
            Session::forget('admin_token');
            return redirect('admin');
        }
    }

    public function destroy($id)
    {
        $user = AppUsers::find($id);

        if ($user) {
            $user->delete();
            return redirect()->route('app-users.index')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->route('app-users.index')->with('error', 'User not found.');
        }
    }
}
