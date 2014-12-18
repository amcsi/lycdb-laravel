<?php

namespace Lycee\Card;

use Illuminate\Http\Request;
use Lycee\Config\Elements;

/**
 * Lycee\Card/FetchService
 */
class FetchService
{
    /**
     * @var Eloquent
     */
    private $eloquent;
    /**
     * @var Elements
     */
    private $elements;
    /**
     * @var array
     */
    private $lyceeConfig;

    /**
     * @param Eloquent $eloquent
     * @param Elements $elements
     */
    public function __construct(Eloquent $eloquent, Elements $elements, array $lyceeConfig)
    {
        $eloquent->setElements($elements);
        $this->eloquent = $eloquent;
        $this->elements = $elements;
        $this->lyceeConfig = $lyceeConfig;
    }

    /**
     * @param array $requestVars
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getByRequest(array $requestVars, &$paginator)
    {
        $mergeKeys = ['cid', 'name', 'cost_type', 'element_type', 'ex', 'text', 'page'];
        $options = array_intersect_key($requestVars, array_flip($mergeKeys));

        $map = [
            'card_type' => 'type',
            'ex_operator' => 'ex_equality',
        ];

        foreach ($map as $src => $dst) {
            if (array_key_exists($src, $requestVars)) {
                $options[$dst] = $requestVars[$src];
            }
        }

        $page = isset($data['page']) ? max(1, $data['page']) : 1;

        $elements = $this->elements;
        $options['cost'] = array ();
        $options['element'] = array ();
        foreach ($elements as $enum => $element) {
            $key = $element['key'];
            if (array_key_exists("cost_$key", $requestVars)) {
                $ex = $requestVars["cost_$key"];
                $cost = max(0, min($this->lyceeConfig['max_ex'], $ex)); // filter value
                $options['cost'][$enum] = $cost;
            }
            if ('star' !== $key && array_key_exists("element_$key", $requestVars)) {
                $options['element'][$key] = (bool) $requestVars["element_$key"];
            }
        }
        $results = $this->eloquent->getByOptions($options, $paginator);

        return $results;
    }
} 