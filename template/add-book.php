<h2>Add Book</h2>
<?php
// make sure user is logged in
 if ( !is_user_logged_in() ) {
 echo '<p>You need to be a site member to be able to ';
 echo 'submit a book. Sign up to gain access!</p>';
 return;
 }
 ?>
 <form method="post" id="writersportal_add_new_book" action="">
 <!-- Nonce fields to verify visitor provenance -->
 <?php wp_nonce_field( 'writersportal_add_book_form', 'writersportal_userform' ); ?>
 <?php if ( isset( $_GET['writersportal-addbook'] )
 && $_GET['writersportal-addbook'] == 1 ) { ?>
 <div style="margin: 8px;border: 1px solid #ddd;
 background-color: #ff0;">
 Thank for your submission!
 </div>
<?php } ?>
<!-- Post variable to indicate user-submitted items -->
<input type="hidden" name="writersportal_add_book" value="1" />
 <table>
 <tr>
 <td>Book Title</td>
 <td><input type="text" name="book_title" /></td>
 </tr>
 <!-- <tr>
 <td>Book Author</td>
 <td><input type="text" name="book_author" /></td>
 </tr> -->
 <tr>
 <td>Book Description</td>
  <td><textarea name="book_description"></textarea></td>
  </tr>

</table>
<input type="submit" name="submit" value="Submit Book" />
</form>