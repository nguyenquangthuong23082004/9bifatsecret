<?php

namespace App\Controllers\AdmMaster;

use App\Controllers\BaseController;
use App\Models\InquiryModel;

class Stats extends BaseController
{
    public function index()
    {
        $inquiryModel = new InquiryModel();
        $db = \Config\Database::connect();

        // Ensure tbl_visits exists
        $db->query("CREATE TABLE IF NOT EXISTS tbl_visits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            device VARCHAR(10) NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // 1. Consulting counts
        $totalConsulting = $inquiryModel->countAllResults();
        $todayStr = date('Y-m-d');
        $todayConsulting = $inquiryModel->like('regdate', $todayStr, 'after')->countAllResults();
        $monthStr = date('Y-m-01 00:00:00');
        $monthConsulting = $inquiryModel->where('regdate >=', $monthStr)->countAllResults();

        // 2. Visitor device counts (PC vs Mobile)
        // Total
        $totalPCVisits = $db->table('tbl_visits')->where('device', 'pc')->countAllResults();
        $totalMobileVisits = $db->table('tbl_visits')->where('device', 'mobile')->countAllResults();
        
        // Today
        $todayPCVisits = $db->table('tbl_visits')->where('device', 'pc')->like('created_at', $todayStr, 'after')->countAllResults();
        $todayMobileVisits = $db->table('tbl_visits')->where('device', 'mobile')->like('created_at', $todayStr, 'after')->countAllResults();

        // This Month
        $monthPCVisits = $db->table('tbl_visits')->where('device', 'pc')->where('created_at >=', $monthStr)->countAllResults();
        $monthMobileVisits = $db->table('tbl_visits')->where('device', 'mobile')->where('created_at >=', $monthStr)->countAllResults();

        // 3. Daily Trends (Last 15 days)
        $dailyTrends = [];
        $days = [];
        for ($i = 14; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $days[$date] = [
                'date' => date('m-d', strtotime("-$i days")),
                'consulting' => 0,
                'pc_visits' => 0,
                'mobile_visits' => 0
            ];
        }

        // Fetch daily counts for tbl_contents (Consulting)
        $q1 = $db->query("
            SELECT DATE(regdate) as d, COUNT(*) as cnt 
            FROM tbl_contents 
            WHERE regdate >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) 
            GROUP BY DATE(regdate)
        ")->getResultArray();

        foreach ($q1 as $row) {
            if (isset($days[$row['d']])) {
                $days[$row['d']]['consulting'] = (int)$row['cnt'];
            }
        }

        // Fetch daily counts for tbl_visits (PC vs Mobile visits)
        $q2 = $db->query("
            SELECT DATE(created_at) as d, 
                   SUM(CASE WHEN device = 'pc' THEN 1 ELSE 0 END) as pc_cnt,
                   SUM(CASE WHEN device = 'mobile' THEN 1 ELSE 0 END) as mobile_cnt
            FROM tbl_visits 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) 
            GROUP BY DATE(created_at)
        ")->getResultArray();

        foreach ($q2 as $row) {
            if (isset($days[$row['d']])) {
                $days[$row['d']]['pc_visits'] = (int)$row['pc_cnt'];
                $days[$row['d']]['mobile_visits'] = (int)$row['mobile_cnt'];
            }
        }

        $dailyTrends = array_values($days);

        // 4. Region Statistics (based on tbl_contents.location)
        $regions = [
            '서울' => 0, '부산' => 0, '대구' => 0, '인천' => 0,
            '광주' => 0, '대전' => 0, '울산' => 0, '세종' => 0,
            '경기' => 0, '강원' => 0, '충북' => 0, '충남' => 0,
            '전북' => 0, '전남' => 0, '경북' => 0, '경남' => 0,
            '제주' => 0, '기타' => 0
        ];

        $locations = $db->query("SELECT location FROM tbl_contents WHERE location IS NOT NULL AND location != ''")->getResultArray();
        foreach ($locations as $loc) {
            $matched = false;
            $text = $loc['location'];
            foreach (array_keys($regions) as $r) {
                if ($r === '기타') continue;
                if (strpos($text, $r) !== false) {
                    $regions[$r]++;
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                $regions['기타']++;
            }
        }
        // Filter out empty regions
        $regions = array_filter($regions, function($v) { return $v > 0; });
        if (empty($regions)) {
            $regions = ['데이터 없음' => 0];
        }

        // 5. Age Group Statistics (based on tbl_contents.company which stores age like "82년생", "35세", etc.)
        $ageGroups = [
            '10대 이하' => 0,
            '20대' => 0,
            '30대' => 0,
            '40대' => 0,
            '50대' => 0,
            '60대 이상' => 0,
            '미지정' => 0
        ];

        $ages = $db->query("SELECT company FROM tbl_contents WHERE company IS NOT NULL AND company != ''")->getResultArray();
        $currentYear = (int)date('Y'); // 2026

        foreach ($ages as $row) {
            $text = trim($row['company']);
            
            // Check direct age group notation (e.g. 30대, 40대)
            if (preg_match('/([1-6])0\s*대/', $text, $matches)) {
                $decade = $matches[1] . '0대';
                if ($matches[1] >= 6) {
                    $ageGroups['60대 이상']++;
                } elseif ($matches[1] <= 1) {
                    $ageGroups['10대 이하']++;
                } else {
                    $ageGroups[$decade]++;
                }
                continue;
            }

            // Check birth year (e.g. 82년생, 95년생, 82, 1982)
            if (preg_match('/(\d{2,4})/', $text, $matches)) {
                $val = (int)$matches[1];
                $birthYear = $val;
                if ($val < 100) {
                    $birthYear = ($val > 30) ? (1900 + $val) : (2000 + $val);
                }
                
                if ($birthYear > 1900 && $birthYear <= $currentYear) {
                    $age = $currentYear - $birthYear;
                    if ($age < 20) {
                        $ageGroups['10대 이하']++;
                    } elseif ($age < 30) {
                        $ageGroups['20대']++;
                    } elseif ($age < 40) {
                        $ageGroups['30대']++;
                    } elseif ($age < 50) {
                        $ageGroups['40대']++;
                    } elseif ($age < 60) {
                        $ageGroups['50대']++;
                    } else {
                        $ageGroups['60대 이상']++;
                    }
                    continue;
                }
            }

            $ageGroups['미지정']++;
        }

        // 6. Recent Activity
        $recentInquiries = $inquiryModel->orderBy('idx', 'DESC')->limit(10)->find();

        // 7. Recent Visits (last 100 visits for modal)
        $recentVisits = $db->table('tbl_visits')->orderBy('id', 'DESC')->limit(100)->get()->getResultArray();

        return view('adm_master/stats/index', [
            'title' => '통계관리',
            
            // Consulting
            'totalConsulting' => $totalConsulting,
            'todayConsulting' => $todayConsulting,
            'monthConsulting' => $monthConsulting,
            
            // Visits
            'totalPCVisits' => $totalPCVisits,
            'totalMobileVisits' => $totalMobileVisits,
            'todayPCVisits' => $todayPCVisits,
            'todayMobileVisits' => $todayMobileVisits,
            'monthPCVisits' => $monthPCVisits,
            'monthMobileVisits' => $monthMobileVisits,
            
            // Graphs
            'dailyTrends' => $dailyTrends,
            'regions' => $regions,
            'ageGroups' => $ageGroups,
            'recentInquiries' => $recentInquiries,
            'recentVisits' => $recentVisits
        ]);
    }
}
