<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;
use App\Models\BbsModel;

class Policy extends BaseController
{
    public function form()
    {
        $bbsModel = new BbsModel();

        // Fetch or create Terms of Use
        $terms = $bbsModel->where('code', 'policy')->where('subject', '이용약관')->first();
        if (!$terms) {
            $bbsModel->insert([
                'code' => 'policy',
                'subject' => '이용약관',
                'writer' => '관리자',
                'contents' => '<p><strong>제1조 (목적)</strong><br>이 약관은 오토스타일(이하 "회사"라 함)이 제공하는 서비스의 이용조건 및 절차, 회사와 회원 간의 권리, 의무 및 책임사항 등을 규정함을 목적으로 합니다.</p>',
                'r_date' => date('Y-m-d H:i:s'),
                'b_ref' => 1,
                'b_step' => 0,
                'b_level' => 0,
                'hit' => 0,
                'onum' => 2
            ]);
            $terms = $bbsModel->where('code', 'policy')->where('subject', '이용약관')->first();
        }

        // Fetch or create Privacy Policy
        $privacy = $bbsModel->where('code', 'policy')->where('subject', '개인정보처리방침')->first();
        if (!$privacy) {
            $bbsModel->insert([
                'code' => 'policy',
                'subject' => '개인정보처리방침',
                'writer' => '관리자',
                'contents' => '<p><strong>1. 개인정보의 처리 목적</strong><br>회사는 회원가입, 세일즈 파트너 신청 및 상담 지원 등을 위해 최소한의 개인정보를 처리하고 있습니다.</p>',
                'r_date' => date('Y-m-d H:i:s'),
                'b_ref' => 2,
                'b_step' => 0,
                'b_level' => 0,
                'hit' => 0,
                'onum' => 1
            ]);
            $privacy = $bbsModel->where('code', 'policy')->where('subject', '개인정보처리방침')->first();
        }

        return view('adm_master/policy/form', [
            'title' => '약관/방침 관리',
            'terms' => $terms,
            'privacy' => $privacy
        ]);
    }

    public function save()
    {
        $bbsModel = new BbsModel();

        $terms_idx = $this->request->getPost('terms_idx');
        $terms_content = $this->request->getPost('terms_content') ?: '';

        $privacy_idx = $this->request->getPost('privacy_idx');
        $privacy_content = $this->request->getPost('privacy_content') ?: '';

        if ($terms_idx) {
            $bbsModel->update($terms_idx, [
                'contents' => $terms_content,
                'r_date' => date('Y-m-d H:i:s')
            ]);
        }

        if ($privacy_idx) {
            $bbsModel->update($privacy_idx, [
                'contents' => $privacy_content,
                'r_date' => date('Y-m-d H:i:s')
            ]);
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'OK',
                'message' => '성공적으로 저장되었습니다.'
            ]);
        }

        return redirect()->to(base_url('AdmMaster/bbs/policy'))->with('success', '성공적으로 저장되었습니다.');
    }
}
