<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ads = [
            [
                'title' => 'श्री महाकाल हेरिटेज होटल एवं धर्मशाला · Ujjain',
                'placement' => 'billboard',
                'subtitle' => 'Special Pilgrim Offer · 4.9 ★ (850+ Reviews)',
                'badge_text' => 'Ad · विज्ञापन',
                'description' => 'महाकाल मंदिर से मात्र २०० मीटर दूरी पर • 24/7 एसी डीलक्स रूम्स • शुद्ध सात्विक भोजन • भस्म आरती दर्शन सुविधा उपलब्ध।',
                'cta_text' => 'Book Room Now',
                'cta_url' => 'tel:+919827012345',
                'phone' => '+91 98270 12345',
                'icon' => '🛕',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'श्री खंडेलवाल स्वीट्स & नमकीन • फ्रीगंज',
                'placement' => 'sponsor_card',
                'subtitle' => '100% Pure Desi Ghee',
                'badge_text' => 'Sponsored · विज्ञापन',
                'description' => 'उज्जैन का प्रसिद्ध पेड़ा, रसमलाई और स्पेशल रतलामी सेंव। होम डिलीवरी उपलब्ध।',
                'cta_text' => 'Order Now',
                'cta_url' => 'tel:+919827154321',
                'phone' => '+91 98271 54321',
                'icon' => '🍧',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'अवन्तिका पैथोलॉजी & सीटी स्कैन सेंटर',
                'placement' => 'sponsor_card',
                'subtitle' => '50% Off Health Packages',
                'badge_text' => 'Sponsored · विज्ञापन',
                'description' => 'टावर चौराहा, उज्जैन • घर बैठे फ्री ब्लड सैंपल कलेक्शन • रिपोर्ट 2 घंटे में।',
                'cta_text' => 'Call Lab',
                'cta_url' => 'tel:+919827298765',
                'phone' => '+91 98272 98765',
                'icon' => '🩺',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'title' => 'सुभाष ज्वेलर्स • 100% हॉलमार्क स्वर्ण एवं हीरक आभूषण • सराफा बाजार, उज्जैन',
                'placement' => 'in_feed',
                'subtitle' => 'Festive Discount 25% Off Making',
                'badge_text' => 'Special Sponsor · प्रायोजक',
                'description' => 'शादी, सगाई व त्यौहारों की विशेष खरीदारी के लिए उज्जैन का 50 वर्षों का सबसे विश्वसनीय नाम।',
                'cta_text' => 'Visit Showroom',
                'cta_url' => 'tel:+919827099999',
                'phone' => '+91 98270 99999',
                'icon' => '💎',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'महाकाल दर्शन स्पेशल टैक्सी सेवा',
                'placement' => 'left_rail',
                'subtitle' => 'उज्जैन, ओंकारेश्वर एवं इंदौर टूर',
                'badge_text' => 'विज्ञापन',
                'description' => 'महाकाल दर्शन, ओंकारेश्वर, इंदौर एयरपोर्ट व स्थानीय भ्रमण के लिए 24/7 सुलभ एसी गाड़ियां, विनम्र व अनुभवी सारथी, उचित व पारदर्शी दरें।',
                'cta_text' => 'कॉल करें',
                'cta_url' => 'tel:+919827011111',
                'phone' => '+91 98270 11111',
                'icon' => '🚕',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'उज्जैन वैदिक पूजा व दर्शन गाइड',
                'placement' => 'right_rail',
                'subtitle' => 'कालसर्प, मंगल भात पूजा एवं अनुष्ठान',
                'badge_text' => 'प्रायोजक',
                'description' => 'उज्जैन के प्रख्यात तीर्थों में कालसर्प दोष, मंगल भात पूजा, महामृत्युंजय जाप एवं विशेष भस्म आरती दर्शन हेतु अधिकृत व प्रामाणिक वैदिक व्यवस्था।',
                'cta_text' => 'पूजा बुक करें',
                'cta_url' => 'tel:+919827022222',
                'phone' => '+91 98270 22222',
                'icon' => '🕉️',
                'is_active' => true,
                'order' => 1,
            ],
        ];

        foreach ($ads as $ad) {
            Advertisement::updateOrCreate(
                ['title' => $ad['title'], 'placement' => $ad['placement']],
                $ad
            );
        }
    }
}
