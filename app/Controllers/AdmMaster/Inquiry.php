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
            $title = '1:1 무료컨설팅 예약 관리';
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

    public function excel($type = 1)
    {
        if ($type == 2) {
            $inquiryModel = new \App\Models\InquiryModel2();
            $filename = '품질검사신청서_'.date('Ymd');
        } elseif ($type == 3) {
            $inquiryModel = new \App\Models\InquiryModel3();
            $filename = '고객의소리_'.date('Ymd');
        } elseif ($type == 4) {
            $inquiryModel = new \App\Models\InquiryModel4();
            $filename = '고객문의_'.date('Ymd');
        } else {
            $inquiryModel = new \App\Models\InquiryModel();
            $filename = '무료컨설팅예약_'.date('Ymd');
        }

        $builder = $inquiryModel->builder();
        $search_category = $this->request->getGet('search_category');
        $search_name = $this->request->getGet('search_name');

        if (!empty($search_name) && !empty($search_category)) {
            $builder->like($search_category, $search_name);
        }

        $list = $builder->orderBy('idx', 'DESC')->get()->getResultArray();

        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Content-Disposition: attachment; filename={$filename}.xls");
        header("Cache-Control: max-age=0");

        echo "\xEF\xBB\xBF";
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<style>th, td { text-align: center; vertical-align: middle; }</style>';
        echo '</head>';
        echo '<body>';
        echo '<table border="1" style="text-align: center;">';
        
        if ($type == 3) {
            echo '<thead><tr>';
            echo '<th>번호</th><th>분류</th><th>제목</th><th>작성자</th><th>연락처</th><th>방문일자</th><th>방문매장</th><th>등록일</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            $num = count($list);
            $gubuns = ['01'=>'칭찬합니다', '02'=>'불만있습니다', '03'=>'창업희망', '04'=>'기타'];
            foreach ($list as $row) {
                $gText = $gubuns[$row['gubun'] ?? '04'] ?? '기타';
                echo '<tr>';
                echo '<td>'.$num--.'</td>';
                echo '<td>'.htmlspecialchars($gText).'</td>';
                echo '<td>'.htmlspecialchars($row['subject'] ?? '').'</td>';
                echo '<td>'.htmlspecialchars($row['user_name'] ?? '').'</td>';
                echo '<td>'.htmlspecialchars($row['user_phone'] ?? '').'</td>';
                echo '<td>'.htmlspecialchars($row['visit_date'] ?? '').'</td>';
                echo '<td>'.htmlspecialchars($row['visit_store'] ?? '').'</td>';
                echo '<td>'.htmlspecialchars($row['r_date'] ?? $row['regdate'] ?? '').'</td>';
                echo '</tr>';
            }
            echo '</tbody>';
        } elseif ($type == 4) {
            echo '<thead><tr>';
            echo '<th>번호</th><th>작성자</th><th>연락처</th><th>신청일</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            $num = count($list);
            foreach ($list as $row) {
                echo '<tr>';
                echo '<td>'.$num--.'</td>';
                echo '<td>'.htmlspecialchars($row['user_name'] ?? '').'</td>';
                echo '<td>'.htmlspecialchars($row['phone'] ?? '').'</td>';
                echo '<td>'.htmlspecialchars($row['r_date'] ?? $row['regdate'] ?? '').'</td>';
                echo '</tr>';
            }
            echo '</tbody>';
        } else {
            echo '<thead><tr>';
            echo '<th>번호</th><th>이름</th><th>휴대전화</th><th>나이</th><th>거주지역</th><th>신청일</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            $num = count($list);
            foreach ($list as $row) {
                $regdate = !empty($row['regdate']) ? date('Y-m-d H:i', strtotime($row['regdate'])) : '';
                echo '<tr>';
                echo '<td>'.$num--.'</td>';
                echo '<td>'.htmlspecialchars($row['manager'] ?? '').'</td>';
                echo '<td>'.htmlspecialchars($row['tel'] ?? '').'</td>';
                echo '<td>'.htmlspecialchars($row['company'] ?? '').'</td>';
                echo '<td>'.htmlspecialchars($row['location'] ?? '').'</td>';
                echo '<td>'.htmlspecialchars($regdate).'</td>';
                echo '</tr>';
            }
            echo '</tbody>';
        }
        
        echo '</table></body></html>';
        exit;
    }
}
