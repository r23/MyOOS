<?php
/* ----------------------------------------------------------------------
   $Id: news_reviews_write.js.php 216 2013-04-02 08:24:45Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_reviews_write.php,v 1.51 2003/02/13 04:23:23 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

?>
<script language="javascript"><!--
function checkForm() {
  var error = 0;
  var error_message = "<?php echo decode($aLang['js_error']); ?>";

  var review = document.news_reviews_write.review.value;

  if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo decode($aLang['js_review_text']); ?>";
    error = 1;
  }

  if ((document.news_reviews_write.rating[0].checked) || (document.news_reviews_write.rating[1].checked) || (document.news_reviews_write.rating[2].checked) || (document.news_reviews_write.rating[3].checked) || (document.news_reviews_write.rating[4].checked)) {
  } else {
    error_message = error_message + "<?php echo decode($aLang['js_review_rating']); ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
