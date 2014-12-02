<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Sheepy85\Traits\SanitizerTrait;

class Search extends FormRequest {

    use SanitizerTrait;

    public function sanitizeAfterValidator() {
        return false;
    }

    public function sanitizeRules() {
        return [
            'search' => 'trim|strtoupper|test'
        ];
    }

    public function rules() {
        return [
            'search' => 'min:3' ,
        ];
    }

    protected function sanitizeTest( $value ) {
        return $value . 'TEST';
    }

}
