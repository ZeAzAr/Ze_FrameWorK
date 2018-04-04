<?php
/**
 *
 * User access layer class.
 *
 * This is a simple user access layer resource.
 *
 *
 * @copyright  2008 Tal Cohen
 * @author     Tal Cohen MSN: tal7814@hotmail.com
 * @version    CVS: $Id:$
 *
 *
 */


/**
 * simple extend class for SimpleAcl
 *
 */

include_once("simple_acl.php");

class NotSoSimpleAcl extends SimpleAcl {

   /**
    * make sure user can edit only his post in the blog
    *
    */
   public function editPost($db,$postId){

      //admin can edit everything
      if ($this->isAdmin()) {
         return true;
      }

      $postId = (int)$postId;

      //let check user vs. db
      $result = $db->fetchRow(
       " SELECT * "
      ." FROM posts "
      ." WHERE post_id = $postId "
      ." AND user_id = $this->userId "
      );

      //good user
      if (!empty($result)) {
      	return true;
      }
      //bad user
      else return false;
   }
}



/*

$userId = 127;
$userLevel = 4;

$acl = new NotSoSimpleAcl($userId,$userLevel);


$postId = 827;
$db="adodb or whatever";

if ($acl->editPost($db,$postId)) {
	echo "user can edit this post";
}
else {
   echo "user can't edit this post";
}

*/

?>