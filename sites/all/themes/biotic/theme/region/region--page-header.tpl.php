<!-- This is the header regions -->

<?php 
    $load= array();
    // $block = module_invoke('user','block_view' ,'new','138');
    $load[] = block_load('delta_blocks','page-title');
    $load[] = block_load('delta_blocks','breadcrumb'); 

    $r = _block_render_blocks( $load );
    $v = _block_get_renderable_array($r);
    // drupal_render($v);

    // dpm( $block);
    // dpm( $load);
    // $r['css_class'] = "new classes";

    foreach ($r as $value) {
        $predefined = explode(" ", $value->css_class);
        $custom = array('new-class','other-classes');
        // Add Custom Classes;
        $value->css_class = implode(" ", array_merge($predefined, $custom ) );
    }

    // dpm( $r);
    // dpm( $v);
    // 
    // $module = "delta_blocks";
    // $delta = "page-title";

    // $block = block_load($module, $delta);
    // $render_array = _block_get_renderable_array(_block_render_blocks(array($block)));
    // $output = render($render_array);

    // dpm($output);
    // print render($v);

    print $content;
    
 ?>