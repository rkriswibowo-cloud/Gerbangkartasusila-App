<?php

$dir = __DIR__ . '/app/Filament/Resources';

$icons = [
    'DestinationCategoryResource.php' => 'heroicon-o-tag',
    'DestinationResource.php'         => 'heroicon-o-map-pin',
    'EventResource.php'               => 'heroicon-o-calendar-days',
    'CulinaryPlaceResource.php'       => 'heroicon-o-cake',
    'HotelResource.php'               => 'heroicon-o-building-office-2',
    'ArticleResource.php'             => 'heroicon-o-document-text',
    'GalleryResource.php'             => 'heroicon-o-photo',
    'UserResource.php'                => 'heroicon-o-users',
];

foreach ($icons as $filename => $icon) {
    $filePath = $dir . '/' . $filename;
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $content = preg_replace(
            "/'heroicon-o-rectangle-stack'/",
            "'" . $icon . "'",
            $content
        );
        file_put_contents($filePath, $content);
        echo "Updated $filename with icon $icon\n";
    }
}
