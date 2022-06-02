<?php

/**
 * @file
 * Provide views data for nodeCounter.module.
 */

/**
 * Implements hook_views_data().
 */
function nodeCounter_views_data() {
  $data['node_counter']['table']['group'] = t('Content nodeCounter');

  $data['node_counter']['table']['join'] = [
    'node_field_data' => [
      'left_field' => 'nid',
      'field' => 'nid',
    ],
  ];

  $data['node_counter']['totalcount'] = [
    'title' => t('Total views'),
    'help' => t('The total number of times the node has been viewed.'),
    'field' => [
      'id' => 'nodeCounter_numeric',
      'click sortable' => TRUE,
     ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['node_counter']['daycount'] = [
    'title' => t('Views today'),
    'help' => t('The total number of times the node has been viewed today.'),
    'field' => [
      'id' => 'nodeCounter_numeric',
      'click sortable' => TRUE,
     ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['node_counter']['timestamp'] = [
    'title' => t('Most recent view'),
    'help' => t('The most recent time the node has been viewed.'),
    'field' => [
      'id' => 'node_counter_timestamp',
      'click sortable' => TRUE,
    ],
    'filter' => [
      'id' => 'date',
    ],
    'argument' => [
      'id' => 'date',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  return $data;
}
