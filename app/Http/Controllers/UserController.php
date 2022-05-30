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
                $data = User::role(['client', 'admin'])->addSelect(['address' => Address::query()->select('name')->whereColumn('address_id', 'addresses.id')->limit(1)])->with('roles')->get();
            else
                $data = User::role('client')->addSelect(['address' => Address::query()->select('name')->whereColumn('address_id', 'addresses.id')->limit(1)])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('admin.users.buttons');
                })
                ->addColumn('role', function ($user) {
                    return ucfirst($user->roles->first()->name);
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
