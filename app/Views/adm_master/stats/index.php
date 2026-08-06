<?= $this->extend('adm_master/layout/main') ?>

<?= $this->section('header_buttons') ?>
<div class="d-flex gap-2">
    <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-printer"></i> 보고서 인쇄
    </button>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid px-0">
    <!-- 1. Overview Cards -->
    <div class="row g-4 mb-4">
        <!-- 1:1 consulting card -->
        <div class="col-md-6 col-xl-6">
            <div class="card border-0 shadow-sm overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted fw-bold mb-1 small" style="letter-spacing: .5px;">1:1 무료컨설팅 접수</h6>
                            <h2 class="display-6 fw-bold mb-0 text-primary"><?= number_format($totalConsulting) ?><span class="fs-6 text-muted ms-1">건</span></h2>
                        </div>
                        <a href="<?= base_url('AdmMaster/inquiry/1') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">자세히 보기</a>
                    </div>
                    <div class="row g-2 border-top pt-3 mt-3">
                        <div class="col-6 border-end">
                            <span class="text-muted d-block small mb-1">오늘 신청</span>
                            <span class="fw-bold text-dark fs-5">+<?= number_format($todayConsulting) ?></span>
                        </div>
                        <div class="col-6 ps-3">
                            <span class="text-muted d-block small mb-1">이번 달 신청</span>
                            <span class="fw-bold text-dark fs-5"><?= number_format($monthConsulting) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- website visits card (PC vs Mobile) -->
        <div class="col-md-6 col-xl-6">
            <div class="card border-0 shadow-sm overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted fw-bold mb-1 small" style="letter-spacing: .5px;">웹사이트 방문자 수 (PC / 모바일)</h6>
                            <h2 class="display-6 fw-bold mb-0 text-success">
                                <?= number_format($totalPCVisits + $totalMobileVisits) ?><span class="fs-6 text-muted ms-1">회</span>
                                <span class="fs-6 fw-normal text-muted ms-2">(PC: <?= number_format($totalPCVisits) ?> / 모바일: <?= number_format($totalMobileVisits) ?>)</span>
                            </h2>
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#visitsModal">자세히 보기</button>
                    </div>
                    <div class="row g-2 border-top pt-3 mt-3">
                        <div class="col-6 border-end">
                            <span class="text-muted d-block small mb-1">오늘 방문</span>
                            <span class="fw-bold text-dark fs-5">+<?= number_format($todayPCVisits + $todayMobileVisits) ?></span>
                            <span class="text-muted small ms-1">(PC: <?= $todayPCVisits ?> / Mob: <?= $todayMobileVisits ?>)</span>
                        </div>
                        <div class="col-6 ps-3">
                            <span class="text-muted d-block small mb-1">이번 달 방문</span>
                            <span class="fw-bold text-dark fs-5"><?= number_format($monthPCVisits + $monthMobileVisits) ?></span>
                            <span class="text-muted small ms-1">(PC: <?= $monthPCVisits ?> / Mob: <?= $monthMobileVisits ?>)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Daily Trends & Region Stats -->
    <div class="row g-4 mb-4">
        <!-- Daily Trend (Line Chart) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-graph-up me-1 text-primary"></i> 최근 15일 접수 및 방문 추이</h5>
                </div>
                <div class="card-body p-4">
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Region Distribution (Donut Chart) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-geo-alt me-1 text-danger"></i> 거주지역별 통계 (1:1 예약)</h5>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div style="position: relative; height: 220px; width: 100%;" class="mb-3">
                        <canvas id="regionChart"></canvas>
                    </div>
                    <div class="text-center text-muted small mt-2">상담 예약 신청자의 거주지역 통계입니다.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Age Demographics & Recent list -->
    <div class="row g-4">
        <!-- Age Demographics (Bar Chart) -->
        <div class="col-lg-5 col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-people me-1 text-info"></i> 연령대별 통계 (1:1 예약)</h5>
                </div>
                <div class="card-body p-4">
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Inquiries Table -->
        <div class="col-lg-7 col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-list-stars me-1 text-warning"></i> 무료컨설팅 접수</h5>
                    <a href="<?= base_url('AdmMaster/inquiry/1') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">전체보기</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width: 500px;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">이름</th>
                                    <th>나이</th>
                                    <th>거주지역</th>
                                    <th>연락처</th>
                                    <th class="pe-4">등록일자</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentInquiries)): ?>
                                    <?php foreach ($recentInquiries as $item): ?>
                                        <tr style="cursor: pointer;" onclick="window.location.href='<?= base_url('AdmMaster/inquiry/1/view/'.$item['idx']) ?>'">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light text-dark rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px; font-weight: bold;">
                                                        <?= mb_substr($item['manager'] ?? '고', 0, 1) ?>
                                                    </div>
                                                    <span><?= esc($item['manager']) ?></span>
                                                </div>
                                            </td>
                                            <td><?= esc($item['company']) ?></td>
                                            <td>
                                                <span class="d-inline-block text-truncate" style="max-width: 150px;" title="<?= esc($item['location']) ?>">
                                                    <?= esc($item['location']) ?>
                                                </span>
                                            </td>
                                            <td><?= esc($item['tel']) ?></td>
                                            <td class="pe-4 text-muted small"><?= date('m-d H:i', strtotime($item['regdate'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                            접수된 예약 내역이 없습니다.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Render Charts -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Daily Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const trendData = <?= json_encode($dailyTrends) ?>;
    
    const labels = trendData.map(d => d.date);
    const consultingCounts = trendData.map(d => d.consulting);
    const pcVisits = trendData.map(d => d.pc_visits);
    const mobileVisits = trendData.map(d => d.mobile_visits);

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: '1:1 무료컨설팅 접수',
                    data: consultingCounts,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.03)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y'
                },
                {
                    label: 'PC 방문자 수',
                    data: pcVisits,
                    borderColor: '#fd7e14',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    tension: 0.3,
                    pointRadius: 3,
                    yAxisID: 'y1'
                },
                {
                    label: '모바일 방문자 수',
                    data: mobileVisits,
                    borderColor: '#198754',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.3,
                    pointRadius: 3,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        font: { size: 12, weight: 'bold' }
                    }
                },
                tooltip: {
                    padding: 10,
                    cornerRadius: 6
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '예약 신청 수 (건)'
                    },
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        borderDash: [5, 5]
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '방문자 수 (회)'
                    },
                    ticks: {
                        stepSize: 5,
                        precision: 0
                    },
                    grid: {
                        drawOnChartArea: false // only want the grid lines for one axis
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // 2. Region Chart (Donut)
    const regionCtx = document.getElementById('regionChart').getContext('2d');
    const regionData = <?= json_encode($regions) ?>;
    
    const regionLabels = Object.keys(regionData);
    const regionValues = Object.values(regionData);

    new Chart(regionCtx, {
        type: 'doughnut',
        data: {
            labels: regionLabels,
            datasets: [{
                data: regionValues,
                backgroundColor: [
                    '#0d6efd', '#20c997', '#ffc107', '#dc3545', '#fd7e14',
                    '#6f42c1', '#d63384', '#0dcaf0', '#adb5bd', '#6610f2',
                    '#198754', '#ff8c00', '#4b0082', '#ff1493', '#00ced1'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 8,
                        font: { size: 10 }
                    }
                }
            },
            cutout: '65%'
        }
    });

    // 3. Age Group Chart (Bar)
    const ageCtx = document.getElementById('ageChart').getContext('2d');
    const ageData = <?= json_encode($ageGroups) ?>;
    
    const ageLabels = Object.keys(ageData);
    const ageValues = Object.values(ageData);

    new Chart(ageCtx, {
        type: 'bar',
        data: {
            labels: ageLabels,
            datasets: [{
                label: '신청 수',
                data: ageValues,
                backgroundColor: 'rgba(13, 202, 240, 0.7)',
                borderColor: '#0dcaf0',
                borderWidth: 2,
                borderRadius: 6,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        borderDash: [5, 5]
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>

<!-- Visits Modal -->
<div class="modal fade" id="visitsModal" tabindex="-1" aria-labelledby="visitsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="visitsModalLabel">
                    <i class="bi bi-list-columns-reverse me-1 text-success"></i> 웹사이트 최근 방문 기록 (최근 100건)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive" style="max-height: 400px; border-radius: 12px; border: 1px solid #dee2e6;">
                    <table class="table table-hover align-middle mb-0 text-center" style="font-size: 0.85rem;">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width: 70px;">번호</th>
                                <th style="width: 120px;">접속 기기</th>
                                <th style="width: 140px;">IP 주소</th>
                                <th class="text-start">기기 정보 (User Agent)</th>
                                <th style="width: 150px;">방문 시간</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentVisits)): ?>
                                <tr>
                                    <td colspan="5" class="py-4 text-muted">방문 기록이 없습니다.</td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $num = count($recentVisits);
                                foreach ($recentVisits as $row): 
                                ?>
                                <tr>
                                    <td><?= $num-- ?></td>
                                    <td>
                                        <?php 
                                        $devType = strtolower(trim($row['device'] ?? ''));
                                        if ($devType === 'pc'): 
                                        ?>
                                            <span class="badge bg-primary">PC</span>
                                        <?php elseif ($devType === 'mobile'): ?>
                                            <span class="badge bg-success">MOBILE</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">UNKNOWN</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><code><?= esc($row['ip_address']) ?></code></td>
                                    <td class="text-start text-muted small" style="word-break: break-all; max-width: 320px;">
                                        <?= esc($row['device_sig']) ?>
                                    </td>
                                    <td class="text-muted small"><?= esc($row['created_at']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Styles overrides */
#contents {
    background-color: #f8f9fa !important;
}
.card {
    border-radius: 12px;
}
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}
</style>
<?= $this->endSection() ?>
