import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../services/api_service.dart';
import '../widgets/app_image.dart';
import '../theme/app_colors.dart';

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
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: AppColors.primary,
        elevation: 0,
        centerTitle: true,
        title: const Text('Agenda & Event ✨', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
          : _events.isEmpty
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Container(
                        padding: const EdgeInsets.all(20),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          shape: BoxShape.circle,
                          border: Border.all(color: AppColors.border),
                        ),
                        child: const Icon(Icons.event_busy_rounded, size: 56, color: AppColors.primary),
                      ),
                      const SizedBox(height: 16),
                      const Text(
                        'Belum Ada Event Saat Ini',
                        style: TextStyle(color: AppColors.textDark, fontSize: 16, fontWeight: FontWeight.bold),
                      ),
                    ],
                  ),
                )
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: _events.length,
                  itemBuilder: (context, index) {
                    final ev = _events[index];
                    return Container(
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
                                  imageUrl: _apiService.getImageUrl(ev['thumbnail'] ?? ev['image']),
                                  height: 180,
                                  width: double.infinity,
                                  fit: BoxFit.cover,
                                ),
                                if (ev['start_date'] != null)
                                  Positioned(
                                    top: 12,
                                    left: 12,
                                    child: Container(
                                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                                      decoration: BoxDecoration(
                                        color: AppColors.primary,
                                        borderRadius: BorderRadius.circular(12),
                                      ),
                                      child: Row(
                                        children: [
                                          const Icon(Icons.calendar_month_rounded, color: Colors.white, size: 14),
                                          const SizedBox(width: 4),
                                          Text(
                                            ev['start_date'],
                                            style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold),
                                          ),
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
                                    ev['name'] ?? ev['title'] ?? 'Event Wisata',
                                    style: const TextStyle(color: AppColors.textDark, fontSize: 18, fontWeight: FontWeight.bold),
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    ev['description'] ?? '',
                                    style: const TextStyle(color: AppColors.textMuted, fontSize: 13, height: 1.5),
                                    maxLines: 2,
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                  if (ev['location'] != null) ...[
                                    const SizedBox(height: 10),
                                    Row(
                                      children: [
                                        const Icon(Icons.location_on_rounded, color: AppColors.primary, size: 16),
                                        const SizedBox(width: 4),
                                        Expanded(
                                          child: Text(
                                            ev['location'],
                                            style: const TextStyle(color: AppColors.textDark, fontSize: 12, fontWeight: FontWeight.w600),
                                          ),
                                        ),
                                      ],
                                    ),
                                  ],
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                    ).animate().fadeIn(delay: (index * 60).ms, duration: 400.ms).slideY(begin: 0.1, end: 0);
                  },
                ),
    );
  }
}