<?php if ( $global_settings->responsive_enabled ) : ?>

@media ( min-width: <?php echo $global_settings->medium_breakpoint . 'px'; ?> ) {
<?php
    for ( $i = 1; $i <= absint( $settings->columns ); $i++ ) { 
        echo ".fl-node-$id .wpzabb-image-grid.columns-desktop-$i .wpzabb-image-grid-items li {
            width: calc( ( 100% / $i ) - 30px );
        }\n";
    }
?>
}

@media ( max-width: <?php echo $global_settings->medium_breakpoint . 'px'; ?> ) {
<?php
    for ( $i = 1; $i <= absint( $settings->columns_medium ); $i++ ) { 
        echo ".fl-node-$id .wpzabb-image-grid.columns-tablet-$i .wpzabb-image-grid-items li {
            width: calc( ( 100% / $i ) - 30px );
        }\n";
    }
?>
}

@media ( max-width: <?php echo $global_settings->responsive_breakpoint . 'px'; ?> ) {
<?php
    for ( $i = 1; $i <= absint( $settings->columns_responsive ); $i++ ) { 
        echo ".fl-node-$id .wpzabb-image-grid.columns-phone-$i .wpzabb-image-grid-items li {
            width: calc( ( 100% / $i ) - 30px );
        }\n";
    }
?>
}

<?php endif; ?>