<?php

namespace MyPluginNamespace\App\Http\Controllers;

use DateTime as PhpDateTime;

trait DateTime {
    private $format = 'Y-m-d';

    protected function date_validator( string $input_name, string $format = '' ) {
        if ( ! $this->wp_rest_request->has_param( $input_name ) ) {
            return;
        }

        $value = $this->wp_rest_request->get_param( $input_name );

        if ( ! empty( $format ) ) {
            $this->format = $format;
        }

        $format = $this->get_format();
    
        if ( ! empty( $value ) && $this->is_it_valid_date( $value, $format ) ) {
            return;
        }

        $message = $this->get_message( 'date' );
        $message = (string) str_replace( ':attribute', $input_name, $message );

        $this->errors[$input_name][] = $message;
    }

    public function date_equals_validator( string $input_name, $date ) {
        if ( ! $this->wp_rest_request->has_param( $input_name ) ) {
            return;
        }

        $value = $this->wp_rest_request->get_param( $input_name );

        if ( ! empty( $value ) ) {
            $format = $this->get_format();

            if ( ! $this->is_it_valid_date( $value, $format ) ) {
                return;
            }

            $timestamp       = $this->get_timestamp( $date, $format );
            $input_timestamp = $this->get_timestamp( $value, $format );

            if ( $input_timestamp === $timestamp ) {
                return;
            }
        }

        $message = $this->get_message( 'date_equals' );
        $message = (string) str_replace( [':attribute', ':date'], [$input_name, $date], $message );
        
        $this->errors[$input_name][] = $message;
    }

    public function before_validator( string $input_name, $date ) {
        if ( ! $this->wp_rest_request->has_param( $input_name ) ) {
            return;
        }

        $value = $this->wp_rest_request->get_param( $input_name );

        if ( ! empty( $value ) ) {
            $format = $this->get_format();

            if ( ! $this->is_it_valid_date( $value, $format ) ) {
                return;
            }

            $timestamp       = $this->get_timestamp( $date, $format );
            $input_timestamp = $this->get_timestamp( $value, $format );

            if ( $input_timestamp < $timestamp ) {
                return;
            }
        }

        $message = $this->get_message( 'before' );
        $message = (string) str_replace( [':attribute', ':date'], [$input_name, $date], $message );
        
        $this->errors[$input_name][] = $message;
    }

    public function after_validator( string $input_name, $date ) {
        if ( ! $this->wp_rest_request->has_param( $input_name ) ) {
            return;
        }

        $value = $this->wp_rest_request->get_param( $input_name );

        if ( ! empty( $value ) ) {
            $format = $this->get_format();

            if ( ! $this->is_it_valid_date( $value, $format ) ) {
                return;
            }

            $timestamp       = $this->get_timestamp( $date, $format );
            $input_timestamp = $this->get_timestamp( $value, $format );

            if ( $input_timestamp > $timestamp ) {
                return;
            }
        }

        $message = $this->get_message( 'after' );
        $message = (string) str_replace( [':attribute', ':date'], [$input_name, $date], $message );
        
        $this->errors[$input_name][] = $message;
    }

    protected function before_or_equal_validator( string $input_name, $date ) {
        if ( ! $this->wp_rest_request->has_param( $input_name ) ) {
            return;
        }

        $value = $this->wp_rest_request->get_param( $input_name );

        if ( ! empty( $value ) ) {
            $format = $this->get_format();

            if ( ! $this->is_it_valid_date( $value, $format ) ) {
                return;
            }

            $timestamp       = $this->get_timestamp( $date, $format );
            $input_timestamp = $this->get_timestamp( $value, $format );

            if ( $input_timestamp < $timestamp || $input_timestamp === $timestamp ) {
                return;
            }
        }

        $message = $this->get_message( 'before_or_equal' );
        $message = (string) str_replace( [':attribute', ':date'], [$input_name, $date], $message );
        
        $this->errors[$input_name][] = $message;
    }

    protected function after_or_equal_validator( string $input_name, $date ) {
        if ( ! $this->wp_rest_request->has_param( $input_name ) ) {
            return;
        }

        $value = $this->wp_rest_request->get_param( $input_name );

        if ( ! empty( $value ) ) {
            $format = $this->get_format();

            if ( ! $this->is_it_valid_date( $value, $format ) ) {
                return;
            }

            $timestamp       = $this->get_timestamp( $date, $format );
            $input_timestamp = $this->get_timestamp( $value, $format );

            if ( $input_timestamp > $timestamp || $input_timestamp === $timestamp ) {
                return;
            }
        }

        $message = $this->get_message( 'after_or_equal' );
        $message = (string) str_replace( [':attribute', ':date'], [$input_name, $date], $message );
        
        $this->errors[$input_name][] = $message;
    }

    private function is_it_valid_date( string $date, string $format ) {
        $input_date = PhpDateTime::createFromFormat( $format, $date );
        return $input_date && $input_date->format( $format ) === $date;
    }

    private function get_timestamp( string $date, string $format ) {
        $date_array = date_parse_from_format( $format, $date );
        return mktime(
            ! empty( $date_array['hour'] ) ? $date_array['hour'] : 12,
            ! empty( $date_array['minute'] ) ? $date_array['minute'] : 0,
            ! empty( $date_array['second'] ) ? $date_array['second'] : 0,
            $date_array['month'],
            $date_array['day'],
            $date_array['year']
        );
    }

    private function get_format() {
        foreach ( $this->explode_rules as $key => $value ) {
            $substrings = explode( ':', $value, 2 );
            if ( $substrings[0] !== 'date' ) {
                continue;
            }
            if ( isset( $substrings[1] ) ) {
                return $substrings[1];
            }
            return $this->format;
        }
        return $this->format;
    }
}