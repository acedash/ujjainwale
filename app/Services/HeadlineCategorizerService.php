<?php

namespace App\Services;

use App\Models\Category;
use App\Models\ExternalHeadline;
use Illuminate\Support\Facades\DB;

class HeadlineCategorizerService
{
    /**
     * Map of category name and its matching keywords/regex in Hindi and English.
     *
     * @var array<string, array{slug: string, keywords: array<string>}>
     */
    protected array $rules = [
        'उज्जैन समाचार' => [
            'slug' => 'ujjain-news',
            'keywords' => [
                'उज्जैन', 'ujjain', 'महाकाल', 'mahakal', 'महाकालेश्वर', 'शिप्रा', 'क्षिप्रा', 'kshipra', 'shipra',
                'रामघाट', 'सिंहस्थ', 'सिंहास्थ', 'simhastha', 'कालभैरव', 'हरसिद्धि', 'मंगलनाथ', 'चिंतामण',
                'चिंतामन', 'गदकालिका', 'सिद्धवट', 'भर्तृहरि', 'विक्रमादित्य', 'विक्रम विश्वविद्यालय',
                'कालिदास अकादमी', 'त्रिवेणी', 'सांदीपनि', 'वेधशाला', 'जंतर मंतर', 'कोठी पैलेस', 'कोठी चौराहा',
                'टावर चौराहा', 'टावर चौक', 'फ्रीगंज', 'freeganj', 'नानाखेड़ा', 'nanakheda', 'माधव नगर',
                'चिमनगंज', 'मक्सी रोड', 'देवास गेट', 'आगर रोड', 'इंदौर रोड', 'बड़नगर', 'खाचरौद',
                'महिदपुर', 'तराना', 'घट्टिया', 'नागदा', 'उज्जैन नगर निगम', 'स्मार्ट सिटी उज्जैन',
                'कलेक्टर उज्जैन', 'एसपी उज्जैन', 'उज्जैन पुलिस', 'उज्जैन जंक्शन', 'उन्हेल', 'दत्त अखाड़ा',
                'चरक भवन', 'माधव कॉलेज', 'इस्कॉन उज्जैन', 'त्रिवेणी संग्रहालय',
            ],
        ],
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

    protected ?array $categoryMapCache = null;

    /**
     * Cache and return category ID map for defined categories.
     *
     * @return array<string, int> slug => id
     */
    public function getCategoryMap(): array
    {
        if ($this->categoryMapCache !== null) {
            return $this->categoryMapCache;
        }

        $map = [];
        foreach ($this->rules as $categoryName => $config) {
            $category = Category::firstOrCreate(
                ['slug' => $config['slug']],
                [
                    'name' => $categoryName,
                    'type' => 'both',
                ]
            );

            if ($category->type !== 'both') {
                $category->update(['type' => 'both']);
            }

            $map[$config['slug']] = $category->id;
        }

        return $this->categoryMapCache = $map;
    }

    /**
     * Categorize a single external headline.
     *
     * @return array<int> Matched category IDs
     */
    public function categorize(ExternalHeadline $headline): array
    {
        $categoryMap = $this->getCategoryMap();
        $text = mb_strtolower($headline->title.' '.($headline->original_snippet ?? ''));
        $matchedCategoryIds = [];

        foreach ($this->rules as $config) {
            $catId = $categoryMap[$config['slug']];
            foreach ($config['keywords'] as $keyword) {
                if (mb_stripos($text, mb_strtolower($keyword)) !== false) {
                    $matchedCategoryIds[] = $catId;
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
     * Categorize all uncategorized or all headlines in high-performance chunks.
     */
    public function categorizeAll(bool $onlyUncategorized = false): int
    {
        $categoryMap = $this->getCategoryMap();

        $query = ExternalHeadline::query()->select(['id', 'title', 'original_snippet']);

        if ($onlyUncategorized) {
            $query->doesntHave('categories');
        }

        $totalCategorized = 0;

        $query->chunk(500, function ($headlines) use ($categoryMap, &$totalCategorized) {
            $headlineIds = $headlines->pluck('id')->all();

            // Fetch any existing relations for this batch to prevent duplicates
            $existingRelations = DB::table('category_external_headline')
                ->whereIn('external_headline_id', $headlineIds)
                ->get()
                ->groupBy('external_headline_id')
                ->map(fn ($group) => $group->pluck('category_id')->flip()->all())
                ->all();

            $pivotRows = [];
            $categorizedInBatch = 0;

            foreach ($headlines as $headline) {
                $text = mb_strtolower($headline->title.' '.($headline->original_snippet ?? ''));
                $hasMatch = false;
                $existingCatMap = $existingRelations[$headline->id] ?? [];

                foreach ($this->rules as $config) {
                    $catId = $categoryMap[$config['slug']];

                    // Skip if already linked
                    if (isset($existingCatMap[$catId])) {
                        continue;
                    }

                    foreach ($config['keywords'] as $keyword) {
                        if (mb_stripos($text, mb_strtolower($keyword)) !== false) {
                            $pivotRows[] = [
                                'category_id' => $catId,
                                'external_headline_id' => $headline->id,
                            ];
                            $existingCatMap[$catId] = true; // prevent duplicate within same item
                            $hasMatch = true;
                            break;
                        }
                    }
                }

                if ($hasMatch) {
                    $categorizedInBatch++;
                }
            }

            if (! empty($pivotRows)) {
                DB::table('category_external_headline')->insert($pivotRows);
            }

            $totalCategorized += $categorizedInBatch;
        });

        return $totalCategorized;
    }
}
