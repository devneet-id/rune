<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Minister;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = '0.3';
  
  public $main = 'Minister';

  public $user = 'Anwar Achilles';

  public $note = 'Speaks with a familiar (an AI spirit) on behalf of the runite—asks questions, gathers the answers, and brings them back into the flow.';

  public $mark = 'DEVELOPMENT';

  public $link = [
    ['Aether', 'entity', '1.17'],
    ['Keeper', 'ether:entity', '1.12'],
    ['Whisper', 'entity', '1.13'],
  ];

  public $node = [
    [
      'type' => 'ether',
      'call' => 'MINISTER',
      'note' => 'Flag to indicate the Minister system is active.',
    ],
    [
      'type' => 'essence',
      'call' => 'MINISTER_RESPONSE',
      'note' => 'Answers collected from the familiar.',
    ],
    [
      'type' => 'entity',
      'call' => 'minister()',
      'note' => 'Main entity',
    ],
    [
      'type' => 'entity',
      'call' => 'minister_familiar()',
      'note' => 'Reads the familiar (spirit) kept by Keeper in .echoes/<runite>/familiar.json as {"url": "", "token": ""}',
    ],
    [
      'type' => 'entity',
      'call' => 'minister_http( String $url, Array $headers, Array $body )',
      'note' => 'Sends a JSON request to the familiar and returns the raw response body',
    ],
    [
      'type' => 'entity',
      'call' => 'minister_ask( Array $texts )',
      'note' => 'Asks the familiar (AI spirit) and keeps its answer',
    ],
    [
      'type' => 'entity',
      'call' => 'minister_say()',
      'note' => 'Returns every answer collected from the familiar',
    ],
    [
      'type' => 'manifest',
      'call' => 'ask( Array|String $texts )',
      'note' => 'Asks the familiar (AI spirit) and returns its answer',
    ],
    [
      'type' => 'manifest',
      'call' => 'say()',
      'note' => 'Returns every answer collected from the familiar',
    ],
  ];

  public function awakening() {}
  
}