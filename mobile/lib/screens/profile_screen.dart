import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import 'login_screen.dart';
import 'my_tickets_screen.dart';
import 'edit_profile_screen.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  String _userName = 'John Doe';
  String _userEmail = 'johndoe@example.com';
  String _userPhone = '081234567890';

  Future<void> _openEditProfile() async {
    final updatedData = await Navigator.push<Map<String, String>>(
      context,
      MaterialPageRoute(
        builder: (_) => EditProfileScreen(
          currentName: _userName,
          currentEmail: _userEmail,
          currentPhone: _userPhone,
        ),
      ),
    );

    if (updatedData != null) {
      setState(() {
        if (updatedData['name'] != null && updatedData['name']!.isNotEmpty) {
          _userName = updatedData['name']!;
        }
        if (updatedData['email'] != null && updatedData['email']!.isNotEmpty) {
          _userEmail = updatedData['email']!;
        }
        if (updatedData['phone'] != null) {
          _userPhone = updatedData['phone']!;
        }
      });
    }
  }

  Future<void> _handleLogout() async {
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
      if (mounted) {
        Navigator.pushAndRemoveUntil(
          context,
          MaterialPageRoute(builder: (_) => const LoginScreen()),
          (route) => false,
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        backgroundColor: AppColors.primary,
        elevation: 0,
        centerTitle: true,
        title: const Text('Profil Pengguna 👤', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)),
      ),
      body: ListView(
        padding: const EdgeInsets.all(20),
        children: [
          // Profile Card Header
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              gradient: const LinearGradient(
                colors: [AppColors.primary, AppColors.primaryDark],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              borderRadius: BorderRadius.circular(24),
              boxShadow: [
                BoxShadow(
                  color: AppColors.primary.withOpacity(0.35),
                  blurRadius: 20,
                  offset: const Offset(0, 8),
                ),
              ],
            ),
            child: Column(
              children: [
                Container(
                  padding: const EdgeInsets.all(4),
                  decoration: BoxDecoration(
                    color: AppColors.accent.withOpacity(0.4),
                    shape: BoxShape.circle,
                  ),
                  child: const CircleAvatar(
                    radius: 42,
                    backgroundColor: AppColors.background,
                    child: Icon(Icons.person_rounded, size: 48, color: AppColors.primary),
                  ),
                ),
                const SizedBox(height: 14),
                Text(
                  _userName,
                  style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.white),
                ),
                const SizedBox(height: 4),
                Text(
                  _userEmail,
                  style: const TextStyle(fontSize: 13, color: AppColors.accent),
                ),
                const SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                  children: [
                    _buildStatItem('12', 'Disimpan'),
                    Container(height: 24, width: 1, color: Colors.white.withOpacity(0.3)),
                    _buildStatItem('5', 'Dikunjungi'),
                    Container(height: 24, width: 1, color: Colors.white.withOpacity(0.3)),
                    _buildStatItem('4.9', 'Rating User'),
                  ],
                ),
              ],
            ),
          ),
          
          const SizedBox(height: 24),
          
          // Settings Menu List
          _buildMenuSection(
            'Layanan Wisatawan',
            [
              _buildMenuItem(Icons.confirmation_number_rounded, 'Tiket Saya (E-Ticket)', () {
                Navigator.push(context, MaterialPageRoute(builder: (_) => const MyTicketsScreen()));
              }),
            ],
          ),
          
          const SizedBox(height: 16),

          _buildMenuSection(
            'Pengaturan Akun',
            [
              _buildMenuItem(Icons.person_outline_rounded, 'Edit Profil', _openEditProfile),
              _buildMenuItem(Icons.notifications_outlined, 'Notifikasi', () {}),
              _buildMenuItem(Icons.lock_outline_rounded, 'Keamanan & Kata Sandi', _openEditProfile),
            ],
          ),
          
          const SizedBox(height: 16),
          
          _buildMenuSection(
            'Dukungan & Lainnya',
            [
              _buildMenuItem(Icons.help_outline_rounded, 'Bantuan & FAQ', () {}),
              _buildMenuItem(Icons.info_outline_rounded, 'Tentang Aplikasi', () {}),
              _buildMenuItem(
                Icons.logout_rounded,
                'Keluar dari Akun',
                _handleLogout,
                isDanger: true,
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildStatItem(String count, String label) {
    return Column(
      children: [
        Text(count, style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 16)),
        const SizedBox(height: 2),
        Text(label, style: const TextStyle(color: AppColors.accent, fontSize: 11)),
      ],
    );
  }

  Widget _buildMenuSection(String title, List<Widget> items) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.only(left: 8, bottom: 8),
          child: Text(
            title,
            style: const TextStyle(color: AppColors.primary, fontSize: 13, fontWeight: FontWeight.bold),
          ),
        ),
        Container(
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(20),
            border: Border.all(color: AppColors.border),
          ),
          child: Column(children: items),
        ),
      ],
    );
  }

  Widget _buildMenuItem(IconData icon, String title, VoidCallback onTap, {bool isDanger = false}) {
    return ListTile(
      leading: Container(
        padding: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: isDanger ? const Color(0xFFE74C3C).withOpacity(0.12) : AppColors.primary.withOpacity(0.12),
          borderRadius: BorderRadius.circular(12),
        ),
        child: Icon(icon, color: isDanger ? const Color(0xFFE74C3C) : AppColors.primary, size: 20),
      ),
      title: Text(
        title,
        style: TextStyle(
          color: isDanger ? const Color(0xFFE74C3C) : AppColors.textDark,
          fontSize: 14,
          fontWeight: FontWeight.w600,
        ),
      ),
      trailing: const Icon(Icons.arrow_forward_ios_rounded, size: 14, color: AppColors.textMuted),
      onTap: onTap,
    );
  }
}