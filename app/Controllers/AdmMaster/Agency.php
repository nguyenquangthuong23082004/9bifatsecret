<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;
use App\Models\AgencyModel;

class Agency extends BaseController
{
    public function index()
    {
        $agencyModel = new AgencyModel();
        
        $search_category = $this->request->getGet('search_category');
        $search_name = $this->request->getGet('search_name');
        $pg = $this->request->getGet('pg') ?: 1;
        $limit = 20;
        $offset = ($pg - 1) * $limit;

        $query = $agencyModel;

        if ($search_name) {
            $query = $query->like($search_category ?: 'agency_name', $search_name);
        }

        $totalCount = $query->countAllResults(false);
        $list = $query->orderBy('onum', 'DESC')
                      ->orderBy('a_idx', 'DESC')
                      ->findAll($limit, $offset);

        $totalPages = ceil($totalCount / $limit);

        return view('adm_master/agency/list', [
            'title' => '대리점 리스트',
            'list' => $list,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'pg' => $pg,
            'search_category' => $search_category,
            'search_name' => $search_name
        ]);
    }

    public function form($id = null)
    {
        $agencyModel = new AgencyModel();
        $item = $id ? $agencyModel->find($id) : null;

        return view('adm_master/agency/form', [
            'title' => '대리점 ' . ($id ? '수정' : '등록'),
            'item' => $item
        ]);
    }

    public function save()
    {
        $agencyModel = new AgencyModel();
        $id = $this->request->getPost('a_idx');

        $data = [
            'agency_name' => $this->request->getPost('agency_name'),
            'phone' => $this->request->getPost('phone'),
            'fax' => $this->request->getPost('fax'),
            'open_time' => $this->request->getPost('open_time'),
            'onum' => $this->request->getPost('onum') ?: 0,
            'py_size' => $this->request->getPost('py_size'),
            'opt_1' => $this->request->getPost('opt_1') ?: 'N',
            'opt_2' => $this->request->getPost('opt_2') ?: 'N',
            'opt_3' => $this->request->getPost('opt_3') ?: 'N',
            'contents' => $this->request->getPost('contents'),
            'zip' => $this->request->getPost('zip'),
            'addr1' => $this->request->getPost('addr1'),
            'addr2' => $this->request->getPost('addr2'),
            'lat' => $this->request->getPost('lat'),
            'lng' => $this->request->getPost('lng'),
            'map' => $this->request->getPost('map'),
            'sido' => $this->request->getPost('sido'),
            'gugun' => $this->request->getPost('gugun'),
            'dong' => $this->request->getPost('dong'),
        ];

        if ($id) {
            $agencyModel->update($id, $data);
        } else {
            $data['regdate'] = date('Y-m-d H:i:s');
            $agencyModel->insert($data);
        }

        return redirect()->to(base_url('AdmMaster/agency'))->with('message', '정상적으로 저장되었습니다.');
    }

    public function delete($id)
    {
        $agencyModel = new AgencyModel();
        $agencyModel->delete($id);
        return redirect()->to(base_url('AdmMaster/agency'))->with('message', '정상적으로 삭제되었습니다.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (!empty($ids)) {
            $agencyModel = new AgencyModel();
            $agencyModel->delete($ids);
        }
        return $this->response->setJSON(['status' => 'OK']);
    }
}
