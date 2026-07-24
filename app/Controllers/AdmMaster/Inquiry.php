<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Inquiry extends BaseController
{
    public function index($type = 1)
    {
        if ($type == 2) {
            $inquiryModel = new \App\Models\InquiryModel2();
            $title = '품질검사 신청서 관리';
        } elseif ($type == 3) {
            $inquiryModel = new \App\Models\InquiryModel3();
            $title = '고객의소리 관리';
        } elseif ($type == 4) {
            $inquiryModel = new \App\Models\InquiryModel4();
            $title = '고객문의 관리';
        } else {
            $inquiryModel = new \App\Models\InquiryModel();
            $title = '온라인문의 관리';
        }

        $builder = $inquiryModel->builder();
        $search_category = $this->request->getGet('search_category');
        $search_name = $this->request->getGet('search_name');

        if (!empty($search_name) && !empty($search_category)) {
            $builder->like($search_category, $search_name);
        }

        $pg = $this->request->getGet('pg') ?: 1;
        $limit = 20;
        $offset = ($pg - 1) * $limit;

        $totalCountBuilder = clone $builder;
        $totalCount = $totalCountBuilder->countAllResults(false);
        $totalPages = ceil($totalCount / $limit);

        $list = $builder->orderBy('idx', 'DESC')
                        ->get($limit, $offset)
                        ->getResultArray();

        return view('adm_master/inquiry/index', [
            'title' => $title,
            'list' => $list,
            'pg' => $pg,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount,
            'search_category' => $search_category,
            'search_name' => $search_name,
            'type' => $type
        ]);
    }

    public function view($type, $id)
    {
        if ($type == 2) {
            $inquiryModel = new \App\Models\InquiryModel2();
            $typeName = '품질검사';
        } elseif ($type == 3) {
            $inquiryModel = new \App\Models\InquiryModel3();
            $typeName = '고객의소리';
        } elseif ($type == 4) {
            $inquiryModel = new \App\Models\InquiryModel4();
            $typeName = '고객문의';
        } else {
            $inquiryModel = new \App\Models\InquiryModel();
            $typeName = '문의';
        }

        $item = $inquiryModel->find($id);

        if (!$item) {
            return redirect()->to(base_url('AdmMaster/inquiry/'.$type))->with('error', '문의 내역을 찾을 수 없습니다.');
        }

        return view('adm_master/inquiry/view', [
            'title' => $typeName . ' 상세내용',
            'item' => $item,
            'type' => $type
        ]);
    }

    public function bulkDelete($type)
    {
        $ids = $this->request->getPost('ids');
        if (!empty($ids)) {
            if ($type == 2) {
                $inquiryModel = new \App\Models\InquiryModel2();
            } elseif ($type == 3) {
                $inquiryModel = new \App\Models\InquiryModel3();
            } elseif ($type == 4) {
                $inquiryModel = new \App\Models\InquiryModel4();
            } else {
                $inquiryModel = new \App\Models\InquiryModel();
            }
            $inquiryModel->whereIn('idx', $ids)->delete();
        }
        return $this->response->setJSON(['status' => 'OK']);
    }

    public function delete($type, $id)
    {
        if ($type == 2) {
            $inquiryModel = new \App\Models\InquiryModel2();
        } elseif ($type == 3) {
            $inquiryModel = new \App\Models\InquiryModel3();
        } elseif ($type == 4) {
            $inquiryModel = new \App\Models\InquiryModel4();
        } else {
            $inquiryModel = new \App\Models\InquiryModel();
        }
        $inquiryModel->delete($id);
        return redirect()->to(base_url('AdmMaster/inquiry/'.$type))->with('message', '정상적으로 삭제되었습니다.');
    }
}
