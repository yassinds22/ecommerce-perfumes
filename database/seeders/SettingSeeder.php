<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'لوكس بارفيوم',
                'display_name' => 'اسم الموقع',
                'group' => 'general',
                'type' => 'text',
            ],
            [
                'key' => 'site_logo',
                'value' => '',
                'display_name' => 'شعار الموقع',
                'group' => 'general',
                'type' => 'image',
            ],
            [
                'key' => 'hero_title',
                'value' => 'فن صناعة العطور الفاخرة',
                'display_name' => 'عنوان القسم الترحيبي',
                'group' => 'general',
                'type' => 'text',
            ],

            // Contact Settings
            [
                'key' => 'contact_email',
                'value' => 'info@luxeparfum.com',
                'display_name' => 'البريد الإلكتروني للتواصل',
                'group' => 'contact',
                'type' => 'text',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+966 50 000 0000',
                'display_name' => 'رقم الهاتف',
                'group' => 'contact',
                'type' => 'text',
            ],
            [
                'key' => 'whatsapp_number',
                'value' => '+966500000000',
                'display_name' => 'رقم الواتساب',
                'group' => 'contact',
                'type' => 'text',
            ],
            [
                'key' => 'office_address',
                'value' => 'الرياض، المملكة العربية السعودية',
                'display_name' => 'العنوان الفيزيائي',
                'group' => 'contact',
                'type' => 'text',
            ],

            // Social Settings
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/luxeparfum',
                'display_name' => 'رابط الفيسبوك',
                'group' => 'social',
                'type' => 'text',
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/luxeparfum',
                'display_name' => 'رابط الإنستقرام',
                'group' => 'social',
                'type' => 'text',
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/luxeparfum',
                'display_name' => 'رابط تويتر',
                'group' => 'social',
                'type' => 'text',
            ],

            // SEO Settings
            [
                'key' => 'meta_description',
                'value' => 'اكتشف أرقى العطور الفرنسية والشرقية في متجر لوكس بارفيوم. جودة استثنائية وروائح تدوم طويلاً.',
                'display_name' => 'وصف الميتا (Meta Description)',
                'group' => 'seo',
                'type' => 'textarea',
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'عطور، عطر، لوكس بارفيوم، عطور فرنسية، عطور شرقية، عود، مسك',
                'display_name' => 'الكلمات المفتاحية (Keywords)',
                'group' => 'seo',
                'type' => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
