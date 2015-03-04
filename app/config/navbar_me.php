<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',
 
    // Here comes the menu structure
    'items' => [

        // This is a menu item
        'Hem'  => [
            'text'  => 'Hem',   
           'url'   => '',  
            'title' => 'Forumet'
        ],
 
     
	
		 // This is a menu item
        'Questions'  => [
            'text'  => 'Frågor',   
            'url'   => 'Questions/list',  
            'title' => 'Frågesidan',
			'submenu' => [ 

				'items' => [ 

					// This is a submenu item 
                    'askquestion'  => [ 
                        'text'  => 'Ställ en fråga',    
                        'url'   => 'Questions/add',   
                        'title' => 'Ny Fråga' 
                    ],
				
					
				],			
			],
		],
		
		'Users' => [
			'text' => 'Användare', 
            'url'   =>'Users/list',  
            'title' => 'Användare',
			
			'submenu' => [ 

				'items' => [ 

					// This is a submenu item 
                    'listactive'  => [ 
                        'text'  => 'Medlemmar',    
                        'url'   => 'Users/active',   
                        'title' => 'Medlemmar' 
                    ],
					
					// This is a submenu item 
                    'ny'  => [ 
                        'text'  => 'Registrera dig',    
                        'url'   => 'Users/add',   
                        'title' => 'Nytt konto' 
                    ],	
					
					// This is a submenu item 
                    'login'  => [ 
                        'text'  => 'Logga in',    
                        'url'   => 'Users/login',   
                        'title' => 'Inloggning' 
                    ],	
					
					
				],			
			],
        ],


		
				
 
        // This is a menu item
        'tags' => [
            'text'  =>'Taggar', 
            'url'   =>'Tags/list',  
            'title' => 'Taggar'
        ],
		


        'about'  => [ 
            'text'  => 'About',    
            'url'   => 'about',    
            'title' => 'Om ', 

			],

		
    ],
	
		
 
    // Callback tracing the current selected menu item base on scriptname
    'callback' => function($url) {
        if ($url == $this->di->get('request')->getRoute()) {
            return true;
        }
    },

    // Callback to create the urls
    'create_url' => function($url) {
        return $this->di->get('url')->create($url);
    },
];