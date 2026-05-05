<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    private array $users = [
        [
            'id'       => 1,
            'name'     => 'Admin Kampus',
            'email'    => 'admin@kampus.ac.id',
            'password' => 'password',
            'role'     => 'admin',
        ],
        [
            'id'       => 2,
            'name'     => 'SuperAdmin Kampus',
            'email'    => 'superadmin@kampus.ac.id',
            'password' => 'password',
            'role'     => 'superadmin',
        ],
        [
            'id'       => 3,
            'name'     => 'Dosen',
            'email'    => 'dosen@kampus.ac.id',
            'password' => 'password',
            'role'     => 'dosen',
        ],
        [
            'id'       => 4,
            'name'     => 'I Made Syaeful Gahar',
            'email'    => 'syaefuldarmawan02@gmail.com',
            'password' => 'password',
            'role'     => 'mahasiswa',
        ],
    ];

    public function process(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = collect($this->users)
            ->firstWhere('email', strtolower($request->email));

        if ($user && $user['password'] === $request->password) {
            session([
                'user_id'    => $user['id'],
                'user_name'  => $user['name'],
                'user_email' => $user['email'],
                'user_role'  => $user['role'],
                'logged_in'  => true,
                'is_verified' => true,
            ]);

            return match($user['role']) {
                'superadmin' => redirect()->route('dashboard'),
                'admin'      => redirect()->route('admin.dashboard'),
                default      => redirect()->route('user.dashboard'),
            };
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }

    public function getUsers()
    {
        return $this->users;
    }
}