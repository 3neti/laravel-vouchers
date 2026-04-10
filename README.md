# Laravel Vouchers (3neti Fork)

[![License](https://img.shields.io/github/license/3neti/laravel-vouchers.svg?style=flat-square)](LICENSE)

## ⚠️ Fork Notice

This package is a **maintained fork** of the original  
`frittenkeez/laravel-vouchers`.

### Why this fork exists

- ✅ Adds **Laravel 13 compatibility**
- ✅ Aligns with **3neti migration ownership architecture**
- 🔄 Will evolve independently to support:
  - settlement integration
  - idempotency
  - metadata improvements

> This package is now the **source of truth for the vouchers table schema** in the 3neti ecosystem.

---

## 📦 Installation

```bash
composer require 3neti/laravel-vouchers
```

---

## 🚨 Migration Policy (Important)

Unlike the original package:

### ❌ Original behavior
- Requires `vendor:publish` for migrations

### ✅ This fork
- Uses `loadMigrationsFrom()`
- **No publishing required**
- Migrations are loaded automatically

```bash
php artisan migrate
```

### 🧠 Ownership Rule

This package **owns**:

- `vouchers` table
- `voucherables` table
- all schema updates related to vouchers

Other packages (e.g., `3neti/voucher`, `3neti/cash`)  
**must NOT modify voucher tables directly**

---

## 🔄 Versioning Strategy

Current: `v1.0.0`

Upcoming releases will follow:

- `v1.x` → compatibility + internal alignment
- `v2.x` → schema ownership + architectural changes

---

## ⚙️ Configuration

```bash
php artisan vendor:publish --tag=config --provider="FrittenKeeZ\\Vouchers\\VouchersServiceProvider"
```

---

## 🚀 Usage

This package provides the `Vouchers` facade:

```php
use FrittenKeeZ\\Vouchers\\Facades\\Vouchers;
```

---

### Generate Codes

```php
$code = Vouchers::generate('***-***-***', '1234567890');

$codes = Vouchers::batch(10);
```

---

### Create Vouchers

```php
$voucher = Vouchers::create();
$vouchers = Vouchers::create(10);
```

---

### Redeem Vouchers

```php
Vouchers::redeem('123-456-789', $user);
```

Handles exceptions:

- VoucherNotFoundException
- VoucherRedeemedException
- VoucherExpiredException
- VoucherUnstartedException

---

### Unredeem Vouchers

```php
Vouchers::unredeem('123-456-789', $user);
```

---

## 🧩 Traits

### HasVouchers

```php
use FrittenKeeZ\\Vouchers\\Concerns\\HasVouchers;

$user->vouchers;
$user->createVoucher();
```

---

### HasRedeemers

```php
use FrittenKeeZ\\Vouchers\\Concerns\\HasRedeemers;

$user->redeemers;
```

---

## 🧠 Architectural Notes (3neti)

This fork is part of a larger system:

- `voucher` → business logic
- `cash` → financial ledger
- `settlement-envelope` → settlement gating
- `wallet` → balance orchestration

### Role of this package

> **Schema + Core Voucher Engine**

It should remain:

- deterministic
- storage-focused
- side-effect minimal

---

## 🚫 Anti-Patterns

Do NOT:

- modify voucher tables outside this package
- duplicate voucher schema in other packages
- treat vouchers as business logic containers

---

## 🧪 Testing

```bash
composer test
```

---

## 🙏 Acknowledgement

Original package by:
**Frederik Sauer**  
https://github.com/FrittenKeeZ/laravel-vouchers

---

## 📄 License

MIT
