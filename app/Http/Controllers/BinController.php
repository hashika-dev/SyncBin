<?php

namespace App\Http\Controllers;

use App\Models\Bin;
use App\Models\WasteItem;
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
            try {
                $recipient = 'kurtumali06@gmail.com';
                \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($bin, $recipient) {
                    $message->to($recipient)
                        ->subject("⚠️ CRITICAL ALERT: {$bin->name} has reached {$bin->level}% capacity!")
                        ->html("
                            <div style='font-family: sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #f43f5e; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);'>
                                <div style='background-color: #f43f5e; padding: 24px; text-align: center; color: white;'>
                                    <h1 style='margin: 0; font-size: 24px; font-weight: 900;'>SyncBin Alert</h1>
                                    <p style='margin: 8px 0 0; font-size: 14px; opacity: 0.9;'>Critical Waste Capacity Reached</p>
                                </div>
                                <div style='padding: 32px; background-color: #ffffff; color: #18181b;'>
                                    <p style='font-size: 16px; line-height: 1.6; margin-top: 0;'>Hello Administrator,</p>
                                    <p style='font-size: 15px; line-height: 1.6;'>This is an automated alert from the SyncBin system monitor. The following waste classification bin is approaching critical capacity and requires evacuation:</p>
                                    
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
        }

        return response()->json($bin->load(['items' => function($q) { $q->latest(); }]));
    }

    /**
     * Empty a bin of all items and reset levels.
     */
    public function emptyBin($slug)
    {
        $bin = Bin::where('slug', $slug)->firstOrFail();

        // Delete items
        $bin->items()->delete();

        // Reset levels
        $bin->level = 0;
        $bin->status = 'Empty';
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

        return $pdf->stream('SyncBin-Status-Report-' . now()->format('Y-m-d') . '.pdf');
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

        // Filter by bin slug
        if ($request->filled('bin')) {
            $query->whereHas('bin', function($q) use ($request) {
                $q->where('slug', $request->input('bin'));
            });
        }

        $logs = $query->latest()->paginate(10)->withQueryString();
        $bins = Bin::all();

        return view('dashboards.history', compact('logs', 'bins'));
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

        return view('dashboards.reports', compact('bins', 'totalItemsCount', 'averageFill', 'recyclingRate', 'totalWeightKg', 'mostActiveBin', 'chartLabels', 'chartData'));
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
}
