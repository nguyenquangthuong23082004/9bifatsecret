<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('member')) {
            return redirect()->to(base_url('admin/dashboard'));
        }
        return view('admin/login');
    }

    public function loginProcess()
    {
        $id = $this->request->getPost('user_id');
        $pw = $this->request->getPost('user_pw');

        $adminModel = new AdminModel();
        $user = $adminModel->validateLogin($id, $pw);

        if ($user) {
            if ($user['status'] == 'N') {
                return redirect()->back()->with('error', 'Authentication pending.');
            }
            if ($user['user_level'] != 1) {
                return redirect()->back()->with('error', 'No permission.');
            }

            session()->set('member', [
                'id' => $user['user_id'],
                'idx' => $user['m_idx'],
                'name' => $user['user_name'],
                'email' => $user['user_email'],
                'level' => $user['user_level']
            ]);

            return redirect()->to(base_url('admin/dashboard'));
        }

        return redirect()->back()->with('error', 'Invalid ID or Password.');
    }

    public function logout()
    {
        session()->remove('member');
        return redirect()->to(base_url('admin/login'));
    }
}
