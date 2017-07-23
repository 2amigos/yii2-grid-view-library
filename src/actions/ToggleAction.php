<?php

/*
 * This file is part of the 2amigos/yii2-grid-view-library project.
 * (c) 2amigOS! <http://2amigos.us/>
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace dosamigos\grid\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ToggleAction works in conjunction with ToggleColumn to ease the task to update the model.
 */
class ToggleAction extends Action
{
    const TOGGLE_ANY = 'toggleany';
    const TOGGLE_UNIQ = 'toggleuniq';
    const TOGGLE_COND = 'togglecond';
    /**
     * @var string the class name of the model.
     */
    public $modelClass;
    /**
     * @var mixed the on value to set the attribute with.
     */
    public $onValue = 1;
    /**
     * @var mixed the off value to set the attribute with.
     */
    public $offValue = 0;

    /**
     * one of constants
     * ToggleAction::TOGGLE_ANY - change state of any element (by default)
     * ToggleAction::TOGGLE_UNIQ - only one element must be equal $onValue
     * ToggleAction::TOGGLE_COND - toggle one element to $onValue per condition (for example only one foto can be set as cover per album)
     *
     * @var string
     */
    public $toggleType = self::TOGGLE_ANY;

    /**
     * property for TOGGLE_UNIQ and TOGGLE_COND types - allow set all in offValue
     *
     * @var bool
     */
    public $allowAllOff = false;

    /**
     * @var mixed (array|string|callable) $condition the conditions that will be put in the WHERE part of the UPDATE SQL
     *            (used where $toggleType=ToggleAction::TOGGLE_COND)
     *            Please refer to [[Query::where()]] on how to specify this parameter.
     *
     * @example
     * ['another_attribute'=>20]
     * or
     * function($model){
     *     return ['attr'=>$model->attr];
     * }
     * where $model - is current toggled model object
     **/
    public $condition = [];

    /**
     * @var string scenario for this action
     **/
    public $scenario = 'default';

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if ($this->modelClass === null) {
            throw new InvalidConfigException('"modelClass" cannot be empty.');
        }
        parent::init();
    }

    /**
     * @inheritdoc
     * @throws \yii\web\BadRequestHttpException
     */
    public function run($id, $attribute)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel($id);
            $model->setScenario($this->scenario);
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($this->toggleType == self::TOGGLE_ANY) {
                $model->setAttributes(
                    [$attribute => $model->$attribute == $this->offValue ? $this->onValue : $this->offValue]
                );
                if ($model->validate() && $model->save(false, [$attribute])) {
                    return ['result' => true, 'value' => ($model->$attribute == $this->onValue)];
                }
            } elseif ($this->toggleType == self::TOGGLE_UNIQ) {
                if ($model->$attribute == $this->offValue || $this->allowAllOff) {
                    $model->setAttributes(
                        [$attribute => $model->$attribute == $this->offValue ? $this->onValue : $this->offValue]
                    );
                    if ($model->validate()) {
                        /**may  be transaction?**/
                        $model->updateAll([$attribute => $this->offValue]);
                        if ($model->save(false, [$attribute])) {
                            return ['result' => true, 'value' => ($model->$attribute == $this->onValue)];
                        }
                    }
                }
            } else {
                if ($model->$attribute == $this->offValue || $this->allowAllOff) {
                    $cond = is_callable($this->condition) ? call_user_func($this->condition, $model) : $this->condition;
                    $model->setAttributes(
                        [$attribute => $model->$attribute == $this->offValue ? $this->onValue : $this->offValue]
                    );
                    if ($model->validate()) {
                        $model->updateAll([$attribute => $this->offValue], $cond);
                        if ($model->save(false, [$attribute])) {
                            return ['result' => true, 'value' => ($model->$attribute == $this->onValue)];
                        }
                    }
                }
            }

            return ['result' => false, 'errors' => $model->getErrors()];
        }
        throw new BadRequestHttpException(Yii::t('app', 'Invalid request'));
    }

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer               $id
     * @throws NotFoundHttpException if the model cannot be found
     * @return \yii\db\ActiveRecord  the loaded model
     */
    protected function findModel($id)
    {
        $class = $this->modelClass;
        if ($id !== null && ($model = $class::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
