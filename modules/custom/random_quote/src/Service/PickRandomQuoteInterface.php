<?php

namespace Drupal\random_quote\Service;

interface PickRandomQuoteInterface {
	// define method which other class can override.
	public function getQuote();
}