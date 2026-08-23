<?php
// Test API responses
$apis = [
    'destinations' => 'http://localhost:8000/api/destinations',
    'culinary' => 'http://localhost:8000/api/culinary',
    'hotels' => 'http://localhost:8000/api/hotels',
    'events' => 'http://localhost:8000/api/events',
    'articles' => 'http://localhost:8000/api/articles',
];

foreach ($apis as $name => $url) {
    echo "\n=== $name ===\n";
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    
    $items = $data['data'] ?? [];
    if (isset($items['data'])) $items = $items['data'];
    
    foreach ($items as $item) {
        $itemName = $item['name'] ?? $item['title'] ?? 'unknown';
        $thumbnail = $item['thumbnail'] ?? 'NOT SET';
        $imagePath = $item['image_path'] ?? 'NOT SET';
        $banner = $item['banner'] ?? 'NOT SET';
        echo "  $itemName\n";
        echo "    thumbnail: $thumbnail\n";
        echo "    image_path: $imagePath\n";
        if ($banner !== 'NOT SET') echo "    banner: $banner\n";
        
        // Test the actual image URL that Flutter would use
        $path = $thumbnail !== 'NOT SET' ? $thumbnail : ($imagePath !== 'NOT SET' ? $imagePath : $banner);
        if ($path !== 'NOT SET') {
            $imgUrl = "http://localhost:8000/api/media/$path";
            $headers = @get_headers($imgUrl);
            echo "    media_url: $imgUrl\n";
            echo "    status: " . ($headers ? $headers[0] : 'FAILED') . "\n";
        }
    }
}
