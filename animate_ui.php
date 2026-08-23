<?php
$baseDir = __DIR__ . '/mobile/lib/screens';

function addAnimateImport($file) {
    $content = file_get_contents($file);
    if (strpos($content, 'flutter_animate') === false) {
        $content = str_replace("import 'package:flutter/material.dart';", "import 'package:flutter/material.dart';\nimport 'package:flutter_animate/flutter_animate.dart';", $content);
        file_put_contents($file, $content);
    }
}

// 1. LOGIN SCREEN ANIMATION
addAnimateImport("$baseDir/login_screen.dart");
$content = file_get_contents("$baseDir/login_screen.dart");
// Find the SingleChildScrollView and add .animate() to its child Column
$content = preg_replace('/(child: Column\([\s\S]*?)(,\n\s*\]\n\s*\),)/', '$1$2.animate().fade(duration: 800.ms).slideY(begin: 0.2, end: 0)', $content, 1);
file_put_contents("$baseDir/login_screen.dart", $content);


// 2. LIST SCREENS ANIMATION (Hotels, Culinary, Articles)
$listScreens = ['hotels_screen.dart', 'culinary_screen.dart', 'articles_screen.dart'];
foreach ($listScreens as $screen) {
    addAnimateImport("$baseDir/$screen");
    $content = file_get_contents("$baseDir/$screen");
    // Add BouncingScrollPhysics
    $content = str_replace("ListView.builder(", "ListView.builder(\n            physics: const BouncingScrollPhysics(),", $content);
    // Add staggered animation to the returned item (InkWell)
    $content = preg_replace('/(return InkWell\([\s\S]*?child: Card\([\s\S]*?)(,\n\s*\),\n\s*\),\n\s*\);)/', '$1$2.animate().fade(duration: 500.ms, delay: (50 * index).ms).slideY(begin: 0.1)', $content);
    
    // Add Hero to image
    // Find Image.network and wrap it
    $content = preg_replace('/(Image\.network\([\s\S]*?errorBuilder:[\s\S]*?\),)/', "Hero(\n                      tag: 'hero_\${item.id}',\n                      child: $1\n                    )", $content);
    file_put_contents("$baseDir/$screen", $content);
}

// 3. DETAIL SCREENS HERO
$detailScreens = ['hotel_detail_screen.dart' => 'hotel', 'culinary_detail_screen.dart' => 'culinary', 'article_detail_screen.dart' => 'article'];
foreach ($detailScreens as $screen => $var) {
    addAnimateImport("$baseDir/$screen");
    $content = file_get_contents("$baseDir/$screen");
    // Add BouncingScrollPhysics to SingleChildScrollView
    $content = str_replace("SingleChildScrollView(", "SingleChildScrollView(\n        physics: const BouncingScrollPhysics(),", $content);
    // Add Hero
    $content = preg_replace('/(Image\.network\([\s\S]*?errorBuilder:[\s\S]*?\),)/', "Hero(\n              tag: 'hero_\${{$var}.id}',\n              child: $1\n            )", $content);
    file_put_contents("$baseDir/$screen", $content);
}

// 4. HOME SCREEN (Explore)
addAnimateImport("$baseDir/home_screen.dart");
$content = file_get_contents("$baseDir/home_screen.dart");
// Add BouncingScrollPhysics
$content = str_replace("SingleChildScrollView(", "SingleChildScrollView(\n        physics: const BouncingScrollPhysics(),", $content);
$content = str_replace("ListView.builder(", "ListView.builder(\n                      physics: const BouncingScrollPhysics(),", $content);

// Add Hero to Popular destinations
$content = preg_replace('/(Image\.network\([\s\S]*?errorBuilder:[\s\S]*?\),)/', "Hero(\n                        tag: 'hero_dest_\${dest.id}',\n                        child: $1\n                      )", $content);

// Wait, home screen has two Image.network (one in popular, one in explore more)
// The regex might wrap them. Let's see if we can do staggered animation for explore more list
$content = preg_replace('/(return GestureDetector\([\s\S]*?child: Container\([\s\S]*?)(,\n\s*\),\n\s*\);)/', '$1$2.animate().fade(duration: 400.ms, delay: (100 * index).ms).slideX(begin: 0.1)', $content);

file_put_contents("$baseDir/home_screen.dart", $content);

// 5. DETAIL SCREEN DESTINATION
addAnimateImport("$baseDir/detail_screen.dart");
$content = file_get_contents("$baseDir/detail_screen.dart");
$content = str_replace("CustomScrollView(", "CustomScrollView(\n        physics: const BouncingScrollPhysics(),", $content);
// We can't use id for destination detail because the detail screen receives a slug, not id.
// We must modify detail_screen.dart to use slug for Hero tag, or modify home_screen to use slug.
// Let's modify home_screen.dart to use slug for Hero.
$content = file_get_contents("$baseDir/home_screen.dart");
$content = str_replace("tag: 'hero_dest_\${dest.id}'", "tag: 'hero_dest_\${dest.slug}'", $content);
file_put_contents("$baseDir/home_screen.dart", $content);

$content = file_get_contents("$baseDir/detail_screen.dart");
$content = preg_replace('/(Image\.network\([\s\S]*?errorBuilder:[\s\S]*?\),)/', "Hero(\n                    tag: 'hero_dest_\${dest.slug}',\n                    child: $1\n                  )", $content);
file_put_contents("$baseDir/detail_screen.dart", $content);

echo "Animations added.\n";
