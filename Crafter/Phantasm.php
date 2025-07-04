<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Crafter;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = '1.7';
  
  public $main = 'Crafter';

  public $user = 'Anwar Achilles';

  public $note = 'Focused on dynamic creation and composition—designed to build, shape, and assemble various data structures or logic units through modular, seed-based, and spark-driven processes.';

  public $link = [
    ['Aether', 'ether', '1.15'],
    ['Weaver', 'essence', '1.5'],
    ['Cipher', 'entity', '1.5'],
    ['Forger', 'entity', '1.9'],
    ['Keeper', 'ether:essence', '1.8'],
  ];

  public $node = [
    [
      'type' => 'ether',
      'call' => 'CRAFTER',
      'note' => 'Flag to enable or indicate the Crafter system is active.',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_WEAVER',
      'note' => 'Defines available weaver templates for different language sets and output types (plain, class, function), used during bundling.',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_VARIABLE',
      'note' => 'Maps cluster types to variable placeholders used during template binding.',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_CLEANING',
      'note' => 'Defines cleaning rules for each language type to remove unwanted wrappers or tags before bundling.',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_RESET_SEED',
      'note' => 'Default seed configuration used when resetting the Crafter state.',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_RESET_SPARK',
      'note' => 'Default empty structure for CRAFTER_SPARK used during reset.',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_RESET_SPARK_STATE',
      'note' => 'Default spark state, marking the crafter as not ready after reset.',
    ],
    [
      'type' => 'ether',
      'call' => 'CRAFTER_RESET_SPARK_CLUSTER',
      'note' => 'Default empty cluster groups for each language type after reset.',
    ],
    [
      'type' => 'essence',
      'call' => 'CRAFTER',
      'note' => 'Main flag to indicate that the Crafter system is active.',
    ],
    [
      'type' => 'essence',
      'call' => 'CRAFTER_ITEM',
      'note' => 'Stores registered crafter items as callable functions.',
    ],
    [
      'type' => 'essence',
      'call' => 'CRAFTER_SEED',
      'note' => 'Stores registered crafter items as callable functions.',
    ],
    [
      'type' => 'essence',
      'call' => 'CRAFTER_SHARD',
      'note' => 'Holds processed shard info from files.',
    ],
    [
      'type' => 'essence',
      'call' => 'CRAFTER_SHARD_LIST',
      'note' => 'List of shard file paths to be processed.',
    ],
    [
      'type' => 'essence',
      'call' => 'CRAFTER_SHARD_INJECTION',
      'note' => 'Injection callbacks associated with shard file paths.',
    ],
    [
      'type' => 'essence',
      'call' => 'CRAFTER_SPARK',
      'note' => 'Stores crafting metadata including selected item, seed data, and loaded shards.',
    ],
    [
      'type' => 'essence',
      'call' => 'CRAFTER_SPARK_STATE',
      'note' => 'State flags for the crafting process, such as readiness.',
    ],
    [
      'type' => 'essence',
      'call' => 'CRAFTER_SPARK_CLUSTER',
      'note' => 'Clustered content grouped by type, used during bundling (e.g., html, css, js, etc).',
    ],
    [
      'type' => 'essence',
      'call' => 'CRAFTER_SPARK_DISTRIBUTE',
      'note' => 'Final crafted content ready to be written to output.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter()',
      'note' => '',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_reset()',
      'note' => 'Resets all Crafter-related globals to their initial states using reset constants or empty values.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_seed_set( String $name, Mixed $value )',
      'note' => 'Sets a value in the global CRAFTER_SEED array under the given name.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_seed_get( String $name )',
      'note' => 'Retrieves a value from the global CRAFTER_SEED array by name.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_item_set( String $name, ?Callable $callable )',
      'note' => 'Registers a callable into the global CRAFTER_ITEM array under the given name.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_item_get( String $name )',
      'note' => 'Executes a registered crafter item callable by name, then updates CRAFTER_SPARK and marks it as ready.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_shard_set( String $file_path, ?Callable $injection = NULL )',
      'note' => 'Registers a shard file along with optional injection, stores its info and path in global crafter shard arrays.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_shard_get( String $file_path, ?Callable $injection = NULL )',
      'note' => 'Retrieves a shard file info from global crafter shard arrays.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark( String $name, ?Callable $injection = NULL )',
      'note' => 'Executes full crafting process flow — ensures spark readiness, runs core processing steps, optional injection, and finalizes with keeper shard setup.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_message()',
      'note' => 'Displays crafting result summary including item name, output path, file size, and total shard used.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_clustering()',
      'note' => 'Processes each shard by injecting and clustering its content into the appropriate group based on file type or name.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_cleaning()',
      'note' => 'Cleans clustered shard contents by removing unwanted strings based on language-specific rules.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_bundling()',
      'note' => 'Binds cleaned shard clusters and metadata into a final distributable template using selected weaver and encryption.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_distributing()',
      'note' => 'Writes the bundled content to the distribution path defined in the crafter seed.',
    ],
    [
      'type' => 'manifest',
      'call' => '_arise()',
      'note' => 'Optional lifecycle method for internal post-arise logic.',
    ],
    [
      'type' => 'manifest',
      'call' => '_aether_end()',
      'note' => 'Special hook for aether-based awakening phase, executed at the end of the crafter process.',
    ],
    [
      'type' => 'manifest',
      'call' => 'end()',
      'note' => 'Final phase of the class lifecycle, called after all manifest components are registered and ready.',
    ],
    [
      'type' => 'manifest',
      'call' => 'item( String $name, ?Callable $callable = NULL )',
      'note' => 'Registers or retrieves a crafter item by name; sets the active name if callable is provided.',
    ],
    [
      'type' => 'manifest',
      'call' => 'seed( String $name, Mixed $value = NULL )',
      'note' => 'Sets or retrieves a value from the crafter seed configuration.',
    ],
    [
      'type' => 'manifest',
      'call' => 'shard( String $source, ?Callable $injection = NULL )',
      'note' => 'Retrieves or registers a shard source with optional injection logic.',
    ],
    [
      'type' => 'manifest',
      'call' => 'spark( Mixed $name_or_callable = NULL, ?Callable $injection = NULL )',
      'note' => 'Executes the full crafting process using item name or callable, then resets the crafter state and shows crafting summary.',
    ],
  ];

  public function awakening() {}
  
}