<?php

	if (isset($wp_query->query_vars['myvar']))
	{
	print $wp_query->query_vars['myvar'];
	}

	// make sure user is logged in
	if ( !is_user_logged_in() ) {
	echo '<p>You need to be a site member to be able to ';
	echo 'submit book reviews. Sign up to gain access!</p>';
	return;
	}
	?>

	<?php 

	$id = get_the_ID();
	$slug = basename(get_permalink());
	$chap_id = (int)$_GET['chap_id'];
	if(!$chap_id or (get_post($chap_id) == null) or ($chap_id === 0)){
		echo 'please dont break me :(';

	}
	else{
		
		$post_chap = get_post($chap_id);
		$title = $post_id_1->post_title;
		echo '<h1>' . $title . '</h1>';

		?>

<form method="post" id="writersportal_editchapter" action="">
 <!-- Nonce fields to verify visitor provenance -->
 <?php wp_nonce_field( 'writersportal_edit_chapter', 'writersportal_user_form' ); ?>
 <?php if ( isset( $_GET['writersportal-editchapter-success'] ) && $_GET['writersportal-editchapter-success'] == 1 ) { ?>
 <div style="margin: 8px;border: 1px solid #ddd;
 background-color: #ff0;">
 Your chapter has been updated
 </div>
<?php } ?>
<!-- Post variable to indicate user-submitted items -->
<input type="hidden" name="writersportal_hidden_edit_chapter" value="1" />
<input type="hidden" name="writersportal_chapter_id" value="<?php echo $post_chap->ID; ?>" />
	<label for="">Title</label>
	<input type="text" name="chapter_title" value="<?php echo $post_chap->post_title;?>">
	<label for="">Body</label>
	<textarea name="chapter_text" id="" cols="30" rows="10"><?php echo $post_chap->post_content;?></textarea>
	
<?php 
$selected_book = get_post_meta( $post_chap->ID, '_book', false );
$all_books = get_posts( array(
    'post_type' => 'book',
    'numberposts' => -1,
    'orderby' => 'post_title',
    'order' => 'ASC'
) );
?>

<select name="book">
	    <?php foreach ( $all_books as $book ) : ?>
	    	<?php if(get_post_meta( $post_chap->ID, '_book', false )): ?>
	    		<?php 

	    		 ?>
	        <option value="<?php echo $book->ID; ?>"<?php if($book->ID == $selected_book[0]){ echo ' selected="selected"'; } ?> > <?php echo $book->post_title; ?></option>
	    	<?php else: ?>
	    	<option value="<?php echo $book->ID; ?>"><?php echo $book->post_title; ?></option>
	    	<?php endif; ?>
	    <?php endforeach; ?>
	    </select>
	    <br/> 
	<input type="submit" name="submit" value="Update Book" />
</form>



<?php }	 ?>

