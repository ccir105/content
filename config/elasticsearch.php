<?php 
	return [
		'hosts' => array
		 			(
                    	'localhost:9200'
                    ),
    	'logPath' => 'var/log/elasticsearch',
    	'databases' => [
	    	[
	    		'index' => 'advice',
	    		'body' => 
	    		[
	    			'settings' => [
			            'number_of_shards' => 5,
			            'number_of_replicas' => 1
			        ],
			        'mappings' => [
			            'advice' => [
			                '_source' => [
			                    'enabled' => true
			                ],
			                'properties' => [
			                    'content' => [
			                        'type' => 'string',
			                        'analyzer' => 'standard'
			                    ],
			                   
			                    'updated_at' => [
			                    	"type" => "date",
					    			"format" => "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd",
			                    ],
			                    'created_at' => [
			                    	"type" => "date",
					    			"format" => "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd",
			                    ],
			                    'priority' => [
			                    	"type" => "integer"
			                    ]
			                ]
			            ]
			        ]
    			]
    		]
    	]
	];