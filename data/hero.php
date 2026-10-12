<?php
declare(strict_types=1);

/*
 * Home hero background photos, in order. `src` is relative to assets/. To change a photo, put the
 * new file in assets/images/hero/ (landscape, ideally 2400px or wider) and edit its line here.
 * `pos` is the CSS object-position crop; the first photo is the one shown while the page loads.
 */
return [
    ['src' => 'conversation.webp', 'w' => 1536, 'h' => 1024, 'pos' => '50% 50%', 'alt' => 'An accountant and a client reviewing figures together in a bright office'],
    ['src' => 'images/hero/boardroom-skyline.webp', 'w' => 1366, 'h' => 768, 'pos' => '50% 45%', 'alt' => 'Business leaders meeting in a boardroom overlooking a city skyline'],
    ['src' => 'architecture.webp', 'w' => 1122, 'h' => 1402, 'pos' => '50% 58%', 'alt' => 'Sunlit modern building with a curved facade and a clear path upwards'],
];
