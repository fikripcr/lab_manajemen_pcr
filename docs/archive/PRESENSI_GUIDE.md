# Fitur Presensi Online - Geolocation

## Overview

Fitur Presensi Online memungkinkan karyawan untuk melakukan check-in dan check-out menggunakan geolocation browser. Sistem akan memvalidasi lokasi karyawan berdasarkan radius yang ditentukan dari lokasi kantor.

## 🚀 Fitur Utama

### 1. **Presensi dengan Geolocation**
- Check-in dan check-out menggunakan GPS browser
- Validasi lokasi berdasarkan radius kantor
- Deteksi jarak real-time dari lokasi kantor
- Reverse geocoding untuk mendapatkan alamat

### 2. **Pengaturan Lokasi & Radius**
- Set koordinat latitude/longitude kantor
- Tentukan radius presensi (10-1000 meter)
- Aktif/non-aktifkan fitur presensi
- Test lokasi untuk validasi

### 3. **Monitoring & Riwayat**
- Dashboard status presensi harian
- Riwayat presensi lengkap dengan filter
- Export data presensi
- Detail lokasi check-in/check-out

## 📁 Struktur File

### Controllers
```
app/Http/Controllers/Hr/
├── PresensiController.php     # Main controller
```

### Services
```
app/Services/Hr/
├── PresensiService.php       # Business logic
```

### Requests
```
app/Http/Requests/Hr/
├── PresensiRequest.php       # Validation rules
```

### Models
```
app/Models/Hr/
├── Presensi.php             # Eloquent model
```

### Views
```
resources/views/pages/hr/presensi/
├── index.blade.php          # Main presensi page
├── settings.blade.php       # Settings page
└── history.blade.php        # History page
```

### Migrations
```
database/migrations/
├── 2026_02_10_103222_create_hr_presensi_table.php
```

### Config
```
config/
├── presensi.php              # Presensi configuration
```

## 🛠️ Installation & Setup

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Add Environment Variables
```env
# Presensi Settings
PRESENSI_DEFAULT_LATITUDE=-6.208763
PRESENSI_DEFAULT_LONGITUDE=106.845599
PRESENSI_DEFAULT_ADDRESS="Jakarta, Indonesia"
PRESENSI_DEFAULT_RADIUS=100
GEOCODING_PROVIDER=nominatim
GEOCODING_TIMEOUT=10
```

### 3. Configure Settings
- Akses menu "Presensi" → "Pengaturan"
- Set lokasi kantor dan radius
- Test lokasi untuk validasi

## 📱 Cara Penggunaan

### 1. Check-in
1. Buka halaman Presensi
2. Browser akan meminta izin lokasi
3. Sistem akan mendeteksi lokasi saat ini
4. Klik "Check In" jika dalam radius yang diizinkan

### 2. Check-out
1. Pastikan berada dalam radius kantor
2. Klik "Check Out" pada halaman yang sama
3. Sistem akan mencatat waktu dan lokasi check-out

### 3. Monitoring
- Lihat status presensi harian
- Akses riwayat presensi
- Filter berdasarkan tanggal, bulan, status

## 🔧 API Endpoints

### Presensi Actions
```http
POST /hr/presensi/checkin      # Check-in
POST /hr/presensi/checkout     # Check-out
GET  /hr/presensi/get-location # Get address from coordinates
```

### Settings
```http
GET  /hr/presensi/settings           # Get settings
POST /hr/presensi/update-settings    # Update settings
```

### History
```http
GET  /hr/presensi/history            # History page
GET  /hr/presensi/history-data       # History data (JSON)
```

## 🎨 UI Components

### Main Dashboard
- Status presensi real-time
- Informasi lokasi saat ini
- Jarak dari kantor dengan progress bar
- Tombol check-in/check-out

### Settings Page
- Form pengaturan koordinat
- Radius selector
- Test lokasi modal
- Preview lokasi

### History Page
- DataTable dengan riwayat presensi
- Filter bulan/tahun/status
- Detail view modal
- Export functionality

## 🌐 Geolocation Features

### 1. **Location Detection**
- HTML5 Geolocation API
- High accuracy mode
- Error handling untuk berbagai skenario

### 2. **Distance Calculation**
- Haversine formula untuk jarak
- Real-time distance calculation
- Visual progress indicator

### 3. **Reverse Geocoding**
- OpenStreetMap Nominatim API
- Address resolution dari koordinat
- Fallback untuk API failures

## 🔒 Security Features

### 1. **Location Validation**
- Radius checking
- Coordinate validation
- Anti-spoofing measures

### 2. **Time Validation**
- Check-in time limits
- Duplicate prevention
- Daily attendance tracking

### 3. **Data Protection**
- Encrypted IDs
- Secure location storage
- Audit trail

## 📊 Business Rules

### 1. **Presensi Rules**
- Min check-in time: 07:00
- Max check-in time: 10:00
- Min check-out time: 16:00
- Max check-out time: 21:00
- Late threshold: 15 minutes

### 2. **Status Logic**
- `on_time`: Check-in ≤ shift start + 15 min
- `late`: Check-in > shift start + 15 min
- `early_checkout`: Check-out < shift end
- `absent`: No check-in recorded

### 3. **Duration Calculation**
- Working hours: check-in to check-out
- Overtime: hours beyond shift end
- Late minutes: arrival after shift start

## 🚨 Error Handling

### Common Errors
1. **Location Denied**: User menolak akses lokasi
2. **Outside Radius**: User di luar area presensi
3. **Already Checked In**: Duplicate check-in attempt
4. **API Timeout**: Geocoding service unavailable

### Error Messages
- User-friendly error messages
- Specific error codes
- Recovery suggestions

## 🔄 Future Enhancements

### Planned Features
1. **Integration dengan Shift System**
2. **Face Recognition**
3. **Offline Mode Support**
4. **Mobile App Integration**
5. **Advanced Reporting**
6. **Notification System**

### Technical Improvements
1. **Real-time WebSocket Updates**
2. **Advanced Geofencing**
3. **Machine Learning for Anomaly Detection**
4. **Performance Optimization**

## 📞 Support

Untuk bantuan teknis:
1. Check browser compatibility
2. Verify location permissions
3. Test with different devices
4. Review system logs

## 📝 Notes

- Fitur ini menggunakan browser Geolocation API
- Membutuhkan HTTPS untuk production
- Support untuk modern browsers
- Mobile-friendly interface
- Responsive design
