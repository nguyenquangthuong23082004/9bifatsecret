<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Inquiry extends BaseController
{
    public function index()
    {
        $adminModel = new AdminModel();
        
        $pg = $this->request->getGet('pg') ?: 1;
        $limit = 20;
        $offset = ($pg - 1) * $limit;

        $list = $adminModel->getInquiries($limit, $offset);
        $totalCount = $adminModel->getInquiryCount();
        $totalPages = ceil($totalCount / $limit);

        return view('admin/inquiry/index', [
            'title' => 'Inquiry Management',
            'list' => $list,
            'pg' => $pg,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount
        ]);
    }

    public function view($id)
    {
        $db = \Config\Database::connect();
        $item = $db->table('tbl_inquisition')->where('idx', $id)->get()->getRowArray();

        if (!$item) {
            return redirect()->to(base_url('admin/inquiry'))->with('error', 'Inquiry not found.');
        }

        return view('admin/inquiry/view', [
            'title' => 'Inquiry Detail',
            'item' => $item
        ]);
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->table('tbl_inquisition')->where('idx', $id)->delete();
        return redirect()->to(base_url('admin/inquiry'))->with('message', 'Deleted successfully.');
    }
}
