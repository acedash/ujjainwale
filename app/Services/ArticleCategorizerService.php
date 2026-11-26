<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Collection;

class ArticleCategorizerService
{
    /**
     * Category rules mapping category names to slug, keywords, and legacy categories.
     *
     * @var array<string, array{slug: string, keywords: array<string>, legacy?: array<string>}>
     */
    protected array $rules = [
        'धर्म एवं संस्कृति' => [
            'slug' => 'dharma-culture',
            'keywords' => [
                'महाकाल', 'मंदिर', 'दर्शन', 'आरती', 'भस्म', 'पूजा', 'सिंहस्थ', 'पंडित', 'साधु',
                'श्रावण', 'सावन', 'शिवरात्रि', 'नवरात्रि', 'धार्मिक', 'संस्कृति', 'अवन्तिका',
                'ज्योतिर्लिंग', 'रामघाट', 'शिप्रा', 'क्षिप्रा', 'भगवान', 'प्रसाद', 'हनुमान',
                'गणेश', 'शंख', 'अनुष्ठान', 'तीर्थ', 'भक्त', 'श्रद्धालु', 'मंदिरों', 'पर्व', 'त्योहार',
                'इस्कॉन', 'चिंतामण', 'मंगलनाथ', 'हरसिद्धि', 'कालभैरव', 'गदकालिका', 'सिद्धवट', 'संध्या',
                'भक्ति', 'आश्रम', 'ॐ', 'मंत्र', 'महामृत्युंजय', 'स्तोत्र', 'श्लोक', 'वेद', 'संस्कृत',
                'गायत्री', 'प्रार्थना', 'लिंग', 'शिव', 'जटाटवी', 'त्र्यम्बकं', 'स्फटिक', 'कालसर्प',
            ],
            'legacy' => [
                'उज्जैन>सिंहस्थ २०१६',
                'उज्जैन>दर्शनीय स्थल>मंदिर',
                'उज्जैन>दर्शनीय स्थल>मंदिर>इस्कॉन मंदिर',
                'उज्जैन>दर्शनीय स्थल>मंदिर>गेबी साहब',
                'उज्जैन>दर्शनीय स्थल>मंदिर>गोपाल मंदिर',
                'उज्जैन>दर्शनीय स्थल>मंदिर>मंगलनाथ',
                'उज्जैन>दर्शनीय स्थल>मंदिर>चिंतामण गणपति',
            ],
        ],
        'खान-पान एवं स्वाद' => [
            'slug' => 'food-cuisine',
            'keywords' => [
                'पोहा', 'जलेबी', 'कचौरी', 'कचोरी', 'कचौरीयां', 'कचौरियां', 'कचौड़ियां', 'समोसा', 'दाल बाफला', 'बाफले',
                'रबड़ी', 'कुल्फी', 'मावा', 'नमकीन', 'सेव', 'मिठाई', 'नाश्ता', 'चाय', 'ढाबा', 'रेस्टोरेंट',
                'चटोरे', 'स्वाद', 'जायका', 'स्ट्रीट-फूड', 'स्ट्रीट फूड', 'खान पान', 'खान-पान', 'व्यंजन',
                'आलू बड़े', 'आलू बडे', 'पकवान', 'कुल्फी फालूदा', 'भोजन', 'मसाले', 'मिष्ठान', 'स्नैक्स', 'जलेबियां',
            ],
            'legacy' => [
                'उज्जैन>खान पान',
                'Food & Drink',
                'ujajanakhana-pana',
            ],
        ],
        'इतिहास एवं विरासत' => [
            'slug' => 'history-heritage',
            'keywords' => [
                'इतिहास', 'ऐतिहासिक', 'विक्रमादित्य', 'सम्राट', 'राजा', 'भर्तृहरि', 'वराहमिहिर', 'कालिदास',
                'कालगणना', 'वेधशाला', 'जंतर मंतर', 'प्राचीन', 'नवरत्न', 'नौ रत्न', 'पुरातत्व', 'धरोहर',
                'विरासत', 'संग्रहालय', 'अवन्तिका', 'उज्जयिनी', 'मालवा', 'पुराण', 'सभ्यता', 'शिलालेख',
                'सुलतान', 'किला', 'महल', 'राजवंश', 'खंडहर', 'सिक्के', 'मुगल', 'परमार',
            ],
            'legacy' => [
                'उज्जैन>इतिहास',
                'उज्जैन>इतिहास>नौ रत्न',
            ],
        ],
        'साहित्य एवं कला' => [
            'slug' => 'literature-arts',
            'keywords' => [
                'साहित्य', 'कविता', 'कविताएं', 'कवितायें', 'लघुकथा', 'लघुकथाएं', 'लघुकथाएँ', 'व्यंग्य',
                'कहानी', 'कहानियां', 'लेखक', 'कवि', 'कवयित्री', 'रचना', 'पुस्तक', 'उपन्यास', 'नाटक',
                'मंच', 'गज़ल', 'शायरी', 'कला', 'चित्रकला', 'बोलती तस्वीरें', 'तस्वीरें', 'तस्वीर',
                'गीत', 'श्रोता', 'हास्य', 'व्यंग्यकार', 'चिठ्ठे', 'रंगमंच', 'नाट्य', 'व्यास', 'सूर्यनारायण',
                'स्वाभिमान', 'साहित्यकार', 'शिल्प', 'नाटककार', 'संगीत', 'छायाकार', 'छायांकन',
                'फोटोग्राफी', 'श्वेत श्याम', 'मोनोक्रोम', 'फोटो',
            ],
            'legacy' => [
                'विशेष>साहित्य',
                'विशेष>साहित्य>हिंदी व्यंग्य',
                'विशेष>साहित्य>हिंदी लघुकथाएं',
                'विशेष>साहित्य>हिंदी लघुकथाएँ',
                'विशेष>साहित्य>संस्कृत कवितायें',
                'बोलती तस्वीरें',
                'ताज़ा चिठ्ठे',
            ],
        ],
        'दर्शनीय स्थल एवं पर्यटन' => [
            'slug' => 'sightseeing-tourism',
            'keywords' => [
                'दर्शनीय स्थल', 'दर्शनीय', 'पर्यटन', 'पर्यटक', 'भ्रमण', 'यात्रा', 'तीर्थाटन', 'गुफा',
                'भर्तृहरि गुफा', 'कालियादेह', 'त्रिवेणी', 'कोठी पैलेस', 'कोठी महल', 'घाट', 'दत्त अखाड़ा',
                'सैर', 'सैलानी', 'हेरिटेज', 'पहचानें जरा', 'इमारत', 'पर्यटन स्थल',
            ],
            'legacy' => [
                'उज्जैन>दर्शनीय स्थल',
            ],
        ],
        'जीवन शैली एवं समाज' => [
            'slug' => 'lifestyle-society',
            'keywords' => [
                'जीवन शैली', 'जीवनशैली', 'स्वास्थ्य', 'योग', 'रक्तदान', 'देह दान', 'दान', 'समाज',
                'सामाजिक', 'सेवा', 'परंपरा', 'उत्सव', 'मेला', 'रहन-सहन', 'पर्यावरण', 'जैव विविधता',
                'प्रकृति', 'पेड़', 'नदी', 'खान नदी', 'राहगीरी', 'सरोकार', 'हस्तियाँ', 'हस्ती',
                'संस्मरण', 'व्यक्तित्व', 'नारी', 'युवा', 'जीव-जंतु', 'अभियान', 'वृक्षारोपण',
            ],
            'legacy' => [
                'उज्जैन>जीवन शैली',
                'उज्जैन>शहर की हस्तियाँ',
                'उज्जैन>शहर की हलचल>राहगीरी',
                'उज्जैन>सरोकार',
                'उज्जैन>जैव विविधता',
            ],
        ],
        'शिक्षा एवं ज्ञान' => [
            'slug' => 'education-knowledge',
            'keywords' => [
                'शिक्षा', 'अकादमिक', 'कॉलेज', 'विश्वविद्यालय', 'विक्रम विश्वविद्यालय', 'स्कूल', 'छात्र',
                'विद्यार्थी', 'शिक्षक', 'प्रोफेसर', 'विज्ञान', 'गणित', 'विद्युत्-शास्त्र', 'शोध',
                'संहिता', 'वेद', 'ज्ञान', 'अध्ययन', 'पुस्तकालय', 'प्रतियोगिता', 'वैज्ञानिक',
                'आविष्कार', 'अगस्त्य', 'बैटरी', 'तत्वज्ञान', 'खगोल',
            ],
            'legacy' => [
                'उज्जैन>अकादमिक गतिविधियाँ',
            ],
        ],
        'शहर एवं स्थानीय हलचल' => [
            'slug' => 'city-local',
            'keywords' => [
                'उज्जैन', 'फ्रीगंज', 'नानाखेड़ा', 'कोठी', 'टावर चौराहा', 'माधव नगर', 'चिमनगंज',
                'नगर निगम', 'स्मार्ट सिटी', 'कलेक्टर', 'प्रशासन', 'सड़क', 'पुल', 'यातायात',
                'रेलवे', 'स्टेशन', 'महापौर', 'वार्ड', 'कॉलोनी', 'पार्क', 'सफाई', 'अतिक्रमण',
                'शहर', 'चौराहा', 'बिजली', 'रोड', 'मार्ग', 'देवास', 'इंदौर रोड', 'आगर रोड',
                'नागदा', 'तराना', 'महिदपुर', 'खाचरौद', 'बड़नगर',
            ],
            'legacy' => [
                'उज्जैन>शहर की हलचल',
                'उज्जैन>आपकी आवाज',
                'उज्जैन>प्रमुख दूरभाष क्रमांक',
                'Civic',
            ],
        ],
        'व्यापार एवं उद्योग' => [
            'slug' => 'business-market',
            'keywords' => [
                'सराफा', 'मंडी', 'सोना', 'चांदी', 'भाव', 'दाम', 'बाजार', 'व्यापारी', 'उद्योग',
                'जीएसटी', 'कारोबार', 'शेयर', 'बैंक', 'बजट', 'व्यापार', 'कृषि उपज', 'गेहूं',
                'चना', 'सोयाबीन', 'दुकान', 'startup', 'startups', 'business', 'market',
                'retail', 'finance', 'commercial', 'store', 'bakery', 'cafe',
            ],
            'legacy' => [
                'Business',
            ],
        ],
        'अपराध एवं सुरक्षा' => [
            'slug' => 'crime-safety',
            'keywords' => [
                'पुलिस', 'गिरफ्तार', 'हादसा', 'दुर्घटना', 'थाना', 'अपराध', 'चोरी', 'मामला दर्ज',
                'आरोपी', 'वारदात', 'धोखाधड़ी', 'मृत्यु', 'मौत', 'जेल', 'कोर्ट', 'अदालत', 'FIR',
                'हत्या', 'लाश', 'मुकदमा', 'सड़क हादसे', 'तस्करी', 'गांजा', 'शराब', 'सुरक्षा',
            ],
        ],
        'राजनीति एवं शासन' => [
            'slug' => 'politics-governance',
            'keywords' => [
                'भाजपा', 'बीजेपी', 'कांग्रेस', 'चुनाव', 'विधायक', 'सांसद', 'मुख्यमंत्री', 'मंत्री',
                'पार्टी', 'प्रत्याशी', 'मतदान', 'सरकार', 'विपक्ष', 'संसद', 'विधानसभा', 'नेता',
                'अध्यक्ष', 'कार्यकर्ता', 'रैली', 'सभागार', 'संबोधित', 'मतगणना',
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
                'गोल्ड मेडल', 'फुटबॉल', 'कुश्ती', 'दंगल', 'हॉकी', 'cricket', 'tournament',
                'मैराथन', 'दौड़', 'खिलाड़ियों',
            ],
            'legacy' => [
                'Sports',
            ],
        ],
    ];

    /**
     * Cache and return category ID map for defined categories.
     *
     * @var array<string, int>|null
     */
    protected ?array $categoryMapCache = null;

    /**
     * Get or create category IDs map by slug.
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
     * Score and determine best matching categories for an article.
     *
     * @return array<int> Matched category IDs
     */
    public function determineCategoryIds(Article $article): array
    {
        $categoryMap = $this->getCategoryMap();
        $title = mb_strtolower($article->title);
        $dek = mb_strtolower($article->dek ?? '');
        $body = mb_strtolower(strip_tags($article->body ?? ''));
        $existingCatNames = $article->categories->pluck('name')->all();

        $scores = [];

        foreach ($this->rules as $catName => $config) {
            $catId = $categoryMap[$config['slug']] ?? null;
            if (! $catId) {
                continue;
            }

            $score = 0;

            // 1. Legacy category mappings carry heavy weight
            if (isset($config['legacy'])) {
                foreach ($config['legacy'] as $legacyName) {
                    if (in_array($legacyName, $existingCatNames, true)) {
                        $score += 25;
                        break;
                    }
                }
            }

            // 2. Keyword occurrences with contextual weighting
            $bodyHits = 0;
            foreach ($config['keywords'] as $keyword) {
                $kw = mb_strtolower($keyword);

                if (mb_stripos($title, $kw) !== false) {
                    $score += 15;
                }
                if ($dek !== '' && mb_stripos($dek, $kw) !== false) {
                    $score += 8;
                }
                if ($body !== '' && mb_stripos($body, $kw) !== false) {
                    $bodyHits++;
                    if ($bodyHits <= 5) {
                        $score += 2;
                    }
                }
            }

            if ($score > 0) {
                $scores[$catId] = $score;
            }
        }

        if (empty($scores)) {
            // Fallback default category
            $defaultCatId = $categoryMap['city-local'] ?? null;

            return $defaultCatId ? [$defaultCatId] : [];
        }

        arsort($scores);

        $selectedIds = [];
        $topScore = reset($scores);

        foreach ($scores as $catId => $score) {
            // Always include highest scoring category
            if (empty($selectedIds)) {
                $selectedIds[] = $catId;

                continue;
            }

            // Include secondary category if it has strong signal (score >= 6 and >= 25% of top score)
            // Limit to at most 2 top categories for clarity and focus
            if (count($selectedIds) < 2 && $score >= 6 && $score >= ($topScore * 0.25)) {
                $selectedIds[] = $catId;
            }
        }

        return $selectedIds;
    }

    /**
     * Categorize a single Article.
     *
     * @return array<int> Matched category IDs
     */
    public function categorize(Article $article, bool $sync = true): array
    {
        $categoryIds = $this->determineCategoryIds($article);

        // Detach generic 'मुखपृष्ठ' if attached
        $mukhpreshth = Category::where('slug', 'makhapashhatha')->orWhere('name', 'मुखपृष्ठ')->first();
        if ($mukhpreshth) {
            $article->categories()->detach($mukhpreshth->id);
        }

        if (! empty($categoryIds)) {
            if ($sync) {
                $article->categories()->sync($categoryIds);
            } else {
                $article->categories()->syncWithoutDetaching($categoryIds);
            }
        }

        return $categoryIds;
    }

    /**
     * Categorize all articles in high-performance batches.
     *
     * @return array{total_processed: int, categorized: int, categories_assigned: int}
     */
    public function categorizeAll(bool $force = true, bool $sync = true): array
    {
        $mukhpreshth = Category::where('slug', 'makhapashhatha')->orWhere('name', 'मुखपृष्ठ')->first();

        $query = Article::query()->with('categories:id,name');

        if (! $force) {
            // Only articles with no categories or only having 'मुखपृष्ठ'
            $query->where(function ($q) use ($mukhpreshth) {
                $q->doesntHave('categories');
                if ($mukhpreshth) {
                    $q->orWhereHas('categories', function ($catQ) use ($mukhpreshth) {
                        $catQ->where('categories.id', $mukhpreshth->id);
                    });
                }
            });
        }

        $totalProcessed = 0;
        $categorizedCount = 0;
        $categoriesAssigned = 0;

        $query->chunk(100, function (Collection $articles) use ($mukhpreshth, $sync, &$totalProcessed, &$categorizedCount, &$categoriesAssigned) {
            foreach ($articles as $article) {
                $totalProcessed++;
                $matchedCatIds = $this->determineCategoryIds($article);

                // Detach 'मुखपृष्ठ'
                if ($mukhpreshth && $article->categories->contains('id', $mukhpreshth->id)) {
                    $article->categories()->detach($mukhpreshth->id);
                }

                // Sync matched categories
                if (! empty($matchedCatIds)) {
                    if ($sync) {
                        $article->categories()->sync($matchedCatIds);
                    } else {
                        $article->categories()->syncWithoutDetaching($matchedCatIds);
                    }
                    $categorizedCount++;
                    $categoriesAssigned += count($matchedCatIds);
                }
            }
        });

        return [
            'total_processed' => $totalProcessed,
            'categorized' => $categorizedCount,
            'categories_assigned' => $categoriesAssigned,
        ];
    }
}
