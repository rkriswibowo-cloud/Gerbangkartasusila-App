import 'package:flutter/material.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import 'main_layout.dart';
import 'register_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final ApiService _apiService = ApiService();

  final TextEditingController _emailController = TextEditingController(text: 'johndoe@example.com');
  final TextEditingController _passwordController = TextEditingController(text: 'password123');

  bool _isLoading = false;
  bool _obscurePassword = true;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _handleLogin() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _isLoading = true;
    });

    final response = await _apiService.login(
      _emailController.text.trim(),
      _passwordController.text,
    );

    setState(() {
      _isLoading = false;
    });

    if (response != null && response['status'] == 'success') {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(response['message'] ?? 'Selamat Datang Kembali! Log in Berhasil.'),
            backgroundColor: AppColors.primary,
          ),
        );
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (_) => const MainLayout()),
        );
      }
    } else {
      if (mounted) {
        final errorMsg = response?['message'] is String
            ? response!['message']
            : 'Login gagal. Periksa kembali email dan kata sandi Anda.';
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
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(28.0),
          child: Form(
            key: _formKey,
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                // Top Branding Icon
                Center(
                  child: Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      gradient: const LinearGradient(
                        colors: [AppColors.primary, AppColors.primaryDark],
                      ),
                      shape: BoxShape.circle,
                      boxShadow: [
                        BoxShadow(
                          color: AppColors.primary.withOpacity(0.35),
                          blurRadius: 30,
                          offset: const Offset(0, 10),
                        ),
                      ],
                    ),
                    child: const Icon(Icons.travel_explore_rounded, size: 64, color: Colors.white),
                  ).animate().scale(duration: 500.ms, curve: Curves.easeOutBack),
                ),
                
                const SizedBox(height: 24),
                
                const Text(
                  'Selamat Datang Kembali ✨',
                  textAlign: TextAlign.center,
                  style: TextStyle(fontSize: 26, fontWeight: FontWeight.bold, color: AppColors.textDark),
                ),
                const SizedBox(height: 8),
                const Text(
                  'Masuk untuk menjelajahi keindahan wisata Gerbangkartasusila',
                  textAlign: TextAlign.center,
                  style: TextStyle(fontSize: 14, color: AppColors.textMuted),
                ),
                
                const SizedBox(height: 32),
                
                // Form Fields Container
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
                        validator: (v) => (v == null || v.isEmpty) ? 'Kata sandi harus diisi' : null,
                      ),
                    ],
                  ),
                ),
                
                const SizedBox(height: 28),
                
                // Login Button
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
                      onTap: _isLoading ? null : _handleLogin,
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
                                  'MASUK KELUARGA WISATA',
                                  style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.white),
                                ),
                        ),
                      ),
                    ),
                  ),
                ),
                
                const SizedBox(height: 20),
                
                TextButton(
                  onPressed: () {
                    Navigator.push(context, MaterialPageRoute(builder: (_) => const RegisterScreen()));
                  },
                  child: const Text(
                    'Belum punya akun? Daftar Sekarang',
                    style: TextStyle(color: AppColors.primary, fontWeight: FontWeight.bold, fontSize: 14),
                  ),
                ),
              ],
            ),
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