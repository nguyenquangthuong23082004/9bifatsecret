<?php

namespace App\Models;

use CodeIgniter\Model;

class BbsConfigModel extends Model
{
    protected $table      = 'tbl_bbs_config';
    protected $primaryKey = 'tbc_idx';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'board_name', 'board_code', 'is_category', 'is_secure', 'is_right', 
        'is_reply', 'is_comment', 'is_recomm', 'is_notice', 'skin'
    ];

    public function getConfig($code)
    {
        return $this->where('board_code', $code)->first();
    }
}
