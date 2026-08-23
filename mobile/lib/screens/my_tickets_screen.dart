import 'package:flutter/material.dart';
import '../models/ticket.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import '../widgets/app_image.dart';
import 'e_ticket_screen.dart';

class MyTicketsScreen extends StatefulWidget {
  const MyTicketsScreen({super.key});

  @override
  State<MyTicketsScreen> createState() => _MyTicketsScreenState();
}

class _MyTicketsScreenState extends State<MyTicketsScreen> {
  final ApiService _apiService = ApiService();
  late Future<List<dynamic>> _ticketsFuture;

  @override
  void initState() {
    super.initState();
    _loadTickets();
  }

  void _loadTickets() {
    setState(() {
      _ticketsFuture = _apiService.getTickets();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Tiket Saya', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
        backgroundColor: AppColors.primary,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.white),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh_rounded),
            onPressed: _loadTickets,
          ),
        ],
      ),
      body: FutureBuilder<List<dynamic>>(
        future: _ticketsFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator(color: AppColors.primary));
          }

          if (snapshot.hasError || !snapshot.hasData || snapshot.data!.isEmpty) {
            return Center(
              child: Padding(
                padding: const EdgeInsets.all(24.0),
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
                      child: const Icon(Icons.confirmation_number_outlined, size: 56, color: AppColors.primary),
                    ),
                    const SizedBox(height: 20),
                    const Text(
                      'Belum Ada Tiket',
                      style: TextStyle(color: AppColors.textDark, fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 8),
                    const Text(
                      'Anda belum memesan tiket wisata. Pilih destinasi impian Anda dan pesan tiket dengan mudah!',
                      textAlign: TextAlign.center,
                      style: TextStyle(color: AppColors.textMuted, fontSize: 13),
                    ),
                  ],
                ),
              ),
            );
          }

          final rawList = snapshot.data!;
          final tickets = rawList.map((item) => Ticket.fromJson(item)).toList();

          return ListView.builder(
            padding: const EdgeInsets.all(16),
            itemCount: tickets.length,
            itemBuilder: (context, index) {
              final ticket = tickets[index];
              final dest = ticket.destination;
              final formattedTotal = 'Rp ${ticket.totalPrice.toStringAsFixed(0).replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}';

              return Container(
                margin: const EdgeInsets.only(bottom: 16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: AppColors.border),
                  boxShadow: [
                    BoxShadow(
                      color: AppColors.primary.withOpacity(0.08),
                      blurRadius: 12,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: InkWell(
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(builder: (_) => ETicketScreen(ticket: ticket)),
                    );
                  },
                  borderRadius: BorderRadius.circular(20),
                  child: Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            ClipRRect(
                              borderRadius: BorderRadius.circular(12),
                              child: AppImage(
                                imageUrl: _apiService.getImageUrl(dest?.imagePath),
                                width: 70,
                                height: 70,
                                fit: BoxFit.cover,
                              ),
                            ),
                            const SizedBox(width: 14),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                    decoration: BoxDecoration(
                                      color: AppColors.accent,
                                      borderRadius: BorderRadius.circular(6),
                                    ),
                                    child: const Text(
                                      'AKTIF / LUNAS',
                                      style: TextStyle(color: AppColors.textDark, fontSize: 10, fontWeight: FontWeight.bold),
                                    ),
                                  ),
                                  const SizedBox(height: 6),
                                  Text(
                                    dest?.name ?? 'Wisata',
                                    style: const TextStyle(color: AppColors.textDark, fontSize: 16, fontWeight: FontWeight.bold),
                                    maxLines: 1,
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                  const SizedBox(height: 4),
                                  Text(
                                    ticket.ticketCode,
                                    style: const TextStyle(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w600, letterSpacing: 1),
                                  ),
                                ],
                              ),
                            ),
                            const Icon(Icons.arrow_forward_ios_rounded, size: 16, color: AppColors.textMuted),
                          ],
                        ),
                        const SizedBox(height: 12),
                        const Divider(color: AppColors.border),
                        const SizedBox(height: 6),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Row(
                              children: [
                                const Icon(Icons.calendar_month_rounded, size: 14, color: AppColors.primary),
                                const SizedBox(width: 6),
                                Text(
                                  ticket.visitDate,
                                  style: const TextStyle(color: AppColors.textMuted, fontSize: 12),
                                ),
                              ],
                            ),
                            Text(
                              '${ticket.quantity} Tiket • $formattedTotal',
                              style: const TextStyle(color: AppColors.textDark, fontSize: 13, fontWeight: FontWeight.bold),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}
