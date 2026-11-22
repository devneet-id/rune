<?php

/*
 * ENTITY
 * Represents functions related to this domain.
 */

#NOTE: Main entity
function minister() {
  return true;
}

#NOTE: Reads the familiar (spirit) kept by Keeper in .echoes/<runite>/familiar.json as {"url": "", "token": ""}
function minister_familiar() {
  if (!function_exists('keeper_item_get') || !defined('KEEPER_ECHOES')) {
    return false;
  }
  if (!file_exists(KEEPER_ECHOES . '/familiar.json')) {
    return false;
  }

  $spirit = keeper_item_get('familiar');
  $return = (is_object($spirit) && !empty($spirit->url)) ? $spirit : false;

  aether_arcane('Minister.entity.minister_familiar');
  return $return;
}

#NOTE: Sends a JSON request to the familiar and returns the raw response body
function minister_http( String $url, Array $headers, Array $body ) {
  $context = stream_context_create([
    'http' => [
      'method' => 'POST',
      'header' => implode("\r\n", $headers),
      'content' => json_encode($body),
      'ignore_errors' => true,
      'timeout' => 60,
    ],
  ]);
  $return = @file_get_contents($url, false, $context);

  aether_arcane('Minister.entity.minister_http');
  return $return;
}

#NOTE: Asks the familiar (AI spirit) and keeps its answer
function minister_ask( Array $texts ) {
  global $MINISTER_RESPONSE;

  $spirit = minister_familiar();
  if (!$spirit) {
    whisper_echo("{{COLOR-WARNING}}{{ICON-WARNING}} No spirit in your familiar to ask (engrave Keeper and fill familiar.json). {{nl}}");
    aether_arcane('Minister.entity.minister_ask');
    return false;
  }

  $texts[] = "TASK: ANSWER AS LESS AND TO THE POINT";
  $response = minister_http($spirit->url, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . ($spirit->token ?? ''),
  ], [
    'model' => $spirit->model ?? 'gemini',
    'text' => implode(PHP_EOL, $texts),
  ]);

  $answer = ($response !== false) ? (json_decode($response)->data->response ?? false) : false;
  if ($answer !== false) {
    $MINISTER_RESPONSE[] = $answer;
  }

  aether_arcane('Minister.entity.minister_ask');
  return $answer;
}

#NOTE: Returns every answer collected from the familiar
function minister_say() {
  global $MINISTER_RESPONSE;

  aether_arcane('Minister.entity.minister_say');
  return implode(PHP_EOL, $MINISTER_RESPONSE);
}