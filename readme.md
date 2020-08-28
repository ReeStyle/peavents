# Peavents

## Introduction
<blockquote>
Peavents stands for PHP events - with a twist.
</blockquote>

Peavents is a basic event system. Sort of like a hook system, but slightly different.

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