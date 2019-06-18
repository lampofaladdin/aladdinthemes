<?php /**
 * 头部文件
 * @category WordPress
 * @package  aladdinThemes
 * @author   aladdin
 * @license  MIT
 * */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <title><?php the_title(); ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <meta name="renderer" content="webkit"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>">
        <?php wp_head();?>
    </head>
<body>



