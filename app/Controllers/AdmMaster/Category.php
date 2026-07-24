<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;

class Category extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $s_parent_code_no = $this->request->getGet('s_parent_code_no') ?: '0';

        $builder = $db->table('tbl_code')
                      ->select('tbl_code.*')
                      ->select('(select count(*) from tbl_code a where a.parent_code_no=tbl_code.code_no) as cnt')
                      ->where('parent_code_no', $s_parent_code_no);

        $list = $builder->orderBy('onum', 'DESC')
                        ->orderBy('code_idx', 'DESC')
                        ->get()
                        ->getResultArray();

        return view('adm_master/category/list', [
            'title' => '카테고리 관리',
            'list' => $list,
            's_parent_code_no' => $s_parent_code_no
        ]);
    }

    public function form($id = null)
    {
        $db = \Config\Database::connect();
        $item = null;
        $s_parent_code_no = $this->request->getGet('s_parent_code_no') ?: '0';
        $parent_code_no = $s_parent_code_no ?: '0';
        $depth = 1;
        $code_no = '';
        $code_gubun = '';

        if ($id) {
            $item = $db->table('tbl_code')->where('code_idx', $id)->get()->getRowArray();
            $code_no = $item['code_no'];
            $depth = $item['depth'];
            $code_gubun = $item['code_gubun'];
            $parent_code_no = $item['parent_code_no'];
        } else {
            // Get parent info for depth
            $parent = $db->table('tbl_code')->where('code_no', $parent_code_no)->get()->getRowArray();
            $depth = $parent ? ($parent['depth'] + 1) : 1;
            $code_gubun = $parent['code_gubun'] ?? '';

            // Generate next code_no
            $maxCode = $db->table('tbl_code')
                          ->selectMax('code_no')
                          ->where('parent_code_no', $parent_code_no)
                          ->get()
                          ->getRowArray();
            
            if ($maxCode['code_no']) {
                $code_no = $maxCode['code_no'] + 1;
            } else {
                $code_no = $parent_code_no . "01";
            }
        }

        return view('adm_master/category/form', [
            'title' => '코드 ' . ($id ? '수정' : '등록'),
            'item' => $item,
            's_parent_code_no' => $s_parent_code_no,
            'parent_code_no' => $parent_code_no,
            'code_no' => $code_no,
            'depth' => $depth,
            'code_gubun' => $code_gubun
        ]);
    }

    public function save()
    {
        $db = \Config\Database::connect();
        $id = $this->request->getPost('code_idx');
        
        $data = [
            'code_no' => $this->request->getPost('code_no'),
            'code_name' => $this->request->getPost('code_name'),
            'code_name_en' => $this->request->getPost('code_name_en'),
            'parent_code_no' => $this->request->getPost('parent_code_no'),
            'depth' => $this->request->getPost('depth'),
            'code_gubun' => $this->request->getPost('code_gubun'),
            'onum' => $this->request->getPost('onum') ?: 0,
            'status' => $this->request->getPost('status') ?: 'Y',
        ];

        if ($id) {
            $db->table('tbl_code')->where('code_idx', $id)->update($data);
        } else {
            $db->table('tbl_code')->insert($data);
        }

        return redirect()->to(base_url('AdmMaster/category?s_parent_code_no=' . $data['parent_code_no']))->with('message', '정상적으로 저장되었습니다.');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->table('tbl_code')->where('code_idx', $id)->delete();
        return redirect()->back()->with('message', '정상적으로 삭제되었습니다.');
    }

    public function updateOrder()
    {
        $db = \Config\Database::connect();
        $ids = $this->request->getPost('code_idx');
        $onums = $this->request->getPost('onum');
        
        if (!empty($ids)) {
            foreach ($ids as $key => $id) {
                $db->table('tbl_code')->where('code_idx', $id)->update(['onum' => $onums[$key]]);
            }
        }
        return $this->response->setJSON(['status' => 'OK']);
    }
}
