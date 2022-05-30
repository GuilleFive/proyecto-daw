<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Builder;
use Yajra\DataTables\DataTables;
use function PHPUnit\Framework\returnArgument;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole('super_admin'))
                $data = User::role(['client', 'admin'])->with(['roles', 'address'])->get();
            else
                $data = User::role('client')->with(['roles', 'addresses'])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function () {
                    return view('admin.users.buttons');
                })
                ->addColumn('role', function ($user) {
                    return ucfirst($user->roles->first()->name);
                })
                ->addColumn('address', function ($user) {
                    if(isset($user->address)){
                        return ($user->address->name);
                    }
                    else
                        return '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getDatatable()
    {
        if(Auth::user()->hasRole('super_admin')){
            return view('superadmin.users.list');
        }
        else{
            return view('admin.users.list');
        }
    }
}
