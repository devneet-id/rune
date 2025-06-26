<?php

use Rune\Chanter\Manifest as Chanter;
use Rune\Weaver\Manifest as Weaver;

// familiar
Chanter::cast('familiar', function() {
  (aether_has_entity('whisper')) ?: die(PHP_EOL.'[!]WARNING: Required Whisper:entity'.PHP_EOL);
  (aether_has_entity('keeper')) ?: die(PHP_EOL.'[!]WARNING: Required Keeper:entity'.PHP_EOL);

  $header = Weaver::load(__DIR__ . '/weaver/familiar-header.txt');
  $header = Weaver::bindAll($header, [
    'AETHER-FILE'=> AETHER_FILE,
  ]);
  
  if (aether_has_entity('whisper')) {
    whisper_clear();
    whisper_nl($header);
  }else {
    print($header);
  }


  if (chanter_option('ask')) {
    whisper_nl("{{COLOR-INFO}}{{ICON-INFO}}{{LABEL-INFO}}You will ask the familiar.");
    $ask = whisper_input('Ask anything: ');
    if ($ask) {
      minister_ask([$ask]);
      whisper_nl("Answer: " . minister_say());
    }
  }


  if (chanter_option('spirit')) {
    whisper_nl("{{COLOR-INFO}}{{ICON-INFO}}{{LABEL-INFO}}You will insert Ai spirit to be your Minister as your familiar.");
    $ask = whisper_input('Rewrite the last spirit? [y/n]: ');
    if ($ask == 'y') {
      $url = whisper_input('AI api endpoint: ');
      $token = whisper_input('AI api token: ');
  
      keeper_set('familiar.json', json_encode([
        'url'=> $url,
        'token'=> $token
      ]));
      whisper_nl("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} You insert spirit to Minister as your familiar.");
    }else {
      whisper_nl("{{COLOR-SUCCESS}}{{ICON-SUCCESS}} You used spirit from last time.");
    }
  }

});