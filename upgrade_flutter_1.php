<?php
$dir = __DIR__ . '/mobile/lib';

$apiServiceDart = <<<DART
import 'package:dio/dio.dart';

class ApiService {
  static const String baseUrl = 'http://127.0.0.1:8000/api/v1'; 

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

  Future<List<dynamic>> getEvents() async {
    try {
      final response = await _dio.get('/events');
      return response.data['data'] ?? [];
    } catch (e) {
      print('Error fetching events: \$e');
      return [];
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

$homeScreenDart = <<<DART
import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/destination.dart';
import 'detail_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final ApiService _apiService = ApiService();
  List<Destination> _destinations = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  Future<void> _fetchData() async {
    try {
      final data = await _apiService.getDestinations();
      setState(() {
        _destinations = data.map((json) => Destination.fromJson(json)).toList();
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Explore Kediri', style: TextStyle(color: Colors.green[800], fontWeight: FontWeight.bold, fontSize: 24)),
            const Text('Discover beautiful places', style: TextStyle(color: Colors.grey, fontSize: 12)),
          ],
        ),
        actions: [
          Padding(
            padding: const EdgeInsets.only(right: 16.0),
            child: CircleAvatar(
              backgroundColor: Colors.green[100],
              child: IconButton(
                icon: const Icon(Icons.notifications_none, color: Colors.green),
                onPressed: () {},
              ),
            ),
          )
        ],
      ),
      body: _isLoading 
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: _fetchData,
              child: SingleChildScrollView(
                physics: const AlwaysScrollableScrollPhysics(),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Search Bar
                    Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: TextField(
                        decoration: InputDecoration(
                          hintText: 'Search destinations...',
                          prefixIcon: const Icon(Icons.search),
                          filled: true,
                          fillColor: Colors.white,
                          border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(30),
                            borderSide: BorderSide.none,
                          ),
                          contentPadding: const EdgeInsets.symmetric(vertical: 0),
                          elevation: 2,
                        ),
                      ),
                    ),
                    
                    // Categories
                    Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 16.0),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text('Categories', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                          TextButton(onPressed: (){}, child: const Text('See All')),
                        ],
                      ),
                    ),
                    SizedBox(
                      height: 50,
                      child: ListView(
                        scrollDirection: Axis.horizontal,
                        padding: const EdgeInsets.symmetric(horizontal: 16),
                        children: [
                          _buildCategoryChip('All', true),
                          _buildCategoryChip('Nature', false),
                          _buildCategoryChip('History', false),
                          _buildCategoryChip('Culinary', false),
                        ],
                      ),
                    ),
                    
                    const SizedBox(height: 24),
                    
                    // Popular Destinations
                    const Padding(
                      padding: EdgeInsets.symmetric(horizontal: 16.0),
                      child: Text('Popular Destinations', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    ),
                    const SizedBox(height: 12),
                    SizedBox(
                      height: 280,
                      child: ListView.builder(
                        scrollDirection: Axis.horizontal,
                        padding: const EdgeInsets.symmetric(horizontal: 16),
                        itemCount: _destinations.length > 5 ? 5 : _destinations.length,
                        itemBuilder: (context, index) {
                          final dest = _destinations[index];
                          return _buildPopularCard(context, dest);
                        },
                      ),
                    ),
                    
                    const SizedBox(height: 24),
                    
                    // Explore More
                    const Padding(
                      padding: EdgeInsets.symmetric(horizontal: 16.0),
                      child: Text('Explore More', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    ),
                    ListView.builder(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      padding: const EdgeInsets.all(16),
                      itemCount: _destinations.length,
                      itemBuilder: (context, index) {
                        final dest = _destinations[index];
                        return _buildListCard(context, dest);
                      },
                    ),
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildCategoryChip(String label, bool isSelected) {
    return Container(
      margin: const EdgeInsets.only(right: 12),
      child: FilterChip(
        label: Text(label, style: TextStyle(color: isSelected ? Colors.white : Colors.black87)),
        selected: isSelected,
        onSelected: (bool value) {},
        backgroundColor: Colors.white,
        selectedColor: Colors.green,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        elevation: 1,
      ),
    );
  }

  Widget _buildPopularCard(BuildContext context, Destination dest) {
    return Container(
      width: 220,
      margin: const EdgeInsets.only(right: 16),
      child: GestureDetector(
        onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => DetailScreen(slug: dest.slug))),
        child: Card(
          clipBehavior: Clip.antiAlias,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          elevation: 4,
          shadowColor: Colors.black26,
          child: Stack(
            fit: StackFit.expand,
            children: [
              Image.network(
                _apiService.getImageUrl(dest.imagePath),
                fit: BoxFit.cover,
                errorBuilder: (c,e,s) => Container(color: Colors.grey[300], child: const Icon(Icons.image)),
              ),
              Container(
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    colors: [Colors.transparent, Colors.black87],
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                  ),
                ),
              ),
              Positioned(
                bottom: 16, left: 16, right: 16,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(dest.name, style: const TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold), maxLines: 2, overflow: TextOverflow.ellipsis),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        const Icon(Icons.location_on, color: Colors.white70, size: 14),
                        const SizedBox(width: 4),
                        Expanded(child: Text(dest.category?.name ?? 'Destination', style: const TextStyle(color: Colors.white70, fontSize: 12))),
                      ],
                    )
                  ],
                ),
              ),
              Positioned(
                top: 12, right: 12,
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(color: Colors.black54, borderRadius: BorderRadius.circular(12)),
                  child: Row(
                    children: [
                      const Icon(Icons.star, color: Colors.amber, size: 14),
                      const SizedBox(width: 4),
                      Text(dest.rating?.toString() ?? 'N/A', style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12)),
                    ],
                  ),
                ),
              )
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildListCard(BuildContext context, Destination dest) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      elevation: 2,
      child: InkWell(
        onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => DetailScreen(slug: dest.slug))),
        borderRadius: BorderRadius.circular(16),
        child: Row(
          children: [
            ClipRRect(
              borderRadius: const BorderRadius.only(topLeft: Radius.circular(16), bottomLeft: Radius.circular(16)),
              child: Image.network(
                _apiService.getImageUrl(dest.imagePath),
                width: 120, height: 120, fit: BoxFit.cover,
                errorBuilder: (c,e,s) => Container(width: 120, height: 120, color: Colors.grey[300], child: const Icon(Icons.image)),
              ),
            ),
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(12.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(dest.name, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold), maxLines: 2, overflow: TextOverflow.ellipsis),
                    const SizedBox(height: 8),
                    if (dest.category != null)
                      Text(dest.category!.name, style: TextStyle(color: Colors.green[700], fontSize: 12)),
                    const SizedBox(height: 8),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Row(
                          children: [
                            const Icon(Icons.star, color: Colors.amber, size: 16),
                            const SizedBox(width: 4),
                            Text(dest.rating?.toString() ?? 'N/A', style: const TextStyle(fontWeight: FontWeight.bold)),
                          ],
                        ),
                        Text(dest.price == 0 ? 'Free' : 'Rp \${dest.price}', style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.blue)),
                      ],
                    )
                  ],
                ),
              ),
            )
          ],
        ),
      ),
    );
  }
}
DART;

file_put_contents($dir . '/services/api_service.dart', $apiServiceDart);
file_put_contents($dir . '/screens/home_screen.dart', $homeScreenDart);

echo "Flutter UI upgraded part 1.\\n";
