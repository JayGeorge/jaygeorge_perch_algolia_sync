<?php

class jaygeorge_AlgoliaSync_Things extends PerchAPI_Factory
{
    protected $table     = 'jaygeorge_perch_algolia_sync_settings';
	protected $pk        = 'algoliaID';
	protected $singular_classname = 'jaygeorge_AlgoliaSync_Thing';
	
	protected $default_sort_column = 'algoliaDateTime';
	
	public $static_fields   = array('algoliaID', 'algoliaTitle', 'algoliaDateTime', 'algoliaDynamicFields');	
	
}