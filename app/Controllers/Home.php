<?php

namespace App\Controllers;

class Home extends BaseController
{
    private function checkAndSeed($bbsModel, $configModel)
    {
        // 1. FAQ Config
        if (!$configModel->getConfig('faq')) {
            $configModel->insert([
                'board_name' => '자주 묻는 질문(FAQ)',
                'board_code' => 'faq',
                'is_category' => 'N',
                'is_secure' => 'N',
                'is_right' => 'N',
                'is_reply' => 'N',
                'is_comment' => 'N',
                'is_recomm' => 'N',
                'is_notice' => 'N',
                'skin' => 'faq'
            ]);
        }

        // 2. FAQ List Seeding
        $hasTypo = $bbsModel->where('code', 'faq')
            ->groupStart()
                ->like('contents', '파at너')
                ->orLike('contents', 'of 공제')
            ->groupEnd()
            ->countAllResults();
        $hasZeroOnum = $bbsModel->where('code', 'faq')->where('onum', 0)->countAllResults();
        if ($hasTypo > 0 || $hasZeroOnum > 0) {
            $bbsModel->where('code', 'faq')->delete();
        }

        $faqCount = $bbsModel->where('code', 'faq')->countAllResults();
        if ($faqCount == 0) {
            $defaultFaqs = [
                [
                    'code' => 'faq',
                    'subject' => '차를 잘 모르는 초보도 할수 있나요?',
                    'writer' => '관리자',
                    'contents' => '네, 오토스타일에서는 장기렌터카 영업 교육을 기본 제공하며, 복잡한 견적과 심사 등의 실무 업무를 전문가가 1:1로 밀착 지원해드립니다. 차를 잘 모르시더라도 고객 상담 및 연계 프로세스만으로도 충분히 세일즈 파트너로 활동하실 수 있습니다.',
                    'r_date' => date('Y-m-d H:i:s'),
                    'b_ref' => 1,
                    'b_step' => 0,
                    'b_level' => 0,
                    'hit' => 0,
                    'onum' => 4
                ],
                [
                    'code' => 'faq',
                    'subject' => '수익 구조는 어떻게 되나요?',
                    'writer' => '관리자',
                    'contents' => '오토스타일은 업계 최고의 판매 수수료율을 보장합니다. 본인의 장기렌터카 계약 건수와 거래 유형에 따라 투명하게 산정된 수수료가 정산되며, 영업 지원 명목의 공제는 일절 없습니다.',
                    'r_date' => date('Y-m-d H:i:s'),
                    'b_ref' => 2,
                    'b_step' => 0,
                    'b_level' => 0,
                    'hit' => 0,
                    'onum' => 3
                ],
                [
                    'code' => 'faq',
                    'subject' => '법인 간 제휴를 하는 것도 가능한가요?',
                    'writer' => '관리자',
                    'contents' => '개인 단위의 제휴가 아닌, 중고차 에이전시 · 보험대리점 단위 B2B 제휴도 가능합니다. 제휴 조건과 정산 등에 대한 상담을 위해서는 제휴 상담을 요청해주시기 바랍니다.',
                    'r_date' => date('Y-m-d H:i:s'),
                    'b_ref' => 3,
                    'b_step' => 0,
                    'b_level' => 0,
                    'hit' => 0,
                    'onum' => 2
                ],
                [
                    'code' => 'faq',
                    'subject' => '자격증이 따로 필요한가요?',
                    'writer' => '관리자',
                    'contents' => '장기렌터카 판매 및 세일즈 파트너 가입을 위해 필수적으로 요구되는 전문 자격증은 없습니다. 오토스타일의 교육 과정과 멘토링을 이수하시면 누구나 파트너로 즉시 영업 활동이 가능합니다.',
                    'r_date' => date('Y-m-d H:i:s'),
                    'b_ref' => 4,
                    'b_step' => 0,
                    'b_level' => 0,
                    'hit' => 0,
                    'onum' => 1
                ]
            ];
            foreach ($defaultFaqs as $f) {
                $bbsModel->insert($f);
            }
        }

        // 3. Policy Config
        $existingConfig = $configModel->getConfig('policy');
        if ($existingConfig) {
            // Normalize board name to Korean and ensure skin is faq to hide metadata
            if ($existingConfig['board_name'] !== '약관/방침' || $existingConfig['skin'] !== 'faq') {
                $configModel->update($existingConfig['tbc_idx'], [
                    'board_name' => '약관/방침',
                    'skin' => 'faq'
                ]);
            }
        } else {
            $configModel->insert([
                'board_name' => '약관/방침',
                'board_code' => 'policy',
                'is_category' => 'N',
                'is_secure' => 'N',
                'is_right' => 'N',
                'is_reply' => 'N',
                'is_comment' => 'N',
                'is_recomm' => 'N',
                'is_notice' => 'N',
                'skin' => 'faq' // Use faq skin to simplify admin list/form fields
            ]);
        }

        // 4. Policy List Seeding
        $policyCount = $bbsModel->where('code', 'policy')->countAllResults();
        if ($policyCount == 0) {
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
        }
    }

    public function index()
    {
        return view('home', [
            'metaTitle' => '비티엘 다이어트 (BITIEL) – 부산 프리미엄 다이어트 전문 센터',
            'metaDescription' => '19년 노하우, 누적 1만 명의 놀라운 변화. BTL 전문가와 1:1 맞춤 체형관리로 요요 ZERO 다이어트.',
            'ogImage' => base_url('assets/images/btl/main-visual.webp'),
        ]);
    }

    public function terms()
    {
        $bbsModel = new \App\Models\BbsModel();
        $configModel = new \App\Models\BbsConfigModel();

        $this->checkAndSeed($bbsModel, $configModel);

        $policy = $bbsModel->where('code', 'policy')
                           ->where('subject', '이용약관')
                           ->first();

        return view('policy', [
            'metaTitle' => "이용약관 - 오토스타일 (AUTOSTYLE)",
            'metaDescription' => "오토스타일 이용약관 페이지입니다.",
            'ogImage' => base_url('images/logo_app.png'),
            'title' => '이용약관',
            'content' => $policy['contents'] ?? '이용약관 준비 중입니다.'
        ]);
    }

    public function privacy()
    {
        $bbsModel = new \App\Models\BbsModel();
        $configModel = new \App\Models\BbsConfigModel();

        $this->checkAndSeed($bbsModel, $configModel);

        $policy = $bbsModel->where('code', 'policy')
                           ->where('subject', '개인정보처리방침')
                           ->first();

        return view('policy', [
            'metaTitle' => "개인정보처리방침 - 오토스타일 (AUTOSTYLE)",
            'metaDescription' => "오토스타일 개인정보처리방침 페이지입니다.",
            'ogImage' => base_url('images/logo_app.png'),
            'title' => '개인정보처리방침',
            'content' => $policy['contents'] ?? '개인정보처리방침 준비 중입니다.'
        ]);
    }

    public function submitInquiry()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'userName' => 'required',
            'userPhone' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'ERROR',
                'message' => '이름과 휴대전화번호는 필수 입력 항목입니다.'
            ]);
        }

        $userName = $this->request->getPost('userName');
        $userPhone = $this->request->getPost('userPhone');
        $userJob = $this->request->getPost('userJob');
        $experience = $this->request->getPost('experience');
        $partnerType = $this->request->getPost('partnerType');

        $inquiryModel = new \App\Models\InquiryModel();
        
        $data = [
            'manager'  => $userName,
            'tel'      => $userPhone,
            'company'  => $userJob,
            'location' => $experience,
            'content'  => $partnerType
        ];

        $result = $inquiryModel->saveInquiry($data);

        if ($result) {
            return $this->response->setJSON([
                'status' => 'OK',
                'message' => '제휴 파트너 신청이 성공적으로 접수되었습니다!',
                'idx'     => $result
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'ERROR',
                'message' => '서버 오류로 인해 신청을 처리할 수 없습니다. 잠시 후 다시 시도해 주세요.'
            ]);
        }
    }

    public function sendEmailNotification($idx)
    {
        $inquiryModel = new \App\Models\InquiryModel();
        $inquiry = $inquiryModel->find($idx);

        if (!$inquiry) {
            return $this->response->setJSON([
                'status' => 'ERROR',
                'message' => 'Inquiry not found'
            ]);
        }

        try {
            $settingModel = new \App\Models\Setting();
            $setting = $settingModel->getSettings();

            $smtpHost = $setting['smtp_host'] ?? '';
            $smtpId = $setting['smtp_id'] ?? '';
            $smtpPass = $setting['smtp_pass'] ?? '';
            $adminEmailList = $setting['admin_email_list'] ?? '';
            $siteName = '오토스타일';

            if (!empty($smtpHost) && !empty($smtpId) && !empty($smtpPass) && !empty($adminEmailList)) {
                $recipients = preg_split('/[\s,;]+/', trim($adminEmailList));
                $recipients = array_filter($recipients, function($email) {
                    return filter_var($email, FILTER_VALIDATE_EMAIL);
                });

                if (!empty($recipients)) {
                    $emailService = \Config\Services::email();

                    $emailConfig = [
                        'protocol'     => 'smtp',
                        'SMTPHost'     => $smtpHost,
                        'SMTPUser'     => $smtpId,
                        'SMTPPass'     => $smtpPass,
                        'SMTPPort'     => 587,
                        'SMTPCrypto'   => '',
                        'mailType'     => 'html',
                        'charset'      => 'utf-8',
                        'newline'      => "\r\n",
                        'CRLF'         => "\r\n",
                        'SMTPTimeout'  => 10,
                        'wordWrap'     => true
                    ];

                    $emailService->initialize($emailConfig);

                    $subject = "[{$siteName}] 새로운 제휴 파트너 신청이 접수되었습니다.";

                    // Parse English values to Korean
                    $userName = $inquiry['manager'] ?? '';
                    $userPhone = $inquiry['tel'] ?? '';
                    $userJob = $inquiry['company'] ?? '';
                    $experienceRaw = $inquiry['location'] ?? '';
                    $partnerTypeRaw = $inquiry['content'] ?? '';

                    $experience = $experienceRaw === 'yes' ? '경험 있음' : '경험 없음';
                    $partnerType = $partnerTypeRaw === 'corporate' ? '법인 제휴' : '개인 제휴';

                    // Beautiful Warm Rose Pink HTML Table for Admin Notification
                    $htmlContent = "
                    <div style='max-width: 600px; margin: 20px auto; font-family: -apple-system, BlinkMacSystemFont, \"Apple SD Gothic Neo\", \"Malgun Gothic\", sans-serif; color: #374151; line-height: 1.6; border: 1px solid #ffe4e6; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(255, 75, 114, 0.08), 0 4px 6px -2px rgba(255, 75, 114, 0.04); background-color: #ffffff;'>
                        <!-- Top Gradient Header -->
                        <div style='background: linear-gradient(135deg, #ff4e73 0%, #ff7b92 100%); padding: 35px 24px; text-align: center;'>
                            <h1 style='color: #ffffff; margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -0.5px; text-shadow: 0 2px 4px rgba(190, 18, 60, 0.2);'>제휴 파트너 신청 알림</h1>
                            <p style='color: rgba(255, 255, 255, 0.95); margin: 10px 0 0 0; font-size: 15px; font-weight: 500;'>{$siteName} 웹사이트에서 새로운 제휴 신청서가 도착했습니다.</p>
                        </div>
                        
                        <!-- Content Body -->
                        <div style='padding: 35px 28px;'>
                            <!-- Section Heading -->
                            <div style='border-bottom: 2px solid #ff4e73; padding-bottom: 10px; margin-bottom: 20px;'>
                                <span style='background-color: #ff4e73; width: 4px; height: 18px; display: inline-block; vertical-align: middle; border-radius: 2px; margin-right: 8px;'></span>
                                <h2 style='font-size: 17px; font-weight: 800; color: #be123c; margin: 0; display: inline-block; vertical-align: middle;'>접수 내역</h2>
                            </div>
                            
                            <!-- Table -->
                            <table style='width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 15px; margin-bottom: 30px; border: 1px solid #ffe4e6; border-radius: 8px; overflow: hidden;'>
                                    <tr>
                                        <th style='width: 32%; text-align: left; padding: 14px 16px; border-bottom: 1px solid #ffe4e6; font-size: 14px; color: #be123c; font-weight: bold; background-color: #fff1f2;'>이름 / 담당자</th>
                                        <td style='padding: 14px 16px; border-bottom: 1px solid #ffe4e6; font-size: 14px; font-weight: bold; color: #111827;'>{$userName}</td>
                                    </tr>
                                    <tr>
                                        <th style='text-align: left; padding: 14px 16px; border-bottom: 1px solid #ffe4e6; font-size: 14px; color: #be123c; font-weight: bold; background-color: #fff1f2;'>휴대전화번호</th>
                                        <td style='padding: 14px 16px; border-bottom: 1px solid #ffe4e6; font-size: 14px; font-weight: bold; color: #ff4e73;'>{$userPhone}</td>
                                    </tr>
                                    <tr>
                                        <th style='text-align: left; padding: 14px 16px; border-bottom: 1px solid #ffe4e6; font-size: 14px; color: #be123c; font-weight: bold; background-color: #fff1f2;'>현재 하는 일</th>
                                        <td style='padding: 14px 16px; border-bottom: 1px solid #ffe4e6; font-size: 14px; color: #4b5563;'>{$userJob}</td>
                                    </tr>
                                    <tr>
                                        <th style='text-align: left; padding: 14px 16px; border-bottom: 1px solid #ffe4e6; font-size: 14px; color: #be123c; font-weight: bold; background-color: #fff1f2;'>영업/유관 경험</th>
                                        <td style='padding: 14px 16px; border-bottom: 1px solid #ffe4e6; font-size: 14px; color: #4b5563;'>{$experience}</td>
                                    </tr>
                                    <tr>
                                        <th style='text-align: left; padding: 14px 16px; border-bottom: 1px solid #ffe4e6; font-size: 14px; color: #be123c; font-weight: bold; background-color: #fff1f2;'>희망 파트너 유형</th>
                                        <td style='padding: 14px 16px; border-bottom: 1px solid #ffe4e6; font-size: 14px; color: #4b5563;'>{$partnerType}</td>
                                    </tr>
                                    <tr>
                                        <th style='text-align: left; padding: 14px 16px; font-size: 14px; color: #be123c; font-weight: bold; background-color: #fff1f2;'>신청 일시</th>
                                        <td style='padding: 14px 16px; font-size: 14px; color: #6b7280;'>{$inquiry['regdate']}</td>
                                    </tr>
                            </table>
                            
                            <div style='text-align: center; margin-top: 25px;'>
                                <a href='" . base_url('AdmMaster') . "' style='display: inline-block; background: linear-gradient(135deg, #ff4e73 0%, #ff7b92 100%); color: #ffffff; padding: 15px 35px; border-radius: 50px; text-decoration: none; font-size: 14px; font-weight: bold; box-shadow: 0 6px 20px rgba(255, 75, 114, 0.35); transition: all 0.2s;'>관리자 페이지에서 상세 정보 확인</a>
                            </div>
                        </div>
                        
                        <div style='text-align: center; padding: 24px; background-color: #fff5f5; border-top: 1px solid #ffe4e6; font-size: 12px; color: #9ca3af;'>
                            <p style='margin: 0; color: #f43f5e; font-weight: 500;'>본 메일은 시스템에서 자동으로 발송되는 알림 메일입니다.</p>
                            <p style='margin: 6px 0 0 0;'>© " . date('Y') . " {$siteName}. All rights reserved.</p>
                        </div>
                    </div>
                    ";

                    $emailService->setFrom($smtpId, $siteName);
                    $emailService->setTo($recipients);
                    $emailService->setSubject($subject);
                    $emailService->setMessage($htmlContent);
                    $emailService->send();
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Email notification failed: ' . $e->getMessage());
        }

        return $this->response->setJSON([
            'status' => 'OK'
        ]);
    }
}
