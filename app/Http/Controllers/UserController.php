<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Menampilkan semua user / staff
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Mengambil data semua user / staff untuk datatable
     *
     */
    public function all_user()
    {
        $user = User::orderBy('level')->get();

        return DataTables::of($user)
            ->addIndexColumn()
            ->editColumn('photo', function ($user) {
                return '<img src="' . $user->get_photo() . '" class="rounded-circle" width="50" height="50">';
            })
            ->editColumn('role', function ($user) {
                $role = "";
                switch ($user->level) {
                    case 1:
                        $role = '<span class="badge badge-primary">Administrator</span>';
                        break;
                    case 2:
                        $role = '<span class="badge badge-warning">EDP</span>';
                        break;
                    default:
                        $role = '<span class="badge badge-success">Operator</span>';
                }
                return $role;
            })
            ->addColumn('action', function ($user) {
                $buttons = '';
                if ($user->level !== 1) {
                    $buttons = '<button type="button" onclick="editUser(`' . route('users.update', $user->id) . '`)" class="btn btn-sm btn-info mr-1" title="Edit User"><i class="fas fa-edit"></i></button>';
                    if ($user->canDelete()) {
                        $buttons .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(`' . route('users.destroy', $user->id) . '`)" title="Hapus User"><i class="fas fa-trash"></i></button>';
                    } else {
                        $buttons .= '<button type="button" class="btn btn-sm btn-secondary" title="Tidak dapat dihapus"><i class="fas fa-ban"></i></button>';
                    }
                }
                return $buttons;
            })->rawColumns(['role', 'photo', 'action'])->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'level' => 'required|in:2,3'
        ]);

        $photo = 'default.jpg';
        $password = bcrypt($request->password);

        $request->merge(['photo' => $photo]);
        $request->merge(['password' => $password]);

        User::create($request->only('name', 'email', 'password', 'level', 'photo'));

        return response()->json([
            'message' => 'User berhasil dibuat'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'level' => 'required|in:2,3'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->level = $request->level;

        if ($request->has('password') && $request->password != '') {
            $request->validate([
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required',
                'level' => 'required|in:2,3'
            ]);

            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'User berhasil diupdate'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->canDelete()) {
            $user->delete();
            return response()->json([
                'message' => 'User berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'message' => 'User tidak dapat dihapus'
        ], 422);
    }

    public function showProfile(){
        return view('admin.users.profile');
    }

    public function updateProfile(Request $request){
        $user = Auth::user();

        $request->validate([
            'name' => 'required',
        ]);

        $user->name = $request->name;

        if($request->has('password') && $request->password != ''){
            $request->validate([
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
                'old_password' => 'required'
            ]);

            if(Hash::check($request->old_password, $user->password)){
                $user->password = bcrypt($request->password);
            } else {
                return redirect()->back()->withErrors([
                    'old_password' => 'Password salah'
                ]);
            }
        }

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile');
            if ($user->photo && Storage::exists($user->photo)) Storage::delete($user->photo);
            $user->photo = $photoPath;
        }

        $user->update();

        return redirect()->back();
    }
}
