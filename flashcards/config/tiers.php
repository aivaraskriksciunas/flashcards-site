<?php 

return [

    /**
     *  Store limitations for each type of tier defined in the system.
     */

    /**
     * Default tier - if user does not have a tier chosen or if their tier does not exist.
     * Will be default tier for all free accounts.
     */

    'default' => [
        
        'decks' => [ 
            'count' => 15, // Max deck count
            'cards' => 200, // Max card count
            'images' => false, // Can upload images?
        ],

        'organizations' => [
            'users' => 10,
        ],

        'courses' => [
            'count' => 1,
            'topics' => 10,
            'items' => 20,
        ],

        'forum' => [
            'posts' => 3,
            'comments' => 15,
        ]
    ]

];