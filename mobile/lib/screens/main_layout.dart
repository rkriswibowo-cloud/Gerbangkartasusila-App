import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import 'login_screen.dart';
import 'home_screen.dart';
import 'events_screen.dart';
import 'favorites_screen.dart';
import 'profile_screen.dart';
import 'hotels_screen.dart';
import 'culinary_screen.dart';
import 'articles_screen.dart';
import 'my_tickets_screen.dart';

class MainLayout extends StatefulWidget {
  const MainLayout({super.key});

  @override
  State<MainLayout> createState() => _MainLayoutState();
}

class _MainLayoutState extends State<MainLayout> {
  int _currentIndex = 0;

  final List<Widget> _pages = [
    const HomeScreen(),
    const EventsScreen(),
    const FavoritesScreen(),
    const ProfileScreen(),
  ];

  Future<void> _handleLogout(BuildContext context) async {
    Navigator.pop(context); // Close drawer first
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        backgroundColor: AppColors.background,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: const Text('Konfirmasi Keluar', style: TextStyle(color: AppColors.textDark, fontWeight: FontWeight.bold)),
        content: const Text('Apakah Anda yakin ingin keluar dari akun Gerbangkartasusila App?', style: TextStyle(color: AppColors.textMuted)),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Batal', style: TextStyle(color: AppColors.textMuted, fontWeight: FontWeight.bold)),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFFE74C3C),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Keluar', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );

    if (confirm == true) {
      await ApiService().logout();
      if (context.mounted) {
        Navigator.pushAndRemoveUntil(
          context,
          MaterialPageRoute(builder: (_) => const LoginScreen()),
          (route) => false,
        );
      }
    }
  }

  Widget _buildDrawer(BuildContext context) {
    return Drawer(
      backgroundColor: AppColors.background,
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          Container(
            padding: const EdgeInsets.only(top: 50, bottom: 24, left: 20, right: 20),
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                colors: [AppColors.primary, AppColors.primaryDark],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              borderRadius: BorderRadius.only(bottomRight: Radius.circular(32)),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  padding: const EdgeInsets.all(3),
                  decoration: BoxDecoration(
                    color: AppColors.accent.withValues(alpha: 0.4),
                    shape: BoxShape.circle,
                  ),
                  child: const CircleAvatar(
                    radius: 32,
                    backgroundColor: AppColors.background,
                    child: Icon(Icons.person, size: 38, color: AppColors.primary),
                  ),
                ),
                const SizedBox(height: 16),
                const Text(
                  'Gerbangkartasusila App ✨',
                  style: TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 4),
                const Text(
                  'Jelajahi Pesona Wisata Gerbangkartasusila',
                  style: TextStyle(color: AppColors.accent, fontSize: 13, fontWeight: FontWeight.w500),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          _buildDrawerTile(
            icon: Icons.hotel_rounded,
            color: AppColors.primary,
            title: 'Hotel & Penginapan',
            onTap: () {
              Navigator.pop(context);
              Navigator.push(context, MaterialPageRoute(builder: (_) => const HotelsScreen()));
            },
          ),
          _buildDrawerTile(
            icon: Icons.restaurant_rounded,
            color: const Color(0xFFE67E22),
            title: 'Wisata Kuliner Khas',
            onTap: () {
              Navigator.pop(context);
              Navigator.push(context, MaterialPageRoute(builder: (_) => const CulinaryScreen()));
            },
          ),
          _buildDrawerTile(
            icon: Icons.article_rounded,
            color: const Color(0xFF27AE60),
            title: 'Artikel & Berita Wisata',
            onTap: () {
              Navigator.pop(context);
              Navigator.push(context, MaterialPageRoute(builder: (_) => const ArticlesScreen()));
            },
          ),
          _buildDrawerTile(
            icon: Icons.confirmation_number_rounded,
            color: AppColors.primary,
            title: 'Tiket Saya',
            onTap: () {
              Navigator.pop(context);
              Navigator.push(context, MaterialPageRoute(builder: (_) => const MyTicketsScreen()));
            },
          ),
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 20.0, vertical: 8),
            child: Divider(color: AppColors.border),
          ),
          _buildDrawerTile(
            icon: Icons.logout_rounded,
            color: const Color(0xFFE74C3C),
            title: 'Keluar',
            onTap: () => _handleLogout(context),
          ),
        ],
      ),
    );
  }

  Widget _buildDrawerTile({
    required IconData icon,
    required Color color,
    required String title,
    required VoidCallback onTap,
  }) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: AppColors.primary.withValues(alpha: 0.06),
            blurRadius: 10,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: ListTile(
        leading: Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: color.withValues(alpha: 0.12),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Icon(icon, color: color, size: 22),
        ),
        title: Text(
          title,
          style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 14, color: AppColors.textDark),
        ),
        trailing: const Icon(Icons.arrow_forward_ios_rounded, size: 14, color: AppColors.textMuted),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        onTap: onTap,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      drawer: _buildDrawer(context),
      body: _pages[_currentIndex],
      bottomNavigationBar: Container(
        margin: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: AppColors.primary,
          borderRadius: BorderRadius.circular(24),
          boxShadow: [
            BoxShadow(
              color: AppColors.primary.withValues(alpha: 0.35),
              blurRadius: 20,
              offset: const Offset(0, 6),
            ),
          ],
        ),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(24),
          child: NavigationBar(
            height: 65,
            backgroundColor: AppColors.primary,
            elevation: 0,
            indicatorColor: AppColors.accent,
            selectedIndex: _currentIndex,
            onDestinationSelected: (index) {
              setState(() {
                _currentIndex = index;
              });
            },
            destinations: const [
              NavigationDestination(
                icon: Icon(Icons.explore_outlined, color: Colors.white70),
                selectedIcon: Icon(Icons.explore_rounded, color: AppColors.primaryDark),
                label: 'Jelajah',
              ),
              NavigationDestination(
                icon: Icon(Icons.event_note_outlined, color: Colors.white70),
                selectedIcon: Icon(Icons.event_note_rounded, color: AppColors.primaryDark),
                label: 'Event',
              ),
              NavigationDestination(
                icon: Icon(Icons.bookmark_outline_rounded, color: Colors.white70),
                selectedIcon: Icon(Icons.bookmark_rounded, color: AppColors.primaryDark),
                label: 'Favorit',
              ),
              NavigationDestination(
                icon: Icon(Icons.person_outline_rounded, color: Colors.white70),
                selectedIcon: Icon(Icons.person_rounded, color: AppColors.primaryDark),
                label: 'Profil',
              ),
            ],
          ),
        ),
      ),
    );
  }
}
