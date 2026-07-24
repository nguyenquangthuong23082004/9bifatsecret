<?php

namespace App\Models;

use CodeIgniter\Model;

class AgencyModel extends Model
{
    protected $table = 'tbl_agency';
    protected $primaryKey = 'a_idx';
    protected $returnType = 'array';
    protected $allowedFields = [
        'agency_name', 'phone', 'fax', 'open_time', 'onum', 'py_size', 
        'opt_1', 'opt_2', 'opt_3', 'contents', 'zip', 'addr1', 'addr2', 
        'lat', 'lng', 'map', 'sido', 'gugun', 'dong', 'regdate'
    ];
}
