import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../services/api_service.dart';
import '../models/hotel.dart';
import '../widgets/app_image.dart';
import '../theme/app_colors.dart';
import 'hotel_detail_screen.dart';

class HotelsScreen extends StatefulWidget {
  const HotelsScreen({super.key});
  @override
  State<HotelsScreen> createState() => _HotelsScreenState();
}

class _HotelsScreenState extends State<HotelsScreen> {
  final ApiService _apiService = ApiService();
  List<Hotel> _items = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetch();
  }

  Future<void> _fetch() async {
    try {
      final data = await _apiService.getHotels();
      setState(() {
        _items = data.map((json) => Hotel.fromJson(json)).toList();
        _isLoading = false;
      });
    } catch (e) {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Hotel & Penginapan 🏨', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)),
        backgroundColor: AppColors.primary,
        elevation: 0,
        centerTitle: true,
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
          : RefreshIndicator(
              color: AppColors.primary,
              backgroundColor: AppColors.background,
              onRefresh: _fetch,
              child: ListView.builder(
                physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
                padding: const EdgeInsets.all(16),
                itemCount: _items.length,
                itemBuilder: (context, index) {
                  final item = _items[index];
                  return InkWell(
                    onTap: () {
                      Navigator.push(context, MaterialPageRoute(builder: (_) => HotelDetailScreen(hotel: item)));
                    },
                    borderRadius: BorderRadius.circular(20),
                    child: Container(
                      margin: const EdgeInsets.only(bottom: 16),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(color: AppColors.border),
                        boxShadow: [
                          BoxShadow(
                            color: AppColors.primary.withOpacity(0.08),
                            blurRadius: 16,
                            offset: const Offset(0, 4),
                          ),
                        ],
                      ),
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(20),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Stack(
                              children: [
                                AppImage(
                                  imageUrl: _apiService.getImageUrl(item.thumbnail),
                                  height: 190,
                                  width: double.infinity,
                                  fit: BoxFit.cover,
                                ),
                                Positioned(
                                  top: 12,
                                  right: 12,
                                  child: Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                                    decoration: BoxDecoration(
                                      color: AppColors.textDark.withOpacity(0.8),
                                      borderRadius: BorderRadius.circular(12),
                                      border: Border.all(color: Colors.white.withOpacity(0.2)),
                                    ),
                                    child: const Row(
                                      children: [
                                        Icon(Icons.star_rounded, color: Color(0xFFF1C40F), size: 14),
                                        SizedBox(width: 4),
                                        Text('4.9', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12)),
                                      ],
                                    ),
                                  ),
                                ),
                              ],
                            ),
                            Padding(
                              padding: const EdgeInsets.all(16),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    item.name,
                                    style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppColors.textDark),
                                  ),
                                  const SizedBox(height: 8),
                                  Row(
                                    children: [
                                      const Icon(Icons.location_on_rounded, color: AppColors.primary, size: 16),
                                      const SizedBox(width: 4),
                                      Expanded(
                                        child: Text(
                                          item.address,
                                          style: const TextStyle(color: AppColors.textMuted, fontSize: 13),
                                          maxLines: 1,
                                          overflow: TextOverflow.ellipsis,
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ).animate().fadeIn(delay: (index * 60).ms, duration: 400.ms).slideY(begin: 0.1, end: 0);
                },
              ),
            ),
    );
  }
}