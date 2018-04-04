<?php

include_once("simple_acl.php");


$userId = 127;
$userLevel = 4;


/**
 * start simpleAcl class
 */
$acl = new SimpleAcl($userId,$userLevel);


//if in your script admin level is 50 or 5 you can set it this way, default=5
$acl->adminLevel =5;


/**
 * you can add new resources as array,
 * every resource have a global minimun access level
 */
$acl->addResource(
   array(
      "post"=>3,
      "delete"=>5)
      );

/**
 * in this way "edit_post"=>3 and all the rest will be assigned with user level 2
 *
 */

$acl->addResource(
   array(
      "edit_post"=>3,
      "forward_post",
      "delete_monkeys"
      )
      ,2
      );



if ($acl->isValid("post")) {
   echo "User can post\n";
}
else {
   echo "User can't post\n";
}

if (!$acl->isValid("delete")) {
	echo "user can't delete\n";
}
else {
   echo "user can delete\n";
}



/**
 * you also can add one resource at a time,
 * numbers are minimun access level for resource
 */
$acl->addResource("publish",4);

if ($acl->isValid("publish")) {
	echo "now user can publish\n";
}
else {
   echo "user can't publish\n";
}



/**
 * you can add current user access to resource
 * not depending on user level or minimun level
 */
$acl->allowUser("delete_comments"); //now current user can access to 'delete_comments' resource

if ($acl->isValid("delete_comments")) {
	echo "user can delete comment\n";
}



/**
 * in this way you can prevent from current user accessing a resource
 */
$acl->allowUser("delete_comments",false); //now current user can access to 'delete_comments' resource

if (!$acl->isValid("delete_comments\n")) {
	echo "now user can't delete comments\n";
}


/**
 * in this way you can show login box and register
 * box only to guests
 *
 * in this way resource is assigned only to users with level 1
 * users with user level 3 or will not have access to this resource
 */
$acl->allowLevel('register_box',true,1);
if ($acl->isValid("register_box")) {
	echo "view rgister box\n";
}else {
echo "do not view rgister box\n";
}
$acl->allowLevel('login_box',true,1);
?>