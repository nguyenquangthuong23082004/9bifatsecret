<?php

namespace App\Models;

use CodeIgniter\Model;

class PopupModel extends Model
{
    protected $table = 'tbl_popup';
    protected $primaryKey = 'idx';
    protected $returnType = 'array';
    protected $allowedFields = [
        'P_TYPES', 'P_SUBJECT', 'P_STARTDAY', 'P_START_HH', 'P_START_MM', 
        'P_ENDDAY', 'P_END_HH', 'P_END_MM', 'status', 'P_CATE', 'ufile', 
        'P_WIN_WIDTH', 'P_WIN_HEIGHT', 'P_WIN_LEFT', 'P_WIN_TOP', 'P_CONTENT'
    ];
}
