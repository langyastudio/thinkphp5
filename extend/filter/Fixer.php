<?php
namespace filter;

/**
 * fixer类，处理数据。
 * fixer class, to fix data types.
 *
 * @package framework
 */
class Fixer
{
	/**
	 * 处理的数据。
	 * The data to be fixed.
	 *
	 * @var ojbect
	 * @access public
	 */
	public $data;

	/**
	 * 跳过处理的字段。
	 * The fields to striped.
	 *
	 * @var array
	 * @access public
	 */
	public $stripedFields = array();

	/**
	 * 构造方法，将超级全局变量转换为对象。
	 * The construction function, according the scope, convert it to object.
	 *
	 * @param  string $scope    the scope of the var, should be post|get|server|session|cookie|env
	 * @access public
	 * @return void
	 */
	public function __construct($scope)
	{
		switch($scope)
		{
			case 'post':
				$this->data = (object)$_POST;
				break;
			case 'server':
				$this->data = (object)$_SERVER;
				break;
			case 'get':
				$this->data = (object)$_GET;
				break;
			case 'session':
				$this->data = (object)$_SESSION;
				break;
			case 'cookie':
				$this->data = (object)$_COOKIE;
				break;
			case 'env':
				$this->data = (object)$_ENV;
				break;
			case 'file':
				$this->data = (object)$_FILES;
				break;

			default:
				die('scope not supported, should be post|get|server|session|cookie|env');
		}
	}

	/**
	 * 工厂方法。
	 * The factory function.
	 *
	 * @param  string $scope
	 * @access public
	 * @return object fixer object.
	 */
	public static function input($scope)
	{
		return new fixer($scope);
	}

	/**
	 * 处理Email。
	 * Email fix.
	 *
	 * @param  string $fieldName
	 * @access public
	 * @return object fixer object.
	 */
	public function cleanEmail($fieldName)
	{
		$fields = $this->processFields($fieldName);
		foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_EMAIL);
		return $this;
	}

	/**
	 * url编码。
	 * urlencode.
	 *
	 * @param  string $fieldName
	 * @access public
	 * @return object fixer object.
	 */
	public function encodeURL($fieldName)
	{
		$fields = $this->processFields($fieldName);
		$args   = func_get_args();
		foreach($fields as $fieldName)
		{
			$this->data->$fieldName = isset($args[1]) ?  filter_var($this->data->$fieldName, FILTER_SANITIZE_ENCODED, $args[1]) : filter_var($this->data->$fieldName, FILTER_SANITIZE_ENCODED);
		}
		return $this;
	}

	/**
	 * 清理网址。
	 * Clean the url.
	 *
	 * @param  string $fieldName
	 * @access public
	 * @return object fixer object.
	 */
	public function cleanURL($fieldName)
	{
		$fields = $this->processFields($fieldName);
		foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_URL);
		return $this;
	}

	/**
	 * 处理Float类型。
	 * Float fixer.
	 *
	 * @param  string $fieldName
	 * @access public
	 * @return object fixer object.
	 */
	public function cleanFloat($fieldName)
	{
		$fields = $this->processFields($fieldName);
		foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION|FILTER_FLAG_ALLOW_THOUSAND);
		return $this;
	}

	/**
	 * 处理Int类型。
	 * Int fixer.
	 *
	 * @param  string $fieldName
	 * @access public
	 * @return object fixer object.
	 */
	public function cleanINT($fieldName = '')
	{
		$fields = $this->processFields($fieldName);
		foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_NUMBER_INT);
		return $this;
	}

	/**
	 * 将字符串转换为可以在浏览器查看的编码。
	 * Special chars.
	 *
	 * @param  string $fieldName
	 * @access public
	 * @return object fixer object
	 */
	public function specialChars($fieldName)
	{
		$fields = $this->processFields($fieldName);
		foreach($fields as $fieldName)
		{
			if(empty($this->stripedFields) or !in_array($fieldName, $this->stripedFields)) $this->data->$fieldName = $this->specialArray($this->data->$fieldName);
		}
		return $this;
	}

	/**
	 * Special array
	 *
	 * @param  mix      $data
	 * @access public
	 * @return mix
	 */
	public function specialArray($data)
	{
		if(!is_array($data)) return htmlspecialchars($data, ENT_QUOTES);

		foreach($data as &$value) $value = $this->specialArray($value);

		return $data;
	}

	/**
	 * 忽略该标签。
	 * Strip tags
	 *
	 * @param  string $fieldName
	 * @param  string $allowableTags
	 * @access public
	 * @return object fixer object
	 */
	public function stripTags($fieldName, $allowedTags = '')
	{
		global $app, $config;
		if(empty($allowedTags) and isset($config->allowedTags)) $allowedTags = $config->allowedTags;
		$usePurifier = isset($config->framework->purifier) ? $config->framework->purifier : false;
		if($usePurifier)
		{
			$app->loadClass('purifier', true);
			$purifierConfig = HTMLPurifier_Config::createDefault();
			$purifierConfig->set('Filter.YouTube', 1);

			/* Disable caching. */
			$purifierConfig->set('Cache.DefinitionImpl', null);

			$purifier = new HTMLPurifier($purifierConfig);
			$def = $purifierConfig->getHTMLDefinition(true);
			$def->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');
		}

		$fields = $this->processFields($fieldName);
		foreach($fields as $fieldName)
		{
			if(version_compare(phpversion(), '5.4', '<') and get_magic_quotes_gpc()) $this->data->$fieldName = stripslashes($this->data->$fieldName);

			if(!in_array($fieldName, $this->stripedFields))
			{
				if(!defined('RUN_MODE') or RUN_MODE != 'admin')
				{
					/*
					 * purifier会把&nbsp;替换空格，kindeditor在会吧行首的空格去掉。
					 * purifier will change &nbsp; to ' ', and edit it will no space in line head use kindeditor.
					 **/
					if($usePurifier) $this->data->$fieldName = str_replace('&nbsp;', '&spnb;', $this->data->$fieldName);
					$this->data->$fieldName = $usePurifier ? $purifier->purify($this->data->$fieldName) : strip_tags($this->data->$fieldName, $allowedTags);
					if($usePurifier) $this->data->$fieldName = str_replace('&amp;spnb;', '&nbsp;', $this->data->$fieldName);
				}
			}
			$this->stripedFields[] = $fieldName;
		}
		return $this;
	}

	/**
	 * 忽略处理给定的字段。
	 * Skip special chars check.
	 *
	 * @param  string    $filename
	 * @access public
	 * @return object fixer object
	 */
	public function skipSpecial($fieldName)
	{
		$fields = $this->processFields($fieldName);
		foreach($fields as $fieldName) $this->stripedFields[] = $fieldName;
		return $this;
	}

	/**
	 * 给字段添加引用，防止字符与关键字冲突。
	 * Quote
	 *
	 * @param  string $fieldName
	 * @access public
	 * @return object fixer object
	 */
	public function quote($fieldName)
	{
		$fields = $this->processFields($fieldName);
		foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_SANITIZE_MAGIC_QUOTES);
		return $this;
	}

	/**
	 * 设置字段的默认值。
	 * Set default value of some fileds.
	 *
	 * @param  string $fields
	 * @param  mixed  $value
	 * @access public
	 * @return object fixer object
	 */
	public function setDefault($fields, $value)
	{
		$fields = strpos($fields, ',') ? explode(',', str_replace(' ', '', $fields)) : array($fields);
		foreach($fields as $fieldName)if(!isset($this->data->$fieldName) or empty($this->data->$fieldName)) $this->data->$fieldName = $value;
		return $this;
	}

	/**
	 * 如果条件为真，则为字段赋值。
	 * Set value of a filed on the condition is true.
	 *
	 * @param  bool   $condition
	 * @param  string $fieldName
	 * @param  string $value
	 * @access public
	 * @return object fixer object
	 */
	public function setIF($condition, $fieldName, $value)
	{
		if($condition) $this->data->$fieldName = $value;
		return $this;
	}

	/**
	 * 强制给字段赋值。
	 * Set the value of a filed in force.
	 *
	 * @param  string $fieldName
	 * @param  mixed  $value
	 * @access public
	 * @return object fixer object
	 */
	public function setForce($fieldName, $value)
	{
		$this->data->$fieldName = $value;
		return $this;
	}

	/**
	 * 移除一个字段。
	 * Remove a field.
	 *
	 * @param  string $fieldName
	 * @access public
	 * @return object fixer object
	 */
	public function remove($fieldName)
	{
		$fields = $this->processFields($fieldName);
		foreach($fields as $fieldName) unset($this->data->$fieldName);
		return $this;
	}

	/**
	 * 如果条件为真，移除该字段。
	 * Remove a filed on the condition is true.
	 *
	 * @param  bool   $condition
	 * @param  string $fields
	 * @access public
	 * @return object fixer object
	 */
	public function removeIF($condition, $fields)
	{
		$fields = $this->processFields($fields);
		if($condition) foreach($fields as $fieldName) unset($this->data->$fieldName);
		return $this;
	}

	/**
	 * 为数据添加新的项。
	 * Add an item to the data.
	 *
	 * @param  string $fieldName
	 * @param  mixed  $value
	 * @access public
	 * @return object fixer object
	 */
	public function add($fieldName, $value)
	{
		$this->data->$fieldName = $value;
		return $this;
	}

	/**
	 * 如果条件为真，则为数据添加新的项。
	 * Add an item to the data on the condition if true.
	 *
	 * @param  bool   $condition
	 * @param  string $fieldName
	 * @param  mixed  $value
	 * @access public
	 * @return object fixer object
	 */
	public function addIF($condition, $fieldName, $value)
	{
		if($condition) $this->data->$fieldName = $value;
		return $this;
	}

	/**
	 * 为指定字段增加值。
	 * Join the field.
	 *
	 * @param  string $fieldName
	 * @param  string $value
	 * @access public
	 * @return object fixer object
	 */
	public function join($fieldName, $value)
	{
		if(!isset($this->data->$fieldName) or !is_array($this->data->$fieldName)) return $this;
		$this->data->$fieldName = join($value, $this->data->$fieldName);
		return $this;
	}

	/**
	 * 调用一个方法来处理数据。
	 * Call a function to fix it.
	 *
	 * @param  string $fieldName
	 * @param  string $func
	 * @access public
	 * @return object fixer object
	 */
	public function callFunc($fieldName, $func)
	{
		$fields = $this->processFields($fieldName);
		foreach($fields as $fieldName) $this->data->$fieldName = filter_var($this->data->$fieldName, FILTER_CALLBACK, array('options' => $func));
		return $this;
	}

	/**
	 * 处理完成后返回数据。
	 * Get the data after fixing.
	 *
	 * @param  string $fieldName
	 * @access public
	 * @return object
	 */
	public function get($fields = '')
	{
		$fields = str_replace(' ', '', trim($fields));
		foreach($this->data as $field => $value) $this->specialChars($field);

		if(empty($fields)) return $this->data;
		if(strpos($fields, ',') === false) return $this->data->$fields;

		$fields = array_flip(explode(',', $fields));
		foreach($this->data as $field => $value)
		{
			if(!isset($fields[$field])) unset($this->data->$field);
			if(!in_array($field, $this->stripedFields)) $this->data->$field = $this->specialChars($this->data->field);
		}

		return $this->data;
	}

	/**
	 * 处理字段，如果字段中含有','，拆分成数组。如果字段不在$data中，删除掉。
	 * Process fields, if contains ',', split it to array. If not in $data, remove it.
	 *
	 * @param  string $fields
	 * @access public
	 * @return array
	 */
	public function processFields($fields)
	{
		$fields = strpos($fields, ',') ? explode(',', str_replace(' ', '', $fields)) : array($fields);
		foreach($fields as $key => $fieldName) if(!isset($this->data->$fieldName)) unset($fields[$key]);
		return $fields;
	}
}