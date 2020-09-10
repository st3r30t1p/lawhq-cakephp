<?php $this->Paginator->setTemplates([
        'nextActive' => '<a class="pagination-next outter-button" href="{{url}}">{{text}}</a>',
        'nextDisabled' => '<a class="pagination-next outter-button" disabled href="" onclick="return false;">{{text}}</a>',
        'prevActive' => '<a class="pagination-previous outter-button" href="{{url}}">{{text}}</a>',
        'prevDisabled' => '<a class="pagination-previous outter-button" disabled href="" onclick="return false;">{{text}}</a>',
        'counterRange' => '{{start}} - {{end}} of {{count}}',
        'counterPages' => '{{page}} of {{pages}}',
        'first' => '<a class="pagination-previous outter-button" href="{{url}}">{{text}}</a>',
        'last' => '<a class="pagination-next outter-button" href="{{url}}">{{text}}</a>',
        'number' => '<li class="pagination-link"><a href="{{url}}">{{text}}</a></li>',
        'current' => '<li class="pagination-link is-green"><a href="">{{text}}</a></li>'
    ]);
?>
<div class="pagination is-centered" role="navigation" aria-label="pagination">
        <?= $this->Paginator->first(__('First'), ['class' => 'thisisatest']) ?>
        <?= $this->Paginator->prev(__('Previous')) ?>
        <?= $this->Paginator->next(__('Next')) ?>
        <?= $this->Paginator->last(__('Last')) ?>
    <ul class="pagination-list">
        <?= $this->Paginator->numbers() ?>
    </ul>
</div>

<p class="is-pulled-right" style="font-size:13px">
	<?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?>
</p>