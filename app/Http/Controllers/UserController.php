<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use function PHPUnit\Framework\returnArgument;

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
                $data = $data->role(['admin'])->with(['roles', 'address']);
            } elseif (Auth::user()->hasRole('super_admin')) {
                $data = $data->role(['admin', 'client'])->with(['roles', 'address']);
            } else
                $data = $data->role(['admin', 'client'])->with(['address']);

            $data = $data->orderBy('id')->get();


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($user) {
                    return view('admin.users.buttons', ['user' => $user]);
                })
                ->addColumn('role', function ($user) {
                    return ucfirst($user->roles->first()->name);
                })
                ->addColumn('address', function ($user) {
                    if (isset($user->address)) {
                        return ($user->address->name);
                    } else
                        return '-';
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
}
