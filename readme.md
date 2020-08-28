# Peavents

## Introduction
<blockquote>
Peavents stands for PHP events - with a twist. I just thought throwing in a pea would be fun... :)
</blockquote>

Peavents is a basic event system. Sort of like a hook system, but slightly different.

## Requirements

- PHP 7.0+ 

This project makes use of datatype type hinting and return value type.
Both classes are based on ArrayObject classes.

## Installation
Use composer:

`$ composer require reestyle/peavents`

Remember to use the autoloader.

## Basic usage:
<pre>
    $registry = \Peavents\Registry::instance();
    
    $event = $registry->make('event_name');
    
    $event
        ->setParams(['hello world'])
        ->attach(function($a) {
            print $a;
        })
        ->attach(function($a) {
            print ' - and another ' . $a;
        });
        
    $registry->raise('event_name');
</pre>