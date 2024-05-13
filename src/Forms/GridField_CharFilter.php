<?php

namespace Clesson\Charfilter\Forms;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\AbstractGridFieldComponent;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridField_ActionProvider;
use SilverStripe\Forms\GridField\GridField_DataManipulator;
use SilverStripe\Forms\GridField\GridField_FormAction;
use SilverStripe\Forms\GridField\GridField_HTMLProvider;
use SilverStripe\ORM\SS_List;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;

class GridField_CharFilter extends AbstractGridFieldComponent implements GridField_HTMLProvider, GridField_ActionProvider, GridField_DataManipulator
{

    /**
     * The HTML fragment to write this component into
     */
    protected $targetFragment;

    protected $chars;

    protected $_selectedChar = '';

    /**
     *
     * @param string $targetFragment
     * @param array $searchFields Which fields on the object in the list should be searched
     */
    public function __construct($targetFragment = 'before', $chars = null)
    {
        $this->targetFragment = $targetFragment;
        $this->chars = $chars ? $chars : $this->defaultChars();
    }

    protected function defaultChars()
    {
        $chars = [];
        for ($char = ord('a'); $char <= ord('z'); ++$char) {
            $chars[] = strtoupper(chr($char));
        }
        for ($char = 0; $char <= 9; ++$char) {
            $chars[] = $char;
        }
        return $chars;
    }

    /**
     * @param $gridField
     * @return array|void
     */
    public function getHTMLFragments($gridField)
    {
        $dataClass = $gridField->getModelClass();

        $forTemplate = new ArrayData([]);
        $forTemplate->Fields = new FieldList();

        foreach ($this->chars as $key => $char) {
            $selected = $char == $this->_selectedChar;
            $charField = new GridField_FormAction(
                $gridField,
                'gridfield_charfilter-' . $key,
                $char,
                'charfilter',
                ['char' => $selected ? '' : $char]
            );
            $charField->addExtraClass('action_gridfield_charfilter');

            if ($selected) {
                $charField->addExtraClass('active');
            }

            $forTemplate->Fields->push($charField);
        }

        if ($form = $gridField->getForm()) {
            $forTemplate->Fields->setForm($form);
        }

        $template = SSViewer::get_templates_by_class($this, '', __CLASS__);
        return [
            $this->targetFragment => $forTemplate->renderWith($template)
        ];
    }

    /**
     * @inheritdoc
     */
    public function getActions($gridField)
    {
        return ['charfilter'];
    }

    /**
     * @inheritdoc
     */
    public function handleAction(GridField $gridField, $actionName, $arguments, $data)
    {
        if ($actionName == 'charfilter') {
            if (isset($arguments['char'])) {
                $this->_selectedChar = $arguments['char'];
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getManipulatedData(GridField $gridField, SS_List $dataList)
    {
        if ($this->_selectedChar) {
            return $dataList->filter(['Name:StartsWith' => (string)$this->_selectedChar]);
        }
        return $dataList;
    }
}
