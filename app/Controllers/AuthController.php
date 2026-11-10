<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Tampilkan halaman login Admin atau proses login
     */
    public function login()
    {
        // Jika sudah login, redirect ke admin dashboard
        if (session()->get('is_admin_logged_in')) {
            return redirect()->to(site_url('admin/dashboard'));
        }

        if ($this->request->is('post')) {
            $rules = [
                'username' => 'required',
                'password' => 'required',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Silakan isi username dan password.');
            }

            $username = trim($this->request->getPost('username'));
            $password = $this->request->getPost('password');

            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                session()->set([
                    'admin_id'           => $user['id'],
                    'admin_username'     => $user['username'],
                    'admin_name'         => $user['nama_lengkap'],
                    'admin_role'         => $user['role'],
                    'is_admin_logged_in' => true,
                ]);

                return redirect()->to(site_url('admin/dashboard'))->with('success', 'Selamat datang kembali, ' . esc($user['nama_lengkap']) . '!');
            }

            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        return view('auth/login');
    }

    /**
     * Logout Admin
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Anda telah berhasil logout.');
    }
}
