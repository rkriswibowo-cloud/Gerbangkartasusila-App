import 'package:flutter/material.dart';
import '../theme/app_colors.dart';

class FavoritesScreen extends StatelessWidget {
  const FavoritesScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: AppColors.primary,
        elevation: 0,
        centerTitle: true,
        title: const Text('Wisata Favorit Saya ❤️', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)),
      ),
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(32.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: Colors.white,
                  shape: BoxShape.circle,
                  border: Border.all(color: AppColors.border),
                  boxShadow: [
                    BoxShadow(
                      color: AppColors.primary.withOpacity(0.12),
                      blurRadius: 24,
                      offset: const Offset(0, 8),
                    ),
                  ],
                ),
                child: const Icon(Icons.bookmark_border_rounded, size: 64, color: AppColors.primary),
              ),
              const SizedBox(height: 24),
              const Text(
                'Belum Ada Destinasi Tersimpan',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppColors.textDark),
              ),
              const SizedBox(height: 8),
              const Text(
                'Tandai tempat wisata favorit Anda dengan menekan ikon bookmark untuk menyimpannya di sini.',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 13, color: AppColors.textMuted, height: 1.5),
              ),
            ],
          ),
        ),
      ),
    );
  }
}