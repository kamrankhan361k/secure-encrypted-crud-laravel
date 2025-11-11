# 🔐 Secure Encrypted CRUD - Laravel

A comprehensive Laravel CRUD application with advanced AES-256 multi-layer encryption for sensitive data storage. All sensitive information is encrypted at rest using complex encryption techniques that make decryption extremely difficult for unauthorized parties.

## 🚀 Features

- **🔒 Multi-Layer AES-256 Encryption** - 4 layers of encryption with data obfuscation
- **🛡️ Field-Level Encryption** - Only sensitive fields are encrypted
- **📊 Complex Data Structure** - Random padding, checksums, and segmented data
- **🔍 Data Integrity** - SHA-256 checksums to detect tampering
- **💾 Secure Storage** - Encrypted data stored in database
- **🎯 Smart Decryption** - Automatic decryption when accessing data
- **⚡ Maintenance Commands** - Encryption integrity checks and management
- **📱 Responsive UI** - Bootstrap-based responsive interface
- **🔧 Admin Tools** - Encryption verification and management

## 🏗️ Architecture

### Encryption Layers
1. **Layer 1**: Standard Laravel Encryption
2. **Layer 2**: Custom AES-256-CBC Encryption
3. **Layer 3**: Base64 with Character Substitution
4. **Layer 4**: Complex Structure with Random Padding

### Security Features
- Unique salt per record and field
- Data integrity verification
- Character substitution obfuscation
- Random padding injection
- Versioned encryption structure

## 📋 Requirements

- PHP 8.1+
- Laravel 10+
- MySQL 5.7+ / PostgreSQL / SQLite
- OpenSSL Extension

## ⚡ Quick Installation

### 1. Clone and Setup
```bash
git clone <repository-url>
cd secure-encrypted-crud
composer install
cp .env.example .env
