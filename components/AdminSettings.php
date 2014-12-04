<?php

namespace wma\components;

use wmu\models\User;
use yii\base\InvalidConfigException;

class AdminSettings extends \yii\base\Component
{
    private $_template = [
        'theme' => 'default',
        'navStyle' => 'default',
        'fixedLayout' => 'none',
        'fixedFooter' => false,
        'fixedWidth' => false
    ];
    private $_user = [
        'sessionDuration' => 14400,
        'register' => [
            'webRegistration' => false,
            'newUserStatus' => User::STATUS_NEW,
            'newUserRole' => User::ROLE_USER,
            'confirmEmail' => true
        ]
    ];

    public function setAdminSettings($settings) {
        foreach ($settings as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * Set admin template options. Reference SmartAdmin documentation for behaviors.
     * The fixedFooter,and fixedWidth booleans can be combined though the fixedWidth toggle
     * does not work with the 'header+nav' or 'header+nav+ribbon' fixedLayout options.
     * options array[
     *  'theme' => (default|dark-elegance|ultra-white|google),
     *  'navStyle' => (default|minified|hidden|top),
     *  'fixedLayout' => (none|header|header+nav|header+nav+ribbon)
     *  'fixedWidth' => bool (defaults to false),
     *  'fixedFooter' => bool (defaults to false),
     *
     * ]
     * @param $options array set template options via array config
     */

    public function setTemplate($options) {
        if (is_array($options)) {
            foreach ($options as $key => $val){
                if (   ($key == 'theme' && in_array($val,['dark-elegance','ultra-white','google']))
                    || ($key == 'navStyle' && in_array($val,['minified','hidden','top']))
                    || ($key == 'fixedLayout' && in_array($val,['header','header+nav','header+nav+ribbon']))
                ) {
                    $this->_template[$key] = $val;
                } else if (($key == 'fixedFooter' || $key == 'fixedWidth') && is_bool($val)) {
                    $this->_template[$key] = $val;
                }
            }
            // Ensure fixed-layout != header+nav or header+nav+ribbon if fixed-width is true
            if (    $this->_template['fixedWidth'] === true
                && ($this->_template['fixedLayout'] == 'header+nav'
                    || $this->_template['fixedLayout'] == 'header+nav')
            ) {
                $this->_template['fixedLayout'] = 'none';
            }
        }
    }

    /**
     * Set admin user options.
     * options [
     *  'enableAutoLogin' => bool (default set in wma\web\Application),
     *  'sessionDuration' => int (seconds) (default *varies based on allowCookies* set in wma\web\Application),
     *  'register' => [
     *      'webRegistration' -> bool (allow new admin users via web registration form),
     *      'confirmEmail' => bool (send account confirmation email to change status from new to active)
     *                             Only applies when webRegistration is set to true,
     *      'newUserStatus' => int (-1:Deleted|0:New|1:Active),
     *      'newUserRole' => int (1:User->255:SuperAdmin),
     *   ]
     * ]
     * @param $options array set template options via array config
     */

    public function setUser($options) {
        if (is_array($options)) {
            if (isset($options['register'])) {
                //register
                foreach ($options['register'] as $key => $val) {
                    if (
                        ($key == 'newUserStatus' && is_int($val)
                            && $val >=  User::STATUS_DELETED && $val <= User::STATUS_ACTIVE)
                        ||
                        ($key == 'newUserRole' && is_int($val)
                            && $val >= User::ROLE_USER && $val <= User::ROLE_SUPERADMIN)
                    ) {
                        $this->_user['register'][$key] = $val;
                    } else {
                        if (
                            ($key == 'webRegistration' || $key == 'confirmEmail')
                            && is_bool($val)
                        ) {
                            $this->_user['register'][$key] = $val;
                        }
                    }
                }
            }
        }
    }

    public function getOption($index) {
        $parts = explode('.', $index);
        $type = '_' . array_shift($parts);
        if (isset($this->$type)) {
            if (count($parts) > 0) {
                $property = $this->$type;
                while (count($parts) > 0) {
                    $val = array_shift($parts);
                    $property = $property[$val];
                }
                return $property;
            }
        }
        throw new InvalidConfigException('No property found at ' . $index . '.');
    }

}