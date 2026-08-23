import 'package:flutter/foundation.dart';
import 'package:dio/dio.dart';

class ApiService {
  static String get host {
    if (kIsWeb) return 'http://localhost:8000';
    return 'http://127.0.0.1:8000';
  }

  static String get baseUrl => '$host/api'; 

  late final Dio _dio;

  ApiService() {
    _dio = Dio(BaseOptions(
      baseUrl: baseUrl,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    ));
  }

  Future<List<dynamic>> getDestinations() async {
    try {
      final response = await _dio.get('/destinations');
      final data = response.data['data'];
      return (data is List) ? data : (data['data'] ?? []);
    } catch (e) {
      return [];
    }
  }

  Future<Map<String, dynamic>?> getDestinationDetail(String slug) async {
    try {
      final response = await _dio.get('/destinations/$slug');
      return response.data['data'];
    } catch (e) {
      return null;
    }
  }

  Future<List<dynamic>> getEvents() async {
    try {
      final response = await _dio.get('/events');
      final data = response.data['data'];
      return (data is List) ? data : (data['data'] ?? []);
    } catch (e) {
      return [];
    }
  }

  Future<List<dynamic>> getHotels() async {
    try {
      final response = await _dio.get('/hotels');
      final data = response.data['data'];
      return (data is List) ? data : (data['data'] ?? []);
    } catch (e) {
      return [];
    }
  }

  Future<List<dynamic>> getCulinary() async {
    try {
      final response = await _dio.get('/culinary');
      final data = response.data['data'];
      return (data is List) ? data : (data['data'] ?? []);
    } catch (e) {
      return [];
    }
  }

  Future<List<dynamic>> getArticles() async {
    try {
      final response = await _dio.get('/articles');
      final data = response.data['data'];
      return (data is List) ? data : (data['data'] ?? []);
    } catch (e) {
      return [];
    }
  }

  Future<Map<String, dynamic>?> bookTicket(Map<String, dynamic> ticketData) async {
    try {
      final response = await _dio.post('/tickets', data: ticketData);
      return response.data;
    } catch (e) {
      return null;
    }
  }

  Future<List<dynamic>> getTickets() async {
    try {
      final response = await _dio.get('/tickets');
      final data = response.data['data'];
      return (data is List) ? data : [];
    } catch (e) {
      return [];
    }
  }

  Future<Map<String, dynamic>?> login(String email, String password) async {
    try {
      final response = await _dio.post('/login', data: {
        'email': email,
        'password': password,
      });
      return response.data;
    } on DioException catch (e) {
      if (e.response != null && e.response?.data != null) {
        if (e.response?.data is Map<String, dynamic>) {
          return e.response?.data as Map<String, dynamic>;
        }
      }
      return {'status': 'error', 'message': 'Gagal terhubung ke server.'};
    } catch (e) {
      return {'status': 'error', 'message': 'Terjadi kesalahan sistem.'};
    }
  }

  Future<Map<String, dynamic>?> register(String name, String email, String password) async {
    try {
      final response = await _dio.post('/register', data: {
        'name': name,
        'email': email,
        'password': password,
      });
      return response.data;
    } on DioException catch (e) {
      if (e.response != null && e.response?.data != null) {
        if (e.response?.data is Map<String, dynamic>) {
          return e.response?.data as Map<String, dynamic>;
        }
      }
      return {'status': 'error', 'message': 'Gagal terhubung ke server.'};
    } catch (e) {
      return {'status': 'error', 'message': 'Terjadi kesalahan sistem.'};
    }
  }

  Future<bool> logout() async {
    try {
      await _dio.post('/logout');
      return true;
    } catch (e) {
      return true;
    }
  }

  Future<Map<String, dynamic>?> updateProfile(Map<String, dynamic> userData) async {
    try {
      final response = await _dio.post('/profile/update', data: userData);
      return response.data;
    } catch (e) {
      return null;
    }
  }

  String getImageUrl(String? path) {
    if (path == null || path.isEmpty) {
      return 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&q=80';
    }
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    
    String cleanPath = path;
    if (cleanPath.startsWith('storage/')) {
      cleanPath = cleanPath.substring(8);
    } else if (cleanPath.startsWith('/storage/')) {
      cleanPath = cleanPath.substring(9);
    } else if (cleanPath.startsWith('/')) {
      cleanPath = cleanPath.substring(1);
    }
    return '$host/api/media/$cleanPath';
  }
}