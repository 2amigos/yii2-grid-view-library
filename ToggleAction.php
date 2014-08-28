<?php
/**
 * @copyright Copyright (c) 2014 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\grid;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * ToggleAction works in conjunction with ToggleColumn to ease the task to update the model.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package doamigos\grid
 */
class ToggleAction extends Action
{
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
            $model->setAttributes([$attribute => $model->$attribute == $this->offValue ? $this->onValue : $this->offValue]);
            
            // Can also be handled by ContentNegotiator
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->save(false, [$attribute])) {
                return ['result' => true, 'value' => ($model->$attribute == $this->onValue)];
            }
            return ['result' => false, 'errors' => $model->getErrors()];

        } else {
            throw new BadRequestHttpException(Yii::t('app', 'Invalid request'));
        }
    }

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return \yii\db\ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $class = $this->modelClass;
        if ($id !== null && ($model = $class::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
} 
