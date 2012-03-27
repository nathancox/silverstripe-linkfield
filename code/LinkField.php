<?php 

class LinkField extends CompositeField {
	var $config = array(
		'localLabel' => 'A page on this site',
		'localPageType' => 'SiteTree',
		'remoteLabel' => 'Another site or URL'
	);
	
	
	function __construct($name, $title) {
		$this->name = $name;
		$this->title = $title;
		
		parent::__construct($this->fieldArray());
	}
	
	
	function setConfig($paramOne, $paramTwo = null) {
		if (!is_array($paramOne)) {
			$paramOne = array(
				$paramOne => $paramTwo
			);
		}
		
		foreach($paramOne as $key => $value) {
			$this->config[$key] = $value;
		}
	}
	
	function getConfig($name) {
		return (isset($this->config[$name]) ? $this->config[$name] : false);
	}
	
	
	function FieldHolder() {
		Requirements::javascript(THIRDPARTY_DIR.'/jquery-livequery/jquery.livequery.js');   
		Requirements::javascript('linkfield/javascript/LinkField.js');
		Requirements::css('linkfield/css/LinkField.css');
		
		return $this->renderWith("LinkField");
	}
	
	function fieldArray() {
		$items = array();
		$localField = new TreeDropdownField($this->name."_local", $this->getConfig('localLabel'), $this->getConfig('localPageType'));
		$items['local'] = $localField;

		if (class_exists('URLField')) {
			$remoteField = new URLField($this->name."_remote", $this->getConfig('remoteLabel'));
		} else {
			$remoteField = new TextField($this->name."_remote", $this->getConfig('remoteLabel'));
		}
		$items['remote'] = $remoteField;
		
		$localField->linkType = 'local';
		$remoteField->linkType = 'remote';
		
		return $items;
	}
	
	function FieldSet() {
		$items = $this->fieldArray();
		
		$linkType = $this->linkType();

		if ($linkType == 'local') {
			$items['local']->setValue($this->getLinkID());
		} else if ($linkType == 'remote') {
			$items['remote']->setValue($this->Value());
		}
		
		$count = 0;
		$firstSelected = $checked = "";
		
		foreach($items as $item) {
			$title = $item->title;
			$item->setTitle('');
			if($item->linkType == $linkType) {
				$firstSelected = " class=\"selected\"";
				$checked = " checked=\"checked\"";
			}
			
			$itemID = $this->ID() . '_' . (++$count);
			$extra = array(
				"RadioButton" => "<input class=\"selector\" type=\"radio\" id=\"$itemID\" name=\"".$this->name."\" value=\"$item->linkType\" $checked  />",
				"RadioLabel" => "<label for=\"$itemID\">$title</label>",
				"Selected" => $firstSelected,
			);
			if(is_object($item)) $newItems[] = $item->customise($extra);
			else $newItems[] = new ArrayData($extra);

			$firstSelected = $checked ="";			
		}
		return new DataObjectSet($newItems);
	}

	function dataValue() {
		$fields = $this->fieldArray();
		
		if (isset($fields[$this->value])) {
			$fieldToUse = $fields[$this->value];
			$fieldName = $fieldToUse->Name();
			if (isset($_REQUEST[$fieldName])) {
				$value = $_REQUEST[$fieldName];
				
				if ($this->value == 'local') {
					$value = '[sitetree_link id='.$value.']';
				}
				
				return $value;
			}
		}
		
		return '';
	}


	function linkType() {
		if (substr($this->Value(), 0, 14) == '[sitetree_link') {
			return 'local';
		} else if ($this->Value() == '') {
			return 'none';
		} else {
			return 'remote';
		}
	}
	
	function getLinkID() {
		//[sitetree_link id=2]
		
		$value = str_replace('[sitetree_link id=', '', $this->Value());
		$value = str_replace(']', '', $value);
		return (int)$value;
	}
	
	function hasData() {
		return true;
	}
	
	function linkURL() {
		return LinkField::link_url($this->value);
	}
	
	static function link_url($value) {
		return ShortcodeParser::get_active()->parse($value);
	}
	
	
}