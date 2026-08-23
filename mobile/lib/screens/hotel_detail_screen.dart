import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/hotel.dart';
import '../widgets/app_image.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart';

class HotelDetailScreen extends StatelessWidget {
  final Hotel hotel;
  HotelDetailScreen({super.key, required this.hotel});
  final ApiService _apiService = ApiService();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(title: Text(hotel.name), backgroundColor: Colors.white, elevation: 0, foregroundColor: Colors.black),
      body: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            AppImage(
              imageUrl: _apiService.getImageUrl(hotel.thumbnail),
              width: double.infinity,
              height: 250,
              fit: BoxFit.cover,
            ),
            Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(hotel.name, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  const Text('Description', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(hotel.description, style: const TextStyle(fontSize: 16, height: 1.5)),
                  const SizedBox(height: 16),
                  const Text('Location', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(hotel.address, style: const TextStyle(fontSize: 16)),
                  if (hotel.phone != null) ...[
                    const SizedBox(height: 8),
                    Row(children: [const Icon(Icons.phone, size: 16), const SizedBox(width: 8), Text(hotel.phone!, style: const TextStyle(fontSize: 16))]),
                  ],
                  const SizedBox(height: 16),
                  if (hotel.latitude != null && hotel.longitude != null)
                    Container(
                      height: 200, width: double.infinity,
                      decoration: BoxDecoration(borderRadius: BorderRadius.circular(16)),
                      clipBehavior: Clip.antiAlias,
                      child: FlutterMap(
                        options: MapOptions(
                          initialCenter: LatLng(hotel.latitude!, hotel.longitude!),
                          initialZoom: 15.0,
                        ),
                        children: [
                          TileLayer(urlTemplate: 'https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', userAgentPackageName: 'com.etourism.kediri'),
                          MarkerLayer(markers: [Marker(point: LatLng(hotel.latitude!, hotel.longitude!), width: 80, height: 80, child: const Icon(Icons.location_pin, color: Colors.red, size: 40))]),
                        ],
                      ),
                    ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}