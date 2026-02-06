<?php

return [

    'tags' => [
        'hand-drawn' => [
            'id' => 1,
            'name' => 'Hand-Drawn',
            'slug' => 'hand-drawn',
            'short_description' => 'Classic hand-drawn animation',
        ],
        'stop-motion' => [
            'id' => 2,
            'name' => 'Stop Motion',
            'slug' => 'stop-motion',
            'short_description' => 'Stop motion animation',
        ],
        'computer-3d' => [
            'id' => 3,
            'name' => 'Computer 3D',
            'slug' => 'computer-3d',
            'short_description' => 'Computer-generated 3D animation',
        ],
        'modern-2d' => [
            'id' => 4,
            'name' => 'Modern 2D',
            'slug' => 'modern-2d',
            'short_description' => 'Modern 2D digital animation',
        ],
    ],

    'movies' => [
        'treasure-planet' => [
            'id' => 1,
            'title' => 'Treasure Planet',
            'slug' => 'treasure-planet',
            'length' => 95,
            'description' => 'A futuristic twist on Robert Louis Stevenson\'s Treasure Island, following young Jim Hawkins on a thrilling journey through outer space to find the legendary treasure of Captain Flint.',
            'poster' => 'images/posters/treasure-planet.jpg',
            'heroimg' => 'images/heroimgs/treasure-planet-bg.jpg',
            'youtube_id' => 'DJNT7C61NrE',
            'released' => '2002-11-27',
            'director' => 'Ron Clements, John Musker',
            'now_upcoming' => 1,
            'is_news' => true,
            'actors' => [
                ['name' => 'Joseph Gordon-Levitt', 'role' => 'Jim Hawkins'],
                ['name' => 'Emma Thompson', 'role' => 'Captain Amelia'],
                ['name' => 'Martin Short', 'role' => 'B.E.N.'],
                ['name' => 'David Hyde Pierce', 'role' => 'Dr. Doppler'],
            ],
            'tags' => ['hand-drawn'],
        ],

        'the-witcher-nightmare-of-the-wolf' => [
            'id' => 2,
            'title' => 'The Witcher: Nightmare of the Wolf',
            'slug' => 'the-witcher-nightmare-of-the-wolf',
            'length' => 83,
            'description' => 'Escaping from poverty to become a witcher, Vesemir slays monsters for coin and glory, but when a new menace rises, he must face the demons of his past.',
            'poster' => 'images/posters/twnotw.jpg',
            'heroimg' => 'images/heroimgs/wolf-heroimg.jpg',
            'youtube_id' => 'J365hQpaWRw',
            'released' => '2021-08-23',
            'director' => 'Kwang Il Han',
            'now_upcoming' => 1,
            'is_news' => true,
            'actors' => [
                ['name' => 'Theo James', 'role' => 'Vesemir'],
                ['name' => 'Lara Pulver', 'role' => 'Tetra'],
                ['name' => 'Graham McTavish', 'role' => 'Deglan'],
                ['name' => 'Mary McDonnell', 'role' => 'Lady Zerbst'],
            ],
            'tags' => ['modern-2d'],
        ],

        'the-nightmare-before-christmas' => [
            'id' => 3,
            'title' => 'The Nightmare Before Christmas',
            'slug' => 'the-nightmare-before-christmas',
            'length' => 76,
            'description' => 'Jack Skellington, the pumpkin king of Halloween Town, discovers Christmas Town and becomes obsessed with celebrating the holiday, leading to hilarious and spooky consequences.',
            'poster' => 'images/posters/tnbc.jpg',
            'heroimg' => 'images/heroimgs/Nightmare-Before-Christmas-2-release-date-story-details.jpg',
            'youtube_id' => 'wr6N_hZyBCk',
            'released' => '1993-10-29',
            'director' => 'Henry Selick',
            'now_upcoming' => 2,
            'is_news' => true,
            'actors' => [
                ['name' => 'Chris Sarandon', 'role' => 'Jack Skellington'],
                ['name' => 'Catherine O\'Hara', 'role' => 'Sally'],
                ['name' => 'Danny Elfman', 'role' => 'Jack Skellington (singing)'],
                ['name' => 'Ken Page', 'role' => 'Oogie Boogie'],
            ],
            'tags' => ['stop-motion'],
        ],

        'shrek' => [
            'id' => 4,
            'title' => 'Shrek',
            'slug' => 'shrek',
            'length' => 90,
            'description' => 'An ogre named Shrek embarks on a quest to rescue Princess Fiona for the vertically challenged Lord Farquaad, only to discover that true love comes in unexpected forms.',
            'poster' => 'images/posters/shrek.jpg',
            'heroimg' => 'images/heroimgs/shrek-heroimg.jpg',
            'youtube_id' => 'CwXOrWvPBPk',
            'released' => '2001-05-18',
            'director' => 'Andrew Adamson, Vicky Jenson',
            'now_upcoming' => 2,
            'is_news' => true,
            'actors' => [
                ['name' => 'Mike Myers', 'role' => 'Shrek'],
                ['name' => 'Eddie Murphy', 'role' => 'Donkey'],
                ['name' => 'Cameron Diaz', 'role' => 'Princess Fiona'],
                ['name' => 'John Lithgow', 'role' => 'Lord Farquaad'],
            ],
            'tags' => ['computer-3d'],
        ],
    ],

];
