<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;
use App\Models\BbsModel;

class LineCard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tbl_bbs_list l')
                      ->where('l.code', 'line_card');

        $search_mode = $this->request->getGet('search_mode');
        $search_word = $this->request->getGet('search_word');

        if (!empty($search_word)) {
            if (!empty($search_mode)) {
                $builder->like('l.' . $search_mode, $search_word);
            } else {
                $builder->groupStart()
                        ->like('l.subject', $search_word)
                        ->orLike('l.contents', $search_word)
                        ->groupEnd();
            }
        }

        $pg = $this->request->getGet('pg') ?: 1;
        $limit = 20;
        $offset = ($pg - 1) * $limit;

        $totalCountBuilder = clone $builder;
        $totalCount = $totalCountBuilder->countAllResults(false);
        $totalPages = ceil($totalCount / $limit);

        $list = $builder->orderBy('l.onum', 'DESC')
                        ->orderBy('l.bbs_idx', 'DESC')
                        ->get($limit, $offset)
                        ->getResultArray();

        $data = [
            'title' => 'Line Card 관리',
            'list' => $list,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'pg' => $pg,
            'search_mode' => $search_mode,
            'search_word' => $search_word
        ];

        return view('adm_master/line_card/index', $data);
    }

    public function form($id = null)
    {
        $data = [
            'title' => 'Line Card ' . ($id ? '수정' : '등록'),
            'code'  => 'line_card'
        ];

        if ($id) {
            $bbsModel = new BbsModel();
            $data['item'] = $bbsModel->find($id);
        } else {
            $data['item'] = [
                'notice_yn' => 'N',
                'secure_yn' => 'N'
            ];
        }

        return view('adm_master/line_card/form', $data);
    }

    public function save()
    {
        return redirect()->to(base_url('AdmMaster/bbs/line_card/save'), 'post');
    }
}
