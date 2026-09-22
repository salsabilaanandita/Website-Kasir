<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function store()
    {
        $settings = [
            'nama_toko'   => Setting::get('nama_toko', 'Toko Kasir'),
            'alamat'      => Setting::get('alamat', ''),
            'telepon'     => Setting::get('telepon', ''),
            'email'       => Setting::get('email', ''),
            'currency'    => Setting::get('currency', 'Rp'),
            'tagline'     => Setting::get('tagline', ''),
            'pajak'       => Setting::get('pajak', '0'),
            'logo'        => Setting::get('logo', ''),
        ];

        return view('settings.store', compact('settings'));
    }

    public function updateStore(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:100',
            'alamat'    => 'nullable|string',
            'telepon'   => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:100',
            'tagline'   => 'nullable|string|max:200',
            'pajak'     => 'nullable|numeric|min:0|max:100',
        ]);

        $fields = ['nama_toko', 'alamat', 'telepon', 'email', 'tagline', 'pajak'];
        foreach ($fields as $field) {
            Setting::set($field, $request->input($field, ''), 'toko');
        }

        // Upload logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', $path, 'toko');
        }

        return redirect()->route('settings.store')
            ->with('success', 'Pengaturan toko berhasil disimpan!');
    }

    public function receipt()
    {
        $settings = [
            'receipt_header' => Setting::get('receipt_header', ''),
            'receipt_footer' => Setting::get('receipt_footer', 'Terima kasih telah berbelanja!'),
            'show_member'    => Setting::get('show_member', '1'),
        ];

        return view('settings.receipt', compact('settings'));
    }

    public function updateReceipt(Request $request)
    {
        Setting::set('receipt_header', $request->receipt_header ?? '', 'receipt');
        Setting::set('receipt_footer', $request->receipt_footer ?? 'Terima kasih telah berbelanja!', 'receipt');
        Setting::set('show_member', $request->show_member ? '1' : '0', 'receipt');

        return redirect()->route('settings.receipt')
            ->with('success', 'Pengaturan struk berhasil disimpan!');
    }
}
