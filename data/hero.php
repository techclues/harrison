<?php
declare(strict_types=1);

/*
 * Home hero background photos, in order. `src` is relative to assets/. To change a photo, put the
 * new file in assets/images/hero/ (landscape, ideally 2400px or wider) and edit its line here.
 * `pos` is the CSS object-position crop; the first photo is the one shown while the page loads.
 */
return [
    ['src' => 'conversation.webp', 'w' => 1536, 'h' => 1024, 'pos' => '50% 50%', 'alt' => 'An accountant and a client reviewing figures together in a bright office'],
    ['src' => 'images/stock/financial-review.webp', 'w' => 2400, 'h' => 1600, 'pos' => '50% 52%', 'alt' => 'Professional reviewing financial figures and notes at a desk'],
    ['src' => 'images/stock/business-planning.webp', 'w' => 2400, 'h' => 1600, 'pos' => '50% 44%', 'alt' => 'Business team planning strategy together in a bright meeting room'],
];
