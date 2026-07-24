<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BbsModel;

class Banners extends BaseController
{
    public function index()
    {
        $bbsModel = new BbsModel();
        
        // We manage banners for both KO (0) and EN (1)
        $banners_ko = $bbsModel->where('code', 'banner')->where('category', 1)->findAll(); // Assuming 1 is KO banner category
        $banners_en = $bbsModel->where('code', 'banner')->where('category', 2)->findAll(); // Assuming 2 is EN banner category

        // Actually, let's get them dynamically
        $db = \Config\Database::connect();
        $categories = $db->table('tbl_bbs_category')->where('code', 'banner')->get()->getResultArray();

        $data = [
            'title' => 'Main Banner Management',
            'categories' => $categories,
            'bbsModel' => $bbsModel
        ];

        return view('admin/banners/index', $data);
    }

    public function edit($id)
    {
        $bbsModel = new BbsModel();
        $banner = $bbsModel->find($id);

        return view('admin/banners/form', [
            'title' => 'Edit Banner',
            'banner' => $banner
        ]);
    }

    public function save()
    {
        $bbsModel = new BbsModel();
        $id = $this->request->getPost('bbs_idx');
        
        $data = [
            'subject' => $this->request->getPost('subject'),
            'url' => $this->request->getPost('url'),
            'notice_yn' => $this->request->getPost('notice_yn') ?: 'N'
        ];

        // Handle file uploads (simplified)
        $file5 = $this->request->getFile('ufile5'); // Mobile
        $file6 = $this->request->getFile('ufile6'); // PC

        if ($file5 && $file5->isValid() && !$file5->hasMoved()) {
            $newName = $file5->getRandomName();
            $file5->move(ROOTPATH . 'public/data/bbs', $newName);
            $data['ufile5'] = $newName;
        }

        if ($file6 && $file6->isValid() && !$file6->hasMoved()) {
            $newName = $file6->getRandomName();
            $file6->move(ROOTPATH . 'public/data/bbs', $newName);
            $data['ufile6'] = $newName;
        }

        if ($id) {
            $bbsModel->update($id, $data);
        } else {
            // New banner logic if needed
        }

        return redirect()->to(base_url('admin/banners'))->with('message', 'Saved successfully.');
    }
}
