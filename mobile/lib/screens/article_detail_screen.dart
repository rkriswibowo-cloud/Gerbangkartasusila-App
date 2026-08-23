import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/article.dart';
import '../widgets/app_image.dart';

class ArticleDetailScreen extends StatelessWidget {
  final Article article;
  ArticleDetailScreen({super.key, required this.article});
  final ApiService _apiService = ApiService();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(title: const Text('Article'), backgroundColor: Colors.white, elevation: 0, foregroundColor: Colors.black),
      body: SingleChildScrollView(
        physics: const BouncingScrollPhysics(),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            AppImage(
              imageUrl: _apiService.getImageUrl(article.thumbnail),
              width: double.infinity,
              height: 250,
              fit: BoxFit.cover,
            ),
            Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(article.title, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  if (article.publishedAt != null) Text(article.publishedAt!, style: TextStyle(color: Colors.grey[600])),
                  const SizedBox(height: 16),
                  Text(article.content, style: const TextStyle(fontSize: 16, height: 1.6)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}