<?php
    # Side panel
    echo $HTML->side_panel_start();

    echo $HTML->para('In the sidebar you should try and give the user guidance and tips.');
    echo $HTML->para('For content editing pages, presume the user is non-technical editor using the software.');
    echo $HTML->para('For configuration pages, presume the user is the web designer who is setting up the site.');

    echo $HTML->side_panel_end();

    # Main panel
    echo $HTML->main_panel_start();
    include('_subnav.php');
    
    echo $HTML->heading1('About the Algolia Sync app');

    // if (isset($message)) echo $message;
    echo '<p>The <em>Algolia Sync</em> app communicates with Algolia&apos;s search API to show autocomplete suggestions.<br/><strong>All settings for Algolia Sync are stored in <a href="/perch/core/settings#jaygeorge_perch_algolia_sync">Settings</a></strong>.<p/><p>This app was developed by Jay George.</p>';

    echo $HTML->heading1('Notes');
    echo '<p><sub>v. 2017-12-20-105001</sub></p>';

    if (PerchUtil::count($things)) {

    /* ----------------------------------------- SMART BAR ----------------------------------------- */
    ?>
    <ul class="smartbar">
        <li class="selected"><a href="<?php echo PerchUtil::html($API->app_path()); ?>"><?php echo $Lang->get('All'); ?></a></li>
    </ul>
    <?php
    /* ----------------------------------------- /SMART BAR ----------------------------------------- */


?>
    <table class="d">
        <thead>
            <tr>
                <th class="first"><?php echo $Lang->get('Title'); ?></th>  
                <th><?php echo $Lang->get('Date'); ?></th>
                <th class="action last"></th>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($things as $Thing) {
?>
            <tr>
                <td class="primary"><a href="<?php echo $HTML->encode($API->app_path()); ?>/edit/?id=<?php echo $HTML->encode(urlencode($Thing->id())); ?>"><?php echo $HTML->encode($Thing->algoliaTitle()); ?></a></td>
                <td><?php echo $HTML->encode(strftime('%d %B %Y, %l:%M %p', strtotime($Thing->algoliaDateTime()))); ?></td>
                <td><a href="<?php echo $HTML->encode($API->app_path()); ?>/delete/?id=<?php echo $HTML->encode(urlencode($Thing->id())); ?>" class="delete inline-delete"><?php echo $Lang->get('Delete'); ?></a></td>
            </tr>
<?php   
    }
?>
        </tbody>
    </table>
<?php    
       

    } // if things
    
    echo $HTML->main_panel_end();
