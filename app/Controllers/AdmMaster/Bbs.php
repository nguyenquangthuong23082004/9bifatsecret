<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;
use App\Models\BbsModel;
use App\Models\BbsConfigModel;

class Bbs extends BaseController
{
    private function getBoardConfig($code)
    {
        $configModel = new BbsConfigModel();
        
        // Auto-seed policy board config and list items if not exist in admin
        if ($code === 'policy' && !$configModel->getConfig('policy')) {
            $configModel->insert([
                'board_name' => '약관/방침',
                'board_code' => 'policy',
                'is_category' => 'N',
                'is_secure' => 'N',
                'is_right' => 'N',
                'is_reply' => 'N',
                'is_comment' => 'N',
                'is_recomm' => 'N',
                'is_notice' => 'N',
                'skin' => 'faq'
            ]);
            
            $bbsModel = new BbsModel();
            $policyCount = $bbsModel->where('code', 'policy')->countAllResults();
            if ($policyCount == 0) {
                $bbsModel->insert([
                    'code' => 'policy',
                    'subject' => '이용약관',
                    'writer' => '관리자',
                    'contents' => '<p><strong>제1조 (목적)</strong><br>이 약관은 오토스타일(이하 "회사"라 함)이 제공하는 서비스의 이용조건 및 절차, 회사와 회원 간의 권리, 의무 및 책임사항 등을 규정함을 목적으로 합니다.</p>',
                    'r_date' => date('Y-m-d H:i:s'),
                    'b_ref' => 1,
                    'b_step' => 0,
                    'b_level' => 0,
                    'hit' => 0,
                    'onum' => 2
                ]);
                $bbsModel->insert([
                    'code' => 'policy',
                    'subject' => '개인정보처리방침',
                    'writer' => '관리자',
                    'contents' => '<p><strong>1. 개인정보의 처리 목적</strong><br>회사는 회원가입, 세일즈 파트너 신청 및 상담 지원 등을 위해 최소한의 개인정보를 처리하고 있습니다.</p>',
                    'r_date' => date('Y-m-d H:i:s'),
                    'b_ref' => 2,
                    'b_step' => 0,
                    'b_level' => 0,
                    'hit' => 0,
                    'onum' => 1
                ]);
            }
        }

        $config = $configModel->getConfig($code);
        if (!$config) {
            // Fallback for codes not in config
            return [
                'board_name' => ($code === 'policy') ? '약관/방침' : strtoupper($code),
                'skin' => ($code === 'policy') ? 'faq' : 'basic',
                'is_category' => 'N',
                'is_notice' => 'N',
                'is_secure' => 'N',
            ];
        }

        // Safety check to ensure policy board name is in Korean if it was previously named POLICY
        if ($code === 'policy' && ($config['board_name'] !== '약관/방침' || $config['skin'] !== 'faq')) {
            $configModel->update($config['tbc_idx'], [
                'board_name' => '약관/방침',
                'skin' => 'faq'
            ]);
            $config['board_name'] = '약관/방침';
            $config['skin'] = 'faq';
        }

        return $config;
    }

    public function list($code)
    {
        $bbsModel = new BbsModel();
        $config = $this->getBoardConfig($code);

        $search_mode = $this->request->getGet('search_mode');
        $search_word = $this->request->getGet('search_word');
        $scategory = $this->request->getGet('scategory');

        $db = \Config\Database::connect();
        $query = $db->table('tbl_bbs_list')->where('code', $code);

        if ($code == 'app') {
            $config['is_category'] = 'Y';
            $query->select('*, (select code_name from tbl_code where tbl_code.code_idx=tbl_bbs_list.category) as category_name, (select count(*) from tbl_bbs_comment where tbl_bbs_comment.bbs_idx=tbl_bbs_list.bbs_idx) as comment_cnt');
            $categories = $db->table('tbl_code')->orderBy('onum', 'DESC')->get()->getResultArray();
        } else {
            $query->select('*, (select subject from tbl_bbs_category where tbl_bbs_category.tbc_idx=tbl_bbs_list.category) as category_name, (select count(*) from tbl_bbs_comment where tbl_bbs_comment.bbs_idx=tbl_bbs_list.bbs_idx) as comment_cnt');
            $categories = [];
            if ($config['is_category'] == 'Y') {
                $categories = $db->table('tbl_bbs_category')
                    ->where('code', $code)
                    ->orderBy('onum', 'DESC')
                    ->get()
                    ->getResultArray();
            }
        }

        if (!empty($scategory)) {
            $query->where('category', $scategory);
        }

        if (!empty($search_word)) {
            if (!empty($search_mode)) {
                $query->like($search_mode, $search_word);
            } else {
                $query->groupStart()
                    ->like('subject', $search_word)
                    ->orLike('contents', $search_word)
                    ->groupEnd();
            }
        }

        $pg = $this->request->getGet('pg') ?: 1;
        $limit = 20;
        $offset = ($pg - 1) * $limit;

        if ($code == 'app' || $code == 'faq') {
            $list = $query->orderBy('onum', 'DESC')
                ->orderBy('bbs_idx', 'DESC')
                ->get($limit, $offset)
                ->getResultArray();
        } else {
            $list = $query->orderBy('notice_yn', 'DESC')
                ->orderBy('b_ref', 'DESC')
                ->orderBy('b_step', 'ASC')
                ->orderBy('bbs_idx', 'DESC')
                ->get($limit, $offset)
                ->getResultArray();
        }

        $totalCountBuilder = $db->table('tbl_bbs_list')->where('code', $code);
        if (!empty($scategory)) {
            $totalCountBuilder->where('category', $scategory);
        }
        if (!empty($search_word)) {
            if (!empty($search_mode)) {
                $totalCountBuilder->like($search_mode, $search_word);
            } else {
                $totalCountBuilder->groupStart()
                    ->like('subject', $search_word)
                    ->orLike('contents', $search_word)
                    ->groupEnd();
            }
        }
        $totalCount = $totalCountBuilder->countAllResults();
        $totalPages = ceil($totalCount / $limit);

        return view('adm_master/bbs/list', [
            'title' => $config['board_name'] . ' 리스트',
            'code' => $code,
            'config' => $config,
            'list' => $list,
            'pg' => $pg,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'search_mode' => $search_mode,
            'search_word' => $search_word,
            'scategory' => $scategory,
            'categories' => $categories
        ]);
    }

    public function form($code, $id = null)
    {
        $bbsModel = new BbsModel();
        $config = $this->getBoardConfig($code);
        $item = $id ? $bbsModel->find($id) : null;

        $db = \Config\Database::connect();
        if ($code == 'app' || $config['is_category'] == 'Y') {
            if ($code == 'app') {
                $categories = $db->table('tbl_code')->orderBy('onum', 'DESC')->get()->getResultArray();
            } else {
                $categories = $db->table('tbl_bbs_category')
                    ->where('code', $code)
                    ->orderBy('onum', 'DESC')
                    ->get()
                    ->getResultArray();
            }
        } else {
            $categories = [];
        }

        return view('adm_master/bbs/form', [
            'title' => $config['board_name'] . ($id ? ' 수정' : ' 등록'),
            'code' => $code,
            'config' => $config,
            'item' => $item,
            'categories' => $categories
        ]);
    }

    public function save($code)
    {
        $bbsModel = new BbsModel();
        $id = $this->request->getPost('bbs_idx');

        $data = [
            'code' => $code,
            'writer' => $this->request->getPost('writer') ?: '관리자',
            'email' => $this->request->getPost('email') ?: '',
            'subject' => $this->request->getPost('subject') ?: '',
            'contents' => $this->request->getPost('contents') ?: '',
            'notice_yn' => $this->request->getPost('notice_yn') ?: 'N',
            'secure_yn' => $this->request->getPost('secure_yn') ?: 'N',
            'hit' => $this->request->getPost('hit') ?: 0,
            'r_date' => $this->request->getPost('r_date') ?: date('Y-m-d H:i:s'),
            'url' => $this->request->getPost('url') ?: '',
            'category' => $this->request->getPost('category') ?: '',
            's_date' => $this->request->getPost('s_date') ?: '',
            'e_date' => $this->request->getPost('e_date') ?: '',
            'simple' => $this->request->getPost('simple') ?: '',
            'onum' => $this->request->getPost('onum') ?: 0,
        ];

        if ($id) {
            $item = $bbsModel->find($id);
        }

        // Handle file deletions
        for ($i = 1; $i <= 6; $i++) {
            if ($this->request->getPost('del_' . $i) == 'Y' && $id) {
                if (!empty($item['ufile' . $i])) {
                    @unlink(ROOTPATH . 'public/data/bbs/' . $item['ufile' . $i]);
                }
                $data['ufile' . $i] = '';
                $data['rfile' . $i] = '';
            }
        }

        // Handle file uploads
        for ($i = 1; $i <= 6; $i++) {
            $file = $this->request->getFile('file' . $i);
            // Specifically for banner/legacy where ufile5 and ufile6 are used directly
            if (!$file || !$file->isValid()) {
                $file = $this->request->getFile('ufile' . $i);
            }

            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Delete old file if exists
                if ($id && !empty($item['ufile' . $i])) {
                    @unlink(ROOTPATH . 'public/data/bbs/' . $item['ufile' . $i]);
                }
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/data/bbs', $newName);
                $data['ufile' . $i] = $newName;
                $data['rfile' . $i] = $file->getClientName();
            }
        }

        $data['ip_address'] = $this->request->getIPAddress();
        $data['b_step'] = 0;
        $data['b_level'] = 0;

        try {
            if ($id) {
                $item = $bbsModel->find($id);
                if ($bbsModel->update($id, $data)) {
                    return $this->response->setBody("OK");
                } else {
                    return $this->response->setBody(implode("<br>", $bbsModel->errors()));
                }
            } else {
                $data['user_id'] = session()->get('member')['id'];
                $newId = $bbsModel->insert($data);
                if ($newId) {
                    // Set b_ref as the new idx for root posts
                    $bbsModel->update($newId, ['b_ref' => $newId]);
                    return $this->response->setBody("OK");
                } else {
                    return $this->response->setBody(implode("<br>", $bbsModel->errors()));
                }
            }
        } catch (\Exception $e) {
            return $this->response->setBody($e->getMessage());
        }
    }

    public function bulkDelete($code)
    {
        $ids = $this->request->getPost('ids');
        if (!empty($ids)) {
            $bbsModel = new BbsModel();
            foreach ($ids as $id) {
                $item = $bbsModel->find($id);
                for ($i = 1; $i <= 6; $i++) {
                    if (!empty($item['ufile' . $i])) {
                        @unlink(ROOTPATH . 'public/data/bbs/' . $item['ufile' . $i]);
                    }
                }
                $bbsModel->delete($id);
            }
        }
        return $this->response->setJSON(['status' => 'OK']);
    }

    public function delete($code, $id)
    {
        $bbsModel = new BbsModel();
        $item = $bbsModel->find($id);

        // Delete physical files
        for ($i = 1; $i <= 6; $i++) {
            if (!empty($item['ufile' . $i])) {
                @unlink(ROOTPATH . 'public/data/bbs/' . $item['ufile' . $i]);
            }
        }

        $bbsModel->delete($id);
        return $this->response->setBody("OK");
    }

    public function updateOrder($code)
    {
        $db = \Config\Database::connect();
        $ids = $this->request->getPost('bbs_idx');
        $onums = $this->request->getPost('onum');

        if (!empty($ids)) {
            foreach ($ids as $key => $id) {
                if (isset($onums[$key])) {
                    $db->table('tbl_bbs_list')->where('bbs_idx', $id)->update(['onum' => $onums[$key]]);
                }
            }
        }
        return $this->response->setJSON(['status' => 'OK']);
    }
}
