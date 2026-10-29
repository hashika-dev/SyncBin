<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Critical Alert Capacity Threshold (%)
    |--------------------------------------------------------------------------
    |
    | Percentage threshold (default 80%) where a bin enters Critical state
    | and automatically triggers an emergency email/SMS dispatch.
    |
    */
    'critical_threshold' => (int) env('ALERT_CAPACITY_THRESHOLD', 80),

    /*
    |--------------------------------------------------------------------------
    | Default Bin Depth / Height (cm)
    |--------------------------------------------------------------------------
    |
    | Fallback chamber depth in centimeters used to convert ultrasonic distance
    | measurements to fill percentage:
    | fill% = clamp(round((1 - distance_cm / depth_cm) * 100), 0, 100)
    |
    */
    'default_depth_cm' => (float) env('BIN_DEPTH_CM', 60.0),

    /*
    |--------------------------------------------------------------------------
    | Bin Physical Dimensions & Volume
    |--------------------------------------------------------------------------
    |
    | Physical internal chamber dimensions (height, length, width in cm).
    | Volume is calculated in Liters: (height * length * width) / 1000.
    |
    */
    'dimensions' => [
        'hazardous' => [
            'height_cm'     => (float) env('BIN_HAZARDOUS_HEIGHT_CM', 60.0),
            'length_cm'     => (float) env('BIN_HAZARDOUS_LENGTH_CM', 34.0),
            'width_cm'      => (float) env('BIN_HAZARDOUS_WIDTH_CM', 51.0),
            'volume_liters' => 104.04,
            'is_custom'     => false,
        ],
        'recyclable' => [
            'height_cm'     => (float) env('BIN_RECYCLABLE_HEIGHT_CM', 60.0),
            'length_cm'     => (float) env('BIN_RECYCLABLE_LENGTH_CM', 34.0),
            'width_cm'      => (float) env('BIN_RECYCLABLE_WIDTH_CM', 51.0),
            'volume_liters' => 104.04,
            'is_custom'     => false,
        ],
        'non-bio' => [
            'height_cm'     => (float) env('BIN_NON_BIO_HEIGHT_CM', 60.0),
            'length_cm'     => (float) env('BIN_NON_BIO_LENGTH_CM', 34.0),
            'width_cm'      => (float) env('BIN_NON_BIO_WIDTH_CM', 51.0),
            'volume_liters' => 104.04,
            'is_custom'     => false,
        ],
        'biodegradable' => [
            'height_cm'     => (float) env('BIN_BIODEGRADABLE_HEIGHT_CM', 60.0),
            'length_cm'     => (float) env('BIN_BIODEGRADABLE_LENGTH_CM', 0.0),
            'width_cm'      => (float) env('BIN_BIODEGRADABLE_WIDTH_CM', 0.0),
            'volume_liters' => null,
            'is_custom'     => true,
            'notes'         => 'Custom bin enclosure, dimensions pending physical measurement.',
        ],
    ],

];
