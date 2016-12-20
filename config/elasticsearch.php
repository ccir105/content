<?php 
	return [
		
		'hosts' => array
		 			(
                    	'localhost:9200'
                    ),
    	'logPath' => 'var/log/elasticsearch',

    	'schema' => [
    		'body' => [
    			'settings' => [
    				'number_of_shards' => 5,
		            'number_of_replicas' => 1
    			]
    		]
    	],

    	'tables' => [
    		'project' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => [
                    'updated_at' => [
                    	"type" => "date",
		    			"format" => "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd",
                    ],
                    'created_at' => [
                    	"type" => "date",
		    			"format" => "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd",
                    ],
                ]
            ],
            
            'threads' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => [
                    'updated_at' => [
                    	"type" => "date",
		    			"format" => "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd",
                    ],
                    'created_at' => [
                    	"type" => "date",
		    			"format" => "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd",
                    ],
                ]
            ]
	    ]
	];