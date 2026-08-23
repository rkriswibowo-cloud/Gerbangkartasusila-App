<?php
$dir = __DIR__ . '/mobile/lib';

$detailScreenDart = <<<DART
import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/destination.dart';

class DetailScreen extends StatefulWidget {
  final String slug;
  const DetailScreen({super.key, required this.slug});

  @override
  State<DetailScreen> createState() => _DetailScreenState();
}

class _DetailScreenState extends State<DetailScreen> {
  final ApiService _apiService = ApiService();
  Destination? _destination;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchDetail();
  }

  Future<void> _fetchDetail() async {
    final data = await _apiService.getDestinationDetail(widget.slug);
    if (data != null) {
      setState(() {
        _destination = Destination.fromJson(data);
        _isLoading = false;
      });
    } else {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(body: Center(child: CircularProgressIndicator()));
    }
    if (_destination == null) {
      return Scaffold(
        appBar: AppBar(),
        body: const Center(child: Text('Destination not found')),
      );
    }

    final dest = _destination!;
    return Scaffold(
      backgroundColor: Colors.white,
      bottomNavigationBar: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, -5))
          ]
        ),
        child: Row(
          children: [
            Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Price', style: TextStyle(color: Colors.grey, fontSize: 12)),
                Text(
                  dest.price == null || dest.price == 0 ? 'Free' : 'Rp \${dest.price}',
                  style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.blue),
                ),
              ],
            ),
            const SizedBox(width: 24),
            Expanded(
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.green,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  elevation: 0,
                ),
                onPressed: () {},
                child: const Text('Book Ticket', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              ),
            )
          ],
        ),
      ),
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 350,
            pinned: true,
            backgroundColor: Colors.white,
            iconTheme: const IconThemeData(color: Colors.white),
            flexibleSpace: FlexibleSpaceBar(
              background: Stack(
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
                    bottom: 24, left: 24, right: 24,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        if (dest.category != null)
                           Container(
                             padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                             margin: const EdgeInsets.only(bottom: 8),
                             decoration: BoxDecoration(color: Colors.green, borderRadius: BorderRadius.circular(20)),
                             child: Text(dest.category!.name, style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
                           ),
                        Text(dest.name, style: const TextStyle(color: Colors.white, fontSize: 28, fontWeight: FontWeight.bold)),
                      ],
                    ),
                  )
                ],
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Container(
              decoration: const BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.only(topLeft: Radius.circular(30), topRight: Radius.circular(30)),
              ),
              transform: Matrix4.translationValues(0, -20, 0),
              child: Padding(
                padding: const EdgeInsets.all(24.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceAround,
                      children: [
                        _buildIconStat(Icons.star, dest.rating?.toString() ?? 'N/A', 'Rating', Colors.amber),
                        _buildIconStat(Icons.access_time, dest.openingHours ?? '24 Hours', 'Open', Colors.blue),
                        _buildIconStat(Icons.map, 'Maps', 'Direction', Colors.red),
                      ],
                    ),
                    const SizedBox(height: 32),
                    const Text('About Destination', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 12),
                    Text(dest.description, style: const TextStyle(fontSize: 16, height: 1.6, color: Colors.black87)),
                    const SizedBox(height: 24),
                    if (dest.address != null) ...[
                      const Row(children: [Icon(Icons.location_on, color: Colors.red), SizedBox(width: 8), Text('Location', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold))]),
                      const SizedBox(height: 8),
                      Text(dest.address!, style: const TextStyle(fontSize: 16, color: Colors.black54)),
                      const SizedBox(height: 32),
                    ],
                  ],
                ),
              ),
            ),
          )
        ],
      ),
    );
  }

  Widget _buildIconStat(IconData icon, String value, String label, Color color) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(12),
          decoration: BoxDecoration(color: color.withOpacity(0.1), shape: BoxShape.circle),
          child: Icon(icon, color: color, size: 28),
        ),
        const SizedBox(height: 8),
        Text(value, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
        Text(label, style: const TextStyle(color: Colors.grey, fontSize: 12)),
      ],
    );
  }
}
DART;

$eventsDart = <<<DART
import 'package:flutter/material.dart';
import '../services/api_service.dart';

class EventsScreen extends StatefulWidget {
  const EventsScreen({super.key});

  @override
  State<EventsScreen> createState() => _EventsScreenState();
}

class _EventsScreenState extends State<EventsScreen> {
  final ApiService _apiService = ApiService();
  List<dynamic> _events = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchEvents();
  }

  Future<void> _fetchEvents() async {
    final data = await _apiService.getEvents();
    setState(() {
      _events = data;
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: const Text('Upcoming Events', style: TextStyle(color: Colors.black, fontWeight: FontWeight.bold)),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _events.isEmpty
              ? const Center(child: Text('No events found.'))
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: _events.length,
                  itemBuilder: (context, index) {
                    final event = _events[index];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 16),
                      clipBehavior: Clip.antiAlias,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                      elevation: 2,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Image.network(
                            _apiService.getImageUrl(event['image_path']),
                            height: 180,
                            width: double.infinity,
                            fit: BoxFit.cover,
                            errorBuilder: (c,e,s) => Container(height: 180, color: Colors.grey[300], child: const Icon(Icons.event, size: 50)),
                          ),
                          Padding(
                            padding: const EdgeInsets.all(16.0),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Expanded(child: Text(event['name'], style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold))),
                                    Container(
                                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                      decoration: BoxDecoration(color: Colors.blue[50], borderRadius: BorderRadius.circular(8)),
                                      child: Text(
                                        event['start_date'] ?? 'TBA',
                                        style: TextStyle(color: Colors.blue[700], fontWeight: FontWeight.bold, fontSize: 12),
                                      ),
                                    )
                                  ],
                                ),
                                const SizedBox(height: 8),
                                Text(event['description'] ?? '', maxLines: 2, overflow: TextOverflow.ellipsis, style: const TextStyle(color: Colors.black54)),
                                const SizedBox(height: 12),
                                Row(
                                  children: [
                                    const Icon(Icons.location_on, size: 16, color: Colors.red),
                                    const SizedBox(width: 4),
                                    Text(event['location'] ?? 'Kediri', style: const TextStyle(color: Colors.grey, fontSize: 12)),
                                  ],
                                )
                              ],
                            ),
                          )
                        ],
                      ),
                    );
                  },
                ),
    );
  }
}
DART;

file_put_contents($dir . '/screens/detail_screen.dart', $detailScreenDart);
file_put_contents($dir . '/screens/events_screen.dart', $eventsDart);

echo "Flutter UI upgraded part 2.\\n";
