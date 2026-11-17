import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:dio/dio.dart';

class ApiService {
  static String? _token;
  static Map<String, dynamic>? currentUser;
  static bool get isLoggedIn => _token != null;

  static String get host {
    if (kIsWeb) {
      final origin = Uri.base.origin;
      if (origin.isNotEmpty && origin != 'null') {
        return '$origin/Gerbangkartasusila-App/backend/public';
      }
    }
    const configured = String.fromEnvironment('API_BASE_URL');
    if (configured.isNotEmpty) return configured.replaceFirst(RegExp(r'/+$'), '');
    if (defaultTargetPlatform == TargetPlatform.android) return 'http://10.0.2.2/Gerbangkartasusila-App/backend/public';
    return 'http://localhost/Gerbangkartasusila-App/backend/public';
  }

  static String get baseUrl => '$host/api'; 

  late final Dio _dio;

  Future<List<dynamic>> getFavorites() => _getList('/favorites');

  Future<void> saveFavorite(int destinationId) async {
    await _dio.post('/favorites', data: {'destination_id': destinationId});
  }

  Future<void> removeFavorite(int id) async {
    await _dio.delete('/favorites/$id');
  }

  Future<List<dynamic>> _getList(String endpoint) async {
    final items = <dynamic>[];
    var page = 1;
    while (true) {
      final response = await _dio.get(endpoint, queryParameters: {'page': page});
      final data = response.data['data'];
      if (data is List) return [...items, ...data];
      items.addAll(data['data'] as List);
      if (page >= (data['last_page'] as int)) return items;
      page++;
    }
  }

  ApiService({Dio? client}) {
    _dio = client ?? Dio(BaseOptions(
      baseUrl: baseUrl,
      connectTimeout: const Duration(seconds: 15),
      receiveTimeout: const Duration(seconds: 30),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    ));
    _dio.interceptors.add(InterceptorsWrapper(onRequest: (options, handler) {
      if (_token != null) options.headers['Authorization'] = 'Bearer $_token';
      handler.next(options);
    }));
  }

  Future<List<dynamic>> getDestinations() => _getList('/destinations');

  Future<Map<String, dynamic>?> getDestinationDetail(String slug) async {
    try {
      final response = await _dio.get('/destinations/$slug');
      return response.data['data'];
    } catch (e) {
      return null;
    }
  }

  Future<List<dynamic>> getEvents() => _getList('/events');

  Future<List<dynamic>> getHotels() => _getList('/hotels');

  Future<List<dynamic>> getCulinary() => _getList('/culinary');

  Future<List<dynamic>> getArticles() => _getList('/articles');

  Future<Map<String, dynamic>?> bookTicket(Map<String, dynamic> ticketData) async {
    try {
      final response = await _dio.post('/tickets', data: ticketData);
      return response.data;
    } catch (e) {
      return null;
    }
  }

  Future<List<dynamic>> getTickets() => _getList('/tickets');

  static Map<String, dynamic>? _extractResponseData(dynamic raw) {
    if (raw == null) return null;
    if (raw is String) {
      try {
        final decoded = jsonDecode(raw);
        if (decoded is Map) {
          return Map<String, dynamic>.from(decoded);
        }
      } catch (_) {}
    } else if (raw is Map) {
      return Map<String, dynamic>.from(raw);
    }
    return null;
  }

  Future<Map<String, dynamic>?> login(String email, String password) async {
    try {
      final response = await _dio.post('/login', data: {
        'email': email,
        'password': password,
      });
      final data = _extractResponseData(response.data) ?? {'status': 'success'};
      _saveSession(data);
      return data;
    } on DioException catch (e) {
      final errorData = _extractResponseData(e.response?.data);
      if (errorData != null && errorData['message'] != null) {
        return errorData;
      }
      return {
        'status': 'error',
        'message': e.message != null && e.message!.isNotEmpty
            ? 'Gagal terhubung ke server (${e.message})'
            : 'Gagal terhubung ke server. Periksa koneksi dan coba lagi.'
      };
    } catch (e) {
      return {'status': 'error', 'message': 'Terjadi kesalahan sistem: $e'};
    }
  }

  Future<Map<String, dynamic>?> register(String name, String email, String password) async {
    try {
      final response = await _dio.post('/register', data: {
        'name': name,
        'email': email,
        'password': password,
      });
      final data = _extractResponseData(response.data) ?? {'status': 'success'};
      _saveSession(data);
      return data;
    } on DioException catch (e) {
      final errorData = _extractResponseData(e.response?.data);
      if (errorData != null && errorData['message'] != null) {
        return errorData;
      }
      return {
        'status': 'error',
        'message': e.message != null && e.message!.isNotEmpty
            ? 'Gagal terhubung ke server (${e.message})'
            : 'Gagal terhubung ke server. Periksa koneksi dan coba lagi.'
      };
    } catch (e) {
      return {'status': 'error', 'message': 'Terjadi kesalahan sistem: $e'};
    }
  }

  Future<bool> logout() async {
    try {
      await _dio.post('/logout');
      return true;
    } catch (e) {
      return false;
    } finally {
      _token = null;
      currentUser = null;
    }
  }

  static void _saveSession(dynamic data) {
    if (data is Map && data['access_token'] is String) {
      _token = data['access_token'];
      if (data['data'] is Map) {
        currentUser = Map<String, dynamic>.from(data['data']);
      }
    }
  }

  Future<Map<String, dynamic>?> updateProfile(Map<String, dynamic> userData) async {
    try {
      final response = await _dio.post('/profile/update', data: userData);
      if (response.data['status'] == 'success') {
        currentUser = Map<String, dynamic>.from(response.data['data']);
      }
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
