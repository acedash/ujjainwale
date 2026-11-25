<?php

namespace App\Services;

use App\Models\Category;
use App\Models\ExternalHeadline;

class HeadlineCategorizerService
{
    /**
     * Map of category name and its matching keywords/regex in Hindi and English.
     *
     * @var array<string, array{slug: string, keywords: array<string>}>
     */
    protected array $rules = [
        'धर्म एवं संस्कृति' => [
            'slug' => 'dharma-culture',
            'keywords' => [
                'महाकाल', 'मंदिर', 'दर्शन', 'आरती', 'भस्म', 'पूजा', 'सिंहस्थ', 'पंडित', 'साधु',
                'श्रावण', 'सावन', 'शिवरात्रि', 'नवरात्रि', 'धार्मिक', 'संस्कृति', 'अवन्तिका',
                'ज्योतिर्लिंग', 'रामघाट', 'शिप्रा', 'क्षिप्रा', 'भगवान', 'प्रसाद', 'हनुमान',
                'गणेश', 'शंख', 'अनुष्ठान', 'तीर्थ', 'भक्त', 'श्रद्धालु', 'मंदिरों', 'पर्व', 'त्योहार',
            ],
        ],
        'शहर एवं स्थानीय हलचल' => [
            'slug' => 'city-local',
            'keywords' => [
                'उज्जैन', 'फ्रीगंज', 'नानाखेड़ा', 'कोठी', 'टावर चौराहा', 'माधव नगर', 'चिमनगंज',
                'नगर निगम', 'स्मार्ट सिटी', 'कलेक्टर', 'प्रशासन', 'सड़क', 'पुल', 'यातायात',
                'रेलवे', 'स्टेशन', 'महापौर', 'वार्ड', 'कॉलोनी', 'पार्क', 'सफाई', 'अतिक्रमण',
            ],
        ],
        'अपराध एवं सुरक्षा' => [
            'slug' => 'crime-safety',
            'keywords' => [
                'पुलिस', 'गिरफ्तार', 'हादसा', 'दुर्घटना', 'थाना', 'अपराध', 'चोरी', 'मामला दर्ज',
                'आरोपी', 'वारदात', 'धोखाधड़ी', 'मृत्यु', 'मौत', 'जेल', 'कोर्ट', 'अदालत', 'FIR',
                'हत्या', 'लाश', 'मुकदमा', 'सड़क हादसे', 'तस्करी', 'गांजा', 'शराब',
            ],
        ],
        'राजनीति एवं शासन' => [
            'slug' => 'politics-governance',
            'keywords' => [
                'भाजपा', 'बीजेपी', 'कांग्रेस', 'चुनाव', 'विधायक', 'सांसद', 'मुख्यमंत्री', 'मंत्री',
                'पार्टी', 'प्रत्याशी', 'मतदान', 'सरकार', 'विपक्ष', 'संसद', 'विधानसभा', 'नेता',
                'अध्यक्ष', 'कार्यकर्ता', 'रैली', 'सभागार', 'संबोधित',
            ],
        ],
        'व्यापार एवं मंडी' => [
            'slug' => 'business-market',
            'keywords' => [
                'सराफा', 'मंडी', 'सोना', 'चांदी', 'भाव', 'दाम', 'बाजार', 'व्यापारी', 'उद्योग',
                'जीएसटी', 'कारोबार', 'शेयर', 'बैंक', 'बजट', 'व्यापार', 'कृषि उपज', 'गेहूं', 'चना',
            ],
        ],
        'मौसम' => [
            'slug' => 'weather',
            'keywords' => [
                'मौसम', 'बारिश', 'तापमान', 'ठंड', 'गर्मी', 'मानसून', 'हवा', 'कोहरा', 'अलर्ट',
                'ओले', 'धूप', 'बादल', 'मावठा',
            ],
        ],
        'खेल' => [
            'slug' => 'sports-news',
            'keywords' => [
                'क्रिकेट', 'मैच', 'टूर्नामेंट', 'खिलाड़ी', 'जीत', 'हार', 'विश्व कप', 'आईपीएल',
                'गोल्ड मेडल', 'फुटबॉल', 'कुश्ती', 'दंगल', 'हॉकी',
            ],
        ],
    ];

    /**
     * Categorize a single external headline.
     *
     * @return array<int> Matched category IDs
     */
    public function categorize(ExternalHeadline $headline): array
    {
        $text = mb_strtolower($headline->title.' '.($headline->original_snippet ?? ''));
        $matchedCategoryIds = [];

        foreach ($this->rules as $categoryName => $config) {
            foreach ($config['keywords'] as $keyword) {
                if (mb_stripos($text, mb_strtolower($keyword)) !== false) {
                    $category = Category::firstOrCreate(
                        ['slug' => $config['slug']],
                        [
                            'name' => $categoryName,
                            'type' => 'news',
                        ]
                    );
                    $matchedCategoryIds[] = $category->id;
                    break;
                }
            }
        }

        if (! empty($matchedCategoryIds)) {
            $headline->categories()->syncWithoutDetaching($matchedCategoryIds);
        }

        return $matchedCategoryIds;
    }

    /**
     * Categorize all uncategorized or all headlines.
     */
    public function categorizeAll(bool $onlyUncategorized = false): int
    {
        $query = ExternalHeadline::query();

        if ($onlyUncategorized) {
            $query->doesntHave('categories');
        }

        $count = 0;
        $query->chunk(100, function ($headlines) use (&$count) {
            foreach ($headlines as $headline) {
                $matched = $this->categorize($headline);
                if (! empty($matched)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
