{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
    <style>
        .dashboard-page {
            --brand-start: #0d47a1;
            --brand-end: #1976d2;
        }

        .dashboard-page .page-header h2 {
            font-weight: 700;
            color: #1b2a4a;
        }

        .dashboard-page .page-header p {
            font-size: .95rem;
        }

        .dashboard-page .card {
            border: none;
            border-radius: .9rem;
            box-shadow: 0 2px 10px rgba(20, 30, 60, .06);
        }

        .dashboard-page .section-title {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
        }

        .dashboard-page .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            height: 42px;
            padding: 0 1.15rem;
            font-size: .9rem;
            font-weight: 600;
            border-radius: .55rem;
            white-space: nowrap;
            line-height: 1;
        }

        /* ---- Overview stats matrix ---- */
        .dashboard-page .stat-tile {
            background: #fff;
            border-radius: .9rem;
            box-shadow: 0 2px 10px rgba(20, 30, 60, .06);
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .9rem;
            height: 100%;
        }

        .dashboard-page .stat-tile .icon-wrap {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: .7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
        }

        .dashboard-page .stat-tile .stat-value {
            font-size: 1.45rem;
            font-weight: 700;
            color: #1b2a4a;
            line-height: 1.15;
        }

        .dashboard-page .stat-tile .stat-label {
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #6b7280;
            margin-top: .1rem;
        }

        .dashboard-page .icon-blue   { background: linear-gradient(135deg, #0d47a1, #1976d2); }
        .dashboard-page .icon-aqua   { background: linear-gradient(135deg, #0e9e6e, #1baf7a); }
        .dashboard-page .icon-orange { background: linear-gradient(135deg, #d1531f, #eb6834); }
        .dashboard-page .icon-violet { background: linear-gradient(135deg, #382b80, #4a3aa7); }
        .dashboard-page .icon-amber  { background: linear-gradient(135deg, #c98500, #eda100); }
        .dashboard-page .icon-red    { background: linear-gradient(135deg, #c53837, #e34948); }

        .dashboard-page .breakdown-card .card-body {
            padding: 1.25rem 1.35rem;
        }

        .dashboard-page .segmented-bar {
            display: flex;
            height: 14px;
            border-radius: 999px;
            overflow: hidden;
            background: #eef1f6;
            margin-bottom: 1rem;
            gap: 2px;
        }

        .dashboard-page .legend-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: .85rem;
            margin-bottom: .45rem;
        }

        .dashboard-page .legend-row:last-child {
            margin-bottom: 0;
        }

        .dashboard-page .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: .45rem;
        }

        .dashboard-page .breakdown-subhead {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #9aa5b6;
            margin-bottom: .6rem;
        }

        .dashboard-page .mini-bar-row {
            margin-bottom: .8rem;
        }

        .dashboard-page .mini-bar-row:last-child {
            margin-bottom: 0;
        }

        .dashboard-page .mini-bar-row .mini-bar-label {
            display: flex;
            justify-content: space-between;
            font-size: .82rem;
            font-weight: 600;
            color: #1b2a4a;
            margin-bottom: .3rem;
        }

        .dashboard-page .mini-bar-track {
            height: 10px;
            border-radius: 999px;
            background: #eef1f6;
            overflow: hidden;
        }

        .dashboard-page .mini-bar-fill {
            height: 100%;
            border-radius: 999px;
        }

        .dashboard-page .breakdown-divider {
            border-top: 1px dashed #e3e8f0;
            margin: 1rem 0;
        }

        /* ---- Chart cards ---- */
        .dashboard-page .chart-card .card-body {
            padding: 1.25rem 1.35rem;
        }

        .dashboard-page .chart-canvas-wrap {
            position: relative;
            height: 260px;
        }

        .dashboard-page .chart-canvas-wrap.chart-tall {
            height: 300px;
        }

        .dashboard-page .chart-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-4 dashboard-page">

        {{-- Page header --}}
        <div class="page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="mb-1"><i class="fa-solid fa-gauge-high text-primary me-2"></i>Dashboard</h2>
                <p class="text-muted mb-0">An overview of every certificate issued in the system.</p>
            </div>
            <div>
                <a href="{{ route('certificates.index') }}" class="btn btn-action btn-primary">
                    <i class="fa-solid fa-table-list"></i> View All Certificates
                </a>
            </div>
        </div>

        {{-- Overview stats matrix --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-blue"><i class="fa-solid fa-certificate"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['total']) }}</div>
                        <div class="stat-label">Certificates</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-aqua"><i class="fa-solid fa-map-location-dot"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['districts']) }}</div>
                        <div class="stat-label">Districts</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-orange"><i class="fa-solid fa-school"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['schools']) }}</div>
                        <div class="stat-label">Schools</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-violet"><i class="fa-solid fa-industry"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['sectors']) }}</div>
                        <div class="stat-label">Sectors</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-amber"><i class="fa-solid fa-briefcase"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['job_roles']) }}</div>
                        <div class="stat-label">Job Roles</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-tile">
                    <div class="icon-wrap icon-red"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <div class="stat-value">{{ number_format($stats['levels']) }}</div>
                        <div class="stat-label">NSQF Levels</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            {{-- Gender distribution --}}
            <div class="col-12 col-xl-4">
                <div class="card breakdown-card h-100">
                    <div class="card-body">
                        <div class="section-title mb-3"><i class="fa-solid fa-venus-mars me-1"></i> Gender
                            Distribution</div>

                        <div class="chart-canvas-wrap chart-tall">
                            @if ($stats['gender']->isNotEmpty())
                                <canvas id="genderChart"></canvas>
                            @else
                                <div class="chart-empty text-muted">No data available yet.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Class & NSQF level mix --}}
            <div class="col-12 col-xl-4">
                <div class="card breakdown-card h-100">
                    <div class="card-body">
                        <div class="section-title mb-3"><i class="fa-solid fa-layer-group me-1"></i> Class & Level
                            Mix</div>

                        @if ($stats['class']->isNotEmpty())
                            <div class="breakdown-subhead">Class / Std</div>
                            @foreach ($stats['class'] as $c)
                                <div class="mini-bar-row">
                                    <div class="mini-bar-label">
                                        <span>{{ $c['label'] }}</span>
                                        <span>{{ number_format($c['count']) }}</span>
                                    </div>
                                    <div class="mini-bar-track">
                                        <div class="mini-bar-fill"
                                            style="width: {{ $c['pct'] }}%; background: linear-gradient(90deg, #0d47a1, #1976d2);">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if ($stats['class']->isNotEmpty() && $stats['level']->isNotEmpty())
                            <div class="breakdown-divider"></div>
                        @endif

                        @if ($stats['level']->isNotEmpty())
                            <div class="breakdown-subhead">NSQF Level</div>
                            @foreach ($stats['level'] as $l)
                                <div class="mini-bar-row">
                                    <div class="mini-bar-label">
                                        <span>{{ $l['label'] }}</span>
                                        <span>{{ number_format($l['count']) }}</span>
                                    </div>
                                    <div class="mini-bar-track">
                                        <div class="mini-bar-fill"
                                            style="width: {{ $l['pct'] }}%; background: linear-gradient(90deg, #0aa5c0, #0dcaf0);">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        @if ($stats['class']->isEmpty() && $stats['level']->isEmpty())
                            <p class="text-muted mb-0">No data available yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Top districts --}}
            <div class="col-12 col-xl-4">
                <div class="card breakdown-card h-100">
                    <div class="card-body">
                        <div class="section-title mb-3"><i class="fa-solid fa-ranking-star me-1"></i> Top Districts
                        </div>
                        @forelse ($stats['top_districts'] as $d)
                            <div class="mini-bar-row">
                                <div class="mini-bar-label">
                                    <span>{{ $d['label'] }}</span>
                                    <span>{{ number_format($d['count']) }}</span>
                                </div>
                                <div class="mini-bar-track">
                                    <div class="mini-bar-fill"
                                        style="width: {{ $d['pct'] }}%; background: linear-gradient(90deg, #0d47a1, #1976d2);">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No data available yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart visualizations --}}
        <div class="row g-3 mb-4">
            {{-- Certificates issued over time --}}
            <div class="col-12 col-xl-3">
                <div class="card chart-card h-100">
                    <div class="card-body">
                        <div class="section-title mb-3"><i class="fa-solid fa-chart-line me-1"></i> Certificates
                            Issued Over Time</div>
                        <div class="chart-canvas-wrap chart-tall">
                            @if ($stats['monthly_trend']->isNotEmpty())
                                <canvas id="trendChart"></canvas>
                            @else
                                <div class="chart-empty text-muted">No data available yet.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Job role split --}}
            <div class="col-12 col-xl-3">
                <div class="card chart-card h-100">
                    <div class="card-body">
                        <div class="section-title mb-3"><i class="fa-solid fa-chart-pie me-1"></i> Job Role Split
                        </div>
                        <div class="chart-canvas-wrap chart-tall">
                            @if ($stats['job_role_split']->isNotEmpty())
                                <canvas id="jobRoleChart"></canvas>
                            @else
                                <div class="chart-empty text-muted">No data available yet.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top schools --}}
            <div class="col-12 col-xl-6">
                <div class="card chart-card h-100">
                    <div class="card-body">
                        <div class="section-title mb-3"><i class="fa-solid fa-school-flag me-1"></i> Top Schools by
                            Certificates</div>
                        @forelse ($stats['top_schools'] as $s)
                            <div class="mini-bar-row">
                                <div class="mini-bar-label">
                                    <span>{{ $s['label'] }}</span>
                                    <span>{{ number_format($s['count']) }}</span>
                                </div>
                                <div class="mini-bar-track">
                                    <div class="mini-bar-fill"
                                        style="width: {{ $s['pct'] }}%; background: linear-gradient(90deg, #382b80, #4a3aa7);">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No data available yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>
        (function () {
            Chart.register(ChartDataLabels);

            const inkSecondary = '#52514e';
            const inkMuted = '#898781';
            const gridline = '#e1e0d9';

            const trendData = @json($stats['monthly_trend']);
            const jobRoleData = @json($stats['job_role_split']);
            const genderData = @json($stats['gender']);

            if (genderData.length) {
                const genderColors = { male: '#2a78d6', female: '#eb6834' };
                new Chart(document.getElementById('genderChart'), {
                    type: 'doughnut',
                    data: {
                        labels: genderData.map(d => d.label),
                        datasets: [{
                            data: genderData.map(d => d.count),
                            backgroundColor: genderData.map(d => genderColors[d.label.toLowerCase()] ?? '#6c757d'),
                            borderColor: '#fff',
                            borderWidth: 2,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '62%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: inkSecondary, usePointStyle: true, boxWidth: 8, padding: 12 },
                            },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#1b2a4a',
                                bodyColor: inkSecondary,
                                borderColor: gridline,
                                borderWidth: 1,
                                padding: 10,
                                callbacks: {
                                    label: (item) => {
                                        const total = item.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = total ? Math.round(item.raw / total * 100) : 0;
                                        return ` ${item.label}: ${item.raw.toLocaleString()} (${pct}%)`;
                                    },
                                },
                            },
                            datalabels: {
                                color: '#fff',
                                font: { weight: 700, size: 13 },
                                formatter: (value, ctx) => {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total ? Math.round(value / total * 100) : 0;
                                    return `${value.toLocaleString()}\n(${pct}%)`;
                                },
                            },
                        },
                    },
                });
            }

            if (trendData.length) {
                new Chart(document.getElementById('trendChart'), {
                    type: 'line',
                    data: {
                        labels: trendData.map(d => d.label),
                        datasets: [{
                            label: 'Certificates issued',
                            data: trendData.map(d => d.count),
                            borderColor: '#2a78d6',
                            backgroundColor: 'rgba(42, 120, 214, 0.10)',
                            borderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#2a78d6',
                            pointBorderColor: '#fcfcfb',
                            pointBorderWidth: 2,
                            tension: 0.3,
                            fill: true,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#1b2a4a',
                                bodyColor: inkSecondary,
                                borderColor: gridline,
                                borderWidth: 1,
                                padding: 10,
                                displayColors: false,
                            },
                            datalabels: { display: false },
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: inkMuted } },
                            y: {
                                beginAtZero: true,
                                grid: { color: gridline },
                                ticks: { color: inkMuted, precision: 0 },
                            },
                        },
                    },
                });
            }

            if (jobRoleData.length) {
                const palette = ['#2a78d6', '#eb6834', '#1baf7a', '#eda100', '#e87ba4', '#4a3aa7'];
                new Chart(document.getElementById('jobRoleChart'), {
                    type: 'doughnut',
                    data: {
                        labels: jobRoleData.map(d => d.label),
                        datasets: [{
                            data: jobRoleData.map(d => d.count),
                            backgroundColor: palette,
                            borderColor: '#fff',
                            borderWidth: 2,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '62%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: inkSecondary, usePointStyle: true, boxWidth: 8, padding: 12 },
                            },
                            tooltip: {
                                backgroundColor: '#fff',
                                titleColor: '#1b2a4a',
                                bodyColor: inkSecondary,
                                borderColor: gridline,
                                borderWidth: 1,
                                padding: 10,
                            },
                            datalabels: { display: false },
                        },
                    },
                });
            }
        })();
    </script>
@endsection
