class Culinary {
  final int id;
  final String name;
  final String slug;
  final String description;
  final String address;
  final double? latitude;
  final double? longitude;
  final String? thumbnail;

  Culinary({
    required this.id, required this.name, required this.slug, required this.description,
    required this.address, this.latitude, this.longitude, this.thumbnail,
  });

  factory Culinary.fromJson(Map<String, dynamic> json) {
    return Culinary(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      description: json['description'] ?? '',
      address: json['address'] ?? '',
      latitude: json['latitude'] != null ? double.parse(json['latitude'].toString()) : null,
      longitude: json['longitude'] != null ? double.parse(json['longitude'].toString()) : null,
      thumbnail: json['thumbnail'] ?? json['image_path'],
    );
  }
}