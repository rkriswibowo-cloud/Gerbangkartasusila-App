import 'package:flutter/foundation.dart';
import 'package:dio/dio.dart';

class ApiService {
  static String? _token;
  static Map<String, dynamic>? currentUser;
  static bool get isLoggedIn => _token != null;

  static String get host {
    const configured = String.fromEnvironment('API_BASE_URL');
    if (configured.isNotEmpty) return configured.replaceFirst(RegExp(r'/+$'), '');
    if (kReleaseMode) {
      throw StateError('API_BASE_URL wajib diisi saat build release.');
    }
    if (kIsWeb) return 'http://localhost:8000';
    if (defaultTargetPlatform == TargetPlatform.android) return 'http://10.0.2.2:8000';
    return 'http://127.0.0.1:8000';
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

  Future<Map<String, dynamic>?> login(String email, String password) async {
    try {
      final response = await _dio.post('/login', data: {
        'email': email,
        'password': password,
      });
      _saveSession(response.data);
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
      _saveSession(response.data);
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
      return false;
    } finally {
      _token = null;
      currentUser = null;
    }
  }

  static void _saveSession(dynamic data) {
    if (data is Map && data['access_token'] is String) {
      _token = data['access_token'];
      currentUser = Map<String, dynamic>.from(data['data']);
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
