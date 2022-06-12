<?php

namespace Drupal\random_quote\Service;

interface QuoteInterface {

    public function getContentResponse();

    public function getBodyName();
}