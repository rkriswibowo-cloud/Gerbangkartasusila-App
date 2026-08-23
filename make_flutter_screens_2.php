<?php

$dir = __DIR__ . '/mobile/lib/screens';

$loginDart = <<<DART
import 'package:flutter/material.dart';
import 'main_layout.dart';
import 'register_screen.dart';

class LoginScreen extends StatelessWidget {
  const LoginScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              const Icon(Icons.travel_explore, size: 100, color: Colors.green),
              const SizedBox(height: 16),
              const Text('Welcome Back!', textAlign: TextAlign.center, style: TextStyle(fontSize: 28, fontWeight: FontWeight.bold)),
              const SizedBox(height: 32),
              TextField(
                decoration: InputDecoration(
                  labelText: 'Email',
                  prefixIcon: const Icon(Icons.email),
                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                ),
              ),
              const SizedBox(height: 16),
              TextField(
                obscureText: true,
                decoration: InputDecoration(
                  labelText: 'Password',
                  prefixIcon: const Icon(Icons.lock),
                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                ),
              ),
              const SizedBox(height: 24),
              ElevatedButton(
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  backgroundColor: Colors.green,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                onPressed: () {
                  Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const MainLayout()));
                },
                child: const Text('LOGIN', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              ),
              TextButton(
                onPressed: () {
                  Navigator.push(context, MaterialPageRoute(builder: (_) => const RegisterScreen()));
                },
                child: const Text('Don\'t have an account? Register'),
              )
            ],
          ),
        ),
      ),
    );
  }
}
DART;

$registerDart = <<<DART
import 'package:flutter/material.dart';

class RegisterScreen extends StatelessWidget {
  const RegisterScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Register')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            TextField(
              decoration: InputDecoration(
                labelText: 'Full Name',
                prefixIcon: const Icon(Icons.person),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
            const SizedBox(height: 16),
            TextField(
              decoration: InputDecoration(
                labelText: 'Email',
                prefixIcon: const Icon(Icons.email),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
            const SizedBox(height: 16),
            TextField(
              obscureText: true,
              decoration: InputDecoration(
                labelText: 'Password',
                prefixIcon: const Icon(Icons.lock),
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
            const SizedBox(height: 24),
            ElevatedButton(
              style: ElevatedButton.styleFrom(
                padding: const EdgeInsets.symmetric(vertical: 16),
                backgroundColor: Colors.green,
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
              onPressed: () {
                Navigator.pop(context);
              },
              child: const Text('REGISTER', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
            ),
          ],
        ),
      ),
    );
  }
}
DART;

$eventsDart = <<<DART
import 'package:flutter/material.dart';

class EventsScreen extends StatelessWidget {
  const EventsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Upcoming Events')),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: 3,
        itemBuilder: (context, index) {
          return Card(
            margin: const EdgeInsets.only(bottom: 16),
            child: ListTile(
              leading: const Icon(Icons.event, color: Colors.green, size: 40),
              title: Text('Kediri Festival \${index + 1}'),
              subtitle: const Text('Coming soon this month. Join the celebration!'),
              trailing: const Icon(Icons.arrow_forward_ios, size: 16),
            ),
          );
        },
      ),
    );
  }
}
DART;

$favoritesDart = <<<DART
import 'package:flutter/material.dart';

class FavoritesScreen extends StatelessWidget {
  const FavoritesScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Saved Destinations')),
      body: const Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.favorite_border, size: 80, color: Colors.grey),
            SizedBox(height: 16),
            Text('No favorites yet', style: TextStyle(fontSize: 18, color: Colors.grey)),
          ],
        ),
      ),
    );
  }
}
DART;

$profileDart = <<<DART
import 'package:flutter/material.dart';
import 'login_screen.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('My Profile')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          const CircleAvatar(
            radius: 50,
            child: Icon(Icons.person, size: 50),
          ),
          const SizedBox(height: 16),
          const Text('John Doe', textAlign: TextAlign.center, style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
          const Text('johndoe@example.com', textAlign: TextAlign.center, style: TextStyle(color: Colors.grey)),
          const SizedBox(height: 32),
          ListTile(
            leading: const Icon(Icons.settings),
            title: const Text('Settings'),
            onTap: () {},
          ),
          ListTile(
            leading: const Icon(Icons.logout, color: Colors.red),
            title: const Text('Logout', style: TextStyle(color: Colors.red)),
            onTap: () {
              Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const LoginScreen()));
            },
          ),
        ],
      ),
    );
  }
}
DART;

file_put_contents($dir . '/login_screen.dart', $loginDart);
file_put_contents($dir . '/register_screen.dart', $registerDart);
file_put_contents($dir . '/events_screen.dart', $eventsDart);
file_put_contents($dir . '/favorites_screen.dart', $favoritesDart);
file_put_contents($dir . '/profile_screen.dart', $profileDart);

echo "Screens part 2 generated.\\n";
