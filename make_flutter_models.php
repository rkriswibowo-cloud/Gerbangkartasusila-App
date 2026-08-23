<?php

$dir = __DIR__ . '/mobile/lib';

$modelsDir = $dir . '/models';
if (!is_dir($modelsDir)) mkdir($modelsDir, 0755, true);

$categoryDart = <<<DART
class Category {
  final int id;
  final String name;
  final String icon;

  Category({required this.id, required this.name, required this.icon});

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: json['id'],
      name: json['name'] ?? '',
      icon: json['icon'] ?? '',
    );
  }
}
DART;

$destinationDart = <<<DART
import 'category.dart';

class Destination {
  final int id;
  final String name;
  final String slug;
  final String description;
  final String? address;
  final double? latitude;
  final double? longitude;
  final double? price;
  final double? rating;
  final String? openingHours;
  final String? imagePath;
  final Category? category;

  Destination({
    required this.id,
    required this.name,
    required this.slug,
    required this.description,
    this.address,
    this.latitude,
    this.longitude,
    this.price,
    this.rating,
    this.openingHours,
    this.imagePath,
    this.category,
  });

  factory Destination.fromJson(Map<String, dynamic> json) {
    return Destination(
      id: json['id'],
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      description: json['description'] ?? '',
      address: json['address'],
      latitude: json['latitude'] != null ? double.parse(json['latitude'].toString()) : null,
      longitude: json['longitude'] != null ? double.parse(json['longitude'].toString()) : null,
      price: json['price'] != null ? double.parse(json['price'].toString()) : null,
      rating: json['rating'] != null ? double.parse(json['rating'].toString()) : null,
      openingHours: json['opening_hours'],
      imagePath: json['image_path'],
      category: json['category'] != null ? Category.fromJson(json['category']) : null,
    );
  }
}
DART;

$servicesDir = $dir . '/services';
if (!is_dir($servicesDir)) mkdir($servicesDir, 0755, true);

$apiServiceDart = <<<DART
import 'package:dio/dio.dart';

class ApiService {
  static const String baseUrl = 'http://127.0.0.1:8000/api/v1'; // Localhost
  // For android emulator, use 10.0.2.2:8000 instead of 127.0.0.1

  final Dio _dio = Dio(BaseOptions(
    baseUrl: baseUrl,
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    },
  ));

  Future<List<dynamic>> getDestinations() async {
    try {
      final response = await _dio.get('/destinations');
      return response.data['data'] ?? [];
    } catch (e) {
      print('Error fetching destinations: \$e');
      return [];
    }
  }

  Future<Map<String, dynamic>?> getDestinationDetail(String slug) async {
    try {
      final response = await _dio.get('/destinations/\$slug');
      return response.data['data'];
    } catch (e) {
      print('Error fetching destination detail: \$e');
      return null;
    }
  }

  String getImageUrl(String? path) {
    if (path == null || path.isEmpty) {
      return 'https://via.placeholder.com/400x300?text=No+Image';
    }
    if (path.startsWith('http')) return path;
    return 'http://127.0.0.1:8000/storage/\$path';
  }
}
DART;

file_put_contents($modelsDir . '/category.dart', $categoryDart);
file_put_contents($modelsDir . '/destination.dart', $destinationDart);
file_put_contents($servicesDir . '/api_service.dart', $apiServiceDart);

echo "Models and Services generated.\\n";
