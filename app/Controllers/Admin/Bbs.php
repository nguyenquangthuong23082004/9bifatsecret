<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BbsModel;

class Bbs extends BaseController
{
    public function list($code)
    {
        $bbsModel = new BbsModel();
        
        $pg = $this->request->getGet('pg') ?: 1;
        $limit = 20;
        $offset = ($pg - 1) * $limit;

        $list = $bbsModel->where('code', $code)
                         ->orderBy('notice_yn', 'DESC')
                         ->orderBy('bbs_idx', 'DESC')
                         ->findAll($limit, $offset);
                         
        $totalCount = $bbsModel->where('code', $code)->countAllResults();
        $totalPages = ceil($totalCount / $limit);

        return view('admin/bbs/list', [
            'title' => 'Board: ' . strtoupper($code),
            'code' => $code,
            'list' => $list,
            'pg' => $pg,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount
        ]);
    }

    public function form($code, $id = null)
    {
        $bbsModel = new BbsModel();
        $item = $id ? $bbsModel->find($id) : null;

        return view('admin/bbs/form', [
            'title' => ($id ? 'Edit' : 'Create') . ' ' . strtoupper($code),
            'code' => $code,
            'item' => $item
        ]);
    }

    public function save($code)
    {
        $bbsModel = new BbsModel();
        $id = $this->request->getPost('bbs_idx');

        $data = [
            'code' => $code,
            'subject' => $this->request->getPost('subject'),
            'contents' => $this->request->getPost('contents'),
            'notice_yn' => $this->request->getPost('notice_yn') ?: 'N',
        ];

        if (!$id) {
            $data['r_date'] = date('Y-m-d H:i:s');
            $data['user_id'] = session()->get('member')['id'];
            $data['user_name'] = session()->get('member')['name'];
        }

        // Handle file uploads (up to 5 files as per legacy)
        for ($i = 1; $i <= 5; $i++) {
            $file = $this->request->getFile('file' . $i);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/data/bbs', $newName);
                $data['ufile' . $i] = $newName;
                $data['rfile' . $i] = $file->getClientName();
            }
        }

        if ($id) {
            $bbsModel->update($id, $data);
        } else {
            $bbsModel->insert($data);
        }

        return redirect()->to(base_url('admin/bbs/' . $code))->with('message', 'Saved successfully.');
    }

    public function delete($code, $id)
    {
        $bbsModel = new BbsModel();
        $bbsModel->delete($id);
        return redirect()->to(base_url('admin/bbs/' . $code))->with('message', 'Deleted successfully.');
    }
}
