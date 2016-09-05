<?php
/*
Plugin Name: Wrtiers Portal
Plugin URI:  http://www.lionglove.com
Description: The ultimate plugin to create a writers community
Version:     1.0
Author:      Abdimalik Mohamed
Author URI:  http://www.lionglove.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: writers-portal
*/



class WritersPortal
{

    function __construct()
    {
         add_action('admin_footer', array($this, 'image_sort' ) );
        add_action('init', array($this, 'writersportal_init' ));
        add_action('admin_init', array($this, 'writersportal_add_listofbooks_metabox' ));
        add_action('admin_init', array($this, 'writersportal_add_listofchapters_metabox' ));
        add_action('save_post', array($this, 'writersportal_save_book_field' ));
        add_action('save_post', array($this, 'writersportal_save_chapter_field' ));
        add_shortcode('writers-portal', array($this, 'writersportal_my_account_shortcode' ));
        add_action('template_redirect', array($this,  'writersportal_add_book_redirect' ));
        add_action('template_redirect', array($this,  'writersportal_add_chapter_redirect' ));
        add_action('template_redirect', array($this,  'writersportal_edit_book_redirect' ));
        add_action('template_redirect', array($this,  'writersportal_edit_chapter_redirect' ));
        add_filter('document_title_parts', array($this,  'dq_override_post_title' ), 10);

    }

    function image_sort(){  


wp_enqueue_script('jquery');
?>
<style>
    #category:focus option:first-of-type {
        display: none;
    }
</style>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
 <!-- <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> -->
<?php 
wp_enqueue_script('jquery-ui-sortable');
?>
<script type="text/javascript">

jQuery(document).ready( function(e) {

    // var Order = $('#sortable li').toArray();
    var Order = [];
    $('#sortable li').each(function(i, elem) {
        Order.push($(elem).attr('id'));
    });
    $('#order').val(Order);

    $( "#sortable" ).sortable({
            placeholder: "ui-state-highlight",
            cursor: 'crosshair',
            update: function(event, ui) {

                  var Order = $("#sortable").sortable('toArray').toString();
                  $('#order').val(Order);
             }
    });
    $( "#sortable" ).disableSelection();

});

</script>
<?php
    }

    function dq_override_post_title($title){
        
    if ($_GET['page']=='add-book') {
        $title['title'] = "add book";
    }

   // change title for singular blog post
    if( is_singular( 'post' ) ){ 
        // change title parts here
        $title['title'] = 'EXAMPLE'; 
    $title['page'] = '2'; // optional
    $title['tagline'] = 'Home Of Genesis Themes'; // optional
        $title['site'] = 'DevelopersQ'; //optional
    }
    
    return $title; 
}

function testfunction(){
    return 'mangos';
}


    function writersportal_init()
    {
        $args = array(
        'public' => true,
        'label'  => 'Books'
        );
        register_post_type('book', $args);

        $args = array(
        'public' => true,
        'label'  => 'Chapters'
        );
        register_post_type('chapter', $args);



    }

    function writersportal_add_listofbooks_metabox()
    {
        add_meta_box('writersportal_listofbooks', 'Book for Chapter', array($this, 'writersportal_book_field' ), 'chapter');
    }

    function writersportal_add_listofchapters_metabox()
    {
        add_meta_box('writersportal_listofchapters', 'Add new Chapter', array($this, 'writersportal_chapters_field' ), 'book');
    }

        function writersportal_book_field()
        {
            global $post;
            $selected_books = get_post_meta($post->ID, '_book', false);
            $all_books = get_posts(array(
                'post_type' => 'book',
                'numberposts' => -1,
                'orderby' => 'post_title',
                'order' => 'ASC'
            ));
            ?><?php //print_r($selected_books); ?>
            <input type="hidden" name="books_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
            <table class="form-table">
            <tr valign="top"><th scope="row">
            <label for="books">Books</label></th>
            <td>
            <select name="books">

            <?php foreach ($all_books as $book) : ?>
                <?php if (get_post_meta($post->ID, '_book', false)) : ?>
                    <?php

                        ?>
                <option value="<?php echo $book->ID; ?>"<?php if ($book->ID == $selected_books[0]) {
                    echo ' selected="selected"';
    } ?> > <?php echo $book->post_title; ?></option>
                <?php else : ?>
                <option value="<?php echo $book->ID; ?>"><?php echo $book->post_title; ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
            </select></td></tr>
            </table>
            <?php
        }



    function writersportal_chapters_field()
    {
        global $post;
        $selected_books = get_post_meta($post->ID, '_book', false);
        $all_books = get_posts(array(
            'post_type' => 'chapter',
            'numberposts' => -1,
            'orderby' => 'post_title',
            'order' => 'ASC'
        ));
        ?><?php //print_r($selected_books); ?>
        <input type="hidden" name="chapters_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
        <p>title: <?php $listchapters = get_post_meta($post->ID, '_chapters');  ?></p>

        <ul id="sortable" style="width: 524px;">
        <?php if($listchapters): ?>
            <?php foreach($listchapters[0] as $chapter): ?>
            <li id="<?php echo $chapter ?>" class="ui-state-default"><?php echo get_the_title($chapter); ?></li>
        <?php endforeach; ?>
        <?php endif; ?>
        </ul>
        <input name="order" type="hidden" id="order" />
        <div style="clear:both;"></div>

        
        <table class="form-table">
        <tr valign="top"><th scope="row">
        <label for="books">Chapter</label></th>
        <td>
        <select name="books" required>
 <option disabled selected>Add a new chapter</option>
        <?php foreach ($all_books as $book) : ?>
            <?php if (get_post_meta($post->ID, '_book', false)) : ?>
                <?php

                    ?>
            <option value="<?php echo $book->ID; ?>"<?php if ($book->ID == $selected_books[0]) {
                echo ' selected="selected"';
} ?> > <?php echo $book->post_title; ?></option>
            <?php else : ?>
            <option value="<?php echo $book->ID; ?>"><?php echo $book->post_title; ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
        </select></td></tr>
        </table>
        <?php
    }

    function writersportal_save_book_field($post_id)
    {

        // only run this for series
        if ('chapter' != get_post_type($post_id)) {
            return $post_id;
        }

        // verify nonce
        if (empty($_POST['books_nonce']) || !wp_verify_nonce($_POST['books_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        // save
        update_post_meta($post_id, '_book', $_POST['books']);

    }

    function writersportal_save_chapter_field($post_id)
    {

        // only run this for series
        if ('book' != get_post_type($post_id)) {
            return $post_id;
        }

        // verify nonce
        if (empty($_POST['chapters_nonce']) || !wp_verify_nonce($_POST['chapters_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
if($_POST['order'] != null){
    $arr=explode(",",$_POST['order']);
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        update_post_meta($post_id, '_chapters', $arr);
        
}

        
        if($_POST['books'] != ""){
            $listchaptersees = get_post_meta($post_id, '_chapters');
            $listchaptersee = $listchaptersees[0];
            if(!empty($listchaptersee)){

                // array_push($listchaptersee, $_POST['books']);
                $listchaptersee[] = $_POST['books'];
                echo '<pre>';
                print_r($listchaptersee);
                echo '</pre>';
                update_post_meta($post_id, '_chapters', $listchaptersee);
               
                
            }else{
                $newlistchapters = array();
                $newlistchapters[] = $_POST['books'];
                update_post_meta($post_id, '_chapters', $newlistchapters);
            }
           
        }else{

        }


        // save
        //update_post_meta($post_id, '_chapters', $listchaptersee);

    }

    function myshortcode_title( ){
   return "eefef";
}


    function writersportal_my_account_shortcode()
    {

    ?>
    <?php if ($_GET['page']) {
        if ($_GET['page']=='add-book') {
            if ($theme_file = locate_template(array( 'writers-portal/add-book.php' ))) {
                
                $file = $theme_file;
                include($file);
            } else {
                add_filter('document_title_parts', array($this,  'dq_override_post_title' ), 10);
                //echo $this->testfunction();
//                 add_filter( 'wp_title', 'add book',10,2 );

// add_shortcode( 'page_title', 'myshortcode_title' );
// add_filter('document_title_parts', 'tests', 10);


                // add_filter('document_title_parts', 'dq_override_post_title', 10);
    //             $title = array();
    //             $title['title'] = 'EXAMPLE'; 
    // $title['page'] = '2'; // optional
    // $title['tagline'] = 'Home Of Genesis Themes'; // optional
    //     $title['site'] = 'DevelopersQ'; //optional
    //             apply_filter ( 'document_title_parts', $title );
                $file = dirname(__FILE__) . '/template/add-book.php';
                include($file);
            }
        }
        if ($_GET['page']=='add-chapter') {
            if ($theme_file = locate_template(array( 'writers-portal/add-chapter.php' ))) {
                $file = $theme_file;
                include($file);
            } else {
                $file = dirname(__FILE__) . '/template/add-chapter.php';
                include($file);
            }
        }

        if ($_GET['page']=='edit-book') {
            if ($theme_file = locate_template(array( 'writers-portal/edit-book.php' ))) {
                $file = $theme_file;
                include($file);
            } else {
                $file = dirname(__FILE__) . '/template/edit-book.php';
                include($file);
            }
        }
        if ($_GET['page']=='edit-chapter') {
            if ($theme_file = locate_template(array( 'writers-portal/edit-chapter.php' ))) {
                $file = $theme_file;
                include($file);
            } else {
                $file = dirname(__FILE__) . '/template/edit-chapter.php';
                include($file);
            }
        }
} else { ?>

            <?php
            global $wp;
            $current_url = home_url(add_query_arg(array(), $wp->request));?>

<button class="button"><a href="<?php echo $current_url; ?>/?page=add-book">Add Book</a></button>
    <h1>My Books</h1>
    <?php
    $books = get_posts(array(
    'post_type' => 'book',
    'numberposts' => -1,
    'orderby' => 'post_title',
    'order' => 'ASC'
    )); ?>
    <table style="width:100%">
    <tr>
        <th>Book Title</th>
        <th>action</th>
    </tr>
      
        <?php foreach ($books as $book) : ?>
        <tr>
        <td><?php echo $book->post_title ?></td>
        <?php $curaddress = ( empty($_POST['_wp_http_referer']) ? site_url() : $_POST['_wp_http_referer'] ); ?>
        <td><a href="<?php echo $current_url.'/?page=edit-book&chap_id='.$book->ID; ?>">edit</a> <a href="">delete</a></td>
        </tr>
        <?php endforeach; ?>
      
     
    </table>

<button class="button"><a href="<?php echo $current_url; ?>/?page=add-chapter">Add Chapter</a></button>
    <h1>My Chapter</h1>
    <?php
    $books = get_posts(array(
    'post_type' => 'chapter',
    'numberposts' => -1,
    'orderby' => 'post_title',
    'order' => 'ASC'
    )); ?>
    <table style="width:100%">
    <tr>
        <th>Chapter Title</th>
        <th>body</th>
        <th>action</th>
    </tr>
      
        <?php foreach ($books as $book) : ?>
        <tr>
        <td><?php echo $book->post_title ?></td>
         <td><?php echo $book->post_content ?></td>
            <?php
            global $wp;
    $current_url = home_url(add_query_arg(array(),$wp->request)); //echo $current_url;?>

        <td><a href="<?php echo $current_url.'/?page=edit-chapter&chap_id='.$book->ID; ?>">edit</a> <a href="">delete</a></td>
        </tr>
        <?php endforeach; ?>
      
     
    </table>
    <?php
}
    }

    function writersportal_add_book_redirect($template)
    {
        if (!empty($_POST['writersportal_add_book'])) {
            $this->writersportal_process_book();
        } else {
            return $template;
        }
    }


    function writersportal_process_book()
    {
        
     // Check that all required fields are present and non-empty
        if (wp_verify_nonce(
            $_POST['writersportal_userform'],
            'writersportal_add_book_form'
        ) &&
        !empty($_POST['book_title']) &&
        !empty($_POST['book_description'])

        ) {
           // Create array with received data
            $new_book_review_data = array(
            'post_status' => 'publish',
            'post_title' => $_POST['book_title'],
            'post_type' => 'book',
            'post_content' => $_POST['book_description'] );
           // Insert new post in site database
           // Store new post ID from return value in variable
            $new_book_review_id = wp_insert_post($new_book_review_data);
           // Store book author and rating
           // add_post_meta( $new_book_review_id, 'book_author', wp_kses( $_POST['book_author'], array() ) );
           // add_post_meta( $new_book_review_id, 'book_rating', (int) $_POST['book_review_rating'] );
           // // Set book type on post
           // if ( term_exists( $_POST['book_review_book_type'], 'book_reviews_book_type' ) ) {
           // wp_set_post_terms( $new_book_review_id,
           // $_POST['book_review_book_type'],
           // 'book_reviews_book_type' );
           // }
           // Redirect browser to book review submission page
            $redirectaddress = ( empty($_POST['_wp_http_referer']) ? site_url() : $_POST['_wp_http_referer'] );
            wp_redirect(add_query_arg('writersportal-addbook', '1', $redirectaddress));
            exit;
        } else {
           // Display message if any required fields are missing
            $abortmessage = 'Some fields were left empty. Please ';
            $abortmessage .= 'go back and complete the form.';
            wp_die($abortmessage);
            exit;
        }
    }

    function writersportal_add_chapter_redirect($template)
    {
        if (!empty($_POST['writersportal_add_chapter_hidden'])) {
            $this->writersportal_process_chapter();
        } else {
            return $template;
        }
    }

    function writersportal_process_chapter()
    {
     // Check that all required fields are present and non-empty
        if (wp_verify_nonce(
            $_POST['chapter_br_user_form'],
            'writersportal_add_chapter_form'
        ) &&
        !empty($_POST['chapter_title']) &&
        !empty($_POST['chapter_body'])

        ) {
           // Create array with received data
            $new_book_review_data = array(
            'post_status' => 'publish',
            'post_title' => $_POST['chapter_title'],
            'post_type' => 'chapter',
            'post_content' => $_POST['chapter_body'] );
           // Insert new post in site database
           // Store new post ID from return value in variable
            $new_chapter_id = wp_insert_post($new_book_review_data);

            add_post_meta($new_chapter_id, '_book', $_POST['book_chapter']);

           // Redirect browser to book review submission page
            $redirectaddress =
            ( empty($_POST['_wp_http_referer']) ? site_url() :
            $_POST['_wp_http_referer'] );
            wp_redirect(add_query_arg(
                'writersportal_add_chapter',
                '1',
                $redirectaddress
            ));
            exit;
        } else {
           // Display message if any required fields are missing
            $abortmessage = 'Some fields were left empty. Please ';
            $abortmessage .= 'go back and complete the form.';
            wp_die($abortmessage);
            exit;
        }
    }


    function writersportal_edit_book_redirect($template)
    {
        if (!empty($_POST['writersportal_hidden_edit_book'])) {
            $this->writersportal_update_book_chapter();
        } else {
            return $template;
        }
    }


    function writersportal_update_book_chapter()
    {
     // Check that all required fields are present and non-empty
        if (wp_verify_nonce(
            $_POST['writersportal_user_form'],
            'writersportal_edit_book'
        ) &&
        !empty($_POST['book_title']) &&
        !empty($_POST['book_text'])

        ) {
           // Create array with received data
            $new_book_review_data = array(
            'ID' => (int)$_POST['writersportal_book_id'],
            'post_title' => $_POST['book_title'],
            'post_content' => $_POST['book_text']
            );
            $my_post = array(
             'ID'           => $_POST['writersportal_book_id'],
             'post_title'   => $_POST['book_title'],
             'post_content' => $_POST['book_text'],
            );
           // Insert new post in site database
           // Store new post ID from return value in variable
            wp_update_post($new_book_review_data);

            // print_r($new_book_review_data);
            // exit;

          // update_post_meta( $_POST['writersportal_book_id'], '_book', $_POST['book']  );

           // Redirect browser to book review submission page
            $redirectaddress =
            ( empty($_POST['_wp_http_referer']) ? site_url() :
            $_POST['_wp_http_referer'] );
            wp_redirect(add_query_arg(
                'writersportal-editbook-success',
                '1',
                $redirectaddress
            ));
            exit;
        } else {
           // Display message if any required fields are missing
            $abortmessage = 'Some fields were left empty. Please ';
            $abortmessage .= 'go back and complete the form.';
            wp_die($abortmessage);
            exit;
        }
    }
    function writersportal_edit_chapter_redirect($template)
    {
        if (!empty($_POST['writersportal_hidden_edit_chapter'])) {
            $this->writersportal_update_chapter();
        } else {
            return $template;
        }
    }
    function writersportal_update_chapter()
    {
     // Check that all required fields are present and non-empty
        if (wp_verify_nonce(
            $_POST['writersportal_user_form'],
            'writersportal_edit_chapter'
        ) &&
        !empty($_POST['chapter_title']) &&
        !empty($_POST['chapter_text'])

        ) {
           // Create array with received data
            $new_chapter_data = array(
            'ID' => (int)$_POST['writersportal_chapter_id'],
            'post_title' => $_POST['chapter_title'],
            'post_content' => $_POST['chapter_text']
            );
            $my_post = array(
             'ID'           => $_POST['writersportal_book_id'],
             'post_title'   => $_POST['chapter_title'],
             'post_content' => $_POST['chapter_text'],
            );
           // Insert new post in site database
           // Store new post ID from return value in variable
            wp_update_post($new_chapter_data);

            // print_r($new_book_review_data);
            // exit;

          update_post_meta( $_POST['writersportal_chapter_id'], '_book', $_POST['book']  );

           // Redirect browser to book review submission page
            $redirectaddress =
            ( empty($_POST['_wp_http_referer']) ? site_url() :
            $_POST['_wp_http_referer'] );
            wp_redirect(add_query_arg(
                'writersportal-editchapter-success',
                '1',
                $redirectaddress
            ));
            exit;
        } else {
           // Display message if any required fields are missing
            $abortmessage = 'Some fields were left empty. Please ';
            $abortmessage .= 'go back and complete the form.';
            wp_die($abortmessage);
            exit;
        }
    }
}
$writersportal = new WritersPortal();
?>
