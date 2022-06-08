<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\Renderer\Google;

use PHPUnit\Framework\TestCase;

class GoogleClassicAnalyticsTest extends TestCase
{
    public function testGetScript(): void
    {
        // Arrange
        $gaRenderer = new GoogleClassicAnalytics([
                                                     'walter' => 1,
                                                     'bernard' => 0
                                                 ]);

        // Act
        $script = $gaRenderer->getScript();

        // Assert
        $this->assertSame(
            "<script>
(function(){
    ga(function(tracker) {
        cxApi.setChosenVariation(1, 'walter');
        _gaq.push(['_trackEvent', 'PhpAb', 'testRun', 'walter', 1]);
    });
})();
(function(){
    ga(function(tracker) {
        cxApi.setChosenVariation(0, 'bernard');
        _gaq.push(['_trackEvent', 'PhpAb', 'testRun', 'bernard', 1]);
    });
})();
</script>",
            $script
        );
    }

    public function testGetScriptWithApiClient(): void
    {
        // Arrange
        $gaRenderer = new GoogleClassicAnalytics([
                                                     'walter' => 1,
                                                     'bernard' => 0
                                                 ]);
        $gaRenderer->setApiClientInclusion(true);

        // Act
        $script = $gaRenderer->getScript(true);

        // Assert
        $this->assertSame(
            "<script src=\"//www.google-analytics.com/cx/api.js\"></script>
<script>
(function(){
    ga(function(tracker) {
        cxApi.setChosenVariation(1, 'walter');
        _gaq.push(['_trackEvent', 'PhpAb', 'testRun', 'walter', 1]);
    });
})();
(function(){
    ga(function(tracker) {
        cxApi.setChosenVariation(0, 'bernard');
        _gaq.push(['_trackEvent', 'PhpAb', 'testRun', 'bernard', 1]);
    });
})();
</script>",
            $script
        );
    }

    public function testGetScriptEmpty(): void
    {
        // Arrange
        $gaRenderer = new GoogleClassicAnalytics([]);

        // Act
        $script = $gaRenderer->getScript();

        // Assert
        $this->assertSame('', $script);
    }

    public function testGetParticipations(): void
    {
        // Arrange
        $data = [
            'walter' => 1,
            'bernard' => 0
        ];
        $gaRenderer = new GoogleClassicAnalytics($data);

        // Act
        $returnedData = $gaRenderer->getParticipations();

        // Assert
        $this->assertSame($data, $returnedData);
    }
}
