<?php

function shortcode_function()
{
	return    do_shortcode('[wpuf_form id="7"]');
}
add_shortcode( 'shortcode', 'shortcode_function' );
