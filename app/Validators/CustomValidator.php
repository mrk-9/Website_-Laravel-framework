<?php

namespace App\Validators;

use Illuminate\Validation\Validator;
use Illuminate\Support\Arr;

class CustomValidator extends Validator {

	/**
     * Validate the date is after a (not empty) given date.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateAfterIfNotEmpty($attribute, $value, $parameters)
    {
        // we only want validate if attribute's value is not empty
    	if (empty($this->getValue($parameters[0]))) {
    		return true;
    	}

        $this->requireParameterCount(1, $parameters, 'after_if_not_empty');

        if ($attribute_format = $this->getDateFormat($attribute)) {
            $parameter_format = $this->getDateFormat($parameters[0]);

            return $this->validateAfterIfNotEmptyWithFormat($parameter_format, $attribute_format, $value, $parameters);
        }

        if (!($date = strtotime($parameters[0]))) {
            return strtotime($value) > strtotime($this->getValue($parameters[0]));
        }

        return strtotime($value) > $date;
    }

    /**
     * Validate the attribute is less than a numeric value.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateLessThan($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'less_than');

        if (!is_numeric($value) || !is_numeric($this->getValue($parameters[0]))) {
            return false;
        }

        return $value < $this->getValue($parameters[0]);
    }

    /**
     * Validate the date is after a given date with given formats.
     *
     * @param  string  $format
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    protected function validateAfterIfNotEmptyWithFormat($parameter_format, $attribute_format, $value, $parameters)
    {
        $param = $this->getValue($parameters[0]) ?: $parameters[0];

        return $this->validateAfterIfNotEmptyCheckDateTimeOrder($parameter_format, $attribute_format, $param, $value);
    }

    /**
     * Given two date/time strings, check that one is after the other.
     *
     * @param  string  $format
     * @param  string  $before
     * @param  string  $after
     * @return bool
     */
    protected function validateAfterIfNotEmptyCheckDateTimeOrder($parameter_format, $attribute_format, $before, $after)
    {
        $before = $this->getDateTimeWithOptionalFormat($parameter_format, $before);
        $after = $this->getDateTimeWithOptionalFormat($attribute_format, $after);

        return ($before && $after) && ($after > $before);
    }
}
