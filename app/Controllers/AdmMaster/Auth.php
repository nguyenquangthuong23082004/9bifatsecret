<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('member')) {
            return redirect()->to(base_url('AdmMaster/inquiry/1'));
        }
        return view('adm_master/login');
    }

    public function loginProcess()
    {
        $id = $this->request->getPost('user_id');
        $pw = $this->request->getPost('user_pw');

        $adminModel = new AdminModel();
        $user = $adminModel->validateLogin($id, $pw);

        if ($user) {
            if ($user['status'] == 'N') {
                return redirect()->back()->with('error', '승인 대기 중인 계정입니다.');
            }
            if ($user['user_level'] != 1) {
                return redirect()->back()->with('error', '접근 권한이 없습니다.');
            }

            session()->set('member', [
                'id' => $user['user_id'],
                'idx' => $user['m_idx'],
                'name' => $user['user_name'],
                'email' => $user['user_email'],
                'level' => $user['user_level']
            ]);

            return redirect()->to(base_url('AdmMaster/inquiry/1'));
        }

        return redirect()->back()->with('error', '아이디 또는 비밀번호가 일치하지 않습니다.');
    }

    public function logout()
    {
        session()->remove('member');
        return redirect()->to(base_url('AdmMaster/login'));
    }
}
