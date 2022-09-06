<?php

return [
    'source_path' => storage_path('app/public/documents'),
    'destination_path' => storage_path('app/public/tiles'),

    // Choose between gd and imagick support.
    'driver' => 'gd',
    'tile_format' => 'jpg',
];
