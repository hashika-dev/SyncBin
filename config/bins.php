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
    | Readings Before a Bin Counts as Empty
    |--------------------------------------------------------------------------
    |
    | How many 0% sensor readings in a row it takes before the gauge drops to
    | empty. The ESP32 reports every ~15 s, so 3 readings is about 30-45 s.
    | One bad echo or a hand in the bin then can't empty the gauge.
    |
    */
    'empty_confirm_readings' => (int) env('SENSOR_EMPTY_READINGS', 3),

    /*
    |--------------------------------------------------------------------------
    | Default Bin Depth / Height (cm)
    |--------------------------------------------------------------------------
    |
    | Fallback depth in centimeters used to convert ultrasonic distance
    | measurements to fill percentage (measured from the sensor face down to
    | the empty bin floor, not the bin's printed height):
    | fill% = clamp(round((1 - distance_cm / depth_cm) * 100), 0, 100)
    |
    */
    'default_depth_cm' => (float) env('BIN_DEPTH_CM', 62.5),

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
            'height_cm'     => (float) env('BIN_HAZARDOUS_HEIGHT_CM', 62.5),
            'length_cm'     => (float) env('BIN_HAZARDOUS_LENGTH_CM', 34.0),
            'width_cm'      => (float) env('BIN_HAZARDOUS_WIDTH_CM', 51.0),
            'volume_liters' => null,
            'is_custom'     => false,
        ],
        'recyclable' => [
            'height_cm'     => (float) env('BIN_RECYCLABLE_HEIGHT_CM', 62.5),
            'length_cm'     => (float) env('BIN_RECYCLABLE_LENGTH_CM', 34.0),
            'width_cm'      => (float) env('BIN_RECYCLABLE_WIDTH_CM', 51.0),
            'volume_liters' => null,
            'is_custom'     => false,
        ],
        'non-bio' => [
            'height_cm'     => (float) env('BIN_NON_BIO_HEIGHT_CM', 62.5),
            'length_cm'     => (float) env('BIN_NON_BIO_LENGTH_CM', 34.0),
            'width_cm'      => (float) env('BIN_NON_BIO_WIDTH_CM', 51.0),
            'volume_liters' => null,
            'is_custom'     => false,
        ],
        'biodegradable' => [
            'height_cm'     => (float) env('BIN_BIODEGRADABLE_HEIGHT_CM', 40.0),
            'length_cm'     => (float) env('BIN_BIODEGRADABLE_LENGTH_CM', 0.0),
            'width_cm'      => (float) env('BIN_BIODEGRADABLE_WIDTH_CM', 0.0),
            'volume_liters' => null,
            'is_custom'     => true,
            'notes'         => 'Round bucket, 40 cm from sensor face to floor.',
        ],
    ],

];
