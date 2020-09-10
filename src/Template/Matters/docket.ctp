<?php
$this->assign('title', 'Matters - Docket');
$this->set('wunder_title', 'Matters - Docket');
?>

<?= $this->element('matters_view_menu') ?>

<div class="docket-content">
    <div class="pre-loader">
        <span class="element-loader"></span>
    </div>
    <div>
        <ul class="case-number-list">
            <?php foreach ($dockets as $key => $docket) : ?>
                <li class="case-number-item <?= $key == 0 ? 'is-active' : '' ?>">
                    <a class="case-number-link"
                       data-fed-abbr="<?= $docket->court->fed_abbr ?>"
                       data-court-id="<?= $docket->court_id ?>"
                       data-case-num-judge="<?= $docket->fed_case_number_judges ?>"
                       data-docket-id="<?= $docket->id ?>"
                       data-case-number="<?= $docket->case_number ?>"
                    >
                        <span><?= $docket->court->name ?></span><br>
                        <span class="case-number"><?= $docket->case_number ?><?= $docket->fed_case_number_judges ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?= $this->Form->create(null, [
        'url' => [
            'controller' => 'Api',
            'action' => 'fetchNewDoc'
        ],
        'id' => 'newDocForm'

    ]); ?>

    <div class="field is-grouped mt-30">
        <div class="control">
            <?= $this->Form->text('documents_numbered_from_', ['class' => 'input', 'placeholder' => 'Docket # From']); ?>
        </div>
        <div class="control">
            <?= $this->Form->text('documents_numbered_to_', ['class' => 'input', 'placeholder' => 'Docket # To']); ?>
        </div>
        <div class="control">
            <?= $this->Form->button('Get', ['class' => 'button is-green', 'id' => 'getNewDocBtn']); ?>
        </div>
    </div>

    <?= $this->Form->end() ?>
    <div class="table-docket-block mt-30">
        <table class="matter-courts-table">
            <thead>
            <tr>
                <td>#</td>
                <td>Date</td>
                <td>Description</td>
            </tr>
            </thead>
            <tbody style="font-size:smaller;">

            </tbody>
        </table>
    </div>

</div>

<?= $this->Html->script('dockets/index'); ?>

<script>

    window.getCurrentData = function() {

        let a = $('li.case-number-item.is-active').children('a');

        if (a.length < 1) {
            return null;
        }

        return {
            fedAbbr: a.attr('data-fed-abbr'),
            caseNumber: a.attr('data-case-number'),
            caseNumberJudge: a.attr('data-case-num-judge'),
            docketID: a.attr('data-docket-id'),
            courtID: a.attr('data-court-id')
        }
    }

    $(document).ready(function () {

        $('li.case-number-item').click(function() {

            if ($(this).hasClass('is-active')) {
                return;
            }

            $(this).prev().removeClass('is-active');
            $(this).next().removeClass('is-active');
            $(this).addClass('is-active');

            getDocketsHandler();

        });

    });

</script>
