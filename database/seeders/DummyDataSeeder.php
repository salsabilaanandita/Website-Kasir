<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Member;
use App\Models\Shift;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pengeluaran;
use App\Models\ReturnPenjualan;
use App\Models\StockMovement;
use App\Models\StockAdjustment;
use App\Models\StockOpname;
use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\Pembelians;
use App\Models\DetailPembelian;
use App\Models\Pembayaran;
use App\Models\User;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // ----------------------------------------------------
        // 0. Ambil Users
        // ----------------------------------------------------
        $admin = User::where('role', 'admin')->first() ?? User::first();
        $manager = User::where('role', 'manager')->first() ?? $admin;
        $staffUsers = User::where('role', 'staff')->get();
        if ($staffUsers->isEmpty()) {
            $staffUsers = collect([$admin]);
        }
        $staff = $staffUsers->first();

        // ----------------------------------------------------
        // 1. Data Pengaturan Toko & Struk (Settings)
        // ----------------------------------------------------
        $settingsData = [
            ['key' => 'nama_toko', 'value' => 'Kasir Express & Minimarket', 'group' => 'store'],
            ['key' => 'alamat_toko', 'value' => 'Jl. Sudirman No. 128, Jakarta Pusat', 'group' => 'store'],
            ['key' => 'telepon_toko', 'value' => '021-5558899', 'group' => 'store'],
            ['key' => 'email_toko', 'value' => 'info@kasirexpress.co.id', 'group' => 'store'],
            ['key' => 'mata_uang', 'value' => 'Rp', 'group' => 'store'],
            ['key' => 'struk_header', 'value' => 'KASIR EXPRESS MINIMARKET\nJl. Sudirman No. 128 Jakarta\nTelp: 021-5558899', 'group' => 'receipt'],
            ['key' => 'struk_footer', 'value' => 'Terima kasih atas kunjungan Anda!\nBarang yang sudah dibeli tidak dapat ditukar kecuali ada cacat pabrik.', 'group' => 'receipt'],
        ];

        foreach ($settingsData as $s) {
            Setting::updateOrCreate(['key' => $s['key']], ['value' => $s['value'], 'group' => $s['group']]);
        }

        // ----------------------------------------------------
        // 2. Data Master Kategori (12+ Kategori)
        // ----------------------------------------------------
        $categoriesList = [
            ['nama_kategori' => 'Makanan Ringan', 'deskripsi' => 'Snack, biskuit, keripik, cokelat dan cemilan'],
            ['nama_kategori' => 'Minuman', 'deskripsi' => 'Air mineral, teh kemasan, kopi, jus dan soda'],
            ['nama_kategori' => 'Sembako & Bahan Pokok', 'deskripsi' => 'Beras, minyak goreng, gula, tepung dan telur'],
            ['nama_kategori' => 'Perawatan Diri', 'deskripsi' => 'Sabun mandi, sampo, pasta gigi dan deodorant'],
            ['nama_kategori' => 'Bumbu Dapur & Masak', 'deskripsi' => 'Kecap, saus, garam, penyedap rasa dan rempah'],
            ['nama_kategori' => 'Kebersihan Rumah', 'deskripsi' => 'Deterjen, sabun cuci piring, pembersih lantai dan tisu'],
            ['nama_kategori' => 'Susu & Produk Olahan', 'deskripsi' => 'Susu UHT, susu kental manis, keju dan yogurt'],
            ['nama_kategori' => 'Roti & Sarapan', 'deskripsi' => 'Roti tawar, selai, sereal dan oatmeal'],
            ['nama_kategori' => 'ATK & Kantor', 'deskripsi' => 'Buku tulis, pulpen, amplop dan perekat'],
            ['nama_kategori' => 'Obat & P3K', 'deskripsi' => 'Minyak kayu putih, plester, obat flu dan vitamin'],
            ['nama_kategori' => 'Elektronik & Aksesoris', 'deskripsi' => 'Baterai, korek gas, kabel data dan lampu LED'],
            ['nama_kategori' => 'Produk Segar & Beku', 'deskripsi' => 'Nugget, sosis, bakso dan es krim'],
        ];

        $categoriesMap = [];
        foreach ($categoriesList as $cat) {
            $created = Category::firstOrCreate(['nama_kategori' => $cat['nama_kategori']], ['deskripsi' => $cat['deskripsi']]);
            $categoriesMap[$cat['nama_kategori']] = $created->id;
        }

        // ----------------------------------------------------
        // 3. Data Master Supplier (12+ Supplier)
        // ----------------------------------------------------
        $suppliersList = [
            ['nama_supplier' => 'PT Indofood Sukses Makmur', 'telepon' => '021-57958822', 'email' => 'contact@indofood.co.id', 'alamat' => 'Sudirman Plaza, Indofood Tower, Jakarta', 'kontak_person' => 'Budi Santoso'],
            ['nama_supplier' => 'PT Unilever Indonesia Tbk', 'telepon' => '021-80827000', 'email' => 'suplai@unilever.co.id', 'alamat' => 'BSD Green Office Park, Tangerang', 'kontak_person' => 'Ratna Dewi'],
            ['nama_supplier' => 'CV Sumber Rejeki Berkah', 'telepon' => '081234567890', 'email' => 'sumberrejeki@gmail.com', 'alamat' => 'Jl. Pasar Induk No. 45, Bandung', 'kontak_person' => 'H. Asep Ridwan'],
            ['nama_supplier' => 'PT Mayora Indah Tbk', 'telepon' => '021-80637000', 'email' => 'sales@mayora.co.id', 'alamat' => 'Jl. Tomang Raya 21-23, Jakarta Barat', 'kontak_person' => 'Irfan Hakim'],
            ['nama_supplier' => 'PT Wings Surya', 'telepon' => '031-5312345', 'email' => 'distribusi@wingscorp.com', 'alamat' => 'Jl. Embong Malang 61-65, Surabaya', 'kontak_person' => 'Surya Wijaya'],
            ['nama_supplier' => 'PT Coca-Cola Amatil Indonesia', 'telepon' => '021-88327777', 'email' => 'order@coca-cola.co.id', 'alamat' => 'Cibitung Industrial Estate, Bekasi', 'kontak_person' => 'Denny Pratama'],
            ['nama_supplier' => 'PT Nestlé Indonesia', 'telepon' => '021-78836000', 'email' => 'supply@nestle.co.id', 'alamat' => 'Arkadia Tower B, Jl. TB Simatupang, Jakarta', 'kontak_person' => 'Lestari Ayu'],
            ['nama_supplier' => 'CV Mandiri Pangan Utama', 'telepon' => '081398765432', 'email' => 'mandiripangan@gmail.com', 'alamat' => 'Jl. Raya Bogor KM 30, Depok', 'kontak_person' => 'Agus Priyono'],
            ['nama_supplier' => 'PT Kalbe Farma Tbk', 'telepon' => '021-42873888', 'email' => 'logistik@kalbe.co.id', 'alamat' => 'Jl. Letjen Suprapto Kav 4, Jakarta Pusat', 'kontak_person' => 'dr. Rudi Hermanto'],
            ['nama_supplier' => 'PT Sayap Mas Utama', 'telepon' => '021-4602688', 'email' => 'distribusi@sayapmas.co.id', 'alamat' => 'Jl. Tipar Cakung Kav F 5-7, Jakarta Timur', 'kontak_person' => 'Gunawan Wibowo'],
            ['nama_supplier' => 'PT Danone Aqua Indonesia', 'telepon' => '021-29961000', 'email' => 'order@aqua.co.id', 'alamat' => 'Cyber 2 Tower, Jl. HR Rasuna Said, Jakarta', 'kontak_person' => 'Sinta Nuria'],
            ['nama_supplier' => 'PT Garudafood Putra Putri Jaya', 'telepon' => '021-72888888', 'email' => 'order@garudafood.com', 'alamat' => 'Jl. Bintaro Raya No. 10A, Jakarta Selatan', 'kontak_person' => 'Eko Yulianto'],
        ];

        foreach ($suppliersList as $sup) {
            Supplier::firstOrCreate(
                ['nama_supplier' => $sup['nama_supplier']],
                [
                    'telepon' => $sup['telepon'],
                    'email' => $sup['email'],
                    'alamat' => $sup['alamat'],
                    'kontak_person' => $sup['kontak_person']
                ]
            );
        }

        // ----------------------------------------------------
        // 4. Data Master Produk (28+ Produk Beragam)
        // ----------------------------------------------------
        $productsList = [
            ['nama' => 'Indomie Goreng Spesial', 'kode' => 'PRD-001', 'harga' => 3500, 'stok' => 120, 'kat' => 'Makanan Ringan'],
            ['nama' => 'Aqua Air Mineral 600ml', 'kode' => 'PRD-002', 'harga' => 3500, 'stok' => 85, 'kat' => 'Minuman'],
            ['nama' => 'Beras Premium Ramos 5kg', 'kode' => 'PRD-003', 'harga' => 68000, 'stok' => 24, 'kat' => 'Sembako & Bahan Pokok'],
            ['nama' => 'Minyak Goreng Bimoli 2L', 'kode' => 'PRD-004', 'harga' => 34000, 'stok' => 30, 'kat' => 'Sembako & Bahan Pokok'],
            ['nama' => 'Sabun Cair Lifebuoy 450ml', 'kode' => 'PRD-005', 'harga' => 22000, 'stok' => 40, 'kat' => 'Perawatan Diri'],
            ['nama' => 'Taro Net Seaweed 65g', 'kode' => 'PRD-006', 'harga' => 7500, 'stok' => 45, 'kat' => 'Makanan Ringan'],
            ['nama' => 'Teh Pucuk Harum 350ml', 'kode' => 'PRD-007', 'harga' => 4000, 'stok' => 90, 'kat' => 'Minuman'],
            ['nama' => 'Kopi Kapal Api Spesial Mix 10s', 'kode' => 'PRD-008', 'harga' => 15000, 'stok' => 35, 'kat' => 'Minuman'],
            ['nama' => 'Susu Ultra Milk Cokelat 250ml', 'kode' => 'PRD-009', 'harga' => 6500, 'stok' => 50, 'kat' => 'Susu & Produk Olahan'],
            ['nama' => 'Chitato Sapi Panggang 68g', 'kode' => 'PRD-010', 'harga' => 11500, 'stok' => 30, 'kat' => 'Makanan Ringan'],
            ['nama' => 'Silverqueen Milk Chocolate 62g', 'kode' => 'PRD-011', 'harga' => 16500, 'stok' => 20, 'kat' => 'Makanan Ringan'],
            ['nama' => 'Pocari Sweat 500ml', 'kode' => 'PRD-012', 'harga' => 7500, 'stok' => 40, 'kat' => 'Minuman'],
            ['nama' => 'Kecap Manis Bango 550ml', 'kode' => 'PRD-013', 'harga' => 24500, 'stok' => 25, 'kat' => 'Bumbu Dapur & Masak'],
            ['nama' => 'Saus Sambal ABC Extra Pedas 335ml', 'kode' => 'PRD-014', 'harga' => 14000, 'stok' => 28, 'kat' => 'Bumbu Dapur & Masak'],
            ['nama' => 'Sunlight Jeruk Nipis 700ml', 'kode' => 'PRD-015', 'harga' => 16000, 'stok' => 35, 'kat' => 'Kebersihan Rumah'],
            ['nama' => 'Pasta Gigi Pepsodent 190g', 'kode' => 'PRD-016', 'harga' => 13500, 'stok' => 40, 'kat' => 'Perawatan Diri'],
            ['nama' => 'Shampo Pantene Anti Dandruff 160ml', 'kode' => 'PRD-017', 'harga' => 26000, 'stok' => 18, 'kat' => 'Perawatan Diri'],
            ['nama' => 'Tisu Wajah Paseo 250s', 'kode' => 'PRD-018', 'harga' => 15000, 'stok' => 50, 'kat' => 'Kebersihan Rumah'],
            ['nama' => 'Roti Tawar Kupas Sari Roti', 'kode' => 'PRD-019', 'harga' => 17000, 'stok' => 15, 'kat' => 'Roti & Sarapan'],
            ['nama' => 'Telur Ayam Negeri 1kg', 'kode' => 'PRD-020', 'harga' => 28000, 'stok' => 40, 'kat' => 'Sembako & Bahan Pokok'],
            ['nama' => 'Gula Pasir Gulaku Premium 1kg', 'kode' => 'PRD-021', 'harga' => 17500, 'stok' => 50, 'kat' => 'Sembako & Bahan Pokok'],
            ['nama' => 'Deterjen Rinso Molto Rose Fresh 770g', 'kode' => 'PRD-022', 'harga' => 21000, 'stok' => 8, 'kat' => 'Kebersihan Rumah'], // Low Stock
            ['nama' => 'Kopi Good Day Cappuccino 5s', 'kode' => 'PRD-023', 'harga' => 10000, 'stok' => 5, 'kat' => 'Minuman'], // Low Stock
            ['nama' => 'Baterai Alkaline ABC AA 2pcs', 'kode' => 'PRD-024', 'harga' => 18000, 'stok' => 4, 'kat' => 'Elektronik & Aksesoris'], // Low Stock
            ['nama' => 'Minyak Kayu Putih Cap Lang 60ml', 'kode' => 'PRD-025', 'harga' => 24000, 'stok' => 0, 'kat' => 'Obat & P3K'], // Out of Stock
            ['nama' => 'Masker Medis Sensu 3-Ply 50s', 'kode' => 'PRD-026', 'harga' => 35000, 'stok' => 0, 'kat' => 'Obat & P3K'], // Out of Stock
            ['nama' => 'Buku Tulis Sinar Dunia 38lbr', 'kode' => 'PRD-027', 'harga' => 4500, 'stok' => 60, 'kat' => 'ATK & Kantor'],
            ['nama' => 'Pulpen Standard AE7 0.5 Hitam', 'kode' => 'PRD-028', 'harga' => 3000, 'stok' => 100, 'kat' => 'ATK & Kantor'],
        ];

        $createdProducts = [];
        foreach ($productsList as $p) {
            $catId = $categoriesMap[$p['kat']] ?? Category::first()->id;
            $product = Product::updateOrCreate(
                ['kode_produk' => $p['kode']],
                [
                    'category_id' => $catId,
                    'nama_produk' => $p['nama'],
                    'harga' => $p['harga'],
                    'stok' => $p['stok']
                ]
            );
            $createdProducts[] = $product;

            // Pastikan ada Movement Awal
            if (StockMovement::where('product_id', $product->id)->count() == 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'tipe' => 'masuk',
                    'qty' => $p['stok'] > 0 ? $p['stok'] : 10,
                    'stok_sebelum' => 0,
                    'stok_sesudah' => $p['stok'] > 0 ? $p['stok'] : 10,
                    'referensi' => 'RESTOCK-INIT-' . $product->id,
                    'keterangan' => 'Stok Awal Sistem',
                    'user_id' => $admin->id,
                    'created_at' => Carbon::now()->subDays(20)
                ]);
            }
        }

        // ----------------------------------------------------
        // 5. Data Member (12+ Member)
        // ----------------------------------------------------
        $membersList = [
            ['phone' => '081234123401', 'name' => 'Budi Santoso', 'points' => 8500, 'days_ago' => 120],
            ['phone' => '081234123402', 'name' => 'Siti Aminah', 'points' => 15200, 'days_ago' => 90],
            ['phone' => '081234123403', 'name' => 'Ahmad Fauzi', 'points' => 3400, 'days_ago' => 60],
            ['phone' => '081234123404', 'name' => 'Dewi Lestari', 'points' => 21000, 'days_ago' => 150],
            ['phone' => '081234123405', 'name' => 'Rian Pratama', 'points' => 6200, 'days_ago' => 45],
            ['phone' => '081234123406', 'name' => 'Rina Safitri', 'points' => 12800, 'days_ago' => 110],
            ['phone' => '081234123407', 'name' => 'Hendra Gunawan', 'points' => 4500, 'days_ago' => 30],
            ['phone' => '081234123408', 'name' => 'Dian Purnama', 'points' => 18900, 'days_ago' => 180],
            ['phone' => '081234123409', 'name' => 'Eko Prasetyo', 'points' => 7100, 'days_ago' => 40],
            ['phone' => '081234123410', 'name' => 'Fitri Handayani', 'points' => 9300, 'days_ago' => 75],
            ['phone' => '081234123411', 'name' => 'Gita Gutawa', 'points' => 2500, 'days_ago' => 15],
            ['phone' => '081234123412', 'name' => 'Hadi Wijaya', 'points' => 11400, 'days_ago' => 85],
        ];

        $createdMembers = [];
        foreach ($membersList as $m) {
            $createdMembers[] = Member::updateOrCreate(
                ['phone_number' => $m['phone']],
                [
                    'name' => $m['name'],
                    'points' => $m['points'],
                    'member_since' => Carbon::now()->subDays($m['days_ago']),
                ]
            );
        }

        // ----------------------------------------------------
        // 6. Data Shift Kasir (12+ Shifts)
        // ----------------------------------------------------
        $createdShifts = [];
        for ($i = 11; $i >= 1; $i--) {
            $shiftDate = Carbon::today()->subDays($i);
            $kasirUser = $staffUsers[$i % $staffUsers->count()];
            $modal = 500000;
            $penjualan = 600000 + ($i * 75000);
            $tunai = $penjualan * 0.7; // 70% tunai
            $kasAkhir = $modal + $tunai;

            $createdShifts[] = Shift::create([
                'user_id' => $kasirUser->id,
                'waktu_buka' => (clone $shiftDate)->setHour(8)->setMinute(0),
                'waktu_tutup' => (clone $shiftDate)->setHour(17)->setMinute(0),
                'modal_awal' => $modal,
                'total_penjualan' => $penjualan,
                'total_tunai' => $tunai,
                'kas_akhir' => $kasAkhir,
                'status' => 'tutup',
                'catatan' => 'Shift selesai, kas klop dan rekonsiliasi lancar.',
                'created_at' => (clone $shiftDate)->setHour(8),
                'updated_at' => (clone $shiftDate)->setHour(17),
            ]);
        }

        // Shift Aktif Hari Ini (Buka)
        $activeShiftUser = $staffUsers->first();
        $shiftHariIni = Shift::create([
            'user_id' => $activeShiftUser->id,
            'waktu_buka' => Carbon::today()->setHour(7)->setMinute(30),
            'waktu_tutup' => null,
            'modal_awal' => 500000,
            'total_penjualan' => 0,
            'total_tunai' => 0,
            'kas_akhir' => null,
            'status' => 'buka',
            'catatan' => null,
            'created_at' => Carbon::today()->setHour(7)->setMinute(30),
        ]);
        $createdShifts[] = $shiftHariIni;

        // ----------------------------------------------------
        // 7. Data Penjualan & Detail (25+ Transaksi Multi-Hari)
        // ----------------------------------------------------
        $metodeList = ['Tunai', 'QRIS', 'Transfer', 'Tunai', 'QRIS'];
        $createdPenjualans = [];

        for ($k = 24; $k >= 0; $k--) {
            $daysAgo = (int) floor($k / 2);
            $trxTime = Carbon::now()->subDays($daysAgo)->subHours(($k % 5) + 1)->subMinutes($k * 3);
            $kasir = $staffUsers[$k % $staffUsers->count()];
            $member = ($k % 3 === 0) ? $createdMembers[$k % count($createdMembers)] : null;
            $metode = $metodeList[$k % count($metodeList)];
            $assignedShift = $daysAgo === 0 ? $shiftHariIni : ($createdShifts[$daysAgo % count($createdShifts)] ?? $shiftHariIni);

            // Pilih 2 - 4 produk acak
            $itemCount = rand(2, 4);
            $selectedIndices = array_rand($createdProducts, $itemCount);
            if (!is_array($selectedIndices)) {
                $selectedIndices = [$selectedIndices];
            }

            $subtotal = 0;
            $details = [];

            foreach ($selectedIndices as $pIdx) {
                $prod = $createdProducts[$pIdx];
                $qty = rand(1, 4);
                $itemSubtotal = $prod->harga * $qty;
                $subtotal += $itemSubtotal;

                $details[] = [
                    'product_id' => $prod->id,
                    'product' => $prod,
                    'qty' => $qty,
                    'harga_satuan' => $prod->harga,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $diskon = ($member && $subtotal > 50000) ? 5000 : 0;
            $total = max(0, $subtotal - $diskon);
            $bayar = $metode === 'Tunai' ? (ceil($total / 10000) * 10000) : $total;
            if ($bayar < $total) $bayar = $total;
            $kembalian = $bayar - $total;

            $noTrx = 'TRX-' . $trxTime->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4)) . $k;

            $penjualan = Penjualan::create([
                'no_transaksi' => $noTrx,
                'user_id' => $kasir->id,
                'member_id' => $member?->id,
                'shift_id' => $assignedShift->id,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'poin_digunakan' => 0,
                'total' => $total,
                'bayar' => $bayar,
                'kembalian' => $kembalian,
                'metode_bayar' => $metode,
                'status' => 'selesai',
                'kasir_nama' => $kasir->name,
                'created_at' => $trxTime,
                'updated_at' => $trxTime,
            ]);

            foreach ($details as $d) {
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'product_id' => $d['product_id'],
                    'qty' => $d['qty'],
                    'harga_satuan' => $d['harga_satuan'],
                    'diskon' => 0,
                    'subtotal' => $d['subtotal'],
                    'created_at' => $trxTime,
                    'updated_at' => $trxTime,
                ]);

                // Record Stock Movement Keluar
                StockMovement::create([
                    'product_id' => $d['product_id'],
                    'tipe' => 'keluar',
                    'qty' => $d['qty'],
                    'stok_sebelum' => $d['product']->stok + $d['qty'],
                    'stok_sesudah' => $d['product']->stok,
                    'referensi' => $noTrx,
                    'keterangan' => 'Penjualan kasir POS',
                    'user_id' => $kasir->id,
                    'created_at' => $trxTime,
                ]);
            }

            if ($daysAgo === 0) {
                $shiftHariIni->increment('total_penjualan', $total);
                if ($metode === 'Tunai') {
                    $shiftHariIni->increment('total_tunai', $total);
                }
            }

            $createdPenjualans[] = $penjualan;
        }

        // ----------------------------------------------------
        // 8. Data Return Penjualan (12+ Return)
        // ----------------------------------------------------
        $reasons = [
            'Kemasan bocor/rusak saat dibawa',
            'Salah beli varian rasa oleh pelanggan',
            'Mendekati tanggal kadaluarsa',
            'Barang cacat produksi pabrik',
            'Salah input kasir saat checkout',
            'Segel kemasan sudah terbuka',
        ];

        for ($r = 0; $r < 12; $r++) {
            $linkedPenjualan = $createdPenjualans[$r % count($createdPenjualans)];
            $detail = DetailPenjualan::where('penjualan_id', $linkedPenjualan->id)->first();
            $retProd = $detail ? Product::find($detail->product_id) : $createdProducts[0];
            $retQty = 1;
            $retTotal = $retProd->harga * $retQty;
            $retTime = (clone $linkedPenjualan->created_at)->addHours(2);
            $noReturn = 'RTN-' . $retTime->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4)) . $r;

            ReturnPenjualan::create([
                'no_return' => $noReturn,
                'penjualan_id' => $linkedPenjualan->id,
                'product_id' => $retProd->id,
                'qty' => $retQty,
                'total_return' => $retTotal,
                'alasan' => $reasons[$r % count($reasons)],
                'status' => 'selesai',
                'user_id' => $manager->id,
                'created_at' => $retTime,
                'updated_at' => $retTime,
            ]);

            StockMovement::create([
                'product_id' => $retProd->id,
                'tipe' => 'masuk',
                'qty' => $retQty,
                'stok_sebelum' => $retProd->stok,
                'stok_sesudah' => $retProd->stok + $retQty,
                'referensi' => $noReturn,
                'keterangan' => 'Return Penjualan: ' . $reasons[$r % count($reasons)],
                'user_id' => $manager->id,
                'created_at' => $retTime,
            ]);
        }

        // ----------------------------------------------------
        // 9. Data Stock Adjustment (12+ Penyesuaian)
        // ----------------------------------------------------
        $adjNotes = [
            'Penyesuaian barang rusak saat bongkar muat',
            'Selisih hitung fisik saat perapihan display rak',
            'Barang sampling promosi toko',
            'Kemasan pecah tertimpa kardus',
            'Koreksi selisih barcode ganda',
            'Barang hilang saat display kasir',
        ];

        for ($a = 0; $a < 12; $a++) {
            $adjProduct = $createdProducts[$a % count($createdProducts)];
            $adjTime = Carbon::now()->subDays($a)->subHours(3);
            $noAdj = 'ADJ-' . $adjTime->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4)) . $a;
            $selisih = ($a % 2 === 0) ? -2 : 3;
            $stokSistem = $adjProduct->stok;
            $stokFisik = max(0, $stokSistem + $selisih);

            StockAdjustment::create([
                'no_adjustment' => $noAdj,
                'product_id' => $adjProduct->id,
                'stok_sistem' => $stokSistem,
                'stok_fisik' => $stokFisik,
                'selisih' => $selisih,
                'keterangan' => $adjNotes[$a % count($adjNotes)],
                'user_id' => $manager->id,
                'created_at' => $adjTime,
                'updated_at' => $adjTime,
            ]);

            StockMovement::create([
                'product_id' => $adjProduct->id,
                'tipe' => 'adjustment',
                'qty' => abs($selisih),
                'stok_sebelum' => $stokSistem,
                'stok_sesudah' => $stokFisik,
                'referensi' => $noAdj,
                'keterangan' => 'Adjustment: ' . $adjNotes[$a % count($adjNotes)],
                'user_id' => $manager->id,
                'created_at' => $adjTime,
            ]);
        }

        // ----------------------------------------------------
        // 10. Data Stock Opname (12+ Sesi Opname)
        // ----------------------------------------------------
        for ($o = 11; $o >= 0; $o--) {
            $opnameDate = Carbon::today()->subWeeks($o);
            $noOpname = 'OPN-' . $opnameDate->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4)) . $o;

            StockOpname::create([
                'no_opname' => $noOpname,
                'tanggal' => $opnameDate->toDateString(),
                'status' => 'selesai',
                'catatan' => 'Audit stok berkala minggu ke-' . (12 - $o) . ' seluruh kategori rak minimarket.',
                'user_id' => $manager->id,
                'created_at' => $opnameDate,
                'updated_at' => $opnameDate,
            ]);
        }

        // ----------------------------------------------------
        // 11. Data Pengeluaran / Expenses (15+ Catatan Biaya)
        // ----------------------------------------------------
        $expensesList = [
            ['judul' => 'Tagihan Listrik PLN Toko', 'kategori' => 'Utilitas', 'jumlah' => 750000, 'days' => 14],
            ['judul' => 'Tagihan Air PDAM', 'kategori' => 'Utilitas', 'jumlah' => 180000, 'days' => 13],
            ['judul' => 'Langganan Internet & WiFi Biznet', 'kategori' => 'Utilitas', 'jumlah' => 450000, 'days' => 12],
            ['judul' => 'Beli Air Galon Aqua & Kebutuhan Karyawan', 'kategori' => 'Operasional', 'jumlah' => 95000, 'days' => 11],
            ['judul' => 'Pembelian Kertas Struk Termal 80mm 20 Roll', 'kategori' => 'Operasional', 'jumlah' => 160000, 'days' => 10],
            ['judul' => 'Biaya Kebersihan & Retribusi Sampah', 'kategori' => 'Operasional', 'jumlah' => 100000, 'days' => 9],
            ['judul' => 'Biaya Keamanan Lingkungan Bulanan', 'kategori' => 'Operasional', 'jumlah' => 150000, 'days' => 8],
            ['judul' => 'Plastik Kresek Sablon Toko Ukuran M & L', 'kategori' => 'Operasional', 'jumlah' => 280000, 'days' => 7],
            ['judul' => 'Service & Cuci AC Ruang Kasir', 'kategori' => 'Operasional', 'jumlah' => 200000, 'days' => 6],
            ['judul' => 'Beli Baterai Cadangan Barcode Scanner', 'kategori' => 'Pembelian Barang', 'jumlah' => 120000, 'days' => 5],
            ['judul' => 'Honor Lembur Karyawan Stock Opname', 'kategori' => 'Gaji', 'jumlah' => 500000, 'days' => 4],
            ['judul' => 'Sewa Gudang Tambahan Bulan Ini', 'kategori' => 'Sewa', 'jumlah' => 1500000, 'days' => 3],
            ['judul' => 'Pembersih Lantai, Karbol & Kanebo', 'kategori' => 'Operasional', 'jumlah' => 85000, 'days' => 2],
            ['judul' => 'Konsumsi Rapat Evaluasi Bulanan Staf', 'kategori' => 'Operasional', 'jumlah' => 250000, 'days' => 1],
            ['judul' => 'Beli Lampu LED Display Rak Minimarket', 'kategori' => 'Lainnya', 'jumlah' => 175000, 'days' => 0],
        ];

        foreach ($expensesList as $exp) {
            $expDate = Carbon::today()->subDays($exp['days']);
            Pengeluaran::create([
                'judul' => $exp['judul'],
                'kategori' => $exp['kategori'],
                'jumlah' => $exp['jumlah'],
                'keterangan' => 'Pengeluaran rutin operasional toko',
                'tanggal' => $expDate->toDateString(),
                'user_id' => ($exp['jumlah'] > 500000) ? $manager->id : $staff->id,
                'created_at' => $expDate,
                'updated_at' => $expDate,
            ]);
        }

        // ----------------------------------------------------
        // 12. Data Activity Log (20+ Log Audit Trail)
        // ----------------------------------------------------
        $logEntries = [
            ['user' => $admin, 'action' => 'login', 'modul' => 'Auth', 'desc' => 'Admin Utama login ke sistem'],
            ['user' => $manager, 'action' => 'login', 'modul' => 'Auth', 'desc' => 'Manager Operasional login ke sistem'],
            ['user' => $staff, 'action' => 'login', 'modul' => 'Auth', 'desc' => 'Staf Kasir login ke sistem'],
            ['user' => $admin, 'action' => 'create', 'modul' => 'Settings', 'desc' => 'Memperbarui profil toko dan template struk'],
            ['user' => $manager, 'action' => 'create', 'modul' => 'Category', 'desc' => 'Menambahkan kategori baru: Makanan Ringan'],
            ['user' => $manager, 'action' => 'create', 'modul' => 'Supplier', 'desc' => 'Mendaftarkan supplier baru: PT Indofood Sukses Makmur'],
            ['user' => $admin, 'action' => 'create', 'modul' => 'Product', 'desc' => 'Menambahkan produk Indomie Goreng Spesial ke katalog'],
            ['user' => $staff, 'action' => 'create', 'modul' => 'Shift', 'desc' => 'Membuka shift kasir pagi dengan modal Rp 500.000'],
            ['user' => $staff, 'action' => 'create', 'modul' => 'Penjualan', 'desc' => 'Memproses transaksi TRX-' . date('Ymd') . '-001 senilai Rp 85.000 (Tunai)'],
            ['user' => $staff, 'action' => 'create', 'modul' => 'Penjualan', 'desc' => 'Memproses transaksi TRX-' . date('Ymd') . '-002 senilai Rp 145.000 (QRIS)'],
            ['user' => $manager, 'action' => 'create', 'modul' => 'Return', 'desc' => 'Memproses pengembalian produk RTN-' . date('Ymd') . '-001 (Kemasan rusak)'],
            ['user' => $manager, 'action' => 'create', 'modul' => 'Inventory', 'desc' => 'Melakukan penyesuaian stok ADJ-' . date('Ymd') . '-001'],
            ['user' => $manager, 'action' => 'create', 'modul' => 'StockOpname', 'desc' => 'Menyimpan hasil sesi stock opname OPN-' . date('Ymd') . '-001'],
            ['user' => $staff, 'action' => 'create', 'modul' => 'Expenses', 'desc' => 'Mencatat pengeluaran operasional: Pembelian Kertas Struk Rp 160.000'],
            ['user' => $admin, 'action' => 'update', 'modul' => 'Roles', 'desc' => 'Mengubah hak akses user Hendra Kusuma menjadi Manager'],
            ['user' => $staff, 'action' => 'update', 'modul' => 'Shift', 'desc' => 'Menutup shift kasir dengan kas akhir Rp 1.250.000'],
            ['user' => $staff, 'action' => 'logout', 'modul' => 'Auth', 'desc' => 'Staf Kasir logout dari sistem'],
            ['user' => $admin, 'action' => 'delete', 'modul' => 'ActivityLog', 'desc' => 'Membersihkan jejak log lama lebih dari 30 hari'],
            ['user' => $manager, 'action' => 'update', 'modul' => 'Product', 'desc' => 'Memperbarui harga jual Minyak Goreng Bimoli 2L'],
            ['user' => $admin, 'action' => 'create', 'modul' => 'User', 'desc' => 'Menambahkan akun pengguna baru untuk staf kasir'],
        ];

        foreach ($logEntries as $idx => $le) {
            $logTime = Carbon::now()->subHours(40 - $idx * 2);
            ActivityLog::create([
                'user_id' => $le['user']->id,
                'user_name' => $le['user']->name,
                'action' => $le['action'],
                'modul' => $le['modul'],
                'deskripsi' => $le['desc'],
                'ip_address' => '127.0.0.1',
                'created_at' => $logTime,
                'updated_at' => $logTime,
            ]);
        }

        // ----------------------------------------------------
        // 13. Data Pembelian Legacy (12+ Pembelian)
        // ----------------------------------------------------
        $customerNames = ['Toko Berkah Ibu', 'Warung Bu Siti', 'Kantin Sehat', 'Kedai Kopi Santai', 'Minimarket Barokah', 'Keluarga Bpk. Herman', 'Warung Mbak Nita', 'Kantin Sekolah SD 01', 'Toko Grosir Mandiri', 'Ibu Ratna Sari', 'Bpk. Gunawan', 'Warung Makan Padang'];

        for ($p = 0; $p < 12; $p++) {
            $buyTime = Carbon::now()->subDays($p + 1)->subHours(4);
            $grandTotal = 150000 + ($p * 25000);
            $bayar = $grandTotal + 50000;
            $kembalian = 50000;
            $invNum = 'INV-' . $buyTime->format('Ymd') . '-' . str_pad($p + 1, 4, '0', STR_PAD_LEFT);

            $pembelian = Pembelians::create([
                'invoice_number' => $invNum,
                'customer_name' => $customerNames[$p % count($customerNames)],
                'grand_total' => $grandTotal,
                'tanggal' => $buyTime,
                'dibuat_oleh' => $staff->name,
                'created_at' => $buyTime,
                'updated_at' => $buyTime,
            ]);

            $pProd = $createdProducts[$p % count($createdProducts)];
            DetailPembelian::create([
                'pembelian_id' => $pembelian->id,
                'id_produk' => $pProd->id,
                'quantity' => rand(2, 6),
                'total_price' => $grandTotal,
                'created_at' => $buyTime,
                'updated_at' => $buyTime,
            ]);

            Pembayaran::create([
                'pembelian_id' => $pembelian->id,
                'jumlah_bayar' => $bayar,
                'kembalian' => $kembalian,
                'metode_pembayaran' => ($p % 2 == 0) ? 'Tunai' : 'QRIS',
                'created_at' => $buyTime,
                'updated_at' => $buyTime,
            ]);
        }
    }
}
