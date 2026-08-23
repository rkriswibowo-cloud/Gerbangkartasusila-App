class Article {
  final int id;
  final String title;
  final String slug;
  final String content;
  final String? thumbnail;
  final String? publishedAt;

  Article({
    required this.id, required this.title, required this.slug, required this.content,
    this.thumbnail, this.publishedAt,
  });

  factory Article.fromJson(Map<String, dynamic> json) {
    return Article(
      id: json['id'],
      title: json['title'],
      slug: json['slug'],
      content: json['content'] ?? '',
      thumbnail: json['thumbnail'] ?? json['image_path'],
      publishedAt: json['published_at'],
    );
  }
}