<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class Goods extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        
        $search_category = $this->request->getGet('search_category');
        $search_name = $this->request->getGet('search_name');
        $pg = $this->request->getGet('pg') ?: 1;
        $limit = 20;
        $offset = ($pg - 1) * $limit;

        $db = \Config\Database::connect();
        $builder = $db->table('tbl_goods');

        if ($search_name) {
            $builder->like($search_category ?: 'goods_name_ko', $search_name);
        }

        $totalCount = $builder->countAllResults(false);
        $list = $builder->select('tbl_goods.*')
                        ->select('(select code_name from tbl_code where tbl_code.code_no=tbl_goods.product_code_1) as product_code_name_1')
                        ->select('(select code_name from tbl_code where tbl_code.code_no=tbl_goods.product_code_2) as product_code_name_2')
                        ->orderBy('idx', 'DESC')
                        ->get($limit, $offset)
                        ->getResultArray();

        $totalPages = ceil($totalCount / $limit);

        return view('adm_master/goods/list', [
            'title' => '상품정보 리스트',
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
        $db = \Config\Database::connect();
        $item = null;
        if ($id) {
            $item = $db->table('tbl_goods')->where('idx', $id)->get()->getRowArray();
        }

        // Get parent categories (depth 1)
        $categories1 = $db->table('tbl_code')
                          ->where('depth', 1)
                          ->where('status', 'Y')
                          ->orderBy('onum', 'DESC')
                          ->get()
                          ->getResultArray();

        // Get sub categories if parent is selected
        $categories2 = [];
        if ($item && !empty($item['product_code_1'])) {
            $categories2 = $db->table('tbl_code')
                              ->where('parent_code_no', $item['product_code_1'])
                              ->where('status', 'Y')
                              ->orderBy('onum', 'DESC')
                              ->get()
                              ->getResultArray();
        }

        return view('adm_master/goods/form', [
            'title' => '상품정보 ' . ($id ? '수정' : '등록'),
            'item' => $item,
            'categories1' => $categories1,
            'categories2' => $categories2
        ]);
    }

    public function save()
    {
        $db = \Config\Database::connect();
        $id = $this->request->getPost('idx');
        
        $data = [
            'product_code_1' => $this->request->getPost('product_code_1'),
            'product_code_2' => $this->request->getPost('product_code_2'),
            'goods_name_ko'  => $this->request->getPost('goods_name_ko'),
            'goods_name_en'  => $this->request->getPost('goods_name_en'),
            'oneinfo_ko'     => $this->request->getPost('oneinfo_ko'),
            'oneinfo_en'     => $this->request->getPost('oneinfo_en'),
            'info1_ko'       => $this->request->getPost('info1_ko'),
            'info1_en'       => $this->request->getPost('info1_en'),
            'info2_ko'       => $this->request->getPost('info2_ko'),
            'info2_en'       => $this->request->getPost('info2_en'),
            'info3_ko'       => $this->request->getPost('info3_ko'),
            'info3_en'       => $this->request->getPost('info3_en'),
            'useYN'          => $this->request->getPost('useYN'),
        ];

        // Handle file uploads (Images)
        for ($i = 1; $i <= 6; $i++) {
            $file = $this->request->getFile('ufile' . $i);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/data/goods', $newName);
                $data['ufile' . $i] = $newName;
                $data['rfile' . $i] = $file->getClientName();
            }
        }

        $productModel = new \App\Models\ProductModel();
        
        if ($id) {
            $productModel->update($id, $data);
        } else {
            $data['regdate'] = date('Y-m-d H:i:s');
            $productModel->insert($data);
        }

        return redirect()->to(base_url('AdmMaster/goods'))->with('message', '정상적으로 저장되었습니다.');
    }

    public function get_code()
    {
        $parent_code_no = $this->request->getGet('parent_code_no');
        $depth = $this->request->getGet('depth');
        $db = \Config\Database::connect();
        $list = $db->table('tbl_code')
                   ->where('parent_code_no', $parent_code_no)
                   ->where('depth', $depth)
                   ->where('status', 'Y')
                   ->orderBy('onum', 'DESC')
                   ->get()
                   ->getResultArray();

        return $this->response->setJSON($list);
    }

    public function delete($id)
    {
        $productModel = new \App\Models\ProductModel();
        $productModel->delete($id);
        return redirect()->to(base_url('AdmMaster/goods'))->with('message', '정상적으로 삭제되었습니다.');
    }
}
