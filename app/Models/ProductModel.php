<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'tbl_goods';
    protected $primaryKey = 'idx';
    protected $returnType = 'array';
    protected $allowedFields = [
        'product_code_1', 'product_code_2', 'goods_name_ko', 'goods_name_en', 
        'goods_name_jp', 'goods_name_ch', 'oneinfo_ko', 'oneinfo_en', 
        'oneinfo_jp', 'oneinfo_ch', 'info1_ko', 'info1_en', 'info1_jp', 
        'info1_ch', 'info2_ko', 'info2_en', 'info2_jp', 'info2_ch', 
        'info3_ko', 'info3_en', 'info3_jp', 'info3_ch', 'ufile1', 'rfile1', 
        'ufile2', 'rfile2', 'ufile3', 'rfile3', 'ufile4', 'rfile4', 
        'ufile5', 'rfile5', 'ufile6', 'rfile6', 'regdate', 'useYN'
    ];

    public function getCategory($code_no)
    {
        return $this->db->table('tbl_code')
            ->where('depth', 1)
            ->where('status', 'Y')
            ->where('code_no', $code_no)
            ->get()
            ->getRowArray();
    }

    public function getFirstCategory()
    {
        return $this->db->table('tbl_code')
            ->where('depth', 1)
            ->where('status', 'Y')
            ->orderBy('onum', 'DESC')
            ->orderBy('code_idx', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }

    public function getSubCategories($parent_code_no)
    {
        return $this->db->table('tbl_code')
            ->where('depth', 2)
            ->where('status', 'Y')
            ->where('parent_code_no', $parent_code_no)
            ->orderBy('onum', 'DESC')
            ->orderBy('code_idx', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getGoods($cate1, $cate2 = null)
    {
        $builder = $this->db->table('tbl_goods')
            ->select('tbl_goods.*')
            ->select('(select code_name from tbl_code where tbl_code.code_no=tbl_goods.product_code_1) as product_code_name_1')
            ->select('(select code_name from tbl_code where tbl_code.code_no=tbl_goods.product_code_2) as product_code_name_2_ko')
            ->select('(select code_name_en from tbl_code where tbl_code.code_no=tbl_goods.product_code_2) as product_code_name_2_en')
            ->where('useYN', 'Y')
            ->where('product_code_1', $cate1);

        if ($cate2) {
            $builder->where('product_code_2', $cate2);
        }

        return $builder->orderBy('idx', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getProduct($idx)
    {
        return $this->db->table('tbl_goods')
            ->where('idx', $idx)
            ->get()
            ->getRowArray();
    }
}
