<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */

    public function create()
    {
        $prodi = DB::table('prodi')
                ->select('id', 'nama')
                ->distinct()
                ->get();
        $universitas = DB::table('universitas')
                ->select('id', 'nama')
                ->distinct()
                ->get();
        // dd($universitas);
        return view('admin.user.add', compact('prodi','universitas'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z., ]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', Rules\Password::defaults()],
            'img' => ['nullable'],
            'otoritas' => ['required', 'string', 'max:255'],
            'jabatan'=> ['max:255', 'nullable'],
            'prodi' => ['required', 'nullable'],
            'universitas' => ['required', 'nullable']
        ]);

        $img = $request->file('img');
        if ($img != null) {
            $imagePath = round(microtime(true) * 1000) . '-' . str_replace(' ', '-', $img->getClientOriginalName());
            $img->move(public_path('../public/assets/img/pp/'), $imagePath);
        } else {
            $imagePath = 'User-Profile.png';
        }

        $password = $request->password;
        if ($password == null) {
            $password = 'unilajaya';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'img' => $imagePath,
            'otoritas' => $request->otoritas,
            'jabatan' => $request->jabatan,
            'id_prodiUser' => $request->prodi,
            'id_universitasUser' => $request->universitas
        ]);


        event(new Registered($user));

        // Auth::login($user);

        return redirect('/admin/list-user')->with('success', 'User successfully added!');
    }

    public function list()
    {
        $users = User::where('otoritas', '!=', 'Admin')->orderBy('created_at', 'asc')->get();
        return view('admin.user.list', compact('users'));
    }

    public function reset($id)
    {
        $ids = Crypt::decrypt($id);
        $reset = User::findOrFail($ids);
        $password = 'unilajaya';
        $reset->update(['password' => Hash::make($password)]);
        return redirect('/admin/list-user')->with('success', 'Password successfully reset!');    
    }

    public function delete($id)
    {
        $ids = Crypt::decrypt($id);
        User::where('id', $ids)->delete();
        return redirect('/admin/list-user')->with('success', 'User successfully deleted!');    
    }

    public function edit($id)
    {
        $ids = Crypt::decrypt($id);
        $user = User::findOrFail($ids);

        // Mengambil semua prodi dari database
        $allProdi = DB::table('prodi')->get();

        // Menentukan prodi yang sudah dipilih oleh pengguna
        $selectedProdi = null;
        foreach ($allProdi as $prodi) {
            if ($prodi->id == $user->id_prodiUser) {
                $selectedProdi = $prodi;
                break;
            }
        }

        // Mengambil semua prodi dari database
        $allUniversitas = DB::table('universitas')->get();

        // Menentukan prodi yang sudah dipilih oleh pengguna
        $selectedUniversitas = null;
        foreach ($allUniversitas as $universitas) {
            if ($universitas->id == $user->id_universitasUser) {
                $selectedUniversitas = $universitas;
                break;
            }
        }
        
        return view('admin.user.edit', compact('user', 'allProdi', 'selectedProdi','allUniversitas', 'selectedUniversitas')); 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z., ]+$/'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'img' => ['nullable'],
            'otoritas' => ['required', 'string', 'max:255'],
            'jabatan' => [ 'max:255','nullable'],
            'prodi' => ['nullable'],
            'universitas' => ['nullable'],
        ]);
        

        $ids = Crypt::decrypt($id);
        $user = User::findOrFail($ids);
        $img = $request->file('img');
        if ($img != null) {
            if ($user->img != 'User-Profile.png') {
                File::delete(public_path('../public/assets/img/pp' . $user->img));
            }
            $imagePath = round(microtime(true) * 1000) . '-' . str_replace(' ', '-', $img->getClientOriginalName());
            $img->move(public_path('../public/assets/img/pp'), $imagePath);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'img' => $imagePath,
                'otoritas' => $request->otoritas,
                'jabatan' => $request->jabatan,
                'id_prodiUser' => $request->prodi,
                'id_universitasUser' => $request->universitas
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'otoritas' => $request->otoritas,
                'jabatan' => $request->jabatan,
                'id_prodiUser' => $request->prodi,
                'id_universitasUser' => $request->universitas
            ]);
        }

        return redirect('/admin/list-user')->with('success', 'User successfully edited!');;
    }

    public function create_wfile(Request $request) {
        try {
            $excel = $request->file('excel');
            $excelPath = round(microtime(true) * 1000) . '-' . str_replace(' ', '-', $excel->getClientOriginalName());
            $excel->move(public_path('../public/assets/excel/'), $excelPath);
            Excel::import(new UsersImport, public_path('../public/assets/excel/' . $excelPath));
            $file = new Filesystem;
            $file->cleanDirectory('../public/assets/excel/');
            return redirect('/admin/list-user')->with('success', 'User successfully added!');    
        } catch (\Exception $e) {
            return redirect('/admin/add-user')->with('error', "Terjadi kesalahan, silahkan periksa kembali data dalam excel anda!, ". $e);
        }
    }
}
