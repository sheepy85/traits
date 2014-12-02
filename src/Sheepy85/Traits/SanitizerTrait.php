<?php

namespace Sheepy85\Traits;

// for Laravel FormRrequest
trait SanitizerTrait {

    private function splitSanitizers( $rules ) {
        return is_array( $rules ) ? $rules : explode( '|' , $rules );
    }

    private function applySanitizers( $value , $sanitizers ) {
        foreach ( $this->splitSanitizers( $sanitizers ) as $sanitizer ) {
            $method = 'sanitize' . ucwords( $sanitizer );
            $value = method_exists( $this , $method ) ? call_user_func( [$this , $method] , $value ) : call_user_func( $sanitizer , $value );
        }

        return $value;
    }

    protected function getValidatorInstance() {
        if ( $this->sanitizeAfterValidator() ) {
            return parent::getValidatorInstance()->after( [$this , 'sanitize'] );
        }

        $this->sanitize();
        return parent::getValidatorInstance();
    }

    public function sanitize( array $rules = null ) {
        if ( is_null( $rules ) ) {
            $rules = $this->sanitizeRules();
        }

        foreach ( $rules as $field => $sanitizers ) {
            if ( $this->has( $field ) ) {
                //set new value
                $value = $this->applySanitizers( $this->input( $field ) , $sanitizers );
                $this->isMethod( 'post' ) ? $this->request->set( $field , $value ) : $this->query->set( $field , $value );
            }
        }
    }

    public function sanitizeRules() {
        return null; // [ 'name' => 'trim|strtolower|test'];
    }

    public function sanitizeAfterValidator() {
        return false;
    }

}
