<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::first();
        $currencies = \App\Models\Currency::all();
        return view('settings.edit', compact('setting', 'currencies'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_common_upi' => 'required|string',
            'default_currency_id' => 'required|exists:currencies,id',
            'company_store_discount' => 'required|numeric|min:0|max:100',
            'cgst_percentage' => 'required|numeric|min:0|max:100',
            'sgst_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $setting = Setting::first();
        $oldUpi = $setting ? $setting->company_common_upi : null;

        Setting::updateOrCreate(
            ['id' => 1],
            [
                'company_common_upi' => $request->company_common_upi,
                'default_currency_id' => $request->default_currency_id,
                'company_store_discount' => $request->company_store_discount,
                'cgst_percentage' => $request->cgst_percentage,
                'sgst_percentage' => $request->sgst_percentage,
            ]
        );

        \App\Models\Currency::query()->update(['is_default' => false]);
        \App\Models\Currency::where('id', $request->default_currency_id)->update(['is_default' => true]);

        if ($oldUpi !== $request->company_common_upi) {
            \App\Helpers\QrCodeHelper::generateQrCode($request->company_common_upi, 'company_upi_qr.png');
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function addWatermark(Request $request) {
        $imagePath = storage_path("app/public/workflow-task-uploads/SIGN-2025101312533168eca8f3d3cd2.png");

        if (file_exists($imagePath) && is_file($imagePath)) {
            try {

            $img = \Image::make($imagePath);

            $img->text(rand(1, 100000000000000000), $img->width() - 10, 10, function ($font) {
                $font->file(storage_path('fonts/Roboto-Regular.ttf'));
                $font->size(45);
                $font->color('#ffffff');
                $font->align('right');
                $font->valign('top');
            });

            $path = $imagePath;
            $filename = !empty($path) ? basename($path) : null;

            if ($filename) {
                $img->save("storage/workflow-task-uploads/{$filename}", 90);
            }                
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }
    }
}
