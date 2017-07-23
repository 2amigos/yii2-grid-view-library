<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\traits;

use dosamigos\grid\GridView;

/**
 * @property GridView $grid the grid view object that owns this column.
 */
trait ColumnTrait
{
    /**
     * @var bool
     */
    public $rowSpanNoFilterHeaders = false;

    /**
     * Renders the header cell.
     *
     * @return string
     */
    public function renderHeaderCell()
    {
        if (null !== $this->grid->filterModel && false === $this->filter && $this->rowSpanNoFilterHeaders = true &&
                $this->grid->filterPosition === GridView::FILTER_POS_BODY) {
            $this->headerOptions['rowspan'] = 2;
        }

        return parent::renderHeaderCell();
    }

    /**
     * Renders the filter cell.
     *
     * @return string
     */
    public function renderFilterCell()
    {
        if (null !== $this->grid->filterModel && false === $this->filter && $this->rowSpanNoFilterHeaders = true &&
                $this->grid->filterPosition === GridView::FILTER_POS_BODY) {
            return null;
        }

        return parent::renderFilterCell();
    }
}
