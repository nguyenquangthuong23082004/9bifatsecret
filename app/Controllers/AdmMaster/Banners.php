<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;
use App\Models\BbsModel;

class Banners extends BaseController
{
    public function index()
    {
        $bbsModel = new BbsModel();
        
        $search_mode = $this->request->getGet('search_mode');
        $search_word = $this->request->getGet('search_word');

        $bbsModel = new \App\Models\BbsModel();
        $db = \Config\Database::connect();
        $builder = $db->table('tbl_bbs_list l')
                      ->select('l.*, c.subject as category_name')
                      ->join('tbl_bbs_category c', 'l.category = c.tbc_idx', 'left')
                      ->where('l.code', 'banner');

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

        $list = $builder->orderBy('l.bbs_idx', 'DESC')
                        ->get($limit, $offset)
                        ->getResultArray();

        $data = [
            'title' => '배너관리 리스트',
            'list' => $list,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'pg' => $pg,
            'search_mode' => $search_mode,
            'search_word' => $search_word
        ];

        return view('adm_master/banners/index', $data);
    }

    public function edit($id)
    {
        // Redirect to BBS form for banner to maintain consistency
        return redirect()->to(base_url('AdmMaster/bbs/banner/edit/' . $id));
    }

    public function save()
    {
        // Redirect to BBS save for banner
        return redirect()->to(base_url('AdmMaster/bbs/banner/save'), 'post');
    }
}
