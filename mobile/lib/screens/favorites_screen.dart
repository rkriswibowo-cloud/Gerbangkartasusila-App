import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../theme/app_colors.dart';
import 'detail_screen.dart';

class FavoritesScreen extends StatefulWidget {
  const FavoritesScreen({super.key});
  @override
  State<FavoritesScreen> createState() => _FavoritesScreenState();
}

class _FavoritesScreenState extends State<FavoritesScreen> {
  final _api = ApiService();
  late Future<List<dynamic>> _items;

  @override
  void initState() {
    super.initState();
    _items = _api.getFavorites();
  }

  void _reload() => setState(() => _items = _api.getFavorites());

  Future<void> _remove(int id) async {
    try {
      await _api.removeFavorite(id);
      if (mounted) _reload();
    } catch (_) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Gagal menghapus favorit. Coba lagi.')));
    }
  }

  @override
  Widget build(BuildContext context) => Scaffold(
    backgroundColor: AppColors.background,
    appBar: AppBar(
      title: const Text('Wisata Favorit Saya'),
      actions: [IconButton(onPressed: _reload, icon: const Icon(Icons.refresh))],
    ),
    body: FutureBuilder<List<dynamic>>(
      future: _items,
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        }
        if (snapshot.hasError) {
          return Center(child: TextButton(onPressed: _reload, child: const Text('Gagal memuat favorit. Coba lagi.')));
        }
        final items = snapshot.data ?? [];
        if (items.isEmpty) {
          return const Center(child: Padding(
            padding: EdgeInsets.all(24),
            child: Text('Belum ada favorit. Simpan destinasi melalui tombol bookmark pada halaman detail.', textAlign: TextAlign.center),
          ));
        }
        return ListView.builder(
          itemCount: items.length,
          itemBuilder: (context, index) {
            final item = items[index];
            final destination = item['destination'];
            return ListTile(
              title: Text(destination?['name'] ?? 'Destinasi tidak tersedia'),
              subtitle: Text(destination?['address'] ?? ''),
              trailing: IconButton(icon: const Icon(Icons.delete_outline), tooltip: 'Hapus favorit', onPressed: () => _remove(item['id'])),
              onTap: destination == null ? null : () async {
                await Navigator.push(context, MaterialPageRoute(builder: (_) => DetailScreen(slug: destination['slug'])));
                if (mounted) _reload();
              },
            );
          },
        );
      },
    ),
  );
}