<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 *
 * Example:
 * function starter() {}
 */



function minister_ask( Array $texts ) {
  global $AETHER_FAMILIAR;
  global $MINISTER_RESPONSE;

  if ($AETHER_FAMILIAR) {
    $spirit = json_decode(keeper_get('familiar.json'));
    $texts[] = "TASK: ANSWER AS LESS AND TO THE POINT";
    $text = implode(PHP_EOL, $texts);
    $response = whisper_http($spirit->url, 'POST', [
      'Content-Type: application/json',
      'Authorization: Bearer ' . $spirit->token
    ], [
      'model'=> 'gemini',
      'text'=> $text
    ]);
    $answer = json_decode($response['body'])->data->response;
    $MINISTER_RESPONSE[] = $answer;
  }else {
    keeper_log("No spirit in your familiar to ask.");
  }
}

function minister_say() {
  global $MINISTER_RESPONSE;
  return implode(PHP_EOL, $MINISTER_RESPONSE);
}