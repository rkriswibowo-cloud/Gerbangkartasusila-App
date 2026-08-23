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
      price: json['ticket_price'] != null ? double.parse(json['ticket_price'].toString()) : null,
      rating: json['rating'] != null ? double.parse(json['rating'].toString()) : null,
      openingHours: json['opening_hours'],
      imagePath: json['thumbnail'] ?? json['image_path'] ?? (json['images'] != null && (json['images'] as List).isNotEmpty ? json['images'][0]['image'] : null),
      category: json['category'] != null ? Category.fromJson(json['category']) : null,
    );
  }
}