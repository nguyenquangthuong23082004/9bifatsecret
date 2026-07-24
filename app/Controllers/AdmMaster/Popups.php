<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;
use App\Models\PopupModel;

class Popups extends BaseController
{
    public function index()
    {
        $popupModel = new PopupModel();
        
        $s_status = $this->request->getGet('s_status');
        $search_category = $this->request->getGet('search_category');
        $search_name = $this->request->getGet('search_name');
        $pg = $this->request->getGet('pg') ?: 1;
        $limit = 20;
        $offset = ($pg - 1) * $limit;

        $query = $popupModel;

        if ($s_status) {
            $query = $query->where('status', $s_status);
        }

        if ($search_name) {
            $query = $query->like($search_category ?: 'P_SUBJECT', $search_name);
        }

        $totalCount = $query->countAllResults(false);
        $list = $query->orderBy('idx', 'DESC')
                      ->findAll($limit, $offset);

        $totalPages = ceil($totalCount / $limit);

        return view('adm_master/popups/list', [
            'title' => '팝업관리 리스트',
            'list' => $list,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'pg' => $pg,
            's_status' => $s_status,
            'search_category' => $search_category,
            'search_name' => $search_name
        ]);
    }

    public function form($id = null)
    {
        $popupModel = new PopupModel();
        $item = $id ? $popupModel->find($id) : null;

        return view('adm_master/popups/form', [
            'title' => '팝업창 ' . ($id ? '수정' : '등록'),
            'item' => $item
        ]);
    }

    public function save()
    {
        $popupModel = new PopupModel();
        $id = $this->request->getPost('idx');

        $data = [
            'P_TYPES' => $this->request->getPost('P_TYPES'),
            'P_SUBJECT' => $this->request->getPost('P_SUBJECT'),
            'P_STARTDAY' => $this->request->getPost('P_STARTDAY'),
            'P_START_HH' => $this->request->getPost('P_START_HH'),
            'P_START_MM' => $this->request->getPost('P_START_MM'),
            'P_ENDDAY' => $this->request->getPost('P_ENDDAY'),
            'P_END_HH' => $this->request->getPost('P_END_HH'),
            'P_END_MM' => $this->request->getPost('P_END_MM'),
            'status' => $this->request->getPost('status'),
            'P_CATE' => $this->request->getPost('P_CATE'),
            'P_WIN_WIDTH' => $this->request->getPost('P_WIN_WIDTH'),
            'P_WIN_HEIGHT' => $this->request->getPost('P_WIN_HEIGHT'),
            'P_WIN_LEFT' => $this->request->getPost('P_WIN_LEFT'),
            'P_WIN_TOP' => $this->request->getPost('P_WIN_TOP'),
            'P_CONTENT' => $this->request->getPost('P_CONTENT'),
        ];

        $file = $this->request->getFile('ufile');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/data/popup', $newName);
            $data['ufile'] = $newName;
        }

        if ($id) {
            $popupModel->update($id, $data);
        } else {
            $popupModel->insert($data);
        }

        return redirect()->to(base_url('AdmMaster/popups'))->with('message', '정상적으로 저장되었습니다.');
    }

    public function delete($id)
    {
        $popupModel = new PopupModel();
        $popupModel->delete($id);
        return redirect()->to(base_url('AdmMaster/popups'))->with('message', '정상적으로 삭제되었습니다.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (!empty($ids)) {
            $popupModel = new PopupModel();
            $popupModel->delete($ids);
        }
        return $this->response->setJSON(['status' => 'OK']);
    }

    public function updateStatus()
    {
        $ids = $this->request->getPost('ids');
        $status = $this->request->getPost('status');
        if (!empty($ids)) {
            $popupModel = new PopupModel();
            $popupModel->update($ids, ['status' => $status]);
        }
        return $this->response->setJSON(['status' => 'OK']);
    }
}
