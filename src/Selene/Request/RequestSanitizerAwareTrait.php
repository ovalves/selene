<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-16
 */

namespace Selene\Request;

use Selene\Request\RequestAbstract;
use Selene\Request\RequestException;

trait RequestSanitizerAwareTrait
{
    public function sanitize(array $fields = []): array
    {
        if (!$this instanceof RequestAbstract) {
            throw new RequestException('Unsupported class type for sanitization.');
        }

        $data = $this->all();

        $inputAsArray = [];
        $onlyDefaultValue = [];

        foreach ($fields as $key => $value) {
            if (is_string($key)) {
                $onlyDefaultValue[$key] = $value;
                arr_set($inputAsArray, $key, $value);
                continue;
            }

            arr_set($inputAsArray, $value, true);
        }

        $data = $this->recursiveArrayIntersectKey($data, $inputAsArray);

        foreach ($onlyDefaultValue as $key => $value) {
            $data = arr_add($data, $key, $value);
        }

        return $data;
    }

    private function recursiveArrayIntersectKey(array $left, array $rigth): array
    {
        $left = array_intersect_key($left, $rigth);

        foreach ($left as $key => &$value) {
            if (is_array($value) && is_array($rigth[$key])) {
                $value = $this->recursiveArrayIntersectKey($value, $rigth[$key]);
            }
        }

        return $left;
    }
}
