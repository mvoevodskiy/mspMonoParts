<div class="row" id="mspMonoParts_count" style="display: none">
    <div class="col-md-8">Количество частей</div>
    <div class="col-md-4">
        <select name="mspMonoParts_count_select" id="mspMonoParts_count_select">
            {var $interval = $min..$max}
            {foreach $interval as $count}
                <option value="{$count}">{$count}</option>
            {/foreach}
        </select>
    </div>
    <div class="col-md-12">
        Примерно по <span id="mspMonoParts_count_monthly_print">0</span> грн в течение <span id="mspMonoParts_count_print">0</span> месяцев
    </div>
</div>