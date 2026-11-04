import 'destination.dart';

class Ticket {
  final int id;
  final String ticketCode;
  final int? userId;
  final int destinationId;
  final String visitorName;
  final String visitorEmail;
  final String visitorPhone;
  final String visitDate;
  final int quantity;
  final double pricePerTicket;
  final double totalPrice;
  final String paymentMethod;
  final String status;
  final String createdAt;
  final Destination? destination;

  String get statusLabel => switch (status) {
    'paid' => 'LUNAS',
    'cancelled' => 'DIBATALKAN',
    _ => 'MENUNGGU PEMBAYARAN',
  };

  Ticket({
    required this.id,
    required this.ticketCode,
    this.userId,
    required this.destinationId,
    required this.visitorName,
    required this.visitorEmail,
    required this.visitorPhone,
    required this.visitDate,
    required this.quantity,
    required this.pricePerTicket,
    required this.totalPrice,
    required this.paymentMethod,
    required this.status,
    required this.createdAt,
    this.destination,
  });

  factory Ticket.fromJson(Map<String, dynamic> json) {
    return Ticket(
      id: json['id'],
      ticketCode: json['ticket_code'] ?? '',
      userId: json['user_id'],
      destinationId: json['destination_id'] ?? 0,
      visitorName: json['visitor_name'] ?? '',
      visitorEmail: json['visitor_email'] ?? '',
      visitorPhone: json['visitor_phone'] ?? '',
      visitDate: json['visit_date'] ?? '',
      quantity: json['quantity'] ?? 1,
      pricePerTicket: json['price_per_ticket'] != null
          ? double.parse(json['price_per_ticket'].toString())
          : 0.0,
      totalPrice: json['total_price'] != null
          ? double.parse(json['total_price'].toString())
          : 0.0,
      paymentMethod: json['payment_method'] ?? 'Bayar di lokasi',
      status: json['status'] ?? 'pending',
      createdAt: json['created_at'] ?? '',
      destination: json['destination'] != null
          ? Destination.fromJson(json['destination'])
          : null,
    );
  }
}
