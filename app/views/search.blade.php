@extends('master')

@section('content')

        <input type='button' value='Show/hide basic search' onclick="show_hide('basic_search_form');">
        <form id='basic_search_form' method='get' action='{{{ URL::current() }}}' class="basicSearchForm">
            <input type="hidden" name="search" value="1">
            <fieldset class="card_search">
                <legend>Basic search</legend>
                <ul>
                    <li>
                        <label for='basic_search_cid' class="form_title">ID</label>
                        {{ Form::text('cid', Request::get('cid'), [
                            'id' => 'basic_search_cid',
                        ]) }}
                    </li>
                    <li>
                        <label for='basic_search_name' class="form_title">Card name</label>
                        {{ Form::text('name', Request::get('name'), [
                            'id' => 'basic_search_name',
                        ]) }}
                    </li>
                    <li>
                        <label for='basic_search_card_type' class="form_title">Card type</label>
                        {{ Form::select(
                            'card_type',
                            $selectableCardTypes,
                            null !== ($ct = Request::get('card_type')) ? $ct : '-2',
                            [
                                'id' => 'basic_search_card_type',
                            ]
                        ) }}
                    </li>
                    <li>
                        <label for='basic_search_cost_type' class="form_title">Cost</label>
                        {{ Form::select(
                            'cost_type',
                            $selectableCostTypes,
                            Request::get('cost_type'),
                            [
                                'id' => 'basic_search_cost_type',
                            ]
                        ) }}
                        @foreach ($lyceeConfig['elements'] as $element)
                        <label for="basic_search_cost_{{{ $element['key'] }}}"><img alt='{{{ $element['key'] }}}' src="{{{asset("img/$element[key].gif")}}}"></label>
                        {{ Form::selectRange("cost_$element[key]", 0, $lyceeConfig['max_cost'], Request::get("cost_$element[key]"), ['id' => "cost_$element[key]"]) }}
                        @endforeach
                    </li>
                    <li>
                        <label for='basic_search_name' class="form_title">Ex</label>
                        {{ Form::select(
                            'ex_operator',
                            $selectableOperators,
                            Request::get('ex_operator'),
                            [
                                'id' => 'basic_search_ex_operator',
                            ]
                        ) }}
                        {{ Form::selectRange('ex', 0, $lyceeConfig['max_ex'], Request::get('ex'), ['id' => 'basic_search_ex']) }}
                    </li>
                    <li>
                        <label class="form_title">Element</label>
                        {{ Form::select(
                            'element_type',
                            $selectableElementTypes,
                            Request::get('element_type'),
                            [
                                'id' => 'basic_search_element_type',
                            ]
                        ) }}
                        @foreach ($lyceeConfig['elements'] as $element)
                        <label for="basic_search_element_{{{ $element['key'] }}}">
                            <img alt='{{{ $element['key'] }}}' src="{{{asset("img/$element[key].gif")}}}">
                        </label>
                        {{ Form::checkbox("element_$element[key]", 1, Request::get("element_$element[key]"), [
                            'id' => "basic_search_element_$element[key]",
                        ]) }}
                        @endforeach
                    </li>
                    <li>
                        <label for='basic_search_text' class="form_title">Text contains</label>
                        {{ Form::text('text', Request::get('text'), [
                            'id' => 'basic_search_text',
                        ]) }}
                    </li>
                    <li>
                        <label for='basic_search_text' class="form_title">&nbsp;</label>
                        <input type="submit" value="Search">
                    </li>
                </ul>
            </fieldset>
        </form>

        <p>
            Found <strong>{{ $paginator->getTotal() }}</strong> results.<br>
            Displaying {{ $paginator->count() }}.<br><br>
        </p>

        {{ $paginator->links() }}

        <table class="card_results">
            <thead>
                <colgroup class="card_result_columns">
                    <col class="card_id">
                    <col class="card_name">
                    <col class="card_sets">
                    <col class="card_cost">
                    <col class="card_ex">
                    <col class="card_element">
                    <col class="card_spots">
                    <col class="card_ap">
                    <col class="card_dp">
                    <col class="card_sp">
                </colgroup>
                <tr class="card_result_columns">
                    <th class="card_id" id="card_result_title_0" onclick="reorder_table('card_result', 0)">Card ID</th>
                    <th class="card_name" id="card_result_title_1" onclick="reorder_table('card_result', 1)">Name</th>
                    <th class="card_sets" id="card_result_title_9" onclick="reorder_table('card_result', 2)">Card sets</th>
                    <th class="card_cost" id="card_result_title_2" onclick="reorder_table('card_result', 3)">Cost</th>
                    <th class="card_ex" id="card_result_title_3" onclick="reorder_table('card_result', 4)">Ex</th>
                    <th class="card_element" id="card_result_title_4" onclick="reorder_table('card_result', 5)">Element</th>
                    <th class="card_spots" id="card_result_title_5" onclick="reorder_table('card_result', 6)">FL</th>
                    <th class="card_ap" id="card_result_title_6" onclick="reorder_table('card_result', 7)">AP</th>
                    <th class="card_dp" id="card_result_title_7" onclick="reorder_table('card_result', 8)">DP</th>
                    <th class="card_sp" id="card_result_title_8" onclick="reorder_table('card_result', 9)">SP</th>
                </tr>
            </thead>


            <tbody>
                @foreach ($cards as $key => $card)
                <tr id="card_result_{{{ $card['cid'] }}}" class="{{{ Helper::getTypeTextByCard($card) }}} {{{ $key % 2 ? 'odd' : 'even' }}}">
                    <td class="cardId">{{{ $card['cid'] }}}</td>
                    <td class="cardName">{{{ $card['name_jp'] }}}
                    @if ($card['import_errors'])
                        <span class="clickable tooltip" title="Notice: The import script has reported errors regarding this card.
We are already aware of this error and will fix it sometime soon so please don't report it to us."><img src="{{{ asset('img/exclamation-red-frame.png') }}}" alt="warning"></span>
                    @endif
                    </td>
                    <td class="sets">
                        {{ nl2br(e($card['sets_string_short']), false) }}
                    </td>
                    <td class="cost">
                        {{ Helper::lycdbMarkupToHtml(Helper::markupCostsByCard($card)) }}
                    </td>
                    <td class="ex">
                        {{{ $card['ex'] }}}
                    </td>
                    <td>
                        {{ Helper::lycdbMarkupToHtml(Helper::markupElementsByCard($card)) }}
                    </td>
                    @if (!$card['type'])
                    <td class="positions">
                        {{ Helper::lycdbMarkupToHtml(Helper::markupPositionByCard($card)) }}
                    </td>
                    <td class="ap">
                        {{{ $card['ap'] }}}
                    </td>
                    <td class="dp">
                        {{{ $card['dp'] }}}
                    </td>
                    <td class="sp">
                        {{{ $card['sp'] }}}
                    </td>
                    @else
                    <td class="positions">
                        &nbsp;
                    </td>
                    <td class="ap">
                        &nbsp;
                    </td>
                    <td class="dp">
                        &nbsp;
                    </td>
                    <td class="sp">
                        &nbsp;
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $paginator->links() }}

    <div id='hidden'>
        @if ($cards)
        @foreach($cards as $key => $card)
        <div class="card_js {{{ Helper::getTypeTextByCard($card) }}}" id="card_js_{{{ $card['cid'] }}}" style="display: none;">
            <div class="card_js_image" data-src="{{{ asset("lycee_images/180/$card[cid]") }}}" data-width="180">
            </div>
            <div class="card_js_details">
                <div class="card_js_id card_js_detail_left">
                    <span>{{{ $card['cid'] }}}</span>
                </div>
                <div class="card_js_sets">
                    <span>{{ nl2br(e($card['sets_string'])) }}</span>
                </div>
                <div class="card_js_name">
                    <span class="card_name card_js_detail_left">
                        {{{ $card['name_en'] }}}
                    </span>
                </div>
                <div class="card_js_name_jap">
                    <span class="card_name jap">
                        {{{ $card['name_jp'] }}}
                    </span>
                </div>
                <div class="card_js_ex card_js_detail_left">
                    <span>
                        <strong>Ex:</strong> {{{ $card['ex'] }}}
                        {{ Helper::lycdbMarkupToHtml(Helper::markupElementsByCard($card)) }}
                    </span>
                </div>
                <div class="card_js_cost">
                    <span>
                        {{ Helper::lycdbMarkupToHtml(Helper::markupCostsByCard($card)) }}
                    </span>
                </div>
                @if (Lycee\Card\Eloquent::TYPE_CHAR == $card['type'])
                <div class="card_js_ap_dp_sp card_js_detail_left">
                <div class='left ap'>{{{ $card['ap'] }}}</div>
                    <div class='left dp'>{{{ $card['dp'] }}}</div>
                    <div class='left sp'>{{{ $card['sp'] }}}</div>
                </div>
                <div class="card_js_spots">
                    {{ Helper::lycdbMarkupToHtml(Helper::markupPositionByCard($card)) }}
                </div>
                <div class="card_js_text card_js_detail_full">
                    @if ($card['conversion_jp'])
                    <span class="conversion">{{{ Lang::trans('Conversion') }}}: {{{ $card['conversion_jp'] }}}</span>
                    @endif
                    @if ($card['basic_abilities_jp'])
                    @foreach (explode("\n", $card['basic_abilities_jp']) as $basicAbility)
                    <p class="basic_ability_name">
                        {{ Helper::lycdbMarkupToHtml($basicAbility) }}
                    </p>
                    @endforeach
                    @endif
                    <p class="ability_name">
                        <span>
                            <span class="abilityName"><?php $cost = Helper::lycdbMarkupToHtml($card['ability_cost_jp']) ?>
                            {{{ $card['ability_name_jp'] }}}@if ($card['ability_cost_jp']):@endif</span>
                            @if ($card['ability_cost_jp'])
                            <span class="cost">{{ Helper::lycdbMarkupToHtml($card['ability_cost_jp']) }}</span>
                            @endif
                        </span>
                    </p>
                    <p class="card_text">
                        <span>
                            {{ Helper::lycdbMarkupToHtml($card['ability_desc_jp']) }}
                        </span>
                    </p>
                </div>
                @else
                <div class="card_js_text card_js_detail_full">
                    <p class="card_text">
                        <span>
                            {{ Helper::lycdbMarkupToHtml($card['ability_desc_jp']) }}
                        </span>
                    </p>
                </div>
                @endif
                <div style="clear: both;"></div>
            </div>
            <div style="clear: both;"></div>
        </div>
        @endforeach
        @endif
    </div>

@stop
