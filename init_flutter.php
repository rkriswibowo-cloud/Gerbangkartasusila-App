<?php

$dir = __DIR__ . '/mobile';
if (!is_dir($dir)) mkdir($dir, 0755, true);
if (!is_dir($dir . '/lib')) mkdir($dir . '/lib', 0755, true);
if (!is_dir($dir . '/lib/screens')) mkdir($dir . '/lib/screens', 0755, true);
if (!is_dir($dir . '/lib/models')) mkdir($dir . '/lib/models', 0755, true);
if (!is_dir($dir . '/lib/services')) mkdir($dir . '/lib/services', 0755, true);
if (!is_dir($dir . '/lib/widgets')) mkdir($dir . '/lib/widgets', 0755, true);

$pubspec = <<<YAML
name: mobile
description: Kediri Tourism Mobile App.
publish_to: 'none'
version: 1.0.0+1

environment:
  sdk: '>=3.0.0 <4.0.0'

dependencies:
  flutter:
    sdk: flutter
  cupertino_icons: ^1.0.6
  provider: ^6.1.1
  dio: ^5.4.3
  google_fonts: ^6.1.0
  cached_network_image: ^3.3.1

dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^3.0.0

flutter:
  uses-material-design: true
YAML;

$mainDart = <<<DART
import 'package:flutter/material.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Kediri Tourism',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.teal),
        useMaterial3: true,
      ),
      home: const Scaffold(
        body: Center(
          child: Text('Welcome to Kediri Tourism Mobile App!'),
        ),
      ),
    );
  }
}
DART;

file_put_contents($dir . '/pubspec.yaml', $pubspec);
file_put_contents($dir . '/lib/main.dart', $mainDart);

echo "Flutter structure created manually.\\n";
