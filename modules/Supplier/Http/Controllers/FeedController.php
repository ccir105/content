<?php namespace Modules\Supplier\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Feeds;

class FeedController extends Controller {

	public $keywords = [
		
		'energy' => [
			'Energie',
			'Heizöl',
			'Diesel',
			'Holzpellets',
		],
		
		'heat' => [
			'Heizungen',
			'Ölheizung',
			'Wärmepumpe',
			'Pelletsheizung',
			'Gasheizung',
			'Kamin'
		],

		'tank' => [
			'Tankreinigung',
			'Heizöl',
			'Diesel',
			'Holzpellets'
		]
	];
	
	public function getFeed()
	{
		$allNews = [];

		foreach ( $this->keywords as $keywords ) {
			$keyword =  $keywords[ array_rand( $keywords ) ];
		
			$feed = Feeds::make('https://news.google.com/news/section?q=' . $keyword. '&edchanged=1&ned=de_ch&authuser=0&output=rss');
		
			$items = $feed->get_items();
		
			$item = $items[ array_rand( $items ) ];
		
			$description = $item->get_description();
	    
	        $crawler = new Crawler($description);
	    
	        $firstTd = $crawler->filter('tr > td');
	    
	        $secondTd = $crawler->filter('tr > td + td');
		
			
			$news = [
				'title' => $item->get_title(),
				'description' => '',
				'link' => '',
				'image' => ''
			];

			if( $firstTd->html() != "" ){
				$news['link'] = $firstTd->filter('a')->attr('href');
				$news['image'] = $firstTd->filter('img')->attr('src');		
			}else{
				$news['link'] = $secondTd->filter('a')->first()->attr('href');
			}


			$descriptionHolder = $secondTd->filter('.lh');

			$descriptionHolder->filter('a')->each(function($nodes){
				foreach ($nodes as $node) {
					$node->parentNode->removeChild($node);
				}
			});

			$news['description'] = strip_tags($descriptionHolder->html());

			$allNews[] = $news;
		}

		return $allNews;
	}
	
}