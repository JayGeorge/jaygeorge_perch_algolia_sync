<?php
    $this->register_app('jaygeorge_perch_algolia_sync', 'Algolia Sync', 1, 'An app that syncs records with Algolia', 1.0);
    $this->require_version('jaygeorge_perch_algolia_sync', '1.0');
    $this->add_setting('jaygeorge_perch_algolia_sync_application_id', 'Algolia Application ID', 'text', false,'','You can find this in your online Algolia account under API Keys > Application ID');
    $this->add_setting('jaygeorge_perch_algolia_sync_application_admin_api_key', 'Algolia Admin API Key', 'text', false,'','The admin API key for your alternative account');
    $this->add_setting('jaygeorge_perch_algolia_sync_application_collection_number', 'Perch Collection ID that will communicate with Algolia', 'text', false,'','This is the unique number of the collection that will be communicating with Algolia. You can find this in the URL of the collection admin page e.g. 1');

    $http_host = getenv('HTTP_HOST');
    if((strpos(getenv('HTTP_HOST'), 'portfolio-') !== false)) {
        $this->add_setting('jaygeorge_perch_algolia_sync_application_id_alt', '(Optional) Alternative Algolia Application ID', 'text', false,'','(Optional) used for connecting to a different account under some circumstances');
        $this->add_setting('jaygeorge_perch_algolia_sync_application_admin_api_key_alt', '(Optional) Alternative Algolia Admin API Key', 'text', false,'','The admin API key for your alternative account');
        $this->add_setting('jaygeorge_perch_algolia_sync_perch_resources_folder_alt', '(Optional) Alternative Resources Folder', 'text', false,'','Fill this in if you are using an alternative Algolia Application ID and also an alternative resources folder e.g. resources_portfolio');
    }
    $this->add_create_page('jaygeorge_perch_algolia_sync', 'edit');

    $API = new PerchAPI(1.0, 'jaygeorge_perch_algolia_sync');

    // Gather some variables from the app. Put them in functions so that they can be accessed from inside other functions.
    /* Algolia Application ID
    =================================================== */
    function get_algolia_application_id() { 
        $API = new PerchAPI(1.0, 'jaygeorge_perch_algolia_sync');
        $algolia_application_id = $API->get('Settings')->get('jaygeorge_perch_algolia_sync_application_id')->val();
        return $algolia_application_id;
    }
    /* Algolia Admin API Key
    =================================================== */
    function get_algolia_application_admin_api_key() { 
        $API = new PerchAPI(1.0, 'jaygeorge_perch_algolia_sync');
        $algolia_application_admin_api_key = $API->get('Settings')->get('jaygeorge_perch_algolia_sync_application_admin_api_key')->val();
        return $algolia_application_admin_api_key;
    }
    /* Algolia Collection ID
    =================================================== */
    function get_algolia_application_collection_number() { 
        $API = new PerchAPI(1.0, 'jaygeorge_perch_algolia_sync');
        $algolia_application_collection_number = $API->get('Settings')->get('jaygeorge_perch_algolia_sync_application_collection_number')->val();
        return $algolia_application_collection_number;
    }


    /* Algolia Application ID (ALT)
    =================================================== */
    function get_algolia_application_id_alt() { 
        $API = new PerchAPI(1.0, 'jaygeorge_perch_algolia_sync');
        $algolia_application_id_alt = $API->get('Settings')->get('jaygeorge_perch_algolia_sync_application_id_alt')->val();
        return $algolia_application_id_alt;
    }
    /* Algolia Application API Key (ALT)
    =================================================== */
    function get_algolia_application_admin_api_key_alt() { 
        $API = new PerchAPI(1.0, 'jaygeorge_perch_algolia_sync');
        $algolia_application_admin_api_key_alt = $API->get('Settings')->get('jaygeorge_perch_algolia_sync_application_admin_api_key_alt')->val();
        return $algolia_application_admin_api_key_alt;
    }
    /* Alternative Perch Resources Folder (ALT)
    =================================================== */
    function get_perch_resources_folder_alt() {
        $API = new PerchAPI(1.0, 'jaygeorge_perch_algolia_sync');
        $perch_resources_folder_alt = $API->get('Settings')->get('jaygeorge_perch_algolia_sync_perch_resources_folder_alt')->val();
        return $perch_resources_folder_alt;
    }


    $API->on('collection.publish_item', function(PerchSystemEvent $Event){

        // Left this here to uncomment for testing
        // var_dump($Event);

        // $itemJSON = $Event->subject->itemJSON();
        // $itemJSON_decoded = json_decode($itemJSON, true);
        // echo '<pre>'; print_r($itemJSON_decoded); echo '</pre>';

        // Gather some variables from Perch
        $collectionID = $Event->subject->CollectionID();
        $algolia_application_id = get_algolia_application_id();
        $algolia_application_id_alt = get_algolia_application_id_alt();
        $algolia_application_collection_number = get_algolia_application_collection_number();
        $algolia_application_admin_api_key = get_algolia_application_admin_api_key();
        $algolia_application_admin_api_key_alt = get_algolia_application_admin_api_key_alt();
        $perch_resources_folder_alt = get_perch_resources_folder_alt();

        // If we're in the chosen collection number
        if($collectionID == $algolia_application_collection_number) {

            /* Load a different configuration for a portfolio or other site */
            $http_host = getenv('HTTP_HOST');
            if((strpos(getenv('HTTP_HOST'), 'portfolio-') !== false)) {
                // Connect to my "Qvoice Portfolio" Algolia account
                require_once 'algoliasearch-client-php-master/algoliasearch.php';
                $client = new \AlgoliaSearch\Client($algolia_application_id_alt, $algolia_application_admin_api_key_alt);

                $index = $client->initIndex('voice_artists');

                // Get some Perch variables
                $itemID = $Event->subject->itemID();
                $itemJSON = $Event->subject->itemJSON();
                $itemJSON_decoded = json_decode($itemJSON, true);

                $index->saveObject(
                    [
                        // Output the name from Perch
                        'name' => $itemJSON_decoded['name'],
                        // Output the image paths from Perch
                        'image' => '/perch/' . $perch_resources_folder_alt . '/' . $itemJSON_decoded['image']['sizes']['w30h45c1']['path'],
                        'image2x' => '/perch/' . $perch_resources_folder_alt . '/' . $itemJSON_decoded['image']['sizes']['w30h45c1@2x']['path'],
                        // Output the item ID from Perch
                        'objectID'  => $itemID
                    ]
                );
            } else {
                // Connect to the main Algolia account
                require_once 'algoliasearch-client-php-master/algoliasearch.php';
                $client = new \AlgoliaSearch\Client($algolia_application_id, $algolia_application_admin_api_key);

                $index = $client->initIndex('voice_artists');

                // Get some Perch variables
                $itemID = $Event->subject->itemID();
                $itemJSON = $Event->subject->itemJSON();
                $itemJSON_decoded = json_decode($itemJSON, true);

                $index->saveObject(
                    [
                        // Output the name from Perch
                        'name' => $itemJSON_decoded['name'],
                        // Output the image paths from Perch
                        'image' => '/perch/resources/' . $itemJSON_decoded['image']['sizes']['w30h45c1']['path'],
                        'image2x' => '/perch/resources/' . $itemJSON_decoded['image']['sizes']['w30h45c1@2x']['path'],
                        // Output the item ID from Perch
                        'objectID'  => $itemID
                    ]
                );
            }
        }
    });

    $API->on('item.delete', function(PerchSystemEvent $Event){

        // Gather some variables from Perch
        $collectionID = $Event->subject->CollectionID();
        $algolia_application_id = get_algolia_application_id();
        $algolia_application_id_alt = get_algolia_application_id_alt();
        $algolia_application_admin_api_key = get_algolia_application_admin_api_key();
        $algolia_application_admin_api_key_alt = get_algolia_application_admin_api_key_alt();

        // For some reason I'm not able to query the collectionID using the delete item event i.e. `if($collectionID == somenumber) {` as it does not trigger anything. However, I have realised it's safe to perform an event on `$Event->subject->itemID();`, since it's unique to the current record, so there's no chance of me accidentally affecting other items or collections.

        /* Load a different configuration for a portfolio or other site */
        $http_host = getenv('HTTP_HOST');
        if((strpos(getenv('HTTP_HOST'), 'portfolio-') !== false)) {
            // Connect to a portfolio Algolia account
            require_once 'algoliasearch-client-php-master/algoliasearch.php';
            $client = new \AlgoliaSearch\Client($algolia_application_id_alt, $algolia_application_admin_api_key_alt);
        } else {
            // Connect to the main Algolia account
            require_once 'algoliasearch-client-php-master/algoliasearch.php';
            $client = new \AlgoliaSearch\Client($algolia_application_id, $algolia_application_admin_api_key);
        }

        $index = $client->initIndex('voice_artists');

        // Get some Perch variables
        $itemID = $Event->subject->itemID();
        $itemJSON = $Event->subject->itemJSON();
        $itemJSON_decoded = json_decode($itemJSON, true);

        $index->deleteObject($itemID);

    });