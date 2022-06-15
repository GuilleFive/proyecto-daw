<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            if ($request->deleted === "true") {
                $data = User::onlyTrashed();
            } else
                $data = User::query();

            if ($request->admins === "true") {
                $data = $data->role(['admin'])->with(['roles']);
            } elseif (Auth::user()->hasRole('super_admin')) {
                $data = $data->role(['admin', 'client'])->with(['roles']);
            } else
                $data = $data->role(['admin', 'client'])->with(['address']);

            $data = $data->get();


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($user) {
                    return view('admin.users.buttons', ['user' => $user]);
                })
                ->addColumn('role', function ($user) {
                    return ucfirst($user->roles->first()->name);
                })
                ->rawColumns(['action'])
                ->blacklist(['action'])
                ->make(true);
        }
    }

    public function getDatatable()
    {
        if (Auth::user()->hasRole('super_admin')) {
            return view('superadmin.users.list');
        } else {
            return view('admin.users.list');
        }
    }

    public function deleteUser(Request $request)
    {

        $user = User::query()->find(json_decode($request->user)->id);
        $user->delete();

    }

    public function forceDeleteUser(Request $request)
    {

        $user = User::withTrashed()->find(json_decode($request->user)->id);

        $user->forceDelete();

    }

    public function changePowerUser(Request $request)
    {
        $user = User::query()->find(json_decode($request->user)->id);
        if ($request->change === 'promote')
            $user->roles()->sync(2);
        else
            $user->roles()->sync(3);

    }

    public function restoreUser(Request $request)
    {
        $user = User::withTrashed()->find(json_decode($request->user)->id);
        $user->restore();
    }

    public function profile()
    {

        return view('all.users.profile', ['user' => Auth::user()]);

    }

    public function editProfile(EditProfileRequest $request)
    {
        if (Hash::check($request->current_password, Auth::user()->password)) {
            if ($request->email !== Auth::user()->email)
                Auth::user()->email_verified_at = null;

            Auth::user()->username = $request->username;
            Auth::user()->email = $request->email;
            Auth::user()->phone = $request->phone;

            if ($request->new_password !== null) {
                if (Hash::check($request->new_password, Auth::user()->password))
                    return redirect()->back()->withErrors(['new_password' => 'La nueva contraseña no debe ser igual a la anterior'])->withInput();
                Auth::user()->password = Hash::make($request->new_password);
            }

            Auth::user()->save();

            return redirect()->back()->with('success', 'Datos actualizados');
        } else
            return redirect()->back()->withErrors(['current_password' => 'Comprueba tu contraseña'])->withInput();
    }

    public function deleteAccount()
    {
        $user = User::find(Auth::user()->id);

        Auth::logout();

        $user->delete();



    }
}
