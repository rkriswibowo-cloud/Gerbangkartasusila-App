import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'package:dio/dio.dart';

/// A widget that loads images by downloading bytes via Dio (which goes through
/// Laravel's /api/media/ endpoint with proper CORS headers), then renders
/// them using Image.memory(). This bypasses the CanvasKit cross-origin
/// image restriction on Flutter Web.
class AppImage extends StatefulWidget {
  final String imageUrl;
  final double? width;
  final double? height;
  final BoxFit fit;
  final Widget? placeholder;
  final Widget? errorWidget;

  const AppImage({
    super.key,
    required this.imageUrl,
    this.width,
    this.height,
    this.fit = BoxFit.cover,
    this.placeholder,
    this.errorWidget,
  });

  @override
  State<AppImage> createState() => _AppImageState();
}

class _AppImageState extends State<AppImage> {
  Uint8List? _imageBytes;
  bool _isLoading = true;
  bool _hasError = false;

  // Simple in-memory cache shared across all AppImage instances
  static final Map<String, Uint8List> _cache = {};

  @override
  void initState() {
    super.initState();
    _loadImage();
  }

  @override
  void didUpdateWidget(AppImage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.imageUrl != widget.imageUrl) {
      _loadImage();
    }
  }

  Future<void> _loadImage() async {
    final url = widget.imageUrl;

    // Check cache first
    if (_cache.containsKey(url)) {
      if (mounted) {
        setState(() {
          _imageBytes = _cache[url];
          _isLoading = false;
          _hasError = false;
        });
      }
      return;
    }

    setState(() {
      _isLoading = true;
      _hasError = false;
    });

    try {
      final dio = Dio();
      final response = await dio.get<List<int>>(
        url,
        options: Options(responseType: ResponseType.bytes),
      );

      if (response.statusCode == 200 && response.data != null) {
        final bytes = Uint8List.fromList(response.data!);
        _cache[url] = bytes; // Cache for reuse
        if (mounted) {
          setState(() {
            _imageBytes = bytes;
            _isLoading = false;
          });
        }
      } else {
        if (mounted) {
          setState(() {
            _hasError = true;
            _isLoading = false;
          });
        }
      }
    } catch (e) {
      debugPrint('AppImage error loading $url: $e');
      if (mounted) {
        setState(() {
          _hasError = true;
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return widget.placeholder ??
          Container(
            width: widget.width,
            height: widget.height,
            color: Colors.grey[200],
            child: const Center(
              child: SizedBox(
                width: 24,
                height: 24,
                child: CircularProgressIndicator(strokeWidth: 2),
              ),
            ),
          );
    }

    if (_hasError || _imageBytes == null) {
      return widget.errorWidget ??
          Container(
            width: widget.width,
            height: widget.height,
            color: Colors.grey[200],
            child: const Icon(Icons.image_not_supported, size: 40, color: Colors.grey),
          );
    }

    return Image.memory(
      _imageBytes!,
      width: widget.width,
      height: widget.height,
      fit: widget.fit,
      gaplessPlayback: true,
    );
  }
}
