<?php namespace Modules\Supplier\Http\Controllers;

use Pingpong\Modules\Routing\Controller;
use Feeds;

class FeedController extends Controller {
	
	public function getFeed()
	{
		$feed = Feeds::make('https://news.google.com/news?cf=all&hl=en&pz=1&ned=us&q=Heizungen&output=rss');

		$items = $feed->get_items();

		$return = [];

		foreach ( $items as $item ) {
			$return[] = $item->get_description();
		}

		print_r($return);
	}
	
}