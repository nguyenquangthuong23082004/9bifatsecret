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
        $hasOldAutoStyle = $bbsModel->where('code', 'faq')
            ->groupStart()
                ->like('contents', '오토스타일')
                ->orLike('subject', '초보도 할수 있나요')
            ->groupEnd()
            ->countAllResults();
        if ($hasOldAutoStyle > 0) {
            $bbsModel->where('code', 'faq')->delete();
        }

        $faqCount = $bbsModel->where('code', 'faq')->countAllResults();
        if ($faqCount == 0) {
            $defaultFaqs = [
                [
                    'code' => 'faq',
                    'subject' => '비용은 얼마인가요?',
                    'writer' => '관리자',
                    'contents' => '<p>프로그램/목표감량kg/관리기간 등에 따라 달라집니다. 1대1 맞춤별 상담을 통해 상세 안내드리며, 비티엘은 10년전 가격 그대로 거품없이 필요한 만큼만 제안 드립니다. 큰 패키지보단 필요할 때 원하는 만큼 1회부터 시작하실 수 있도록 부담없이 구성한 단기프로그램도 다양하게 마련되었으며, 비티엘은 장기등록권유나 방문없이 소진을 하는 등의 행위를 하지 않습니다.</p>',
                    'r_date' => date('Y-m-d H:i:s'),
                    'b_ref' => 1,
                    'b_step' => 0,
                    'b_level' => 0,
                    'hit' => 0,
                    'onum' => 5
                ],
                [
                    'code' => 'faq',
                    'subject' => '통증이나 러닝타임이 어느 정도인가요?',
                    'writer' => '관리자',
                    'contents' => '<p>프로그램별로 차이가 있으나, 대부분의 관리는 통증이 전혀 없거나 기분 좋은 자극 수준입니다. 러닝타임은 보통 40분에서 1시간 내외로 소요됩니다.</p>',
                    'r_date' => date('Y-m-d H:i:s'),
                    'b_ref' => 2,
                    'b_step' => 0,
                    'b_level' => 0,
                    'hit' => 0,
                    'onum' => 4
                ],
                [
                    'code' => 'faq',
                    'subject' => '식단조절이나 운동이 필요한가요?',
                    'writer' => '관리자',
                    'contents' => '<p>억지로 굶는 식단이나 무리한 운동 없이도 효과적인 감량이 가능합니다. 일상생활을 유지하면서 체계적인 기기 관리와 맞춤 컨설팅을 통해 요요 없는 다이어트를 도와드립니다.</p>',
                    'r_date' => date('Y-m-d H:i:s'),
                    'b_ref' => 3,
                    'b_step' => 0,
                    'b_level' => 0,
                    'hit' => 0,
                    'onum' => 3
                ],
                [
                    'code' => 'faq',
                    'subject' => '갱년기 50대 이상 중년도 다이어트가 가능할까요?',
                    'writer' => '관리자',
                    'contents' => '<p>네, 당연히 가능합니다. 나이가 들면서 저하되는 기초대사량과 호르몬 변화를 고려하여, 갱년기 여성분들도 무리 없이 건강하게 체지방 위주로 감량하실 수 있는 맞춤형 프로그램을 제공합니다.</p>',
                    'r_date' => date('Y-m-d H:i:s'),
                    'b_ref' => 4,
                    'b_step' => 0,
                    'b_level' => 0,
                    'hit' => 0,
                    'onum' => 2
                ],
                [
                    'code' => 'faq',
                    'subject' => '요요, 감량 후 얼굴처짐 등이 걱정돼요.',
                    'writer' => '관리자',
                    'contents' => '<p>급격한 굶기 다이어트와 달리 체지방은 태우고 탄력은 유지하는 관리로 진행되므로 얼굴 처짐이 최소화됩니다. 또한 기초대사량을 높여주는 체질 개선을 병행하여 요요를 예방합니다.</p>',
                    'r_date' => date('Y-m-d H:i:s'),
                    'b_ref' => 5,
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

    private function logVisit()
    {
        $db = \Config\Database::connect();
        
        $db->query("CREATE TABLE IF NOT EXISTS tbl_visits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            device VARCHAR(10) NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            device_sig VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Safely add column if it was created without device_sig previously
        try {
            $db->query("ALTER TABLE tbl_visits ADD COLUMN device_sig VARCHAR(255) NOT NULL AFTER ip_address");
        } catch (\Throwable $e) {
            // Already exists or handled
        }

        // Safely add visitor_id column for cookie tracking
        try {
            $db->query("ALTER TABLE tbl_visits ADD COLUMN visitor_id VARCHAR(50) NULL AFTER id");
            $db->query("ALTER TABLE tbl_visits ADD INDEX (visitor_id)");
        } catch (\Throwable $e) {
            // Already exists or handled
        }

        $agent = $this->request->getUserAgent();
        $device = $agent->isMobile() ? 'mobile' : 'pc';
        $ip = $this->request->getIPAddress();
        
        // Build a clean, human-readable device signature
        if ($agent->isBrowser()) {
            $deviceSig = $agent->getPlatform() . ' / ' . $agent->getBrowser() . ' ' . $agent->getVersion();
        } elseif ($agent->isMobile()) {
            $deviceSig = $agent->getPlatform() . ' / ' . $agent->getMobile();
        } elseif ($agent->isRobot()) {
            $deviceSig = 'Robot: ' . $agent->getRobot();
        } else {
            $deviceSig = $agent->getAgentString(); // Fallback to full UA string
        }
        
        $deviceSig = substr(trim($deviceSig), 0, 255);

        // Get or set cookie for tracking physical devices
        $visitorId = $_COOKIE['visitor_uuid'] ?? '';
        $isFirstVisit = false;
        if (empty($visitorId)) {
            $visitorId = uniqid('v_', true);
            setcookie('visitor_uuid', $visitorId, time() + (365 * 24 * 60 * 60), '/');
            $isFirstVisit = true;
        }

        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');

        if ($isFirstVisit) {
            // First time load: check both cookie ID and IP + device_sig
            $exists = $db->table('tbl_visits')
                ->groupStart()
                    ->where('visitor_id', $visitorId)
                    ->orGroupStart()
                        ->where('ip_address', $ip)
                        ->where('device_sig', $deviceSig)
                        ->where('created_at >=', $todayStart)
                        ->where('created_at <=', $todayEnd)
                    ->groupEnd()
                ->groupEnd()
                ->countAllResults();
        } else {
            // Returning visitor: check strictly by visitor_id cookie
            $exists = $db->table('tbl_visits')
                ->where('visitor_id', $visitorId)
                ->where('created_at >=', $todayStart)
                ->where('created_at <=', $todayEnd)
                ->countAllResults();
        }

        if ($exists === 0) {
            $db->table('tbl_visits')->insert([
                'visitor_id' => $visitorId,
                'device' => $device,
                'ip_address' => $ip,
                'device_sig' => $deviceSig
            ]);
        }
    }

    public function index()
    {
        $bbsModel = new \App\Models\BbsModel();
        $configModel = new \App\Models\BbsConfigModel();

        $this->checkAndSeed($bbsModel, $configModel);
        $this->logVisit();

        $faqs = $bbsModel->where('code', 'faq')
                         ->orderBy('onum', 'DESC')
                         ->orderBy('bbs_idx', 'DESC')
                         ->findAll();

        // Fetch active popups
        $now = date('Y-m-d H:i:s');
        $popupModel = new \App\Models\PopupModel();
        $popups = $popupModel->groupStart()
            ->where('status', 'B') // Forced active
            ->orGroupStart()
                ->where('status', 'A') // Automatic schedule
                ->where("CONCAT(P_STARTDAY, ' ', P_START_HH, ':', P_START_MM, ':00') <=", $now)
                ->where("CONCAT(P_ENDDAY, ' ', P_END_HH, ':', P_END_MM, ':59') >=", $now)
            ->groupEnd()
        ->groupEnd()
        ->where('P_TYPES', 'kr')
        ->findAll();

        return view('home', [
            'faqs' => $faqs,
            'popups' => $popups
        ]);
    }

    public function popupView($id)
    {
        $popupModel = new \App\Models\PopupModel();
        $popup = $popupModel->find($id);
        if (!$popup) {
            return "존재하지 않는 팝업입니다.";
        }
        return view('popup_view', [
            'popup' => $popup
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
            $siteName = !empty($setting['browser_title']) ? $setting['browser_title'] : '비티엘 다이어트';

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

                    // Parse dynamic fields
                    $userName = $inquiry['manager'] ?? '';
                    $userPhone = $inquiry['tel'] ?? '';
                    $userCompany = $inquiry['company'] ?? '';
                    $location = $inquiry['location'] ?? '';
                    $content = $inquiry['content'] ?? '';

                    $isConsult = ($content === '1');

                    $labelAgeOrJob = $isConsult ? '나이' : '현재 하는 일';
                    $labelRegionOrExp = $isConsult ? '거주지역' : '영업/유관 경험';
                    $labelAgreeOrType = $isConsult ? '개인정보 수집 동의' : '희망 파트너 유형';

                    $valueAgeOrJob = $userCompany;
                    $valueRegionOrExp = $isConsult ? $location : ($location === 'yes' ? '경험 있음' : '경험 없음');
                    $valueAgreeOrType = $isConsult ? '동의함' : ($content === 'corporate' ? '법인 제휴' : '개인 제휴');

                    $subject = $isConsult 
                        ? "[{$siteName}] 새로운 1:1 무료컨설팅 예약 신청이 접수되었습니다."
                        : "[{$siteName}] 새로운 제휴 파트너 신청이 접수되었습니다.";
                    
                    $headerTitle = $isConsult ? '1:1 무료컨설팅 예약 신청 알림' : '제휴 파트너 신청 알림';
                    $headerSubtitle = $isConsult 
                        ? "{$siteName} 웹사이트에서 새로운 1:1 무료컨설팅 예약 신청이 도착했습니다."
                        : "{$siteName} 웹사이트에서 새로운 제휴 신청서가 도착했습니다.";

                    $agreeOrTypeRow = '';
                    if (!$isConsult) {
                        $agreeOrTypeRow = "
                                    <tr>
                                        <th style='text-align: left; padding: 14px 16px; border-bottom: 1px solid #fbcfe8; font-size: 14px; color: #c8365a; font-weight: bold; background-color: #fff1f2;'>{$labelAgreeOrType}</th>
                                        <td style='padding: 14px 16px; border-bottom: 1px solid #fbcfe8; font-size: 14px; color: #4b5563;'>{$valueAgreeOrType}</td>
                                    </tr>
                        ";
                    }

                    // Beautiful Warm Rose Pink HTML Table for Admin Notification with Responsive Styles
                    $htmlContent = "
                    <style>
                        * { box-sizing: border-box; }
                        @media only screen and (max-width: 600px) {
                            .email-container { width: 95% !important; margin: 10px auto !important; }
                            .email-body { padding: 25px 16px !important; }
                            .email-header { padding: 25px 16px !important; }
                            .email-table th, .email-table td { padding: 12px 10px !important; font-size: 13px !important; }
                            .email-title { font-size: 20px !important; }
                            .email-subtitle { font-size: 13px !important; }
                        }
                    </style>
                    <div class='email-container' style='max-width: 600px; width: 100%; margin: 20px auto; font-family: -apple-system, BlinkMacSystemFont, \"Apple SD Gothic Neo\", \"Malgun Gothic\", sans-serif; color: #374151; line-height: 1.6; border: 1px solid #fbcfe8; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(200, 54, 90, 0.08), 0 4px 6px -2px rgba(200, 54, 90, 0.04); background-color: #ffffff;'>
                        <!-- Top Gradient Header -->
                        <div class='email-header' style='background: linear-gradient(135deg, #c8365a 0%, #da4070 100%); padding: 35px 24px; text-align: center;'>
                            <h1 class='email-title' style='color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; text-shadow: 0 2px 4px rgba(200, 54, 90, 0.2);'>{$headerTitle}</h1>
                            <p class='email-subtitle' style='color: rgba(255, 255, 255, 0.95); margin: 10px 0 0 0; font-size: 14px; font-weight: 500;'>{$headerSubtitle}</p>
                        </div>
                        
                        <!-- Content Body -->
                        <div class='email-body' style='padding: 35px 28px;'>
                            <!-- Section Heading -->
                            <div style='border-bottom: 2px solid #c8365a; padding-bottom: 10px; margin-bottom: 20px;'>
                                <span style='background-color: #c8365a; width: 4px; height: 18px; display: inline-block; vertical-align: middle; border-radius: 2px; margin-right: 8px;'></span>
                                <h2 style='font-size: 16px; font-weight: 800; color: #c8365a; margin: 0; display: inline-block; vertical-align: middle;'>접수 내역</h2>
                            </div>
                            
                            <!-- Table -->
                            <table class='email-table' style='width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 15px; margin-bottom: 30px; border: 1px solid #fbcfe8; border-radius: 8px; overflow: hidden;'>
                                    <tr>
                                        <th style='width: 32%; text-align: left; padding: 14px 16px; border-bottom: 1px solid #fbcfe8; font-size: 14px; color: #c8365a; font-weight: bold; background-color: #fff1f2;'>이름</th>
                                        <td style='padding: 14px 16px; border-bottom: 1px solid #fbcfe8; font-size: 14px; font-weight: bold; color: #111827;'>{$userName}</td>
                                    </tr>
                                    <tr>
                                        <th style='text-align: left; padding: 14px 16px; border-bottom: 1px solid #fbcfe8; font-size: 14px; color: #c8365a; font-weight: bold; background-color: #fff1f2;'>연락처</th>
                                        <td style='padding: 14px 16px; border-bottom: 1px solid #fbcfe8; font-size: 14px; font-weight: bold; color: #da4070;'>{$userPhone}</td>
                                    </tr>
                                    <tr>
                                        <th style='text-align: left; padding: 14px 16px; border-bottom: 1px solid #fbcfe8; font-size: 14px; color: #c8365a; font-weight: bold; background-color: #fff1f2;'>{$labelAgeOrJob}</th>
                                        <td style='padding: 14px 16px; border-bottom: 1px solid #fbcfe8; font-size: 14px; color: #4b5563;'>{$valueAgeOrJob}</td>
                                    </tr>
                                    <tr>
                                        <th style='text-align: left; padding: 14px 16px; border-bottom: 1px solid #fbcfe8; font-size: 14px; color: #c8365a; font-weight: bold; background-color: #fff1f2;'>{$labelRegionOrExp}</th>
                                        <td style='padding: 14px 16px; border-bottom: 1px solid #fbcfe8; font-size: 14px; color: #4b5563;'>{$valueRegionOrExp}</td>
                                    </tr>
                                    {$agreeOrTypeRow}
                                    <tr>
                                        <th style='text-align: left; padding: 14px 16px; font-size: 14px; color: #c8365a; font-weight: bold; background-color: #fff1f2;'>신청 일시</th>
                                        <td style='padding: 14px 16px; font-size: 14px; color: #6b7280;'>{$inquiry['regdate']}</td>
                                    </tr>
                            </table>
                            
                            <div style='text-align: center; margin-top: 25px;'>
                                <a href='" . base_url('AdmMaster') . "' style='display: inline-block; background: linear-gradient(135deg, #c8365a 0%, #da4070 100%); color: #ffffff; padding: 14px 30px; border-radius: 50px; text-decoration: none; font-size: 13px; font-weight: bold; box-shadow: 0 6px 20px rgba(200, 54, 90, 0.2); transition: all 0.2s;'>관리자 페이지에서 상세 정보 확인</a>
                            </div>
                        </div>
                        
                        <div style='text-align: center; padding: 24px; background-color: #fdfafb; border-top: 1px solid #fbcfe8; font-size: 12px; color: #9ca3af;'>
                            <p style='margin: 0; color: #da4070; font-weight: 500;'>본 메일은 시스템에서 자동으로 발송되는 알림 메일입니다.</p>
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

    public function submitConsult()
    {
        // 1. Honeypot check (anti-spam bot)
        $honeypot = $this->request->getPost('email_address');
        if (!empty($honeypot)) {
            // Silently block spambots by pretending the submission succeeded
            return $this->response->setBody("
            <script>
                alert('1:1 무료컨설팅 예약 신청이 완료되었습니다.');
                window.location.href = '" . base_url() . "';
            </script>
            ")->setHeader('Content-Type', 'text/html');
        }

        // 2. IP Rate Limit check (max 5 submissions per hour per IP)
        $ip = $this->request->getIPAddress();
        $db = \Config\Database::connect();
        $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $spamCount = $db->table('tbl_contents')
            ->where('ip_address', $ip)
            ->where('regdate >=', $oneHourAgo)
            ->countAllResults();

        if ($spamCount >= 5) {
            return $this->response->setBody("
            <script>
                alert('단기간 내에 너무 많은 예약 신청이 감지되었습니다. 잠시 후 다시 시도해 주세요.');
                window.history.back();
            </script>
            ")->setHeader('Content-Type', 'text/html');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'name'   => 'required',
            'phone'  => 'required',
            'age'    => 'required',
            'region' => 'required',
            'agree'  => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setBody("
            <script>
                alert('모든 필수 항목(성함, 나이, 거주지역, 연락처)을 입력하고 개인정보 동의에 체크해 주세요.');
                window.history.back();
            </script>
            ")->setHeader('Content-Type', 'text/html');
        }

        $name = $this->request->getPost('name');
        $phone = $this->request->getPost('phone');
        $age = $this->request->getPost('age');
        $region = $this->request->getPost('region');
        $agree = $this->request->getPost('agree');

        // 3. Server-side numeric validation for Age
        if (!is_numeric($age)) {
            return $this->response->setBody("
            <script>
                alert('나이 필드에는 숫자만 입력 가능합니다.');
                window.history.back();
            </script>
            ")->setHeader('Content-Type', 'text/html');
        }

        $inquiryModel = new \App\Models\InquiryModel();

        $data = [
            'manager'  => $name,
            'tel'      => $phone,
            'company'  => $age,
            'location' => $region,
            'content'  => $agree
        ];

        $result = $inquiryModel->saveInquiry($data);

        if ($result) {
            try {
                $this->sendEmailNotification($result);
            } catch (\Throwable $e) {
                log_message('error', 'Email notification failed inside submitConsult: ' . $e->getMessage());
            }

            return $this->response->setBody("
            <script>
                alert('1:1 무료컨설팅 예약 신청이 완료되었습니다.');
                window.location.href = '" . base_url() . "';
            </script>
            ")->setHeader('Content-Type', 'text/html');
        } else {
            return $this->response->setBody("
            <script>
                alert('서버 오류로 인해 신청을 처리할 수 없습니다. 잠시 후 다시 시도해 주세요.');
                window.history.back();
            </script>
            ")->setHeader('Content-Type', 'text/html');
        }
    }

    public function getPrivacyPolicy()
    {
        $bbsModel = new \App\Models\BbsModel();
        $policy = $bbsModel->where('code', 'policy')
                           ->where('subject', '개인정보처리방침')
                           ->first();
        return $this->response->setJSON([
            'status' => 'OK',
            'content' => $policy['contents'] ?? '개인정보처리방침 준비 중입니다.'
        ]);
    }
}
