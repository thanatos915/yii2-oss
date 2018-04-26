<?php
/**
 * @user: thanatos <thanatos915@163.com>
 */

namespace thanatos\oss\result;


use yii\base\Model;

class Result extends Model
{
    /** @var string 生成Response的服务器 */
    public $server;
    /** @var string HTTP 1.1协议中规定的GMT时间 */
    public $date;
    /** @var string 并唯一标识这个response的UUID */
    public $x_oss_request_id;
    /** @var string 资源URL */
    public $oss_request_url;

    public function __set($name, $value)
    {
        $pro = str_replace('-', '_', strtolower($name));
        if (property_exists($this, $pro)) {
            $this->$pro = $value;
        }
        try {
            return parent::__set($pro, $value);
        } catch (\Throwable $throwable) {
            return false;
        }
    }
}