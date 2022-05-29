<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function PHPUnit\Framework\returnArgument;

class UserController extends Controller
{
    public function getClients(Request $request)
    {
        if ($request->ajax()) {
            $data = User::role('client')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                   return view('admin.users.buttons');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
