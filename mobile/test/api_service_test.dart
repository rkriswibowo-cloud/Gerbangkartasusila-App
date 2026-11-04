import 'dart:typed_data';
import 'package:dio/dio.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:mobile/services/api_service.dart';
import 'package:mobile/models/ticket.dart';

void main() {
  test('Token follows requests across service instances and clears on logout', () async {
    final seen = <RequestOptions>[];
    Dio client() {
      final dio = Dio(BaseOptions(baseUrl: 'https://example.test/api'));
      dio.httpClientAdapter = _Adapter(seen);
      return dio;
    }
    final first = ApiService(client: client());
    await first.login('test@example.test', 'test-password');
    expect(ApiService.currentUser?['name'], 'Test');
    final second = ApiService(client: client());
    await second.getTickets();
    expect(seen.last.headers['Authorization'], 'Bearer test-token');
    await second.logout();
    expect(ApiService.isLoggedIn, isFalse);
    expect(ApiService.currentUser, isNull);
  });

  test('Missing ticket status never implies payment succeeded', () {
    final ticket = Ticket.fromJson({'id': 1});
    expect(ticket.status, 'pending');
    expect(ticket.statusLabel, 'MENUNGGU PEMBAYARAN');
  });

  test('Catalog loads subsequent pages rather than dropping destinations', () async {
    final seen = <RequestOptions>[];
    final client = Dio(BaseOptions(baseUrl: 'https://example.test/api'));
    client.httpClientAdapter = _Adapter(seen);
    final result = await ApiService(client: client).getDestinations();
    expect(result, [1, 2]);
    expect(seen.length, 2);
  });
}

class _Adapter implements HttpClientAdapter {
  final List<RequestOptions> seen;
  _Adapter(this.seen);
  @override
  Future<ResponseBody> fetch(RequestOptions options, Stream<Uint8List>? requestStream, Future<void>? cancelFuture) async {
    seen.add(options);
    if (options.path == '/destinations') {
      final page = options.queryParameters['page'];
      return ResponseBody.fromString('{"data":{"data":[$page],"last_page":2}}', 200,
        headers: {Headers.contentTypeHeader: [Headers.jsonContentType]});
    }
    return ResponseBody.fromString(
      options.path == '/login'
          ? '{"access_token":"test-token","data":{"name":"Test"}}'
          : '{"data":[]}',
      200,
      headers: {Headers.contentTypeHeader: [Headers.jsonContentType]},
    );
  }
  @override
  void close({bool force = false}) {}
}
