# Prompt: Update Google Sign-In di Mobile App

## Konteks
Backend Laravel sudah diupdate untuk menerima dan memverifikasi Google ID Token secara aman. Sekarang mobile app perlu disesuaikan agar mengirim `id_token` (bukan hanya `name` dan `email`) ke backend.

## Informasi Penting
- **Google Client ID (Web)**: `175555114047-2adlq775ghjsbm75qfuoukr8q9q0fj5i.apps.googleusercontent.com`
- **SHA-1 Certificate**: `B4:7F:B7:50:C4:EE:AB:41:DC:62:B2:7A:70:6B:90:04:F4:E4:05:21`
- **Backend Endpoint**: `POST /api/google_auth.php`
- **Backend Base URL**: (sesuaikan dengan URL server kamu)

## Yang Perlu Diubah

### 1. Konfigurasi Google Sign-In
Pastikan `GoogleSignInOptions` menggunakan `requestIdToken()` dengan **Web Client ID** (BUKAN Android Client ID):

```kotlin
val gso = GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
    .requestIdToken("175555114047-2adlq775ghjsbm75qfuoukr8q9q0fj5i.apps.googleusercontent.com")
    .requestEmail()
    .requestProfile()
    .build()

val googleSignInClient = GoogleSignIn.getClient(this, gso)
```

> PENTING: Parameter di `requestIdToken()` HARUS menggunakan Client ID bertipe "Web application" dari Google Cloud Console. Jika Client ID `175555114047-2adlq775ghjsbm75qfuoukr8q9q0fj5i.apps.googleusercontent.com` bertipe Android, maka perlu buat Client ID baru bertipe Web di Google Cloud Console terlebih dahulu, lalu gunakan Web Client ID tersebut di sini DAN di backend.

### 2. Setelah Google Sign-In Berhasil, Kirim `id_token` ke Backend
Ubah request ke backend dari mengirim `name` + `email` menjadi mengirim `id_token`:

**SEBELUM (cara lama - HAPUS):**
```kotlin
val params = mapOf(
    "name" to account.displayName,
    "email" to account.email
)
```

**SESUDAH (cara baru - GUNAKAN INI):**
```kotlin
val account = GoogleSignIn.getSignedInAccountFromIntent(data).result
val idToken = account.idToken

if (idToken != null) {
    // Kirim id_token ke backend
    val params = mapOf(
        "id_token" to idToken
    )
    // POST ke BASE_URL + "/api/google_auth.php" dengan params di atas
} else {
    // Error: idToken null, pastikan requestIdToken() sudah dikonfigurasi
    Log.e("GoogleAuth", "ID Token is null")
}
```

### 3. Response dari Backend (TIDAK BERUBAH)
Format response dari backend tetap sama, tidak perlu ubah cara parsing response:

```json
{
    "status": "success",
    "message": "Login Google Berhasil",
    "user": {
        "id": 1,
        "name": "Nama User",
        "email": "user@gmail.com",
        "phone": "000000000000",
        "address": "...",
        "photo": "url_foto_atau_null",
        "is_admin": false,
        "is_member": false
    }
}
```

Jika error:
```json
{
    "status": "error",
    "message": "Token Google tidak valid"
}
```

### 4. Pastikan Internet Permission Ada
Di `AndroidManifest.xml`:
```xml
<uses-permission android:name="android.permission.INTERNET" />
```

## Ringkasan Perubahan
1. Tambahkan `.requestIdToken("WEB_CLIENT_ID")` di GoogleSignInOptions
2. Ubah body POST dari `{name, email}` menjadi `{id_token}`
3. Handle case jika `idToken` null
4. Response handling TIDAK perlu diubah

## Catatan Tambahan
- Jika `account.idToken` selalu `null`, kemungkinan Client ID yang dipakai di `requestIdToken()` bukan tipe Web. Buat Web Client ID baru di Google Cloud Console.
- Backend sudah backward compatible — jika tidak ada `id_token`, backend akan fallback ke cara lama (`name` + `email`). Tapi cara lama ini TIDAK AMAN dan harus segera diganti.
- Setelah mobile app diupdate, user bisa login dengan Google tanpa perlu input password atau daftar akun manual.
