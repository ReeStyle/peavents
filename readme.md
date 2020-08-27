# Peavents

Basic event system.

Basic usage:
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