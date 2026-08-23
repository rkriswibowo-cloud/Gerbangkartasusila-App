<?php

$dir = __DIR__ . '/mobile/lib';

$screensDir = $dir . '/screens';
if (!is_dir($screensDir)) mkdir($screensDir, 0755, true);

$mainLayoutDart = <<<DART
import 'package:flutter/material.dart';
import 'home_screen.dart';

class MainLayout extends StatefulWidget {
  const MainLayout({super.key});

  @override
  State<MainLayout> createState() => _MainLayoutState();
}

class _MainLayoutState extends State<MainLayout> {
  int _currentIndex = 0;

  final List<Widget> _pages = [
    const HomeScreen(),
    const Center(child: Text('Events - Coming Soon')),
    const Center(child: Text('Favorites - Coming Soon')),
    const Center(child: Text('Profile - Coming Soon')),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _pages[_currentIndex],
      bottomNavigationBar: NavigationBar(
        selectedIndex: _currentIndex,
        onDestinationSelected: (index) {
          setState(() {
            _currentIndex = index;
          });
        },
        destinations: const [
          NavigationDestination(icon: Icon(Icons.explore_outlined), selectedIcon: Icon(Icons.explore), label: 'Explore'),
          NavigationDestination(icon: Icon(Icons.event_outlined), selectedIcon: Icon(Icons.event), label: 'Events'),
          NavigationDestination(icon: Icon(Icons.favorite_outline), selectedIcon: Icon(Icons.favorite), label: 'Saved'),
          NavigationDestination(icon: Icon(Icons.person_outline), selectedIcon: Icon(Icons.person), label: 'Profile'),
        ],
      ),
    );
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
      appBar: AppBar(
        title: const Text('Kediri Tourism', style: TextStyle(fontWeight: FontWeight.bold)),
        actions: [
          IconButton(icon: const Icon(Icons.search), onPressed: () {}),
        ],
      ),
      body: _isLoading 
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: _fetchData,
              child: ListView.builder(
                padding: const EdgeInsets.all(16),
                itemCount: _destinations.length,
                itemBuilder: (context, index) {
                  final dest = _destinations[index];
                  return Card(
                    clipBehavior: Clip.antiAlias,
                    margin: const EdgeInsets.only(bottom: 16),
                    elevation: 2,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                    child: InkWell(
                      onTap: () {
                        Navigator.push(context, MaterialPageRoute(
                          builder: (_) => DetailScreen(slug: dest.slug),
                        ));
                      },
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Image.network(
                            _apiService.getImageUrl(dest.imagePath),
                            height: 200,
                            width: double.infinity,
                            fit: BoxFit.cover,
                            errorBuilder: (c,e,s) => Container(
                              height: 200, color: Colors.grey[300],
                              child: const Icon(Icons.image_not_supported, size: 50),
                            ),
                          ),
                          Padding(
                            padding: const EdgeInsets.all(16.0),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Expanded(
                                      child: Text(dest.name, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                                    ),
                                    if (dest.rating != null)
                                      Row(
                                        children: [
                                          const Icon(Icons.star, color: Colors.amber, size: 18),
                                          const SizedBox(width: 4),
                                          Text(dest.rating.toString(), style: const TextStyle(fontWeight: FontWeight.bold)),
                                        ],
                                      )
                                  ],
                                ),
                                const SizedBox(height: 8),
                                if (dest.category != null)
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                    decoration: BoxDecoration(
                                      color: Theme.of(context).colorScheme.primaryContainer,
                                      borderRadius: BorderRadius.circular(8),
                                    ),
                                    child: Text(dest.category!.name, style: TextStyle(fontSize: 12, color: Theme.of(context).colorScheme.onPrimaryContainer)),
                                  ),
                              ],
                            ),
                          )
                        ],
                      ),
                    ),
                  );
                },
              ),
            ),
    );
  }
}
DART;

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
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 300,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              title: Text(dest.name, style: const TextStyle(shadows: [Shadow(color: Colors.black, blurRadius: 4)])),
              background: Image.network(
                _apiService.getImageUrl(dest.imagePath),
                fit: BoxFit.cover,
                errorBuilder: (c,e,s) => const Icon(Icons.image, size: 100),
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      if (dest.category != null)
                        Chip(label: Text(dest.category!.name)),
                      if (dest.rating != null)
                        Row(
                          children: [
                            const Icon(Icons.star, color: Colors.amber),
                            Text(dest.rating.toString(), style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                          ],
                        )
                    ],
                  ),
                  const SizedBox(height: 16),
                  const Text('Description', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(dest.description, style: const TextStyle(fontSize: 16, height: 1.5)),
                  const SizedBox(height: 24),
                  if (dest.address != null) ...[
                    const Row(children: [Icon(Icons.location_on, color: Colors.red), SizedBox(width: 8), Text('Address', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold))]),
                    const SizedBox(height: 8),
                    Text(dest.address!, style: const TextStyle(fontSize: 16)),
                    const SizedBox(height: 16),
                  ],
                  if (dest.price != null) ...[
                    const Row(children: [Icon(Icons.monetization_on, color: Colors.green), SizedBox(width: 8), Text('Ticket Price', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold))]),
                    const SizedBox(height: 8),
                    Text('Rp \${dest.price}', style: const TextStyle(fontSize: 16)),
                  ]
                ],
              ),
            ),
          )
        ],
      ),
    );
  }
}
DART;

// Update main.dart to use MainLayout
$mainDart = <<<DART
import 'package:flutter/material.dart';
import 'screens/main_layout.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Kediri Tourism',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.green),
        useMaterial3: true,
      ),
      home: const MainLayout(),
    );
  }
}
DART;

file_put_contents($screensDir . '/main_layout.dart', $mainLayoutDart);
file_put_contents($screensDir . '/home_screen.dart', $homeScreenDart);
file_put_contents($screensDir . '/detail_screen.dart', $detailScreenDart);
file_put_contents($dir . '/main.dart', $mainDart);

echo "Screens generated.\\n";
