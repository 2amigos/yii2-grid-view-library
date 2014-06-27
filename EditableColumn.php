<?php
/**
 * @copyright Copyright (c) 2014 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace dosamigos\grid;


use dosamigos\editable\EditableAddressAsset;
use dosamigos\editable\EditableBootstrapAsset;
use dosamigos\editable\EditableComboDateAsset;
use dosamigos\editable\EditableDatePickerAsset;
use dosamigos\editable\EditableDateTimePickerAsset;
use dosamigos\editable\EditableSelect2Asset;
use dosamigos\editable\EditableWysiHtml5Asset;
use yii\base\InvalidConfigException;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

/**
 * EditableColumn adds X-Editable capabilities to a column
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\grid
 */
class EditableColumn extends DataColumn
{
    /**
     * @var array the options for the X-editable.js plugin.
     * Closures will be applied to each row being rendered.
     * Please refer to the X-editable.js plugin web page for possible options.
     * @see http://vitalets.github.io/x-editable/docs.html#editable
     */
    public $editableOptions = [];
    /**
     * @var string suffix substituted to a name class of the tag <a>
     */
    public $classSuffix;
    /**
     * @var string the url to post
     */
    public $url;
    /**
     * @var string the type of editor
     */
    public $type = 'text';
    /**
     * @var array JsExpressions or static options that should be applied in
     * the jQuery editable() call (copied from $editableOptions during init).
     */
    private $globalOptions = [];
    /**
     * @var array options that will be applied as-is to the a tag.
     */
    private $linkOptions = [];
    /**
     * @var array closures to be called and applied to the a tag.
     */
    private $funcOptions = [];
    
    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if ($this->url === null) {
            throw new InvalidConfigException("'Url' property must be specified.");
        }

        parent::init();

        $this->globalOptions['url'] = Url::to((array)$this->url);
        $this->globalOptions['name'] = $this->attribute;
        $this->globalOptions['type'] = $this->type;

        $rel = $this->attribute . '_editable' . $this->classSuffix;
        $this->linkOptions['rel'] = $rel;
        $this->linkOptions['pjax'] = '0';
        
        foreach ($this->editableOptions as $prop => $v) {
            if (is_callable($v)) {
                // keep a ref to this closure so we can call it for every row
                $this->funcOptions[$prop] = $v;
            } else {
                // apply this option to the jQuery editable() call
                $this->globalOptions[$prop] = $v;
            }
        }

        $this->registerClientScript();
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);
        
        $options = ['data-pk' => $key] + $this->linkOptions;
        
        foreach ($this->funcOptions as $prop => $v)
        {
            $options['data-' . $prop] = call_user_func($v, $model, $key, $index);
        }

        return Html::a($value, null, $options);
    }

    /**
     * Registers required script to the columns work
     */
    protected function registerClientScript()
    {
        $view = $this->grid->getView();
        $language = ArrayHelper::getValue($this->editableOptions, 'language');

        switch ($this->type) {
            case 'address':
                EditableAddressAsset::register($view);
                break;
            case 'combodate':
                EditableComboDateAsset::register($view);
                break;
            case 'date':
                if ($language) {
                    EditableDatePickerAsset::register(
                        $view
                    )->js[] = 'vendor/js/locales/bootstrap-datetimepicker.' . $language . '.js';
                } else {
                    EditableDatePickerAsset::register($view);
                }
                break;
            case 'datetime':
                if ($language) {
                    EditableDateTimePickerAsset::register(
                        $view
                    )->js[] = 'vendor/js/locales/bootstrap-datetimepicker.' . $language . '.js';
                } else {
                    EditableDateTimePickerAsset::register($view);
                }
                break;
            case 'select2':
                EditableSelect2Asset::register($view);
                break;
            case 'wysihtml5':
                $language = $language ? : 'en-US';
                EditableWysiHtml5Asset::register(
                    $view
                )->js[] = 'vendor/locales/bootstrap-wysihtml5.' . $language . '.js';
                break;
            default:
                EditableBootstrapAsset::register($view);
        }

        EditableColumnAsset::register($view);
        $rel = $this->linkOptions['rel'];
        $selector = "a[rel=\"$rel\"]";
        $grid = "#{$this->grid->id}";
        $options = Json::encode($this->globalOptions);
        $js[] = ";jQuery('$selector').editable($options);";
        $js[] = "dosamigos.editableColumn.registerHandler('$grid', '$selector');";
        $view->registerJs(implode("\n", $js));
    }
} 