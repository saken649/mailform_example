<?php
namespace Libs;
use Libs\Validator;

class Confirm extends Basement {
    
    private $isValid = true;
    
    public function main() {
        try {
            $this->_setAllPostsToSession();
            $rules = $this->getValidationRules();
            if (!empty($rules)) {
                foreach ($rules as $key => $rule) {
                    $data = filter_input(INPUT_POST, $key);
                    if (is_null($data)) {
                        throw new \Exception($key . ' はフォーム内に存在しない項目です。');
                    }
                    if (!Validator::validate($rule, $data, $key)) {
                        $this->isValid = false;
                    }
                }
            }
            if (!$this->isValid) {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            $this->locationToTop($e->getMessage());
        }
    }
    
    /**
     * POSTされた内容を全部セッションに放り込む
     */
    private function _setAllPostsToSession() {
        foreach ($_POST as $key => $val) {
            $_SESSION[$key] = $val;
        }
    }
}
