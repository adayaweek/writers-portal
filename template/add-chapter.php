 <h2>Add Chapter</h2>
 <?php

 // make sure user is logged in
 if ( !is_user_logged_in() ) {
 echo '<p>You need to be a site member to be able to ';
 echo 'submit book reviews. Sign up to gain access!</p>';
 return;
 }
 ?>
 <form method="post" id="writersportal_addchapter" action="">
 <!-- Nonce fields to verify visitor provenance -->
 <?php wp_nonce_field( 'writersportal_add_chapter_form', 'chapter_br_user_form' ); ?>
 <?php if ( isset( $_GET['writersportal_add_chapter'] ) && $_GET['writersportal_add_chapter'] == 1 ) { ?>
 <div style="margin: 8px;border: 1px solid #ddd;
 background-color: #ff0;">
 Thank for your submission!
 </div>
<?php } ?>
<!-- Post variable to indicate user-submitted items -->
<input type="hidden" name="writersportal_add_chapter_hidden" value="1" />
 <table>
 <tr>
 <td>Chapter Title</td>
 <td><input type="text" name="chapter_title" /></td>
 </tr>
 <!-- <tr>
 <td>Book Author</td>
 <td><input type="text" name="book_author" /></td>
 </tr> -->
 <tr>
 <td>Body</td>
  <td><textarea name="chapter_body"></textarea></td>
  </tr>
<?php 
  $all_books = get_posts( array(
      'post_type' => 'book',
      'numberposts' => -1,
      'orderby' => 'post_title',
      'order' => 'ASC'
  ) );

   ?>
  <tr>
  	<td>Book</td>
  	<td>
	  	<select name="book_chapter">
	  	<?php foreach ($all_books as $book) {
	  		?>
	<option value="<?php echo $book->ID; ?>"> <?php echo $book->post_title; ?></option>
	  		<?php
	  	} ?>
	  	 
	  	</select>
  	</td>
  </tr>

  </table>
  
  <input type="submit" name="submit" value="Submit Chapter" />
  </form>