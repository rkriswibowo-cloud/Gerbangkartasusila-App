import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/culinary.dart';
import '../widgets/app_image.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart';

class CulinaryDetailScreen extends StatelessWidget {
  final Culinary culinary;
  CulinaryDetailScreen({super.key, required this.culinary});
  final ApiService _apiService = ApiService();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(title: Text(culinary.name), backgroundColor: Colors.white, elevation: 0, foregroundColor: Colors.black),
      body: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            AppImage(
              imageUrl: _apiService.getImageUrl(culinary.thumbnail),
              width: double.infinity,
              height: 250,
              fit: BoxFit.cover,
            ),
            Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(culinary.name, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  const Text('Description', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(culinary.description, style: const TextStyle(fontSize: 16, height: 1.5)),
                  const SizedBox(height: 16),
                  const Text('Location', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(culinary.address, style: const TextStyle(fontSize: 16)),
                  const SizedBox(height: 16),
                  if (culinary.latitude != null && culinary.longitude != null)
                    Container(
                      height: 200, width: double.infinity,
                      decoration: BoxDecoration(borderRadius: BorderRadius.circular(16)),
                      clipBehavior: Clip.antiAlias,
                      child: FlutterMap(
                        options: MapOptions(
                          initialCenter: LatLng(culinary.latitude!, culinary.longitude!),
                          initialZoom: 15.0,
                        ),
                        children: [
                          TileLayer(urlTemplate: 'https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', userAgentPackageName: 'com.etourism.kediri'),
                          MarkerLayer(markers: [Marker(point: LatLng(culinary.latitude!, culinary.longitude!), width: 80, height: 80, child: const Icon(Icons.location_pin, color: Colors.red, size: 40))]),
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