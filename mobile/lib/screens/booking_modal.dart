import 'package:flutter/material.dart';
import '../models/destination.dart';
import '../models/ticket.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import 'e_ticket_screen.dart';

class BookingModal extends StatefulWidget {
  final Destination destination;

  const BookingModal({super.key, required this.destination});

  static void show(BuildContext context, Destination destination) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => BookingModal(destination: destination),
    );
  }

  @override
  State<BookingModal> createState() => _BookingModalState();
}

class _BookingModalState extends State<BookingModal> {
  final _formKey = GlobalKey<FormState>();
  final _apiService = ApiService();

  late DateTime _selectedDate;
  int _quantity = 1;
  String _selectedPayment = 'QRIS';
  bool _isLoading = false;

  late TextEditingController _nameController;
  late TextEditingController _emailController;
  late TextEditingController _phoneController;

  final List<Map<String, dynamic>> _paymentMethods = [
    {'name': 'QRIS Instant', 'icon': Icons.qr_code_2_rounded, 'color': AppColors.primary},
    {'name': 'Transfer BCA', 'icon': Icons.account_balance_rounded, 'color': Color(0xFF2980B9)},
    {'name': 'GoPay / OVO', 'icon': Icons.account_balance_wallet_rounded, 'color': Color(0xFFD35400)},
    {'name': 'ShopeePay', 'icon': Icons.shopping_bag_rounded, 'color': Color(0xFFC0392B)},
  ];

  @override
  void initState() {
    super.initState();
    _selectedDate = DateTime.now().add(const Duration(days: 1));
    _nameController = TextEditingController(text: 'Wisatawan');
    _emailController = TextEditingController(text: 'visitor@gerbangkartasusila.id');
    _phoneController = TextEditingController(text: '081234567890');
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _phoneController.dispose();
    super.dispose();
  }

  double get _ticketPrice => widget.destination.price ?? 15000.0;
  double get _totalPrice => _ticketPrice * _quantity;

  String _formatCurrency(double amount) {
    return 'Rp ${amount.toStringAsFixed(0).replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}';
  }

  Future<void> _pickDate() async {
    final picked = await showDatePicker(
      context: context,
      initialDate: _selectedDate,
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 90)),
      builder: (context, child) {
        return Theme(
          data: ThemeData.light().copyWith(
            colorScheme: const ColorScheme.light(
              primary: AppColors.primary,
              onPrimary: Colors.white,
              surface: AppColors.background,
              onSurface: AppColors.textDark,
            ),
            dialogBackgroundColor: AppColors.background,
          ),
          child: child!,
        );
      },
    );

    if (picked != null) {
      setState(() {
        _selectedDate = picked;
      });
    }
  }

  Future<void> _processBooking() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _isLoading = true;
    });

    final formattedDate = '${_selectedDate.year}-${_selectedDate.month.toString().padLeft(2, '0')}-${_selectedDate.day.toString().padLeft(2, '0')}';

    final payload = {
      'destination_id': widget.destination.id,
      'visitor_name': _nameController.text.trim(),
      'visitor_email': _emailController.text.trim(),
      'visitor_phone': _phoneController.text.trim(),
      'visit_date': formattedDate,
      'quantity': _quantity,
      'payment_method': _selectedPayment,
    };

    final result = await _apiService.bookTicket(payload);

    setState(() {
      _isLoading = false;
    });

    if (result != null && result['success'] == true) {
      final ticketData = result['data'];
      final ticket = Ticket.fromJson(ticketData);

      if (mounted) {
        Navigator.pop(context); // Close modal
        Navigator.push(
          context,
          MaterialPageRoute(builder: (_) => ETicketScreen(ticket: ticket)),
        );
      }
    } else {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Gagal memesan tiket. Silakan coba lagi.'),
            backgroundColor: Color(0xFFE74C3C),
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      constraints: BoxConstraints(
        maxHeight: MediaQuery.of(context).size.height * 0.9,
      ),
      decoration: const BoxDecoration(
        color: AppColors.background,
        borderRadius: BorderRadius.vertical(top: Radius.circular(28)),
        border: Border(top: BorderSide(color: AppColors.primary, width: 2)),
      ),
      child: Padding(
        padding: EdgeInsets.only(
          left: 20,
          right: 20,
          top: 16,
          bottom: MediaQuery.of(context).viewInsets.bottom + 20,
        ),
        child: Form(
          key: _formKey,
          child: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Top Grab Bar
                Center(
                  child: Container(
                    width: 44,
                    height: 5,
                    decoration: BoxDecoration(
                      color: AppColors.primary.withOpacity(0.3),
                      borderRadius: BorderRadius.circular(10),
                    ),
                  ),
                ),
                const SizedBox(height: 16),

                // Title Section
                Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(
                        color: AppColors.primary.withOpacity(0.12),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: const Icon(Icons.confirmation_num_rounded, color: AppColors.primary, size: 24),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text(
                            'Pesan Tiket Wisata',
                            style: TextStyle(color: AppColors.textDark, fontSize: 18, fontWeight: FontWeight.bold),
                          ),
                          Text(
                            widget.destination.name,
                            style: const TextStyle(color: AppColors.primary, fontSize: 13, fontWeight: FontWeight.w600),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ],
                      ),
                    ),
                  ],
                ),

                const SizedBox(height: 20),
                const Divider(color: AppColors.border),
                const SizedBox(height: 12),

                // Date Picker
                const Text('Tanggal Kunjungan', style: TextStyle(color: AppColors.textDark, fontWeight: FontWeight.bold, fontSize: 14)),
                const SizedBox(height: 8),
                InkWell(
                  onTap: _pickDate,
                  borderRadius: BorderRadius.circular(16),
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: AppColors.border),
                    ),
                    child: Row(
                      children: [
                        const Icon(Icons.calendar_month_rounded, color: AppColors.primary, size: 20),
                        const SizedBox(width: 12),
                        Text(
                          '${_selectedDate.day}/${_selectedDate.month}/${_selectedDate.year}',
                          style: const TextStyle(color: AppColors.textDark, fontSize: 14, fontWeight: FontWeight.w600),
                        ),
                        const Spacer(),
                        const Text('Ubah Tanggal', style: TextStyle(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.bold)),
                      ],
                    ),
                  ),
                ),

                const SizedBox(height: 16),

                // Quantity Selector
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Jumlah Tiket', style: TextStyle(color: AppColors.textDark, fontWeight: FontWeight.bold, fontSize: 14)),
                        Text('${_formatCurrency(_ticketPrice)} / orang', style: const TextStyle(color: AppColors.textMuted, fontSize: 12)),
                      ],
                    ),
                    Container(
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(14),
                        border: Border.all(color: AppColors.border),
                      ),
                      child: Row(
                        children: [
                          IconButton(
                            onPressed: _quantity > 1 ? () => setState(() => _quantity--) : null,
                            icon: const Icon(Icons.remove_rounded),
                            color: AppColors.textDark,
                            disabledColor: AppColors.textMuted.withOpacity(0.4),
                            iconSize: 20,
                          ),
                          Padding(
                            padding: const EdgeInsets.symmetric(horizontal: 12),
                            child: Text(
                              '$_quantity',
                              style: const TextStyle(color: AppColors.textDark, fontSize: 16, fontWeight: FontWeight.bold),
                            ),
                          ),
                          IconButton(
                            onPressed: () => setState(() => _quantity++),
                            icon: const Icon(Icons.add_rounded),
                            color: AppColors.textDark,
                            iconSize: 20,
                          ),
                        ],
                      ),
                    ),
                  ],
                ),

                const SizedBox(height: 16),

                // Visitor Details Form
                const Text('Data Pengunjung', style: TextStyle(color: AppColors.textDark, fontWeight: FontWeight.bold, fontSize: 14)),
                const SizedBox(height: 8),
                TextFormField(
                  controller: _nameController,
                  style: const TextStyle(color: AppColors.textDark, fontSize: 14),
                  decoration: _inputDecoration('Nama Lengkap', Icons.person_outline_rounded),
                  validator: (v) => (v == null || v.isEmpty) ? 'Nama harus diisi' : null,
                ),
                const SizedBox(height: 10),
                TextFormField(
                  controller: _emailController,
                  style: const TextStyle(color: AppColors.textDark, fontSize: 14),
                  decoration: _inputDecoration('Alamat Email', Icons.email_outlined),
                  validator: (v) => (v == null || v.isEmpty) ? 'Email harus diisi' : null,
                ),
                const SizedBox(height: 10),
                TextFormField(
                  controller: _phoneController,
                  style: const TextStyle(color: AppColors.textDark, fontSize: 14),
                  decoration: _inputDecoration('No. Telepon / WhatsApp', Icons.phone_outlined),
                  validator: (v) => (v == null || v.isEmpty) ? 'No. HP harus diisi' : null,
                ),

                const SizedBox(height: 16),

                // Payment Method Selector
                const Text('Metode Pembayaran', style: TextStyle(color: AppColors.textDark, fontWeight: FontWeight.bold, fontSize: 14)),
                const SizedBox(height: 8),
                Wrap(
                  spacing: 10,
                  runSpacing: 10,
                  children: _paymentMethods.map((method) {
                    final isSelected = _selectedPayment == method['name'];
                    return InkWell(
                      onTap: () => setState(() => _selectedPayment = method['name']),
                      borderRadius: BorderRadius.circular(14),
                      child: AnimatedContainer(
                        duration: const Duration(milliseconds: 200),
                        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                        decoration: BoxDecoration(
                          color: isSelected ? AppColors.primary : Colors.white,
                          borderRadius: BorderRadius.circular(14),
                          border: Border.all(
                            color: isSelected ? AppColors.primary : AppColors.border,
                            width: isSelected ? 1.8 : 1.0,
                          ),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(method['icon'] as IconData, size: 18, color: isSelected ? Colors.white : method['color'] as Color),
                            const SizedBox(width: 8),
                            Text(
                              method['name'] as String,
                              style: TextStyle(
                                color: isSelected ? Colors.white : AppColors.textDark,
                                fontSize: 12,
                                fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                              ),
                            ),
                          ],
                        ),
                      ),
                    );
                  }).toList(),
                ),

                const SizedBox(height: 20),
                const Divider(color: AppColors.border),
                const SizedBox(height: 12),

                // Total Price & Submit Button
                Row(
                  children: [
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text('Total Pembayaran', style: TextStyle(color: AppColors.textMuted, fontSize: 12)),
                        const SizedBox(height: 2),
                        Text(
                          _formatCurrency(_totalPrice),
                          style: const TextStyle(color: AppColors.primary, fontSize: 20, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: Container(
                        decoration: BoxDecoration(
                          gradient: const LinearGradient(
                            colors: [AppColors.primary, AppColors.primaryDark],
                          ),
                          borderRadius: BorderRadius.circular(16),
                          boxShadow: [
                            BoxShadow(
                              color: AppColors.primary.withOpacity(0.35),
                              blurRadius: 16,
                              offset: const Offset(0, 4),
                            ),
                          ],
                        ),
                        child: ElevatedButton(
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.transparent,
                            shadowColor: Colors.transparent,
                            padding: const EdgeInsets.symmetric(vertical: 16),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                          ),
                          onPressed: _isLoading ? null : _processBooking,
                          child: _isLoading
                              ? const SizedBox(
                                  height: 20,
                                  width: 20,
                                  child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                                )
                              : const Text(
                                  'Konfirmasi & Bayar',
                                  style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.white),
                                ),
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  InputDecoration _inputDecoration(String label, IconData icon) {
    return InputDecoration(
      labelText: label,
      labelStyle: const TextStyle(color: AppColors.textMuted, fontSize: 13),
      prefixIcon: Icon(icon, color: AppColors.primary, size: 18),
      filled: true,
      fillColor: Colors.white,
      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
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
