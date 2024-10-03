<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppUsers;


class AppUserController extends Controller
{
    public function index()
    {
        $model = new AppUsers();
        $users = $model->appUsers();

        return view('admin.appUsers.appUsers', ['users' => $users]);
    }

    public function create()
    {
        return view('admin.appUsers.appUserAdd');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'district' => 'required|string|max:255',
            'committee' => 'required|string|max:255',
        ]);

        // Create a new user
        $user = new AppUsers([
            'email' => $request->email,
            'password' => ($request->password),
            'district' => $request->district,
            'committee' => $request->committee,
        ]);

        $user->save();

        return redirect()->route('appUsers.index')->with('success', 'User added successfully.');
    }

    public function edit($id)
    {
        $user = AppUsers::find($id);

        return view('admin.user.appUserEdit', compact('user'));
    }


    public function destroy($id)
    {
        $user = AppUsers::find($id);

        if ($user) {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
    }
}
