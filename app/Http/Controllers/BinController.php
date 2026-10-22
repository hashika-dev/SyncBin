<?php

namespace App\Http\Controllers;

use App\Models\Bin;
use App\Models\WasteItem;
use App\Models\RpiTelemetry;
use App\Models\Esp32Telemetry;
use App\Services\HardwareCryptoService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\UpdateBinTelemetryRequest;
use Illuminate\Support\Facades\Http;

class BinController extends Controller
{
    /**
     * Get all bins with their items.
     */
    public function index()
    {
        $bins = Bin::with(['items' => function($query) {
            $query->latest();
        }])->get();

        foreach ($bins as $bin) {
            if ($bin->level === 0 && $bin->zero_level_since) {
                $this->checkAndProcessAutoClear($bin);
            }
        }

        return response()->json($bins->fresh(['items' => function($query) {
            $query->latest();
        }])->keyBy('slug'));
    }

    /**
     * Simulate scanning a waste item.
     */
    public function simulateScan($slug)
    {
        $bin = Bin::where('slug', $slug)->firstOrFail();

        // Mock items repository to pick from
        $mockRepo = [
            'hazardous' => [
                ['name' => 'Used Battery', 'icon' => '🔋'],
                ['name' => 'Expired Medicine', 'icon' => '💊'],
                ['name' => 'Light Bulb', 'icon' => '💡'],
                ['name' => 'Aerosol Can', 'icon' => '🧴'],
                ['name' => 'Syringe', 'icon' => '💉'],
                ['name' => 'Paint Residue', 'icon' => '🎨'],
                ['name' => 'Thermometer', 'icon' => '🌡️']
            ],
            'recyclable' => [
                ['name' => 'Plastic Bottle', 'icon' => '🍼'],
                ['name' => 'Paper Box', 'icon' => '📄'],
                ['name' => 'Aluminum Can', 'icon' => '🥫'],
                ['name' => 'Glass Jar', 'icon' => '🫙'],
                ['name' => 'Magazine', 'icon' => '📖'],
                ['name' => 'Soda Can', 'icon' => '🥤'],
                ['name' => 'Cardboard Tube', 'icon' => '🧻']
            ],
            'biodegradable' => [
                ['name' => 'Banana Peel', 'icon' => '🍌'],
                ['name' => 'Apple Core', 'icon' => '🍎'],
                ['name' => 'Carrot Top', 'icon' => '🥕'],
                ['name' => 'Orange Peel', 'icon' => '🍊'],
                ['name' => 'Lettuce Scrap', 'icon' => '🥬'],
                ['name' => 'Coffee Grounds', 'icon' => '☕']
            ],
            'non-bio' => [
                ['name' => 'Plastic Wrap', 'icon' => '🍬'],
                ['name' => 'Styrofoam Piece', 'icon' => '📦'],
                ['name' => 'Broken Glass', 'icon' => '🍷'],
                ['name' => 'Packaging Film', 'icon' => '🛍️'],
                ['name' => 'Plastic Cutlery', 'icon' => '🍴'],
                ['name' => 'Chip Bag', 'icon' => '🍿'],
                ['name' => 'Mask', 'icon' => '😷']
            ]
        ];

        $pool = $mockRepo[$slug] ?? [];
        if (empty($pool)) {
            return response()->json(['error' => 'Invalid bin classification.'], 400);
        }

        // Pick a random mock item
        $itemTemplate = Arr::random($pool);

        // Add item to database
        $bin->items()->create([
            'name' => $itemTemplate['name'],
            'icon' => $itemTemplate['icon'],
        ]);

        // Increase fill level by random 5%-15%
        $increase = rand(5, 15);
        $bin->level = min(100, $bin->level + $increase);

        // Update Status
        if ($bin->level === 0) $bin->status = 'Empty';
        elseif ($bin->level < 30) $bin->status = 'Low';
        elseif ($bin->level < 60) $bin->status = 'Stable';
        elseif ($bin->level < 85) $bin->status = 'High';
        else $bin->status = 'Critical';

        $previousLevel = $bin->getOriginal('level') ?? 0;
        $bin->save();

        // Dispatch alert only when the bin first crosses the 85% critical capacity threshold
        if ($bin->level >= 85 && $previousLevel < 85) {
            $bin->alert_triggered_at = now();
            $this->dispatchCriticalAlert($bin);
        }

        return response()->json($bin->load(['items' => function($q) { $q->latest(); }]));
    }

    /**
     * Process AI Camera Vision scan payload (real hardware or dashboard simulator).
     */
    public function cameraScan(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:10240', // Max 10MB photo
            'item_name' => 'nullable|string',
            'bin_slug' => 'nullable|string',
            'confidence' => 'nullable|numeric',
            'bounding_box' => 'nullable|string',
        ]);

        // Waste item mapping registry
        $mapping = [
            'plastic bottle'       => ['slug' => 'recyclable', 'icon' => '🍼'],
            'plastic water bottle' => ['slug' => 'recyclable', 'icon' => '🍼'],
            'bottle'               => ['slug' => 'recyclable', 'icon' => '🍼'],
            'soda can'             => ['slug' => 'recyclable', 'icon' => '🥤'],
            'aluminum can'         => ['slug' => 'recyclable', 'icon' => '🥫'],
            'can'                  => ['slug' => 'recyclable', 'icon' => '🥫'],
            'cup'                  => ['slug' => 'recyclable', 'icon' => '🥤'],
            'paper box'            => ['slug' => 'recyclable', 'icon' => '📄'],
            'cardboard box'        => ['slug' => 'recyclable', 'icon' => '📦'],
            'box'                  => ['slug' => 'recyclable', 'icon' => '📦'],
            'paper'                => ['slug' => 'recyclable', 'icon' => '📄'],
            'glass jar'            => ['slug' => 'recyclable', 'icon' => '🫙'],

            'used battery'         => ['slug' => 'hazardous', 'icon' => '🔋'],
            'battery'              => ['slug' => 'hazardous', 'icon' => '🔋'],
            'expired medicine'     => ['slug' => 'hazardous', 'icon' => '💊'],
            'medicine'             => ['slug' => 'hazardous', 'icon' => '💊'],
            'light bulb'           => ['slug' => 'hazardous', 'icon' => '💡'],
            'aerosol can'          => ['slug' => 'hazardous', 'icon' => '🧴'],

            'onion'                => ['slug' => 'biodegradable', 'icon' => '🧅'],
            'banana peel'          => ['slug' => 'biodegradable', 'icon' => '🍌'],
            'banana'               => ['slug' => 'biodegradable', 'icon' => '🍌'],
            'apple core'           => ['slug' => 'biodegradable', 'icon' => '🍎'],
            'apple'                => ['slug' => 'biodegradable', 'icon' => '🍎'],
            'orange peel'          => ['slug' => 'biodegradable', 'icon' => '🍊'],
            'orange'               => ['slug' => 'biodegradable', 'icon' => '🍊'],
            'food scrap'           => ['slug' => 'biodegradable', 'icon' => '🥬'],
            'food'                 => ['slug' => 'biodegradable', 'icon' => '🍲'],
            'vegetable'            => ['slug' => 'biodegradable', 'icon' => '🥕'],
            'carrot'               => ['slug' => 'biodegradable', 'icon' => '🥕'],
            'potato'               => ['slug' => 'biodegradable', 'icon' => '🥔'],
            'tomato'               => ['slug' => 'biodegradable', 'icon' => '🍅'],
            'bread'                => ['slug' => 'biodegradable', 'icon' => '🍞'],
            'egg shell'            => ['slug' => 'biodegradable', 'icon' => '🥚'],
            'egg'                  => ['slug' => 'biodegradable', 'icon' => '🥚'],
            'leaf'                 => ['slug' => 'biodegradable', 'icon' => '🍃'],
            'plant'                => ['slug' => 'biodegradable', 'icon' => '🌱'],

            'styrofoam piece'      => ['slug' => 'non-bio', 'icon' => '📦'],
            'styrofoam'            => ['slug' => 'non-bio', 'icon' => '📦'],
            'plastic wrap'         => ['slug' => 'non-bio', 'icon' => '🍬'],
            'chip bag'             => ['slug' => 'non-bio', 'icon' => '🍿'],
            'face mask'            => ['slug' => 'non-bio', 'icon' => '😷'],
        ];

        $itemName = $request->input('item_name', 'Plastic Water Bottle');
        $lowerName = strtolower(trim($itemName));

        // 1. Direct match
        $matchedInfo = $mapping[$lowerName] ?? null;

        // 2. Partial / keyword match fallback
        if (!$matchedInfo) {
            foreach ($mapping as $key => $info) {
                if (str_contains($lowerName, $key) || str_contains($key, $lowerName)) {
                    $matchedInfo = $info;
                    break;
                }
            }
        }

        // 3. Fallback defaults
        if (!$matchedInfo) {
            $defaultSlug = $request->input('bin_slug', 'recyclable');
            $defaultIcon = match($defaultSlug) {
                'biodegradable' => '🌿',
                'hazardous'     => '⚠️',
                'non-bio'       => '🗑️',
                default         => '♻️',
            };
            $matchedInfo = [
                'slug' => $defaultSlug,
                'icon' => $defaultIcon,
            ];
        }

        $targetSlug = $request->input('bin_slug', $matchedInfo['slug']);
        $bin = Bin::where('slug', $targetSlug)->first() ?? Bin::first();

        // Handle uploaded photo or default simulation image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('scans', 'public');
            $imagePath = 'storage/' . $path;
        } else {
            $imagePath = $request->input('image_url', null);
        }

        $confidence = $request->input('confidence', rand(91, 99) + (rand(0, 9) / 10));
        $icon = $matchedInfo['icon'];

        $bBox = $request->input('bounding_box', json_encode([
            'x' => rand(15, 25),
            'y' => rand(15, 25),
            'width' => rand(50, 65),
            'height' => rand(50, 65)
        ]));

        // Create WasteItem
        $item = $bin->items()->create([
            'name' => ucwords($itemName),
            'icon' => $icon,
            'image_path' => $imagePath,
            'ai_confidence' => $confidence,
            'detection_label' => ucwords($itemName),
            'bounding_box' => $bBox,
        ]);

        // Increase fill level
        $increase = rand(5, 15);
        $previousLevel = $bin->level;
        $bin->level = min(100, $bin->level + $increase);

        if ($bin->level === 0) $bin->status = 'Empty';
        elseif ($bin->level < 30) $bin->status = 'Low';
        elseif ($bin->level < 60) $bin->status = 'Stable';
        elseif ($bin->level < 85) $bin->status = 'High';
        else $bin->status = 'Critical';

        $bin->save();

        // Alert dispatch if crossing 85%
        if ($bin->level >= 85 && $previousLevel < 85) {
            $bin->alert_triggered_at = now();
            $bin->save();
            $this->dispatchCriticalAlert($bin);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'AI scan ingested and categorized successfully.',
            'item' => $item,
            'bin' => $bin->load(['items' => function($q) { $q->latest(); }])
        ]);
    }

    /**
     * Empty a bin of all items and reset levels.
     */
    public function emptyBin($slug)
    {
        $bin = Bin::where('slug', $slug)->firstOrFail();

        $levelBefore = $bin->level > 0 ? $bin->level : ($bin->pre_empty_level > 0 ? $bin->pre_empty_level : 0);
        $responseTimeMinutes = $bin->alert_triggered_at ? (int) max(1, abs(now()->diffInMinutes($bin->alert_triggered_at))) : null;

        // Log clearance audit record
        \App\Models\BinClearanceLog::create([
            'bin_id' => $bin->id,
            'user_id' => auth()->id(),
            'cleared_by_email' => auth()->check() ? auth()->user()->email : 'admin@wastesync.com',
            'clearance_type' => 'manual',
            'level_before_clearance' => $levelBefore,
            'alert_triggered_at' => $bin->alert_triggered_at,
            'cleared_at' => now(),
            'response_time_minutes' => $responseTimeMinutes,
        ]);

        // Delete items
        $bin->items()->delete();

        // Reset levels and timestamps
        $bin->level = 0;
        $bin->status = 'Empty';
        $bin->alert_triggered_at = null;
        $bin->last_emptied_at = now();
        $bin->zero_level_since = now();
        $bin->pre_empty_level = 0;
        $bin->save();

        return response()->json($bin->load(['items' => function($q) { $q->latest(); }]));
    }

    /**
     * Export a PDF report of all bins and their logs.
     */
    public function exportPdf()
    {
        $bins = Bin::with(['items' => function($query) {
            $query->latest();
        }])->get();

        $totalItemsCount = WasteItem::count();

        // Calculate average fill level
        $averageFill = $bins->avg('level') ?? 0;

        // Most active bin
        $mostActiveBin = $bins->sortByDesc(function($bin) {
            return $bin->items->count();
        })->first();

        // Render PDF view
        $pdf = Pdf::loadView('reports.pdf', compact('bins', 'totalItemsCount', 'averageFill', 'mostActiveBin'));

        return $pdf->stream('EcoSync-Status-Report-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export waste history logs as a CSV file with active filters.
     */
    public function exportCsv(Request $request)
    {
        $query = WasteItem::with('bin');

        // Search by item name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Filter by bin slug / classification
        if ($request->filled('bin')) {
            $query->whereHas('bin', function($q) use ($request) {
                $q->where('slug', $request->input('bin'));
            });
        }

        // Quick select range handling
        if ($request->filled('quick_range')) {
            $range = $request->input('quick_range');
            if ($range === 'today') {
                $query->whereDate('created_at', now()->format('Y-m-d'));
            } elseif ($range === 'yesterday') {
                $query->whereDate('created_at', now()->subDay()->format('Y-m-d'));
            } elseif ($range === '7days') {
                $query->where('created_at', '>=', now()->subDays(6)->startOfDay());
            } elseif ($range === '30days') {
                $query->where('created_at', '>=', now()->subDays(29)->startOfDay());
            } elseif ($range === 'this_month') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            }
        } else {
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->input('from_date'));
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->input('to_date'));
            }
        }

        $logs = $query->latest()->get();
        $filename = 'EcoSync-Waste-Logs-' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['ID', 'Item Name', 'Icon', 'Bin Name', 'Classification', 'Logged Date & Time']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->name,
                    $log->icon,
                    $log->bin ? $log->bin->name : 'N/A',
                    $log->bin ? ucfirst($log->bin->slug) : 'N/A',
                    $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display a paginated audit log of waste history.
     */
    public function history(Request $request)
    {
        $query = WasteItem::with('bin');

        // Search by item name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Filter by bin slug / classification
        if ($request->filled('bin')) {
            $query->whereHas('bin', function($q) use ($request) {
                $q->where('slug', $request->input('bin'));
            });
        }

        // Quick select range handling
        if ($request->filled('quick_range')) {
            $range = $request->input('quick_range');
            if ($range === 'today') {
                $query->whereDate('created_at', now()->format('Y-m-d'));
            } elseif ($range === 'yesterday') {
                $query->whereDate('created_at', now()->subDay()->format('Y-m-d'));
            } elseif ($range === '7days') {
                $query->where('created_at', '>=', now()->subDays(6)->startOfDay());
            } elseif ($range === '30days') {
                $query->where('created_at', '>=', now()->subDays(29)->startOfDay());
            } elseif ($range === 'this_month') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            }
        } else {
            // Filter by From Date
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->input('from_date'));
            }

            // Filter by To Date
            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->input('to_date'));
            }
        }

        $logs = $query->latest()->paginate(10)->withQueryString();
        $bins = Bin::all();
        $clearanceLogs = \App\Models\BinClearanceLog::with('bin')->latest()->paginate(10, ['*'], 'clearance_page');
        $avgResponseTimeMinutes = round(\App\Models\BinClearanceLog::whereNotNull('response_time_minutes')->avg('response_time_minutes') ?? 0, 1);

        return view('dashboards.history', compact('logs', 'bins', 'clearanceLogs', 'avgResponseTimeMinutes'));
    }

    /**
     * Display dedicated visual reports dashboard with analytics.
     */
    public function reports()
    {
        $bins = Bin::with('items')->get();
        $totalItemsCount = WasteItem::count();
        $averageFill = $bins->avg('level') ?? 0;
        
        // Calculate recycling rate: (recyclable + biodegradable) / total items
        $recyclableCount = WasteItem::whereHas('bin', function($q) {
            $q->whereIn('slug', ['recyclable', 'biodegradable']);
        })->count();
        
        $recyclingRate = $totalItemsCount > 0 ? round(($recyclableCount / $totalItemsCount) * 100) : 0;
        
        // Most active bin
        $mostActiveBin = $bins->sortByDesc(function($bin) {
            return $bin->items->count();
        })->first();

        // Calculate counts of items segregated per day for the last 7 days
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $formattedDate = now()->subDays($i)->format('M d');
            
            $count = WasteItem::whereDate('created_at', $date)->count();
            
            $last7Days->put($formattedDate, $count);
        }
        
        $chartLabels = $last7Days->keys()->toArray();
        $chartData = $last7Days->values()->toArray();

        $clearanceLogs = \App\Models\BinClearanceLog::with('bin')->latest()->take(5)->get();
        $avgResponseTimeMinutes = round(\App\Models\BinClearanceLog::whereNotNull('response_time_minutes')->avg('response_time_minutes') ?? 0, 1);

        return view('dashboards.reports', compact('bins', 'totalItemsCount', 'averageFill', 'recyclingRate', 'mostActiveBin', 'chartLabels', 'chartData', 'clearanceLogs', 'avgResponseTimeMinutes'));
    }

    /**
     * Seed 30 days of mock history logs.
     */
    public function seedMockData()
    {
        // Delete all existing waste items
        WasteItem::query()->delete();

        // Mock items repository to pick from
        $mockRepo = [
            'hazardous' => [
                ['name' => 'Used Battery', 'icon' => '🔋'],
                ['name' => 'Expired Medicine', 'icon' => '💊'],
                ['name' => 'Light Bulb', 'icon' => '💡'],
                ['name' => 'Aerosol Can', 'icon' => '🧴'],
                ['name' => 'Syringe', 'icon' => '💉'],
                ['name' => 'Paint Residue', 'icon' => '🎨'],
                ['name' => 'Thermometer', 'icon' => '🌡️']
            ],
            'recyclable' => [
                ['name' => 'Plastic Bottle', 'icon' => '🍼'],
                ['name' => 'Paper Box', 'icon' => '📄'],
                ['name' => 'Aluminum Can', 'icon' => '🥫'],
                ['name' => 'Glass Jar', 'icon' => '🫙'],
                ['name' => 'Magazine', 'icon' => '📖'],
                ['name' => 'Soda Can', 'icon' => '🥤'],
                ['name' => 'Cardboard Tube', 'icon' => '🧻']
            ],
            'biodegradable' => [
                ['name' => 'Banana Peel', 'icon' => '🍌'],
                ['name' => 'Apple Core', 'icon' => '🍎'],
                ['name' => 'Carrot Top', 'icon' => '🥕'],
                ['name' => 'Orange Peel', 'icon' => '🍊'],
                ['name' => 'Lettuce Scrap', 'icon' => '🥬'],
                ['name' => 'Coffee Grounds', 'icon' => '☕']
            ],
            'non-bio' => [
                ['name' => 'Plastic Wrap', 'icon' => '🍬'],
                ['name' => 'Styrofoam Piece', 'icon' => '📦'],
                ['name' => 'Broken Glass', 'icon' => '🍷'],
                ['name' => 'Packaging Film', 'icon' => '🛍️'],
                ['name' => 'Plastic Cutlery', 'icon' => '🍴'],
                ['name' => 'Chip Bag', 'icon' => '🍿'],
                ['name' => 'Mask', 'icon' => '😷']
            ]
        ];

        $bins = Bin::all();
        $slugs = ['hazardous', 'recyclable', 'biodegradable', 'non-bio'];
        
        // Loop through past 30 days
        for ($i = 30; $i >= 0; $i--) {
            // Number of scans per day: 2 to 6
            $scansCount = rand(2, 6);
            for ($s = 0; $s < $scansCount; $s++) {
                $slug = Arr::random($slugs);
                $bin = $bins->where('slug', $slug)->first();
                if (!$bin) continue;
                
                $pool = $mockRepo[$slug] ?? [];
                if (empty($pool)) continue;
                
                $template = Arr::random($pool);
                
                // Create item in DB with subDays timestamp
                $createdAt = now()->subDays($i)->setHour(rand(8, 20))->setMinute(rand(0, 59))->setSecond(rand(0, 59));
                
                $bin->items()->create([
                    'name' => $template['name'],
                    'icon' => $template['icon'],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        // Set realistic current level offsets
        foreach ($bins as $bin) {
            $levels = [
                'hazardous' => rand(10, 30),
                'recyclable' => rand(35, 60),
                'biodegradable' => rand(65, 80),
                'non-bio' => rand(20, 45)
            ];
            $bin->level = $levels[$bin->slug] ?? rand(20, 50);
            
            if ($bin->level === 0) $bin->status = 'Empty';
            elseif ($bin->level < 30) $bin->status = 'Low';
            elseif ($bin->level < 60) $bin->status = 'Stable';
            elseif ($bin->level < 85) $bin->status = 'High';
            else $bin->status = 'Critical';
            
            $bin->save();
        }

        // Seed realistic evacuation audit logs
        \App\Models\BinClearanceLog::query()->delete();
        $sampleEmails = ['admin@wastesync.com', 'staff.mendoza@wastesync.com', 'superadmin@wastesync.com'];
        for ($k = 15; $k >= 1; $k--) {
            $bin = $bins->random();
            $triggeredAt = now()->subDays($k)->setHour(rand(9, 17))->setMinute(rand(0, 30));
            $responseTime = rand(5, 45); // 5 to 45 minutes response time
            $clearedAt = (clone $triggeredAt)->addMinutes($responseTime);

            \App\Models\BinClearanceLog::create([
                'bin_id' => $bin->id,
                'user_id' => null,
                'cleared_by_email' => Arr::random($sampleEmails),
                'level_before_clearance' => rand(85, 98),
                'alert_triggered_at' => $triggeredAt,
                'cleared_at' => $clearedAt,
                'response_time_minutes' => $responseTime,
                'created_at' => $clearedAt,
                'updated_at' => $clearedAt,
            ]);
        }

        // Seed live mock telemetry for both Raspberry Pi and ESP32 DevKitC
        RpiTelemetry::updateOrCreate(
            ['device_id' => 'rpi-primary'],
            [
                'cpu_temp'        => rand(42, 53) + (rand(0, 9) / 10),
                'cpu_load'        => rand(18, 38) + (rand(0, 9) / 10),
                'supply_voltage'  => 5.12,
                'memory_used_mb'  => 1420,
                'memory_total_mb' => 7851,
                'disk_used_gb'    => 14.2,
                'disk_total_gb'   => 59.1,
                'ip_address'      => '192.168.1.104',
                'uptime'          => '3 days, 14:22:05',
                'status'          => 'online',
                'updated_at'      => now(),
            ]
        );

        Esp32Telemetry::updateOrCreate(
            ['device_id' => 'NODE-01'],
            [
                'mac_address'     => 'A4:CF:12:6E:3B:10',
                'firmware'        => 'EcoSync-v2.1.4',
                'protocol'        => 'UART / Serial (115200)',
                'bus_status'      => '1k Nominal',
                'ip_address'      => '192.168.1.105',
                'wifi_rssi'       => -58,
                'free_heap_bytes' => 284160,
                'uptime'          => '5 days, 08:14:12',
                'status'          => 'online',
                'updated_at'      => now(),
            ]
        );

        return response()->json(['message' => 'Demo data seeded successfully.']);
    }

    /**
     * Clear all waste items history and reset bin levels.
     */
    public function clearHistory()
    {
        // Delete all waste items
        WasteItem::query()->delete();

        // Reset bin levels
        Bin::query()->update([
            'level' => 0,
            'status' => 'Empty',
            'last_emptied_at' => now(),
        ]);

        return redirect()->route('dashboard.history')->with('status', 'History logs cleared and bins evacuated successfully.');
    }

    /**
     * Display SuperAdmin hardware monitoring dashboard.
     */
    public function hardware(HardwareCryptoService $crypto)
    {
        $bins = Bin::all();
        $cryptoDiagnostic = $crypto->getDiagnosticInfo();

        // Hardware component diagnostic statuses
        $components = [
            [
                'name' => 'ESP32 Wi-Fi Microcontroller',
                'category' => 'Core Controller',
                'status' => 'Online',
                'health' => '100%',
                'signal' => '-58 dBm (Excellent)',
                'ip' => '192.168.1.105',
                'icon' => '⚡',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-900/40',
            ],
            [
                'name' => 'HC-SR04 Ultrasonic Sensors (x4)',
                'category' => 'Fill Level Sensors',
                'status' => 'Calibrated',
                'health' => '100%',
                'signal' => '40kHz Ultrasound',
                'ip' => 'Trig: 12, 27, 25, 32 | Echo: 13, 26, 33, 35',
                'icon' => '📡',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-900/40',
            ],
            [
                'name' => 'OV2640 Camera AI Module',
                'category' => 'Vision Classifier',
                'status' => 'Online',
                'health' => '99%',
                'signal' => '30 FPS / 1600x1200',
                'ip' => 'I2C / SPI Bus',
                'icon' => '📷',
                'badge' => 'bg-sky-100 text-sky-800 border-sky-200 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-900/40',
            ],
            [
                'name' => 'Crypto Security Engine (ECDSA / AES-256)',
                'category' => 'Payload Verification',
                'status' => $cryptoDiagnostic['status'],
                'health' => '100%',
                'signal' => 'secp256r1 & GCM',
                'ip' => 'OpenSSL Security Module',
                'icon' => '🔒',
                'badge' => 'bg-rose-100 text-rose-800 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-900/40',
            ],
            [
                'name' => 'Biodegradable Bin Servo Lid Actuator',
                'category' => 'Odor Seal Mechanism',
                'status' => 'Standby',
                'health' => '100%',
                'signal' => 'PWM 50Hz (GPIO 18)',
                'ip' => 'Single Lid Actuator',
                'icon' => '🔒',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-900/40',
            ],
            [
                'name' => 'Organic Waste Grinder / Shredder Motor',
                'category' => 'Compost Pre-Processor',
                'status' => 'Standby',
                'health' => '100%',
                'signal' => 'High-Torque 12V (Relay #3)',
                'ip' => 'Bio Chute Entrance',
                'icon' => '⚙️',
                'badge' => 'bg-orange-100 text-orange-800 border-orange-200 dark:bg-orange-950/40 dark:text-orange-300 dark:border-orange-900/40',
            ],
            [
                'name' => '12V 5A Regulated Power Supply',
                'category' => 'Power Unit',
                'status' => 'Stable',
                'health' => '100%',
                'signal' => '11.95 V Output',
                'ip' => 'DC Jack Input',
                'icon' => '🔌',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-900/40',
            ]
        ];

        return view('dashboards.hardware', compact('bins', 'components', 'cryptoDiagnostic'));
    }

    /**
     * Return the latest Raspberry Pi telemetry record as JSON (for live dashboard polling).
     *
     * Route: GET /api/hardware/rpi-telemetry
     * Auth:  Authenticated users only (middleware: auth)
     */
    public function getRpiTelemetry()
    {
        $telemetry = RpiTelemetry::latest_for('rpi-primary');

        if (!$telemetry) {
            return response()->json([
                'status'  => 'offline',
                'message' => 'No telemetry received from Raspberry Pi yet.',
                'data'    => null,
            ]);
        }

        // Mark as offline if last update was more than 30 seconds ago
        $isStale = $telemetry->updated_at->diffInSeconds(now()) > 30;

        return response()->json([
            'status' => $isStale ? 'stale' : 'live',
            'data'   => [
                'cpu_temp'          => round($telemetry->cpu_temp, 1),
                'cpu_load'          => round($telemetry->cpu_load, 1),
                'supply_voltage'    => round($telemetry->supply_voltage, 2),
                'memory_used_mb'    => round($telemetry->memory_used_mb),
                'memory_total_mb'   => round($telemetry->memory_total_mb),
                'memory_percent'    => $telemetry->memory_percent,
                'disk_used_gb'      => round($telemetry->disk_used_gb, 1),
                'disk_total_gb'     => round($telemetry->disk_total_gb, 1),
                'disk_percent'      => $telemetry->disk_percent,
                'ip_address'        => $telemetry->ip_address,
                'uptime'            => $telemetry->uptime,
                'last_seen'         => $telemetry->updated_at->diffForHumans(),
                'last_seen_iso'     => $telemetry->updated_at->toISOString(),
            ],
        ]);
    }

    /**
     * Ingest live hardware telemetry POSTed by the Raspberry Pi.
     *
     * Python Payload (form-encoded or JSON):
     * {
     *   "device_id":       "rpi-primary",    // optional
     *   "cpu_temp":        42.3,
     *   "cpu_load":        35.1,
     *   "supply_voltage":  5.11,
     *   "memory_used_mb":  1024,
     *   "memory_total_mb": 4096,
     *   "disk_used_gb":    8.2,
     *   "disk_total_gb":   32.0,
     *   "ip_address":      "192.168.1.50",
     *   "uptime":          "2 days, 4:12:03"
     * }
     *
     * Route: POST /api/hardware/rpi-telemetry
     * Auth:  None (public endpoint, throttle-protected at 120 req/min)
     */
    public function updateRpiTelemetry(Request $request)
    {
        $request->validate([
            'device_id'       => ['nullable', 'string', 'max:64'],
            'cpu_temp'        => ['nullable', 'numeric'],
            'cpu_load'        => ['nullable', 'numeric', 'min:0', 'max:100'],
            'supply_voltage'  => ['nullable', 'numeric', 'min:0'],
            'memory_used_mb'  => ['nullable', 'numeric', 'min:0'],
            'memory_total_mb' => ['nullable', 'numeric', 'min:0'],
            'disk_used_gb'    => ['nullable', 'numeric', 'min:0'],
            'disk_total_gb'   => ['nullable', 'numeric', 'min:0'],
            'ip_address'      => ['nullable', 'string', 'max:45'],
            'uptime'          => ['nullable', 'string', 'max:120'],
        ]);

        $deviceId = $request->input('device_id', 'rpi-primary');

        // Determine status from metrics
        $cpuTemp = (float) $request->input('cpu_temp', 0);
        $status  = $cpuTemp >= 80 ? 'warning' : 'online';

        // Upsert: update today's record for this device or insert a new one
        $telemetry = RpiTelemetry::updateOrCreate(
            ['device_id' => $deviceId],
            [
                'cpu_temp'        => $request->input('cpu_temp'),
                'cpu_load'        => $request->input('cpu_load'),
                'supply_voltage'  => $request->input('supply_voltage'),
                'memory_used_mb'  => $request->input('memory_used_mb'),
                'memory_total_mb' => $request->input('memory_total_mb'),
                'disk_used_gb'    => $request->input('disk_used_gb'),
                'disk_total_gb'   => $request->input('disk_total_gb'),
                'ip_address'      => $request->input('ip_address', $request->ip()),
                'uptime'          => $request->input('uptime'),
                'status'          => $status,
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Raspberry Pi telemetry updated.',
            'data'    => [
                'device_id'      => $telemetry->device_id,
                'cpu_temp'       => $telemetry->cpu_temp,
                'cpu_load'       => $telemetry->cpu_load,
                'supply_voltage' => $telemetry->supply_voltage,
                'status'         => $telemetry->status,
                'updated_at'     => $telemetry->updated_at?->toISOString(),
            ],
        ]);
    }

    /**
     * Return the latest ESP32 telemetry record as JSON (for live dashboard polling).
     *
     * Route: GET /api/hardware/esp32-telemetry
     * Auth:  Authenticated users only (middleware: auth)
     */
    public function getEsp32Telemetry()
    {
        $telemetry = Esp32Telemetry::latest_for('NODE-01');

        if (!$telemetry) {
            return response()->json([
                'status'  => 'offline',
                'message' => 'No telemetry received from ESP32 DevKitC yet.',
                'data'    => null,
            ]);
        }

        // Stale if > 30 seconds, offline if > 90 seconds
        $diffSeconds = abs($telemetry->updated_at->diffInSeconds(now()));
        $status = $diffSeconds <= 30 ? 'live' : ($diffSeconds <= 90 ? 'stale' : 'offline');

        return response()->json([
            'status' => $status,
            'data'   => [
                'device_id'     => $telemetry->device_id,
                'mac_address'   => $telemetry->mac_address ?? 'F4:65:0B:47:17:70',
                'firmware'      => $telemetry->firmware ?? 'EcoSync-v2.1.4',
                'protocol'      => $telemetry->protocol ?? 'Wi-Fi / HTTPS',
                'bus_status'    => $telemetry->bus_status ?? 'Connected',
                'ip_address'    => $telemetry->ip_address,
                'wifi_rssi'     => $telemetry->wifi_rssi,
                'free_heap_kb'  => $telemetry->free_heap_bytes ? round($telemetry->free_heap_bytes / 1024, 1) : null,
                'uptime'        => $telemetry->uptime,
                'last_seen'     => $telemetry->updated_at->diffForHumans(),
                'last_seen_iso' => $telemetry->updated_at->toISOString(),
            ],
        ]);
    }

    /**
     * Ingest live hardware telemetry POSTed by the ESP32 controller.
     *
     * Route: POST /api/hardware/esp32-telemetry
     * Auth:  None (public endpoint, throttle-protected at 120 req/min)
     */
    public function updateEsp32Telemetry(Request $request)
    {
        $request->validate([
            'device_id'       => ['nullable', 'string', 'max:64'],
            'mac_address'     => ['nullable', 'string', 'max:32'],
            'firmware'        => ['nullable', 'string', 'max:64'],
            'protocol'        => ['nullable', 'string', 'max:64'],
            'bus_status'      => ['nullable', 'string', 'max:64'],
            'ip_address'      => ['nullable', 'string', 'max:45'],
            'wifi_rssi'       => ['nullable', 'numeric'],
            'free_heap_bytes' => ['nullable', 'integer'],
            'uptime'          => ['nullable', 'string', 'max:120'],
        ]);

        $deviceId = $request->input('device_id', 'NODE-01');

        $telemetry = Esp32Telemetry::updateOrCreate(
            ['device_id' => $deviceId],
            [
                'mac_address'     => $request->input('mac_address', 'A4:CF:12:6E:3B:10'),
                'firmware'        => $request->input('firmware', 'EcoSync-v2.1.4'),
                'protocol'        => $request->input('protocol', 'UART / Serial (115200)'),
                'bus_status'      => $request->input('bus_status', '1k Nominal'),
                'ip_address'      => $request->input('ip_address', $request->ip()),
                'wifi_rssi'       => $request->input('wifi_rssi'),
                'free_heap_bytes' => $request->input('free_heap_bytes'),
                'uptime'          => $request->input('uptime'),
                'status'          => 'online',
                'updated_at'      => now(),
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'ESP32 telemetry updated.',
            'data'    => [
                'device_id'   => $telemetry->device_id,
                'mac_address' => $telemetry->mac_address,
                'status'      => $telemetry->status,
                'updated_at'  => $telemetry->updated_at?->toISOString(),
            ],
        ]);
    }

    /**
     * Proxy a single-frame camera snapshot from the Raspberry Pi.
     *
     * The Pi must be running the companion snapshot server (camera_server.py)
     * on port 5050. This endpoint triggers libcamera-still, captures one JPEG
     * frame, and returns it as a base64 data URI — no file storage needed.
     *
     * Route: POST /api/hardware/rpi-snapshot  (auth + superadmin only)
     */
    public function rpiSnapshot()
    {
        $cameraUrl = config('services.rpi.camera_url') ?: env('RPI_CAMERA_URL', 'https://camera.ecosync.me/snapshot');
        $telemetry = RpiTelemetry::latest_for('rpi-primary');
        $piIp      = $telemetry?->ip_address ?? 'camera.ecosync.me';

        try {
            $response = Http::timeout(25)->post($cameraUrl);

            if ($response->successful()) {
                $body = $response->json();
                if (($body['status'] ?? '') === 'error' || empty($body['image'])) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => $body['message'] ?? 'Camera server responded without image data.',
                    ], 502);
                }

                return response()->json([
                    'status'    => 'ok',
                    'image'     => $body['image'] ?? null,   // base64 data URI from Pi
                    'width'     => $body['width']  ?? null,
                    'height'    => $body['height'] ?? null,
                    'captured'  => $body['captured_at'] ?? now()->toISOString(),
                    'pi_ip'     => $piIp,
                ]);
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Snapshot server error: ' . ($response->json('message') ?? ('HTTP ' . $response->status())),
            ], 502);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Could not reach Pi snapshot server at ' . $cameraUrl . ' — ' . $e->getMessage(),
            ], 503);
        }
    }

    /**
     * Generate an ECDSA signature & AES-256-GCM encrypted payload demonstration.
     */
    public function cryptoDemo(HardwareCryptoService $crypto)
    {
        $keypair = $crypto->generateKeyPair();
        
        $payload = [
            'device_id' => 'ECOSYNC-ESP32-HW01',
            'bin_slug' => 'recyclable',
            'item_scanned' => 'Plastic Water Bottle',
            'confidence' => 98.4,
            'timestamp' => time(),
        ];

        $jsonPayload = json_encode($payload);

        // Sign payload with ECDSA Private Key
        $signature = $crypto->signPayload($jsonPayload, $keypair['private_key']);
        
        // Encrypt payload with AES-256-GCM
        $encrypted = $crypto->encryptPayload($payload);

        // Verify ECDSA signature with Public Key
        $isSignatureValid = $crypto->verifySignature($jsonPayload, $signature, $keypair['public_key']);

        // Decrypt AES-256-GCM payload
        $decryptedPayload = $crypto->decryptPayload($encrypted['ciphertext'], $encrypted['iv'], $encrypted['tag']);

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'ECDSA & AES-256-GCM Hardware Cryptography Verification Completed',
            'raw_payload' => $payload,
            'ecdsa' => [
                'algorithm' => $keypair['algorithm'],
                'public_key_pem' => $keypair['public_key'],
                'digital_signature_base64' => $signature,
                'signature_verification_passed' => $isSignatureValid,
            ],
            'aes_256_gcm' => [
                'cipher' => $encrypted['cipher'],
                'ciphertext' => $encrypted['ciphertext'],
                'initialization_vector_iv' => $encrypted['iv'],
                'auth_tag' => $encrypted['tag'],
                'decrypted_payload' => $decryptedPayload,
            ],
        ]);
    }

    /**
     * Ingest raw ultrasonic sensor distances (cm) from an ESP32 and convert to fill percentages.
     *
     * ESP32 Arduino Payload (form-encoded):
     *   hazardous=12&recyclable=8&non_bio=20
     *
     * Conversion formula per bin:
     *   fill% = clamp(round((1 - distance_cm / BIN_DEPTH_CM) * 100), 0, 100)
     *
     * Route: POST /api/update_bins  (matches the ESP32 sketch URL exactly)
     * Auth:  None (public endpoint, throttle-protected at 120 req/min)
     */
    public function updateBinsFromESP32(Request $request)
    {
        $request->validate([
            'hazardous'     => ['nullable', 'numeric', 'min:0'],
            'recyclable'    => ['nullable', 'numeric', 'min:0'],
            'non_bio'       => ['nullable', 'numeric', 'min:0'],
            'biodegradable' => ['nullable', 'numeric', 'min:0'],
            'wifi_rssi'     => ['nullable', 'numeric'],
            'mac_address'   => ['nullable', 'string', 'max:32'],
            'firmware'      => ['nullable', 'string', 'max:64'],
            'protocol'      => ['nullable', 'string', 'max:64'],
            'ip_address'    => ['nullable', 'string', 'max:45'],
            'node_id'       => ['nullable', 'string', 'max:64'],
        ]);

        // Configurable bin depth (cm) — set BIN_DEPTH_CM in .env (default: 30cm)
        $defaultBinDepthCm = max(1, (float) (config('bins.default_depth_cm') ?? env('BIN_DEPTH_CM', 60)));

        // Map POST field names → bin slugs
        $fieldToSlug = [
            'hazardous'     => 'hazardous',
            'recyclable'    => 'recyclable',
            'non_bio'       => 'non-bio',
            'biodegradable' => 'biodegradable',
        ];

        $results = [];

        foreach ($fieldToSlug as $field => $slug) {
            if (!$request->has($field)) {
                continue;
            }

            $bin = Bin::where('slug', $slug)->first();

            if (!$bin) {
                $results[$field] = ['status' => 'error', 'message' => "Bin '{$slug}' not found."];
                continue;
            }

            $distanceCm  = max(0, (float) $request->input($field));
            $previousLevel = $bin->level;

            // Convert distance → fill percentage
            // When distance = 0 cm (object right at sensor) → 100% full
            // When distance = binDepthCm (empty bin floor) → 0% full
            $binDepthCm  = $bin->chamber_height_cm ?? $defaultBinDepthCm;
            $fillPercent = (int) max(0, min(100, round((1 - $distanceCm / $binDepthCm) * 100)));
            $bin->level  = $fillPercent;

            // Recalculate status & zero-level debounce
            if ($bin->level === 0) {
                $bin->status = 'Empty';
                if ($previousLevel > 0) {
                    $bin->pre_empty_level = $previousLevel;
                }
                if ($bin->zero_level_since === null) {
                    $bin->zero_level_since = now();
                }
                $this->checkAndProcessAutoClear($bin);
            } else {
                $bin->zero_level_since = null;
                $bin->pre_empty_level  = $bin->level;

                if ($bin->level < 30)      $bin->status = 'Low';
                elseif ($bin->level < 60)  $bin->status = 'Stable';
                elseif ($bin->level < 85)  $bin->status = 'High';
                else                       $bin->status = 'Critical';
            }

            // Trigger critical alert when crossing 85% threshold
            if ($bin->level >= 85 && $previousLevel < 85) {
                $bin->alert_triggered_at = now();
                $this->dispatchCriticalAlert($bin);
            } elseif ($bin->level < 85 && $bin->level > 0 && $bin->alert_triggered_at) {
                $bin->alert_triggered_at = null;
            }

            $bin->save();

            $results[$field] = [
                'status'      => 'updated',
                'bin'         => $bin->slug,
                'distance_cm' => $distanceCm,
                'fill_pct'    => $bin->level,
                'state'       => $bin->status,
            ];
        }

        try {
            $deviceId = $request->input('node_id', $request->input('device_id', 'NODE-01'));
            Esp32Telemetry::updateOrCreate(
                ['device_id' => $deviceId],
                [
                    'mac_address' => $request->input('mac_address', 'F4:65:0B:47:17:70'),
                    'firmware'    => $request->input('firmware', 'EcoSync-v2.1.4'),
                    'protocol'    => $request->input('protocol', 'Wi-Fi / HTTPS'),
                    'bus_status'  => 'Connected',
                    'ip_address'  => $request->input('ip_address', $request->ip()),
                    'wifi_rssi'   => $request->input('wifi_rssi'),
                    'status'      => 'online',
                    'updated_at'  => now(),
                ]
            );
        } catch (\Throwable $e) {}

        return response()->json([
            'status'       => 'success',
            'message'      => 'ESP32 sensor data received and bins updated successfully.',
            'bin_depth_cm' => $defaultBinDepthCm,
            'updated'      => $results,
        ]);
    }

    /**
     * Ingest fill levels from 3 simple sensors in one POST.
     *
     * Simple Sensor Payload (form-encoded or JSON):
     * {
     *   "hazardous":  75,
     *   "recyclable": 40,
     *   "non_bio":    60
     * }
     *
     * Route: POST /api/sensor/levels
     * Auth:  None (public endpoint, throttle-protected)
     */
    public function updateSensorLevels(Request $request)
    {
        $request->validate([
            'hazardous'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'recyclable'    => ['nullable', 'numeric', 'min:0', 'max:100'],
            'non_bio'       => ['nullable', 'numeric', 'min:0', 'max:100'],
            'biodegradable' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        // Map incoming POST field names → bin slugs
        $fieldToSlug = [
            'hazardous'     => 'hazardous',
            'recyclable'    => 'recyclable',
            'non_bio'       => 'non-bio',
            'biodegradable' => 'biodegradable',
        ];

        $results = [];

        foreach ($fieldToSlug as $field => $slug) {
            // Skip fields not present in this request
            if (!$request->has($field)) {
                continue;
            }

            $bin = Bin::where('slug', $slug)->first();

            if (!$bin) {
                $results[$field] = ['status' => 'error', 'message' => "Bin '{$slug}' not found."];
                continue;
            }

            $previousLevel = $bin->level;
            $level = max(0, min(100, (int) round((float) $request->input($field))));

            $bin->level = $level;

            // Recalculate status & zero-level debounce
            if ($bin->level === 0) {
                $bin->status = 'Empty';
                if ($previousLevel > 0) {
                    $bin->pre_empty_level = $previousLevel;
                }
                if ($bin->zero_level_since === null) {
                    $bin->zero_level_since = now();
                }
                $this->checkAndProcessAutoClear($bin);
            } else {
                $bin->zero_level_since = null;
                $bin->pre_empty_level  = $bin->level;

                if ($bin->level < 30)      $bin->status = 'Low';
                elseif ($bin->level < 60)  $bin->status = 'Stable';
                elseif ($bin->level < 85)  $bin->status = 'High';
                else                       $bin->status = 'Critical';
            }

            // Trigger critical alert if crossing 85% threshold
            if ($bin->level >= 85 && $previousLevel < 85) {
                $bin->alert_triggered_at = now();
                $this->dispatchCriticalAlert($bin);
            } elseif ($bin->level < 85 && $bin->level > 0 && $bin->alert_triggered_at) {
                $bin->alert_triggered_at = null;
            }

            $bin->save();

            $results[$field] = [
                'status' => 'updated',
                'bin'    => $bin->slug,
                'level'  => $bin->level,
                'state'  => $bin->status,
            ];
        }

        try {
            Esp32Telemetry::updateOrCreate(
                ['device_id' => 'NODE-01'],
                [
                    'mac_address' => $request->input('mac_address', 'F4:65:0B:47:17:70'),
                    'firmware'    => $request->input('firmware', 'EcoSync-v2.1.4'),
                    'protocol'    => $request->input('protocol', 'Wi-Fi / HTTPS'),
                    'bus_status'  => 'Connected',
                    'ip_address'  => $request->ip(),
                    'wifi_rssi'   => $request->input('wifi_rssi'),
                    'status'      => 'online',
                    'updated_at'  => now(),
                ]
            );
        } catch (\Throwable $e) {}

        return response()->json([
            'status'  => 'success',
            'message' => 'Sensor levels received and bins updated successfully.',
            'updated' => $results,
        ]);
    }

    /**
     * Ingest real-time telemetry from physical ESP32 and ultrasonic/optical sensors.
     *
     * Expected Payload:
     * {
     *   "bin_id": 1,
     *   "distance_mm": 250,
     *   "fullness_percent": 75
     * }
     */
    public function updateTelemetry(UpdateBinTelemetryRequest $request)
    {
        $binIdentifier = $request->input('bin_id');

        // Look up bin by ID first, then by slug
        $bin = is_numeric($binIdentifier)
            ? Bin::find($binIdentifier)
            : Bin::where('slug', $binIdentifier)->first();

        if (!$bin) {
            $bin = Bin::where('id', $binIdentifier)->orWhere('slug', $binIdentifier)->first();
        }

        if (!$bin) {
            return response()->json([
                'status' => 'error',
                'message' => "Bin identifier '{$binIdentifier}' not found in system.",
            ], 404);
        }

        $previousLevel = $bin->level;

        // Resolve fullness level
        if ($request->has('fullness_percent')) {
            $level = (int) round((float) $request->input('fullness_percent'));
        } elseif ($request->has('level')) {
            $level = (int) round((float) $request->input('level'));
        } else {
            $level = $bin->level;
        }

        // Clamp between 0% and 100%
        $level = max(0, min(100, $level));
        $bin->level = $level;

        // Recalculate operational status & manage zero-level debounce
        if ($bin->level === 0) {
            $bin->status = 'Empty';
            if ($previousLevel > 0) {
                $bin->pre_empty_level = $previousLevel;
            }
            if ($bin->zero_level_since === null) {
                $bin->zero_level_since = now();
            }
            $this->checkAndProcessAutoClear($bin);
        } else {
            // Level is > 0: reset zero duration debounce
            $bin->zero_level_since = null;
            $bin->pre_empty_level = $bin->level;

            if ($bin->level < 30) {
                $bin->status = 'Low';
            } elseif ($bin->level < 60) {
                $bin->status = 'Stable';
            } elseif ($bin->level < 85) {
                $bin->status = 'High';
            } else {
                $bin->status = 'Critical';
            }
        }

        // Trigger critical threshold alert if crossing 85%
        if ($bin->level >= 85 && $previousLevel < 85) {
            $bin->alert_triggered_at = now();
            $this->dispatchCriticalAlert($bin);
        } elseif ($bin->level < 85 && $bin->level > 0 && $bin->alert_triggered_at) {
            $bin->alert_triggered_at = null;
        }

        $bin->save();

        try {
            Esp32Telemetry::updateOrCreate(
                ['device_id' => 'NODE-01'],
                [
                    'mac_address' => $request->input('mac_address', 'F4:65:0B:47:17:70'),
                    'firmware'    => $request->input('firmware', 'EcoSync-v2.1.4'),
                    'protocol'    => $request->input('protocol', 'Wi-Fi / HTTPS'),
                    'bus_status'  => 'Connected',
                    'ip_address'  => $request->ip(),
                    'wifi_rssi'   => $request->input('wifi_rssi'),
                    'status'      => 'online',
                    'updated_at'  => now(),
                ]
            );
        } catch (\Throwable $e) {}

        return response()->json([
            'status' => 'success',
            'message' => 'Telemetry ingested and bin status updated successfully.',
            'bin' => [
                'id' => $bin->id,
                'slug' => $bin->slug,
                'name' => $bin->name,
                'level' => $bin->level,
                'status' => $bin->status,
                'zero_level_since' => $bin->zero_level_since?->toISOString(),
                'distance_mm' => $request->input('distance_mm'),
                'updated_at' => $bin->updated_at?->toISOString(),
            ],
        ]);
    }

    /**
     * Check if bin has sustained 0% level for required duration and process auto-clearance.
     */
    public function checkAndProcessAutoClear(Bin $bin): bool
    {
        // Must be at 0% and have a recorded zero_level_since
        if ($bin->level !== 0 || !$bin->zero_level_since) {
            return false;
        }

        // Configurable duration (default: 5 minutes / 300 seconds)
        $requiredSeconds = (int) env('AUTOCLEAR_DURATION_SECONDS', 300);
        $elapsedSeconds = (int) abs(now()->diffInSeconds($bin->zero_level_since));

        if ($elapsedSeconds < $requiredSeconds) {
            return false; // Still within stabilization window
        }

        // Check if already auto-cleared for this zero-level cycle
        if ($bin->last_emptied_at && $bin->last_emptied_at->gte($bin->zero_level_since) && $bin->items()->count() === 0) {
            return false;
        }

        $levelBefore = $bin->pre_empty_level > 0 ? $bin->pre_empty_level : 100;
        $responseTimeMinutes = $bin->alert_triggered_at 
            ? (int) max(1, abs(now()->diffInMinutes($bin->alert_triggered_at))) 
            : null;

        // Log automated clearance audit record
        \App\Models\BinClearanceLog::create([
            'bin_id' => $bin->id,
            'user_id' => null,
            'cleared_by_email' => 'System (IoT Sensor Auto-Detect)',
            'clearance_type' => 'sensor_auto',
            'level_before_clearance' => $levelBefore,
            'alert_triggered_at' => $bin->alert_triggered_at,
            'cleared_at' => now(),
            'response_time_minutes' => $responseTimeMinutes,
        ]);

        // Delete items
        $bin->items()->delete();

        // Reset levels and timestamps
        $bin->level = 0;
        $bin->status = 'Empty';
        $bin->alert_triggered_at = null;
        $bin->last_emptied_at = now();
        $bin->pre_empty_level = 0;
        $bin->save();

        \Illuminate\Support\Facades\Log::info("AUTO-CLEAR: Bin {$bin->name} sustained 0% for {$elapsedSeconds}s. Automatically cleared.");

        return true;
    }

    /**
     * Dispatch email and SMS alerts when a bin reaches critical capacity.
     */
    protected function dispatchCriticalAlert(Bin $bin): void
    {
        try {
            $recipient = 'kurtumali06@gmail.com';
            \Illuminate\Support\Facades\Mail::send('emails.critical-alert', ['bin' => $bin], function ($message) use ($bin, $recipient) {
                $message->to($recipient)
                    ->subject("⚠️ CRITICAL ALERT: {$bin->name} has reached {$bin->level}% capacity!");
            });
            \Illuminate\Support\Facades\Log::info("ALERT: Email successfully queued and sent to {$recipient} for Bin {$bin->name} at {$bin->level}% capacity.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Mail delivery failed: " . $e->getMessage());
        }

        // Semaphore SMS Alert
        $semaphoreApiKey = config('services.semaphore.key');
        $alertPhoneNumber = config('services.semaphore.number');

        if (!empty($semaphoreApiKey) && !empty($alertPhoneNumber)) {
            try {
                $smsResponse = \Illuminate\Support\Facades\Http::post('https://api.semaphore.co/api/v4/messages', [
                    'apikey'  => $semaphoreApiKey,
                    'number'  => $alertPhoneNumber,
                    'message' => "CRITICAL ALERT: EcoSync {$bin->name} has reached {$bin->level}% capacity! Please evacuate the bin.",
                ]);

                if ($smsResponse->successful()) {
                    \Illuminate\Support\Facades\Log::info("Semaphore SMS alert sent successfully to {$alertPhoneNumber} for Bin {$bin->name}.");
                } else {
                    \Illuminate\Support\Facades\Log::warning("Semaphore SMS delivery failed: " . json_encode($smsResponse->json()));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Semaphore SMS error: " . $e->getMessage());
            }
        }
    }
}
