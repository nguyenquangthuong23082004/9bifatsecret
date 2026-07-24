<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'tbl_member';
    protected $primaryKey = 'm_idx';
    protected $allowedFields = [
        'user_id', 'user_pw', 'user_name', 'user_email', 'mail_chk', 'user_level', 'gubun', 
        'birthday', 'mobile', 'zip', 'addr1', 'addr2', 'phone', 'sex', 'sms_chk', 
        'company_name', 'president', 'company_number', 'company_zip', 'company_addr1', 
        'company_addr2', 'status', 'status_datetime', 'secede_yn', 'secede_date', 
        'secede_confirm_date', 'user_ip', 'login_date', 'm_date', 'r_date'
    ];

    public function validateLogin($id, $pw)
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM tbl_member WHERE user_id = ? AND user_pw = PASSWORD(?)", [$id, $pw]);
        return $query->getRowArray();
    }

    public function getInquiries($limit, $offset)
    {
        $db = \Config\Database::connect();
        return $db->table('tbl_contents')
                  ->orderBy('idx', 'DESC')
                  ->get($limit, $offset)
                  ->getResultArray();
    }

    public function getInquiryCount()
    {
        return $this->db->table('tbl_contents')->countAllResults();
    }
}
