<?php

namespace App\Http\Controllers;

use App\Models\role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::paginate(5);
        return view('admin.page.user.index')->with([
            'user' => $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'role' => $request->input('role'),
        ];

        User::create($data);

        return back()->with('message_delete', 'Data Supplier Sudah dihapus');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $user = User::findOrFail($id);

        if($request->input('password') == ""){
            $password = $user->password;
        }else {
            $password = $request->input('password');
        }
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $password,
            'role' => $request->input('role'),
        ];

        $datas = User::findOrFail($id);
        $datas->update($data);
        return back()->with('message_delete', 'Data User Sudah Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = user::findOrFail($id);
        $data->delete();
        return back()->with('message_delete','Data Supplier Sudah dihapus');
    }
}
