<?php

namespace App\Models;

use CodeIgniter\Model;

class BbsModel extends Model
{
    protected $table = 'tbl_bbs_list';
    protected $primaryKey = 'bbs_idx';
    protected $returnType = 'array';
    protected $allowedFields = [
        'code', 'category', 'subject', 'writer', 'email', 'user_id', 'm_idx', 
        'passwd', 'notice_yn', 'secure_yn', 'recomm_yn', 'contents', 'simple', 
        'hit', 'country_code', 'url', 's_date', 'e_date', 'reply', 
        'ufile1', 'rfile1', 'ufile2', 'rfile2', 'ufile3', 'rfile3', 
        'ufile4', 'rfile4', 'ufile5', 'rfile5', 'ufile6', 'rfile6', 
        'b_ref', 'b_step', 'b_level', 'ip_address', 'r_date', 'onum'
    ];

    public function getBanners($category_idx)
    {
        return $this->db->table($this->table)
            ->select('*, (select subject from tbl_bbs_category where tbl_bbs_category.tbc_idx=tbl_bbs_list.category) as category_name')
            ->where('code', 'banner')
            ->where('category', $category_idx)
            ->orderBy('notice_yn', 'DESC')
            ->orderBy('b_ref', 'DESC')
            ->orderBy('b_step', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getCategoryIdx($lang_id)
    {
        $row = $this->db->table('tbl_bbs_category')
            ->select('tbc_idx')
            ->where('code', 'banner')
            ->where('onum', $lang_id)
            ->get()
            ->getRowArray();
        return $row['tbc_idx'] ?? null;
    }

    public function getBoardList($code, $category = null, $page = 1, $perPage = 16, $searchMode = null, $searchWord = null)
    {
        $builder = $this->db->table($this->table)
            ->select('*, (select subject from tbl_bbs_category where tbl_bbs_category.tbc_idx=tbl_bbs_list.category) as category_name')
            ->where('code', $code);

        if ($category) {
            $builder->where('category', $category);
        }

        if ($searchWord) {
            if ($searchMode == 'subcon') {
                $builder->groupStart()
                    ->like('subject', $searchWord)
                    ->orLike('contents', $searchWord)
                    ->groupEnd();
            } else if ($searchMode) {
                $builder->like($searchMode, $searchWord);
            } else {
                $builder->like('subject', $searchWord);
            }
        }

        $totalCount = $builder->countAllResults(false);

        $list = $builder->orderBy('notice_yn', 'DESC')
            ->orderBy('b_ref', 'DESC')
            ->orderBy('b_step', 'ASC')
            ->orderBy('r_date', 'DESC')
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()
            ->getResultArray();

        return [
            'list' => $list,
            'totalCount' => $totalCount,
            'totalPages' => ceil($totalCount / $perPage)
        ];
    }

    public function getCategories()
    {
        return $this->db->table('tbl_code')
            ->orderBy('onum', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getBoardItem($id, $code = 'app')
    {
        $builder = $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->where('code', $code);

        if ($code == 'app') {
            $builder->select('*, (select code_name from tbl_code where tbl_code.code_idx=tbl_bbs_list.category) as category_name');
        } else {
            $builder->select('*, (select subject from tbl_bbs_category where tbl_bbs_category.tbc_idx=tbl_bbs_list.category) as category_name');
        }

        return $builder->get()->getRowArray();
    }
}
