<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\Setting;

class Profile extends BaseController
{
    public function index()
    {
        $adminModel = new AdminModel();
        $settingModel = new Setting();
        
        $idx = session()->get('member')['idx'];
        $user = $adminModel->find($idx);
        $setting = $settingModel->getSettings();

        return view('adm_master/profile/index', [
            'title' => '관리자 설정',
            'admin' => $user,
            'setting' => $setting
        ]);
    }

    public function update()
    {
        $adminModel = new AdminModel();
        $settingModel = new Setting();
        
        $idx = session()->get('member')['idx'];
        $user = $adminModel->find($idx);

        $name = $this->request->getPost('user_name');
        $pw_org = $this->request->getPost('user_pw_org');
        $pw = $this->request->getPost('user_pw');
        $pw2 = $this->request->getPost('user_pw2');

        $adminData = [];
        if (!empty($name)) {
            $adminData['user_name'] = $name;
        }

        if (!empty($pw)) {
            if ($pw !== $pw2) {
                return $this->response->setJSON(['status' => 'PW_MISMATCH', 'message' => '새 비밀번호가 일치하지 않습니다.']);
            }

            $db = \Config\Database::connect();
            $db->query("UPDATE tbl_member SET user_pw = PASSWORD(?), user_name = ? WHERE m_idx = ?", [$pw, $name ?? $user['user_name'], $idx]);
        } else {
            if (!empty($adminData)) {
                $adminModel->update($idx, $adminData);
            }
        }

        $fields = [
            'site_name', 'home_name', 'qna_email', 'zip', 'addr1', 'addr2',
            'com_owner', 'info_owner', 'custom_phone', 'fax', 'comnum', 'mallOrder',
            'copyright', 'browser_title', 'meta_tag', 'meta_keyword', 'og_title',
            'og_des', 'og_url', 'og_site', 'schema_jsonld', 'allim_apikey',
            'allim_userid', 'allim_senderkey', 'sms_phone', 'email', 'ssl_chk',
            'smtp_host', 'smtp_id', 'smtp_pass', 'admin_email_list'
        ];

        $settingData = [];
        foreach ($fields as $field) {
            $val = $this->request->getPost($field);
            if ($val !== null) {
                $settingData[$field] = $val;
            }
        }

        // Handle og_img file upload
        $file = $this->request->getFile('og_img');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/setting', $newName);
            $settingData['og_img'] = $newName;
        }

        // Handle logos (header logo) file upload
        $logosFile = $this->request->getFile('logos');
        if ($logosFile && $logosFile->isValid() && !$logosFile->hasMoved()) {
            $newName = $logosFile->getRandomName();
            $logosFile->move(ROOTPATH . 'public/uploads/setting', $newName);
            $settingData['logos'] = $newName;
        }

        // Handle logos_footer file upload
        $logosFooterFile = $this->request->getFile('logos_footer');
        if ($logosFooterFile && $logosFooterFile->isValid() && !$logosFooterFile->hasMoved()) {
            $newName = $logosFooterFile->getRandomName();
            $logosFooterFile->move(ROOTPATH . 'public/uploads/setting', $newName);
            $settingData['logos_footer'] = $newName;
        }

        // Handle favico file upload
        $favicoFile = $this->request->getFile('favico');
        if ($favicoFile && $favicoFile->isValid() && !$favicoFile->hasMoved()) {
            $newName = $favicoFile->getRandomName();
            $favicoFile->move(ROOTPATH . 'public/uploads/setting', $newName);
            $settingData['favico'] = $newName;
        }

        $existingSetting = $settingModel->getSettings();
        if ($existingSetting) {
            $settingModel->update($existingSetting['idx'], $settingData);
        } else {
            $settingModel->insert($settingData);
        }

        return $this->response->setJSON(['status' => 'OK', 'message' => '정상적으로 수정되었습니다.']);
    }
}
