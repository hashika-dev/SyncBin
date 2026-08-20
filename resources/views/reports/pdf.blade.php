<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>EcoSync System Status Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            border-bottom: 2px solid #a7f3d0;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .header-title {
            font-size: 26px;
            font-weight: bold;
            color: #065f46;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .header-subtitle {
            font-size: 12px;
            color: #059669;
            margin: 5px 0 0 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .meta-table {
            width: 100%;
            margin-top: 10px;
        }
        .meta-table td {
            padding: 0;
            font-size: 11px;
            color: #64748b;
        }
        .meta-right {
            text-align: right;
        }
        .cards-container {
            margin-bottom: 30px;
        }
        .card {
            width: 30%;
            float: left;
            background: #ecfdf5;
            border: 1px solid #d1fae5;
            border-radius: 12px;
            padding: 15px;
            margin-right: 3%;
        }
        .card-last {
            margin-right: 0;
        }
        .card-title {
            font-size: 10px;
            text-transform: uppercase;
            color: #047857;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .card-val {
            font-size: 20px;
            font-weight: bold;
            color: #064e3b;
        }
        .clear {
            clear: both;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #065f46;
            margin: 25px 0 12px 0;
            border-left: 3px solid #10b981;
            padding-left: 8px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background: #ffffff;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #334155;
            text-align: left;
            padding: 10px;
            font-weight: bold;
            font-size: 11px;
            border-bottom: 2px solid #cbd5e1;
        }
        .data-table td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-red { background-color: #fee2e2; color: #991b1b; }
        .badge-sky { background-color: #e0f2fe; color: #075985; }
        .badge-emerald { background-color: #d1fae5; color: #065f46; }
        .badge-orange { background-color: #ffedd5; color: #9a3412; }
        
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
        }
        .status-critical { background-color: #fca5a5; color: #7f1d1d; }
        .status-high { background-color: #fed7aa; color: #7c2d12; }
        .status-stable { background-color: #a7f3d0; color: #064e3b; }
        .status-empty { background-color: #e2e8f0; color: #334155; }
        
        .footer {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="meta-table">
            <tr>
                <td>
                    <h1 class="header-title">EcoSync</h1>
                    <div class="header-subtitle">System Status Report</div>
                </td>
                <td class="meta-right">
                    <strong>Generated on:</strong> {{ now()->format('F d, Y h:i A') }}<br>
                    <strong>Operator:</strong> {{ Auth::user()->email ?? 'System Administrator' }}<br>
                    <strong>Environment:</strong> EcoSync Live Hub
                </td>
            </tr>
        </table>
    </div>

    <div class="cards-container">
        <div class="card">
            <div class="card-title">Total Segregated Items</div>
            <div class="card-val">{{ $totalItemsCount }} items</div>
        </div>
        <div class="card">
            <div class="card-title">Average Capacity Fill</div>
            <div class="card-val">{{ number_format($averageFill, 1) }}%</div>
        </div>
        <div class="card card-last">
            <div class="card-title">Most Active Classification</div>
            <div class="card-val">
                {{ $mostActiveBin ? $mostActiveBin->name : 'N/A' }}
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="section-title">Waste Segregation Compartment Breakdown</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Bin Category</th>
                <th style="width: 25%;">Subtitle</th>
                <th style="width: 15%; text-align: right;">Capacity Level</th>
                <th style="width: 15%; text-align: center;">Status</th>
                <th style="width: 20%;">Last Evacuated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bins as $bin)
                <tr>
                    <td>
                        <span class="badge badge-{{ $bin->color }}">{{ $bin->name }}</span>
                    </td>
                    <td>{{ $bin->subtitle }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $bin->level }}%</td>
                    <td style="text-align: center;">
                        @php
                            $statusClass = 'status-empty';
                            if (strtolower($bin->status) === 'critical') $statusClass = 'status-critical';
                            elseif (strtolower($bin->status) === 'high') $statusClass = 'status-high';
                            elseif (strtolower($bin->status) === 'stable' || strtolower($bin->status) === 'low') $statusClass = 'status-stable';
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $bin->status }}</span>
                    </td>
                    <td>{{ $bin->last_emptied_at instanceof \Carbon\Carbon ? $bin->last_emptied_at->format('M d, g:i A') : ($bin->last_emptied_at ? date('M d, g:i A', strtotime($bin->last_emptied_at)) : 'Never') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Recent Segregated Items Log (Audit Trail)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20%;">Timestamp</th>
                <th style="width: 25%;">Bin Category</th>
                <th style="width: 10%; text-align: center;">Icon</th>
                <th style="width: 30%;">Item Name</th>
                <th style="width: 15%; text-align: right;">Weight</th>
            </tr>
        </thead>
        <tbody>
            @php
                $recentItems = \App\Models\WasteItem::with('bin')->latest()->take(15)->get();
            @endphp
            @forelse($recentItems as $item)
                <tr>
                    <td>{{ $item->created_at->format('h:i:s A') }}</td>
                    <td>
                        <span class="badge badge-{{ $item->bin->color ?? 'slate' }}">{{ $item->bin->name ?? 'N/A' }}</span>
                    </td>
                    <td style="text-align: center; font-size: 14px;">{{ $item->icon }}</td>
                    <td style="font-weight: bold; color: #334155;">{{ $item->name }}</td>
                    <td style="text-align: right; color: #64748b;">{{ $item->weight }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">
                        No simulated scan history logs available in this session database.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>EcoSync Capstone Exhibition • Design and Hardware Prototype segregation reporting system</p>
        <p style="font-size: 8px; color: #cbd5e1;">Generated by automated software. Confidential. Page 1 of 1</p>
    </div>
</body>
</html>
