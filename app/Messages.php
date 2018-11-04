<?php

namespace App;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Elasticquent\ElasticquentTrait;

class Messages extends Model
{
	use SoftDeletes;
    use ElasticquentTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['message', 'user', 'channel', 'ts'];

    protected $indexSettings = [
        "analysis" => [
            "char_filter" => [
                "strip_special_char_filter" => [
                  "type" => "pattern_replace",
                  "pattern" => "[^ \w]+",
                  "replacement" => ""
                ],
            ],
            "filter" => [
                "stopwords_filter" => [
                  "type" => "stop",
                  "stopwords" => ["_english_"]
                ],
            ],
            "analyzer" => [
                "xnohat_analyzer" => [
                    "type" => "custom",
                    "char_filter" => [
                        "html_strip",
                    ],
                    "tokenizer" => "standard",
                    "filter" => [
                        "lowercase",
                        "stopwords_filter",
                    ],
                ],
                "xnohat_parsed_analyzer" => [
                    "type" => "custom",
                    "char_filter" => [
                        "html_strip",
                    ],
                    "tokenizer" => "keyword",
                    "filter" => [
                        "lowercase",
                        "stopwords_filter",
                    ],
                ],
            ],
            "tokenizer" => [
                "xnohat_tokenizer" => [
                    "type" => "standard",
                    "max_token_length" => 900,
                ],
            ],
        ],
    ];

    protected $mappingProperties = [
        "message" => [
            "type" => "string",
            "analyzer" => "xnohat_analyzer",
            "fields" => [
                "parsed" => [
                    "type" => "string",
                    "analyzer" => "xnohat_parsed_analyzer",
                ],
            ],
        ],
        "user" => [
            "type" => "string",
            "analyzer" => "xnohat_analyzer",
            "fields" => [
                "parsed" => [
                    "type" => "string",
                    "analyzer" => "xnohat_parsed_analyzer",
                ],
            ],
        ],
        "channel" => [
            "type" => "string",
            "analyzer" => "xnohat_analyzer",
            "fields" => [
                "parsed" => [
                    "type" => "string",
                    "analyzer" => "xnohat_parsed_analyzer",
                ],
            ],
        ],
        "ts" => [
            "type" => "string",
            "analyzer" => "xnohat_analyzer",
            "fields" => [
                "parsed" => [
                    "type" => "string",
                    "analyzer" => "xnohat_parsed_analyzer",
                ],
            ],
        ],
    ];


	public function ESsearchMessages($searchterm, $channel){

        $page = request()->has('page') ? request()->query('page') : 1; //get current page for pagination cache

        $searchterm = htmlspecialchars(strip_tags(urldecode($searchterm)));

        //Log::info('Search term: '.$searchterm);
        //Log::info('Current From: '.($page > 1) ? ($page-1) * config('sodiz.num_posts_per_page', 10) : 0);
        //$searchterm = "Tin tức"; //DEBUG
        $searchresults = Messages::complexSearch([
            'body' => [
                'min_score' => 0.1,
                'query' => [
                    "bool" => [
                      "should" => [
                        [ "match" => [ "message" =>  "$searchterm" ]],
                      ],
                      "filter" => [
                        [ "match" => [ "channel" => "$channel" ]],
                      ]
                    ],
                ],
                "highlight" => [
                    "pre_tags" => ["<span class='searchhighlight'>"],
                    "post_tags" => ["</span>"],
                    "fields" => [
                        "message" => ["force_source" => true, "number_of_fragments" => 0],
                    ],
                ],
                'sort' => [
                    [
                        '_score' => [
                            'order' => 'asc'
                        ]
                    ]
                ],
                "from" => ($page > 1) ? ($page-1) * config('slackarchive.num_posts_per_page', 10) : 0,
                "size" => config('slackarchive.num_posts_per_page', 10),
            ]
        ])->paginate(config('slackarchive.num_posts_per_page'));


        //var_dump($searchresults);die();

        return $searchresults;
    }


    public function sender(){
        return $this->hasOne('App\User','userid','user');
    }

    public function getMessages($channelid){
        $page = request()->has('page') ? request()->query('page') : 1; //get current page for pagination cache
        
        return Cache::tags(['messages_cache'])->remember('app_messages_'.$channelid.'_order_by_ts_page_'.$page, config('slackarchive.query_cache.timeout_short'), function()use($channelid){
                return Messages::with('sender')->where('channel',$channelid)
                        ->orderBy('ts','asc')
                        ->paginate(config('slackarchive.num_posts_per_page'));

        });
    }


}
