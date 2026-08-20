<?php

namespace App\Http\Controllers;

use App\Models\Bin;
use App\Models\WasteItem;
use App\Services\HardwareCryptoService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;

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

        return response()->json($bins->keyBy('slug'));
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
                ['name' => 'Used Battery', 'icon' => '🔋', 'weight' => '80g'],
                ['name' => 'Expired Medicine', 'icon' => '💊', 'weight' => '15g'],
                ['name' => 'Light Bulb', 'icon' => '💡', 'weight' => '120g'],
                ['name' => 'Aerosol Can', 'icon' => '🧴', 'weight' => '150g'],
                ['name' => 'Syringe', 'icon' => '💉', 'weight' => '10g'],
                ['name' => 'Paint Residue', 'icon' => '🎨', 'weight' => '200g'],
                ['name' => 'Thermometer', 'icon' => '🌡️', 'weight' => '30g']
            ],
            'recyclable' => [
                ['name' => 'Plastic Bottle', 'icon' => '🍼', 'weight' => '120g'],
                ['name' => 'Paper Box', 'icon' => '📄', 'weight' => '200g'],
                ['name' => 'Aluminum Can', 'icon' => '🥫', 'weight' => '50g'],
                ['name' => 'Glass Jar', 'icon' => '🫙', 'weight' => '85g'],
                ['name' => 'Magazine', 'icon' => '📖', 'weight' => '110g'],
                ['name' => 'Soda Can', 'icon' => '🥤', 'weight' => '45g'],
                ['name' => 'Cardboard Tube', 'icon' => '🧻', 'weight' => '30g']
            ],
            'biodegradable' => [
                ['name' => 'Banana Peel', 'icon' => '🍌', 'weight' => '45g'],
                ['name' => 'Apple Core', 'icon' => '🍎', 'weight' => '30g'],
                ['name' => 'Carrot Top', 'icon' => '🥕', 'weight' => '15g'],
                ['name' => 'Orange Peel', 'icon' => '🍊', 'weight' => '20g'],
                ['name' => 'Lettuce Scrap', 'icon' => '🥬', 'weight' => '15g'],
                ['name' => 'Coffee Grounds', 'icon' => '☕', 'weight' => '40g']
            ],
            'non-bio' => [
                ['name' => 'Plastic Wrap', 'icon' => '🍬', 'weight' => '10g'],
                ['name' => 'Styrofoam Piece', 'icon' => '📦', 'weight' => '25g'],
                ['name' => 'Broken Glass', 'icon' => '🍷', 'weight' => '60g'],
                ['name' => 'Packaging Film', 'icon' => '🛍️', 'weight' => '15g'],
                ['name' => 'Plastic Cutlery', 'icon' => '🍴', 'weight' => '12g'],
                ['name' => 'Chip Bag', 'icon' => '🍿', 'weight' => '8g'],
                ['name' => 'Mask', 'icon' => '😷', 'weight' => '5g']
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
            'weight' => $itemTemplate['weight']
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
            $bin->save();
            try {
                $recipient = 'kurtumali06@gmail.com';
                \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($bin, $recipient) {
                    $message->to($recipient)
                        ->subject("⚠️ CRITICAL ALERT: {$bin->name} has reached {$bin->level}% capacity!")
                        ->html("
                            <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #f43f5e; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);'>
                                <div style='background-color: #f43f5e; padding: 24px; text-align: center; color: white;'>
                                    <h1 style='margin: 0; font-size: 24px; font-weight: 900;'>EcoSync Alert</h1>
                                    <p style='margin: 8px 0 0; font-size: 14px; opacity: 0.9;'>Critical Waste Capacity Reached</p>
                                </div>
                                <div style='padding: 32px; background-color: #ffffff; color: #18181b;'>
                                    <p style='font-size: 16px; line-height: 1.6; margin-top: 0;'>Hello Administrator,</p>
                                    <p style='font-size: 15px; line-height: 1.6;'>This is an automated alert from the EcoSync system monitor. The following waste classification bin is approaching critical capacity and requires evacuation:</p>
                                    
                                    <div style='background-color: #fff1f2; border: 1px solid #ffe4e6; border-radius: 12px; padding: 20px; margin: 24px 0;'>
                                        <table style='width: 100%; border-collapse: collapse;'>
                                            <tr>
                                                <td style='font-weight: bold; width: 120px; padding: 8px 0;'>Bin Type:</td>
                                                <td style='padding: 8px 0;'>{$bin->name}</td>
                                            </tr>
                                            <tr>
                                                <td style='font-weight: bold; padding: 8px 0;'>Fill Level:</td>
                                                <td style='padding: 8px 0; color: #e11d48; font-weight: bold;'>{$bin->level}% (Critical)</td>
                                            </tr>
                                            <tr>
                                                <td style='font-weight: bold; padding: 8px 0;'>Status:</td>
                                                <td style='padding: 8px 0;'><span style='background-color: #ef4444; color: white; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: bold; text-transform: uppercase;'>{$bin->status}</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                    
                                    <p style='font-size: 14px; line-height: 1.6; color: #71717a;'>To reset this alert, please physically clear the bin and click \"Empty Bin\" on the system control panel dashboard.</p>
                                    
                                    <div style='text-align: center; margin-top: 32px;'>
                                        <a href='http://127.0.0.1:8000/dashboard' style='background-color: #f43f5e; color: white; text-decoration: none; padding: 14px 28px; font-weight: bold; border-radius: 12px; display: inline-block;'>Access Dashboard</a>
                                    </div>
                                </div>
                                <div style='background-color: #f4f4f5; padding: 16px; text-align: center; font-size: 11px; color: #71717a; border-top: 1px solid #e4e4e7;'>
                                    This is a system generated notification. Please do not reply directly to this message.
                                </div>
                            </div>
                        ");
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
            'weight' => 'nullable|string',
            'bounding_box' => 'nullable|string',
        ]);

        // Waste item mapping registry
        $mapping = [
            'plastic bottle' => ['slug' => 'recyclable', 'icon' => '🍼', 'weight' => '120g'],
            'plastic water bottle' => ['slug' => 'recyclable', 'icon' => '🍼', 'weight' => '120g'],
            'soda can' => ['slug' => 'recyclable', 'icon' => '🥤', 'weight' => '45g'],
            'aluminum can' => ['slug' => 'recyclable', 'icon' => '🥫', 'weight' => '50g'],
            'paper box' => ['slug' => 'recyclable', 'icon' => '📄', 'weight' => '200g'],
            'cardboard box' => ['slug' => 'recyclable', 'icon' => '📦', 'weight' => '250g'],
            'glass jar' => ['slug' => 'recyclable', 'icon' => '🫙', 'weight' => '85g'],

            'used battery' => ['slug' => 'hazardous', 'icon' => '🔋', 'weight' => '80g'],
            'battery' => ['slug' => 'hazardous', 'icon' => '🔋', 'weight' => '80g'],
            'expired medicine' => ['slug' => 'hazardous', 'icon' => '💊', 'weight' => '15g'],
            'light bulb' => ['slug' => 'hazardous', 'icon' => '💡', 'weight' => '120g'],
            'aerosol can' => ['slug' => 'hazardous', 'icon' => '🧴', 'weight' => '150g'],

            'banana peel' => ['slug' => 'biodegradable', 'icon' => '🍌', 'weight' => '45g'],
            'apple core' => ['slug' => 'biodegradable', 'icon' => '🍎', 'weight' => '30g'],
            'orange peel' => ['slug' => 'biodegradable', 'icon' => '🍊', 'weight' => '20g'],
            'food scrap' => ['slug' => 'biodegradable', 'icon' => '🥬', 'weight' => '35g'],

            'styrofoam piece' => ['slug' => 'non-bio', 'icon' => '📦', 'weight' => '25g'],
            'plastic wrap' => ['slug' => 'non-bio', 'icon' => '🍬', 'weight' => '10g'],
            'chip bag' => ['slug' => 'non-bio', 'icon' => '🍿', 'weight' => '8g'],
            'face mask' => ['slug' => 'non-bio', 'icon' => '😷', 'weight' => '5g'],
        ];

        $itemName = $request->input('item_name', 'Plastic Water Bottle');
        $lowerName = strtolower(trim($itemName));

        $matchedInfo = $mapping[$lowerName] ?? [
            'slug' => $request->input('bin_slug', 'recyclable'),
            'icon' => '♻️',
            'weight' => $request->input('weight', '50g')
        ];

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
        $weight = $request->input('weight', $matchedInfo['weight']);
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
            'weight' => $weight,
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

        $levelBefore = $bin->level;
        $responseTimeMinutes = $bin->alert_triggered_at ? (int) max(1, now()->diffInMinutes($bin->alert_triggered_at)) : null;

        // Log clearance audit record
        \App\Models\BinClearanceLog::create([
            'bin_id' => $bin->id,
            'user_id' => auth()->id(),
            'cleared_by_email' => auth()->check() ? auth()->user()->email : 'admin@wastesync.com',
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
            fputcsv($file, ['ID', 'Item Name', 'Icon', 'Bin Name', 'Classification', 'Weight', 'Logged Date & Time']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->name,
                    $log->icon,
                    $log->bin ? $log->bin->name : 'N/A',
                    $log->bin ? ucfirst($log->bin->slug) : 'N/A',
                    $log->weight,
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
        
        // Calculate total weight (weights are stored as strings like "120g", "15g", let's extract integer values)
        $totalWeightG = WasteItem::all()->reduce(function($carry, $item) {
            return $carry + (int) filter_var($item->weight, FILTER_SANITIZE_NUMBER_INT);
        }, 0);
        $totalWeightKg = round($totalWeightG / 1000, 2);

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

        return view('dashboards.reports', compact('bins', 'totalItemsCount', 'averageFill', 'recyclingRate', 'totalWeightKg', 'mostActiveBin', 'chartLabels', 'chartData', 'clearanceLogs', 'avgResponseTimeMinutes'));
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
                ['name' => 'Used Battery', 'icon' => '🔋', 'weight' => '80g'],
                ['name' => 'Expired Medicine', 'icon' => '💊', 'weight' => '15g'],
                ['name' => 'Light Bulb', 'icon' => '💡', 'weight' => '120g'],
                ['name' => 'Aerosol Can', 'icon' => '🧴', 'weight' => '150g'],
                ['name' => 'Syringe', 'icon' => '💉', 'weight' => '10g'],
                ['name' => 'Paint Residue', 'icon' => '🎨', 'weight' => '200g'],
                ['name' => 'Thermometer', 'icon' => '🌡️', 'weight' => '30g']
            ],
            'recyclable' => [
                ['name' => 'Plastic Bottle', 'icon' => '🍼', 'weight' => '120g'],
                ['name' => 'Paper Box', 'icon' => '📄', 'weight' => '200g'],
                ['name' => 'Aluminum Can', 'icon' => '🥫', 'weight' => '50g'],
                ['name' => 'Glass Jar', 'icon' => '🫙', 'weight' => '85g'],
                ['name' => 'Magazine', 'icon' => '📖', 'weight' => '110g'],
                ['name' => 'Soda Can', 'icon' => '🥤', 'weight' => '45g'],
                ['name' => 'Cardboard Tube', 'icon' => '🧻', 'weight' => '30g']
            ],
            'biodegradable' => [
                ['name' => 'Banana Peel', 'icon' => '🍌', 'weight' => '45g'],
                ['name' => 'Apple Core', 'icon' => '🍎', 'weight' => '30g'],
                ['name' => 'Carrot Top', 'icon' => '🥕', 'weight' => '15g'],
                ['name' => 'Orange Peel', 'icon' => '🍊', 'weight' => '20g'],
                ['name' => 'Lettuce Scrap', 'icon' => '🥬', 'weight' => '15g'],
                ['name' => 'Coffee Grounds', 'icon' => '☕', 'weight' => '40g']
            ],
            'non-bio' => [
                ['name' => 'Plastic Wrap', 'icon' => '🍬', 'weight' => '10g'],
                ['name' => 'Styrofoam Piece', 'icon' => '📦', 'weight' => '25g'],
                ['name' => 'Broken Glass', 'icon' => '🍷', 'weight' => '60g'],
                ['name' => 'Packaging Film', 'icon' => '🛍️', 'weight' => '15g'],
                ['name' => 'Plastic Cutlery', 'icon' => '🍴', 'weight' => '12g'],
                ['name' => 'Chip Bag', 'icon' => '🍿', 'weight' => '8g'],
                ['name' => 'Mask', 'icon' => '😷', 'weight' => '5g']
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
                    'weight' => $template['weight'],
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
                'health' => '98%',
                'signal' => '40kHz Ultrasound',
                'ip' => 'GPIO 12, 13, 14, 15',
                'icon' => '📡',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-900/40',
            ],
            [
                'name' => 'HX711 Strain Gauge Load Cells',
                'category' => 'Weight Measurement',
                'status' => 'Active',
                'health' => '95%',
                'signal' => '24-Bit ADC',
                'ip' => 'GPIO 26, 27',
                'icon' => '⚖️',
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
                'name' => 'NIR Optical Moisture Sensor (AS7263 1450nm)',
                'category' => 'Non-Contact Organic Classifier',
                'status' => 'Calibrated',
                'health' => '100%',
                'signal' => 'I2C Bus (0x49)',
                'ip' => 'Overhead AI Camera Mount',
                'icon' => '💡',
                'badge' => 'bg-sky-100 text-sky-800 border-sky-200 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-900/40',
            ],
            [
                'name' => 'Inductive Proximity Metal Sensor (LJ12A3)',
                'category' => 'Electromagnetic Can Detector',
                'status' => 'Active',
                'health' => '100%',
                'signal' => 'Digital NPN (GPIO 35)',
                'ip' => 'Intake Tray Chute',
                'icon' => '🧲',
                'badge' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-900/40',
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
}
