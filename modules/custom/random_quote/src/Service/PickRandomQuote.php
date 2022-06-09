<?php
/**
* @file providing the service that say hello world and hello 'given name'.
*
*/

namespace Drupal\random_quote\Service;
use Drupal\Component\Serialization\Json;
use Drupal\random_quote\Service\PickRandomQuoteInterface;


class PickRandomQuote {
	private $quoteService;
	function __construct(Quote $quoteService) {
	  $this->quoteService = $quoteService;
	}
	
  public function getQuote() {
 	return $this->quoteService->getQuote();
  }
}