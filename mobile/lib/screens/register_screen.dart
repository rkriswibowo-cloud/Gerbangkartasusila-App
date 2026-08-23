import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import 'main_layout.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _formKey = GlobalKey<FormState>();
  final ApiService _apiService = ApiService();

  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  bool _isLoading = false;
  bool _obscurePassword = true;

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _handleRegister() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _isLoading = true;
    });

    final response = await _apiService.register(
      _nameController.text.trim(),
      _emailController.text.trim(),
      _passwordController.text,
    );

    setState(() {
      _isLoading = false;
    });

    if (response != null && (response['status'] == 'success' || response['access_token'] != null)) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(response['message'] ?? 'Pendaftaran berhasil! Selamat bergabung.'),
            backgroundColor: AppColors.primary,
          ),
        );
        Navigator.pushAndRemoveUntil(
          context,
          MaterialPageRoute(builder: (_) => const MainLayout()),
          (route) => false,
        );
      }
    } else {
      if (mounted) {
        final errorMsg = response?['message'] is String
            ? response!['message']
            : 'Pendaftaran gagal. Pastikan email belum pernah terdaftar.';
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(errorMsg),
            backgroundColor: const Color(0xFFE74C3C),
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: AppBar(
        title: const Text('Buat Akun Baru ✨', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 18)),
        backgroundColor: AppColors.primary,
        elevation: 0,
        centerTitle: true,
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(28.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const SizedBox(height: 12),
              const Text(
                'Bergabung dengan Gerbangkartasusila App',
                style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: AppColors.textDark),
              ),
              const SizedBox(height: 6),
              const Text(
                'Isi formulir di bawah ini untuk membuat akun baru.',
                style: TextStyle(fontSize: 13, color: AppColors.textMuted),
              ),
              
              const SizedBox(height: 28),
              
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(24),
                  border: Border.all(color: AppColors.border),
                  boxShadow: [
                    BoxShadow(
                      color: AppColors.primary.withOpacity(0.08),
                      blurRadius: 16,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: Column(
                  children: [
                    TextFormField(
                      controller: _nameController,
                      style: const TextStyle(color: AppColors.textDark, fontSize: 14),
                      decoration: _buildInputDecoration('Nama Lengkap', Icons.person_outline_rounded),
                      validator: (v) => (v == null || v.trim().isEmpty) ? 'Nama lengkap harus diisi' : null,
                    ),
                    
                    const SizedBox(height: 16),
                    
                    TextFormField(
                      controller: _emailController,
                      style: const TextStyle(color: AppColors.textDark, fontSize: 14),
                      keyboardType: TextInputType.emailAddress,
                      decoration: _buildInputDecoration('Email', Icons.email_outlined),
                      validator: (v) => (v == null || v.trim().isEmpty) ? 'Email harus diisi' : null,
                    ),
                    
                    const SizedBox(height: 16),
                    
                    TextFormField(
                      controller: _passwordController,
                      obscureText: _obscurePassword,
                      style: const TextStyle(color: AppColors.textDark, fontSize: 14),
                      decoration: _buildInputDecoration('Kata Sandi', Icons.lock_outline_rounded).copyWith(
                        suffixIcon: IconButton(
                          icon: Icon(
                            _obscurePassword ? Icons.visibility_off_rounded : Icons.visibility_rounded,
                            color: AppColors.textMuted,
                            size: 20,
                          ),
                          onPressed: () {
                            setState(() {
                              _obscurePassword = !_obscurePassword;
                            });
                          },
                        ),
                      ),
                      validator: (v) => (v == null || v.length < 6) ? 'Kata sandi minimal 6 karakter' : null,
                    ),
                  ],
                ),
              ),
              
              const SizedBox(height: 28),
              
              Container(
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [AppColors.primary, AppColors.primaryDark],
                  ),
                  borderRadius: BorderRadius.circular(16),
                  boxShadow: [
                    BoxShadow(
                      color: AppColors.primary.withOpacity(0.35),
                      blurRadius: 20,
                      offset: const Offset(0, 8),
                    ),
                  ],
                ),
                child: Material(
                  color: Colors.transparent,
                  child: InkWell(
                    borderRadius: BorderRadius.circular(16),
                    onTap: _isLoading ? null : _handleRegister,
                    child: Padding(
                      padding: const EdgeInsets.symmetric(vertical: 16),
                      child: Center(
                        child: _isLoading
                            ? const SizedBox(
                                height: 22,
                                width: 22,
                                child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5),
                              )
                            : const Text(
                                'DAFTAR AKUN',
                                style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.white),
                              ),
                      ),
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  InputDecoration _buildInputDecoration(String label, IconData icon) {
    return InputDecoration(
      labelText: label,
      labelStyle: const TextStyle(color: AppColors.textMuted, fontSize: 13),
      prefixIcon: Icon(icon, color: AppColors.primary, size: 20),
      filled: true,
      fillColor: Colors.white,
      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: const BorderSide(color: AppColors.border),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: const BorderSide(color: AppColors.primary, width: 1.5),
      ),
      errorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: const BorderSide(color: Color(0xFFE74C3C)),
      ),
    );
  }
}