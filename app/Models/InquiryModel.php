<?php

namespace App\Models;

use CodeIgniter\Model;

class InquiryModel extends Model
{
    protected $table = 'tbl_contents';
    protected $primaryKey = 'idx';
    protected $returnType = 'array';
    protected $allowedFields = [
        'company', 'location', 'content', 'manager', 'tel', 'email',
        'd1_1', 'd1_2', 'd2_1', 'd2_2', 'd3_1', 'd3_2', 'd4_1', 'd4_2', 
        'd5_1', 'd5_2', 'd6_1', 'd6_2', 'd7_1', 'd7_2', 'd8_1', 'd8_2', 
        'd9_1', 'd9_2', 'd10_1', 'd10_2', 'd11_1', 'd11_2', 'd12', 
        'ip_address', 'regdate'
    ];
    protected $useTimestamps = false; // Using regdate manually or via now()

    public function saveInquiry($data)
    {
        $data['ip_address'] = service('request')->getIPAddress();
        $data['regdate'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }
}
