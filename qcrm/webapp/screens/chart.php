<?php
    $params = json_decode($params);
    $symbol = ($params->symbol) ?: 'EURUSD';
?>

<section id="chart-achart" class="d-hide">
    <div class="section full">
        <div id="tv_chart_container"></div>
    </div>
</section>