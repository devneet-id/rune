<?php

/*
 * PHANTASM
 * Represents the documentation class for this domain.
 */

namespace Rune\Crafter;

class Phantasm extends \Rune\Phantasm {

  public $origin = __DIR__;

  public $version = '1.11';
  
  public $main = 'Crafter';

  public $user = 'Anwar Achilles';

  public $note = 'Focused on dynamic creation and composition—designed to build, shape, and assemble various data structures or logic units through modular, seed-based, and spark-driven processes.';

  public $link = [
    ['Aether', 'ether', '1.16'],
    ['Weaver', 'ether:essence', '1.6'],
    ['Cipher', 'entity', '1.5'],
    ['Forger', 'entity', '1.13'],
    ['Keeper', 'ether:essence', '1.10'],
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
      'note' => 'Main entity.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_reset()',
      'note' => 'Reset all Crafter globals to initial states using constants or empty values.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_seed_set( String $name, Mixed $value )',
      'note' => 'Store a value into the global CRAFTER_SEED array using the given name as key.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_seed_get( String $name )',
      'note' => 'Get a value from the global CRAFTER_SEED array using the given name as key.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_item_set( String $name, ?Callable $callable )',
      'note' => 'Register a callable into the global CRAFTER_ITEM array using the given name as key.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_item_get( String $name )',
      'note' => 'Execute a registered crafter item callable by name and update CRAFTER_SPARK with current state.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_shard_set( String $file_path, ?Callable $injection = NULL )',
      'note' => 'Register a shard file with optional injection, and store its info and path in global shard arrays.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_shard_get( String $file_path, ?Callable $injection = NULL )',
      'note' => 'Retrieve a shard file info, registering it first if not already listed.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark( String $name, ?Callable $injection = NULL )',
      'note' => 'Run full crafting pipeline — validate readiness, process shards, apply injection, and finalize crafting result.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_message()',
      'note' => 'Display crafting summary with item name, output path, file size, and total shards used.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_clustering()',
      'note' => 'Inject and group each shard’s content into the appropriate cluster based on name or file type.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_cleaning()',
      'note' => 'Clean each cluster by removing unwanted patterns using language-specific cleaning rules.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_bundling()',
      'note' => 'Bundle cleaned shard clusters and metadata into a final distributable template.',
    ],
    [
      'type' => 'entity',
      'call' => 'crafter_spark_distributing()',
      'note' => 'Write the bundled template to the distribution path defined in crafter seed.',
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