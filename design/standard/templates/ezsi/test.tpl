<h3>test 1 : simple call to {ldelim}si-block{rdelim}, no key, no ttl</h3>
{si-block}
    test 1 : block content
{/si-block}

<h3>test 2 : simple call to {ldelim}si-block{rdelim}, one key, no ttl</h3>
{si-block key='keytest' ttl=''}
    test 2 : block content
{/si-block}

<h3>test 3 : simple call to {ldelim}si-block{rdelim}, no key, one ttl</h3>
{si-block ttl='4h'}
    test 3 : block content
{/si-block}

<h3>test 4 : simple call to {ldelim}si-block{rdelim}, no keys, no ttl</h3>
{si-block key='keytest4' ttl='4h'}
    test 4 : block content
{/si-block}

<h3>test 5 : simple call to {ldelim}si-block{rdelim}, empty key, one ttl</h3>
{si-block key='' ttl='4h'}
    test 5 : block content
{/si-block}

<h3>test 6 : simple call to {ldelim}si-block{rdelim}, empty key, empty ttl</h3>
{si-block key='' ttl=''}
    test 6 : block content
{/si-block}

<h3>test 7 : simple call to {ldelim}si-block{rdelim}, one key, ttl = zero </h3>
{si-block key='keytest7' ttl='2s'}
    test 7 : block content
{/si-block}

<h3>test 8 : simple call to {ldelim}si-block{rdelim}, non scalar key, one ttl </h3>
{si-block key='keytest8' ttl='2s'}
    test 8 : block content
{/si-block}

<h3>test 9 : key as a variable</h3>
{def $block_key_part_1='mykey'}
{def $block_key_part_2='foo'}
{def $final_block_key=concat( $block_key_part_1, $block_key_part_2 )}

{si-block key=$final_block_key ttl='2s'}
    test 9 : block content
{/si-block}

{undef $block_key_part_1}
{undef $block_key_part_2}
{undef $final_block_key}

<h3>test 10 : key as an array</h3>
{si-block key=array( 1, 23, 'rrt' ) ttl='2s'}
    test 10 : block content
{/si-block}

<h3>test 11: key as a hash</h3>
{si-block key=hash( 'foo', 'bar' ) ttl='2s'}
    test 11 : block content
{/si-block}

