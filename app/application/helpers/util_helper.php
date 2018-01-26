<?php


/**
 * Header Redirect
 *
 * Header redirect in two flavors
 * For very fine grained control over headers, you could use the Output
 * Library's set_header() function.
 *
 * @access    public
 * @param    string    the URL
 * @param    string    the method: location or redirect
 * @return    string
 */
if (!function_exists('redirect')) {
    function redirect($uri = '', $method = 'location', $http_response_code = 301)
    {
        if (!preg_match('#^https?://#i', $uri)) {
            $host       = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . "://" . $_SERVER["HTTP_HOST"];
            $scriptPath = preg_match("/index.php/", $_SERVER["REQUEST_URI"]) ? $_SERVER["SCRIPT_NAME"] . "/" : str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);

            $uri = $host . $scriptPath . $uri;
        }

        switch ($method) {
            case 'refresh':
                header("Refresh:0;url=" . $uri);
                break;
            default:
                header("Location: " . $uri, TRUE, $http_response_code);
                break;
        }

        exit;
    }
}


/**
 * 递归方式的对变量中进行清理
 * 1、trim
 *
 * @param   mix $value
 * @return  mix
 */
function clean_chars_deep($value)
{
    if (empty($value)) {
        return $value;
    } else {
        return is_array($value) ? array_map("clean_chars_deep", $value) : trim($value);
    }
}


/**
 * 递归方式的对变量htmlspecialchars
 */
function htmlspecialcharsRecurs($value)
{
    if (empty($value)) {
        return $value;
    } else {
        if (is_array($value)) {
            return array_map("htmlspecialcharsRecurs", $value);
        } else {
            return is_string($value) ? htmlspecialchars(trim($value)) : $value;
        }
    }
}

/**
 * 根据ua判断是否是手机访问
 *
 * @return boolean
 */
function is_mobile()
{
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : "";
    $all_http   = isset($_SERVER['ALL_HTTP']) ? strtolower($_SERVER['ALL_HTTP']) : '';

    if (empty($user_agent)) {
        return false;
    }
    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', $user_agent)) {
        return true;
    }
    if ((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false)) {
        return true;
    }

    $mobile_ua     = substr($user_agent, 0, 4);
    $mobile_agents = array(
        'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
        'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
        'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
        'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
        'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
        'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
        'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
        'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
        'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-'
    );

    if (in_array($mobile_ua, $mobile_agents) || strpos($all_http, 'operamini') !== false) {
        return true;
    }

    return false;
}


/**
 * 浏览器友好的变量输出
 *
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo = true, $label = null, $strict = true)
{
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);

        return null;
    } else
        return $output;
}


/**
 * curl加强版
 *
 * @param string $url
 * @param array $post
 * @param int $timeout
 * @param string $ref
 * @param string $ua
 * @param string $error
 * @return array
 */
function xcurl($url, $post = array(), $timeout = 5, $ref = null, $ua = "Mozilla/5.0 (X11; Linux x86_64; rv:2.2a1pre) Gecko/20110324 Firefox/4.2a1pre", &$error = '')
{
    $ch = curl_init();

    if (!empty($ref)) {
        curl_setopt($ch, CURLOPT_REFERER, $ref);
    }

    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    if (!empty($ua)) {
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    }

    if (count($post) > 0) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }

    $output = curl_exec($ch);
    if ($output === false) {
        $error = curl_error($ch);
        curl_close($ch);

        return;
    } else {
        curl_close($ch);

        return $output;
    }
}


/**
 * +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 * +----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型 1 字母 2 数字 其它 混合
 * @param string $addChars 额外字符
 * +----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function rand_string($len = 10, $type = '', $addChars = '')
{
    $str = '';
    switch ($type) {
        case 1:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 2:
            $chars = str_repeat('0123456789', 3);
            break;
        case 3:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
            break;
        case 4:
            $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        default:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789' . $addChars;
            break;
    }

    if ($len > 10) { // 位数过长重复字符串一定次数
        $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
    }

    $chars = str_shuffle($chars);
    $str   = substr($chars, 0, $len);


    return $str;
}


/**
 * +----------------------------------------------------------
 * 获取登录验证码 默认为4位数字
 * +----------------------------------------------------------
 * @param string $fmode 文件名
 * +----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function build_verify($length = 4, $mode = 2)
{
    return rand_string($length, $mode);
}


/**
 * +----------------------------------------------------------
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 * +----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function byte_format($size, $dec = 2)
{
    $a   = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }

    return round($size, $dec) . " " . $a[$pos];
}

/**
 * 将一个字串中含有全角的数字字符、字母、空格或'%+-()'字符转换为相应半角字符
 *
 * @access  public
 * @param   string $str 待转换字串
 *
 * @return  string       $str         处理后字串
 */
function makeSemiangle($str)
{
    $arr = array(
        '０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4', '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
        'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E', 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
        'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O', 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
        'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y', 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
        'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i', 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
        'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's', 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
        'ｙ' => 'y', 'ｚ' => 'z', '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[', '】' => ']', '〖' => '[', '〗' => ']',
        '“' => '[', '”' => ']', '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<', '》' => '>', '％' => '%', '＋' => '+',
        '—' => '-', '－' => '-', '～' => '-', '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.', '；' => ',', '？' => '?',
        '！' => '!', '…' => '-', '‖' => '|', '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"', '　' => ' '
    );

    return strtr($str, $arr);
}


/**
 * 获取客户端IP地址
 *
 */
function getClientIp()
{
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
        $ip = getenv("REMOTE_ADDR");
    } elseif (isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) {
        $ip = $_SERVER["REMOTE_ADDR"];
    } else {
        $ip = "unknown";
    }

    return $ip;
}


/**
 * +----------------------------------------------------------
 * 检查字符串是否是UTF8编码
 * +----------------------------------------------------------
 * @param string $string 字符串
 * +----------------------------------------------------------
 * @return Boolean
+----------------------------------------------------------
 */
function is_utf8($string)
{
    return preg_match('%^(?:
         [\x09\x0A\x0D\x20-\x7E]            # ASCII
       | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
       |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
       |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);
}


/**
 * 输出安全的html
 *
 * @param String $text 需要过滤的字符串
 * @param Array $tags 可以不过滤的标签
 */
function h($text, $tags = null)
{
    $text = trim($text);

    $text = preg_replace('/<!--?.*-->/', '', $text);        // 完全过滤注释
    $text = preg_replace('/<\?|\?' . '>/', '', $text);      // 完全过滤动态代码
    $text = preg_replace('/<script?.*\/script>/', '', $text);    // 完全过滤js
    $text = str_replace('[', '&#091;', $text);
    $text = str_replace(']', '&#093;', $text);
    $text = str_replace('|', '&#124;', $text);
    $text = preg_replace('/\r?\n/', '', $text);                    // 过滤换行符
    $text = preg_replace('/<br(\s\/)?' . '>/i', '[br]', $text);    // br
    $text = preg_replace('/(\[br\]\s*){10,}/i', '[br]', $text);

    // 过滤危险的属性，如：过滤on事件lang js
    while (preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1], $text);
    }
    while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1] . $mat[3], $text);
    }

    if (empty($tags)) {
        $tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a';
    }

    $text = preg_replace('/<(' . $tags . ')( [^><\[\]]*)>/i', '[\1\2]', $text);    // 允许的HTML标签
    // 过滤多余html
    $text = preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i', '', $text);
    while (preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i', $text, $mat)) {             // 过滤合法的html标签
        $text = str_replace($mat[0], str_replace('>', ']', str_replace('<', '[', $mat[0])), $text);
    }
    // 转换引号
    while (preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1] . '|' . $mat[3] . '|' . $mat[4], $text);
    }
    // 过滤错误的单个引号
    while (preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i', $text, $mat)) {
        $text = str_replace($mat[0], str_replace($mat[1], '', $mat[0]), $text);
    }
    // 转换其它所有不合法的 < >
    $text = str_replace('<', '&lt;', $text);
    $text = str_replace('>', '&gt;', $text);
    $text = str_replace('"', '&quot;', $text);
    // 反转换
    $text = str_replace('[', '<', $text);
    $text = str_replace(']', '>', $text);
    $text = str_replace('|', '"', $text);
    // 过滤多余空格
    $text = str_replace('  ', ' ', $text);

    return $text;
}


/**
 * 消除xss信息
 *
 * @param String $val
 */
function remove_xss($val)
{
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array(
        'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint',
        'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged',
        'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange',
        'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave',
        'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize',
        'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'
    );
    $ra  = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern     .= '/i';
            $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag

            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {    // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }

    return $val;
}


/**
 * +----------------------------------------------------------
 * 把返回的数据集转换成Tree
 * +----------------------------------------------------------
 * @access public
 * +----------------------------------------------------------
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * +----------------------------------------------------------
 * @return array
+----------------------------------------------------------
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
    $tree = array();             // 创建Tree
    if (is_array($list)) {
        $refer = array();        // 创建基于主键的数组引用
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }

        foreach ($list as $key => $data) {        // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent           = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }

    return $tree;
}


/**
 * +----------------------------------------------------------
 * 对查询结果集进行排序
 * +----------------------------------------------------------
 * @access public
 * +----------------------------------------------------------
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * +----------------------------------------------------------
 * @return array
+----------------------------------------------------------
 */
function list_sort_by($list, $field, $sortby = 'asc')
{
    if (is_array($list)) {
        $refer = $resultSet = array();

        foreach ($list as $i => $data) {
            $refer[$i] = &$data[$field];
        }

        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc': // 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }

        foreach ($refer as $key => $val) {
            $resultSet[] = &$list[$key];
        }

        return $resultSet;
    }

    return false;
}


/**
 * 自动转换字符集 支持数组转换
 *
 * @param mix $fContents
 * @param String $from
 * @param String $to
 */
function auto_charset($fContents, $from = 'gbk', $to = 'utf-8')
{
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to   = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;

    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {     // 如果编码相同或者非字符串标量则不转换
        return $fContents;
    }

    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key             = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key) {
                unset($fContents[$key]);
            }
        }

        return $fContents;
    } else {
        return $fContents;
    }
}

if (!function_exists('getimageurl')) {
    function getimageurl($rdir)
    {
        if (!$rdir) {
            return "";
        }

        if (strtolower(substr($rdir, 0, 4)) == 'http') {
            return $rdir;
        }

        return Yaf_Application::app()->getConfig()->toArray()["upload"]['image_domain'] . $rdir;
    }
}

if (!function_exists('mkdirs')) {
    function mkdirs($dir)
    {
        return is_dir($dir) or (mkdirs(dirname($dir)) and mkdir($dir, 0777));
    }
}

if (!function_exists('uploadimage')) {
    function uploadimage($file, $dir = 'advert', $targetname = '')
    {
        if (false == chkUpLoadType($file['type'])) {
            return false;
        }

        $userfile_name = $file['name'];
        $userfile_tmp  = $file['tmp_name'];
        $realname      = basename($userfile_name);

        $new_tmp_name = gettimeofday();
        $object_id    = $new_tmp_name['sec'] . $new_tmp_name['usec'] . '_' . date("mdH");
        $upload_paths = mkPhotoDir($object_id, $dir);
        $upload_path  = $upload_paths['full'];

        $new_file = md5($object_id);
        if (false === strrpos($realname, '.')) {
            $ext = '.jpg';
        } else {
            $ext = strtolower(substr($realname, strrpos($realname, '.')));
        }
        $new_file   .= $ext;
        $sourcefile = '/' . $dir . '/' . $upload_paths['path'] . $new_file;

        $yuan_file  = $upload_path . 'yuan_' . $new_file;
        $uploadfile = $upload_path . $new_file;

        $upload_status = move_uploaded_file($userfile_tmp, $uploadfile);
        if ($upload_status) {
            copy($uploadfile, $yuan_file);

            return array('sourcefile' => $sourcefile);
        }

        return false;
    }
}

/**
 * ajax 返回的json串
 *
 * $param @data
 * @return string
 */
if (!function_exists("ajax_success")) {
    function ajax_success($data, $msg = "")
    {
        $ret = array(
            'errcode' => 0,
            'msg'     => $msg,
            'data'    => $data,
        );

        header('Content-Type:application/json; charset=utf-8');

        exit(json_encode($ret));
    }
}

/**
 * ajax 返回的json串
 *
 * $param @data
 * @return string
 */
if (!function_exists("ajax_fail")) {
    function ajax_fail($data = [], $msg = "", $errcode)
    {
        $ret = array(
            'errcode' => $errcode,
            'msg'     => $msg,
            'data'    => $data,
        );

        header('Content-Type:application/json; charset=utf-8');

        exit(json_encode($ret));
    }
}


function chkUpLoadType($userfile_type)
{
    $allowed_image_types = array(
        'image/pjpeg' => "jpeg",
        'image/jpeg'  => "jpeg",
        'image/pjpeg' => "jpg",
        'image/jpeg'  => "jpg",
        'image/jpg'   => "jpg",
        'image/png'   => "png",
        'image/x-png' => "png",
        'image/gif'   => "gif"
    );
    foreach ($allowed_image_types as $mime_type => $ext) {
        if ($userfile_type == $mime_type) {
            return true;
        }
    }

    return false;
}

function mkPhotoDir($uid, $type = 'photo')
{
    $md5str = md5($uid);
    $path1  = $md5str{0};
    $path2  = $md5str{1};
    $path3  = $md5str{31};
    //$upload_path    =  SUPERMAN_UPLOAD_PATH . $type;
    $image_path  = Yaf_Application::app()->getConfig()->toArray()["upload"]['image_path'];
    $upload_path = $image_path . $type;

    if (!file_exists($image_path)) mkdir($image_path);

    $rpath = $upload_path . '/' . $path1 . "/" . $path2 . "/" . $path3;
    if (file_exists($rpath)) {
        $full_path = $rpath;
    } else {
        if (!file_exists($upload_path)) {
            mkdir($upload_path);
        }
        chdir($upload_path);
        $full_path = $upload_path . '/' . $path1;
        if (!file_exists($full_path)) {
            mkdir($path1);
        }
        chdir($path1);
        $full_path .= '/' . $path2;
        if (!file_exists($full_path)) {
            mkdir($path2);
        }
        chdir($path2);
        $full_path .= '/' . $path3;
        if (!file_exists($full_path)) {
            mkdir($path3);
        }
    }

    return array('full' => $full_path . '/', 'path' => $path1 . '/' . $path2 . '/' . $path3 . '/');
}

function validMobile($mobile)
{
    return preg_match('/^(\+?86)?1\d{10}$/', $mobile) ? true : false;
}

if (!function_exists("is_json")) {
    function is_json($string)
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (!function_exists("sendInviteMail")) {
    /**
     * 发送邮件
     *
     * @param string $subject
     * @param string $body
     * @param string $to
     * @param string $fromName
     * @return boolean
     */
    function sendInviteMail($subject, $body, $to, $fromName = '豆果美食')
    {
        $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
        $message = '<html><head><title>' . $subject . '</title></head><body>';
        $message .= $body;
        $message .= "<p><font color=\"gray\">品质生活 豆果打造</font></p>";
        $message .= "<p><font color=\"gray\">©豆果美食, 2017.</font></p>";
        $message .= "<p>此邮件为系统自动发送，请勿回复。</p>";
        $message .= '</body></html>';

        $phpMailer = new Util_PHPMailer(true);

        $phpMailer->IsHTML(true);
        $phpMailer->SMTPAuth = true;
        $phpMailer->WordWrap = 80;
        $phpMailer->CharSet  = 'utf-8';
        $phpMailer->Mailer   = "smtp";

        $smtpConfig = Yaf_Application::app()->getConfig()->toArray()["smtp"];

        $phpMailer->Host     = $smtpConfig["host"];
        $phpMailer->Username = $smtpConfig["username"];
        $phpMailer->Password = $smtpConfig["password"];
        $phpMailer->From     = $smtpConfig["username"];
        $phpMailer->FromName = $fromName;

        $to = is_array($to) ? $to : array($to);
        foreach ($to as $item) {
            $phpMailer->AddAddress($item);
        }

        $phpMailer->Subject = $subject;
        $phpMailer->AltBody = "-";
        $body               = preg_replace('/\\\\/', '', $message);
        $phpMailer->MsgHTML($body);

        try {
            return $phpMailer->Send();
        }
        catch (phpmailerException $e) {
            $args = ["to" => $to];
            Dlog::warning("sending email failed, errmsg: " . $e->getMessage(), 0, $args);

            return false;
        }
    }
}

if (!function_exists("diff_date")) {
    function diff_date($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp     = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }

        return round(($second1 - $second2) / 86400);
    }
}

if (!function_exists("get_absolute_url")) {
    /**
     * 获取绝对路径
     *
     * @param $url
     * @return string
     */
    function get_absolute_url($url)
    {
        return Yaf_Application::app()->getConfig()->toArray()["website"]['domain'] . $url;
    }
}


if (!function_exists("is_ajax_request")) {
    /**
     * 判断是否是ajax请求
     *
     * @param $url
     * @return string
     */
    function is_ajax_request()
    {
        return isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest" ? true : false;
    }
}

if (!function_exists('get_template_download')) {
    function get_template_download()
    {
        return Yaf_Application::app()->getConfig()->toArray()["upload"]['image_domain'] . '/advert_user/2017豆果美食移动客户端数据推广服务开通确认函.doc';
    }
}

if (!function_exists('get_style_list'))
{
    function get_style_list()
    {
        return Yaf_Application::app()->getConfig()->toArray()["style"]['style_list'];
    }
}