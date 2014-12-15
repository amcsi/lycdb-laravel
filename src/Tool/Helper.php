<?php

namespace Lycee\Tool;

use Illuminate\Container\Container;
use Lycee\Card\Eloquent;

/**x
 *
 * Lycee\Tool/Helper
 */
class Helper
{

    /**
     * @var Container
     */
    private $app;

    function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @see \Lycee\Tool\MarkupToHtml::convert()
     * @param string $markup
     * @return string
     */
    public function lycdbMarkupToHtml($markup)
    {
        return $this->app->make('Lycee\Tool\MarkupToHtml')->convert($markup);
    }

    public function getTypeTextByCard(Eloquent $card)
    {
        switch ($card['type']) {
            case $card::TYPE_CHAR:
                $tt = 'character';
                break;
            case $card::TYPE_AREA:
                $tt = 'area';
                break;
            case $card::TYPE_EVENT:
                $tt = 'event';
                break;
            case $card::TYPE_ITEM:
                $tt = 'item';
                break;
            default:
                $tt = 'unknown';
                break;
        }

        return $tt;
    }

}