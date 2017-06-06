<?php
namespace filter;

/**
 * validater类，检查数据是否符合规则。
 * The validater class, checking data by rules.
 * 
 * @package framework
 */
class Validater
{
    /**
     * 最大参数个数。
     * The max count of args.
     */
    const MAX_ARGS = 3;

    /**
     * 是否是Bool类型。
     * Bool checking.
     * 
     * @param  bool $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkBool($var)
    {
        return filter_var($var, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * 是否是Int类型。
     * Int checking.
     * 
     * @param  int $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkInt($var)
    {
        $args = func_get_args();
        if($var != 0) $var = ltrim($var, 0);  // 去掉变量左边的0，00不是Int类型
        // Remove the left 0, filter don't think 00 is an int.

        /* 如果设置了最小的整数。  Min is setted.  */
        if(isset($args[1]))
        {
            /* 如果最大的整数也设置了。  And Max is setted.  */
            if(isset($args[2]))
            {
                $options = array('options' => array('min_range' => $args[1], 'max_range' => $args[2]));
            }
            else
            {
                $options = array('options' => array('min_range' => $args[1]));
            }

            return filter_var($var, FILTER_VALIDATE_INT, $options);
        }
        else
        {
            return filter_var($var, FILTER_VALIDATE_INT);
        }
    }

    /**
     * 检查不是Int类型。
     * Not int checking. 
     * 
     * @param  int    $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkNotInt($var)
    {
        return !self::checkInt($var);
    }

    /**
     * 检查Float类型。
     * Float checking.
     * 
     * @param  float  $var 
     * @param  string $decimal 
     * @static
     * @access public
     * @return bool
     */
    public static function checkFloat($var, $decimal = '.')
    {
        return filter_var($var, FILTER_VALIDATE_FLOAT, array('options' => array('decimal' => $decimal)));
    }

    /**
     * 检查Email。
     * Email checking.
     * 
     * @param  string $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkEmail($var)
    {
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }

    /**
     * 检查电话或手机号码
     * Check phone number.
     * 
     * @param  string    $var 
     * @static
     * @access public
     * @return void
     */
    public static function checkPhone($var)
    {
        return (validater::checkTel($var) or validater::checkMobile($var));
    }

    /**
     * 检查电话号码
     * Check tel number.
     * 
     * @param  int    $var 
     * @static
     * @access public
     * @return void
     */
    public static function checkTel($var)
    {
        return preg_match("/^([0-9]{3,4}-)?[0-9]{7,8}$/", $var);
    }

    /**
     * 检查手机号码
     * Check mobile number.
     * 
     * @param  string    $var 
     * @static
     * @access public
     * @return void
     */
    public static function checkMobile($var)
    {
        return preg_match("/^1[3-5,8]{1}[0-9]{9}$/", $var);
    }

    /**
     * 检查网址。
     * 该规则不支持中文字符的网址。
     *
     * URL checking. 
     * The check rule of filter don't support chinese.
     * 
     * @param  string $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkURL($var)
    {
        return filter_var($var, FILTER_VALIDATE_URL);
    }

    /**
     * 检查域名，不支持中文。
     * Domain checking. 
     *
     * The check rule of filter don't support chinese.
     * 
     * @param  string $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkDomain($var)
    {
        return preg_match('/^([a-z0-9-]+\.)+[a-z]{2,15}$/', $var);
    }

    /**
     * 检查IP地址。
     * IP checking.
     * 
     * @param  ip $var 
     * @param  string $range all|public|static|private
     * @static
     * @access public
     * @return bool
     */
    public static function checkIP($var, $range = 'all')
    {
        if($range == 'all') return filter_var($var, FILTER_VALIDATE_IP);
        if($range == 'public static') return filter_var($var, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
        if($range == 'private')
        {
            if($var == '127.0.0.1' or filter_var($var, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false) return true;
            return false;
        }
    }

    /**
     * 日期检查。注意，2009-09-31是一个合法日期，系统会将它转换为2009-10-01。
     * Date checking. Note: 2009-09-31 will be an valid date, because strtotime auto fixed it to 10-01.
     * 
     * @param  date $date 
     * @static
     * @access public
     * @return bool
     */
    public static function checkDate($date)
    {
        if($date == '0000-00-00') return true;
        $stamp = strtotime($date);
        if(!is_numeric($stamp)) return false; 
        return checkdate(date('m', $stamp), date('d', $stamp), date('Y', $stamp));
    }

    /**
     * 检查正则表达式。
     * REG checking.
     * 
     * @param  string $var 
     * @param  string $reg 
     * @static
     * @access public
     * @return bool
     */
    public static function checkREG($var, $reg)
    {
        return filter_var($var, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $reg)));
    }

    /**
     * 检查长度。
     * Length checking.
     * 
     * @param  string $var 
     * @param  string $max 
     * @param  int    $min 
     * @static
     * @access public
     * @return bool
     */
    public static function checkLength($var, $max, $min = 0)
    {
        $length = function_exists('mb_strlen') ? mb_strlen($var, 'utf-8') : strlen($var);
        return self::checkInt($length, $min, $max);
    }

    /**
     * 检查不为空。
     * Not empty checking.
     * 
     * @param  mixed $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkNotEmpty($var)
    {
        return !empty($var);
    }

    /**
     * 检查为空。
     * Empty checking.
     * 
     * @param  mixed $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkEmpty($var)
    {
        return empty($var);
    }

    /**
     * 检查用户名。
     * Account checking.
     * 
     * @param  string $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkAccount($var)
    {
        global $config;
        $accountRule = empty($config->accountRule) ? '|^[a-zA-Z0-9_]{1}[a-zA-Z0-9_\.]{1,}[a-zA-Z0-9_]{1}$|' : $config->accountRule;
        return self::checkREG($var, $accountRule);
    }

    /**
     * 检查Code。
     * Check code.
     * 
     * @param  string $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkCode($var)
    {
        return self::checkREG($var, '|^[A-Za-z0-9]+$|');
    }

    /**
     * 检查验证码。
     * Check captcha.
     * 
     * @param  mixed    $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkCaptcha($var)
    {
        if(!isset($_SESSION['captcha'])) return false;
        return $var == $_SESSION['captcha'];
    }

    /**
     * 是否等于给定的值。
     * Must equal a value.
     * 
     * @param  mixed  $var 
     * @param  mixed $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkEqual($var, $value)
    {
        return $var == $value;
    }

    /**
     * 检查不等于给定的值
     * Must not equal a value.
     * 
     * @param  mixed    $var 
     * @param  mixed    $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkNotEqual($var, $value)
    {
        return $var != $value;
    }

    /**
     * 检查大于给定的值。
     * Must greater than a value.
     * 
     * @param  mixed    $var 
     * @param  mixed    $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkGT($var, $value)
    {
        return $var > $value;
    }

    /**
     * 检查小于给定的值
     * Must less than a value.
     * 
     * @param  mixed    $var 
     * @param  mixed    $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkLT($var, $value)
    {
        return $var < $value;
    }

    /**
     * 检查大于等于给定的值
     * Must greater than a value or equal a value.
     * 
     * @param  mixed    $var 
     * @param  mixed    $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkGE($var, $value)
    {
        return $var >= $value;
    }

    /**
     * 检查小于等于给定的值
     * Must less than a value or equal a value.
     * 
     * @param  mixed    $var 
     * @param  mixed    $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkLE($var, $value)
    {
        return $var <= $value;
    }

    /**
     * 检查是否在给定的列表里面。
     * Must in value list.
     * 
     * @param  mixed  $var 
     * @param  mixed $value 
     * @static
     * @access public
     * @return bool
     */
    public static function checkIn($var, $value)
    {
        if(!is_array($value)) $value = explode(',', $value);
        return in_array($var, $value);
    }

    /**
     * 检查文件名。
     * Check file name.
     * 
     * @param  string    $var 
     * @static
     * @access public
     * @return bool
     */
    public static function checkFileName($var)
    {
        return !preg_match('/>+|:+|<+/', $var);
    }

    /**
     * 检查敏感词。
     * Check sensitive words.
     * 
     * @param  object   $vars 
     * @param  array    $dicts 
     * @static
     * @access public
     * @return void
     */
    public static function checkSensitive($vars, $dicts)
    {
        foreach($vars as $var)
        {
            if(!$var) continue;
            foreach($dicts as $dict)
            {
                if(strpos($var, $dict) === false) continue;
                if(strpos($var, $dict) !== false) return false;
            }
        }
        return true;
    }

    /**
     * 调用一个方法进行检查。
     * Call a function to check it.
     * 
     * @param  mixed  $var 
     * @param  string $func 
     * @static
     * @access public
     * @return bool
     */
    public static function call($var, $func)
    {
        return filter_var($var, FILTER_CALLBACK, array('options' => $func));
    }
}