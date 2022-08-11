<?php
include 'blogpost.php';

function GetBlogPosts($inId=null, $inTagId =null)
{
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "db41150_nettuts_blog";
	// Crear conexión
	$conn = new mysqli($servername, $username, $password, $dbname);
	// detectar conexión
	if ($conn->connect_error) {
    	die("La conexión falló: " . $conn->connect_error);
	}

	$sql = "";
	if (!empty($inId)) {
		$sql = "SELECT * FROM blog_posts WHERE id = " . $inId . " ORDER BY id DESC";
	} else if (!empty($inTagId)) {
		$sql = "SELECT blog_posts.* FROM blog_post_tags LEFT JOIN (blog_posts) ON (blog_post_tags.postID = blog_posts.id) WHERE blog_post_tags.tagID =" . $inTagId . " ORDER BY blog_posts.id DESC";
	} else {
		$sql = "SELECT * FROM blog_posts ORDER BY id DESC";
	}
	
	$result = $conn->query($sql);

	$postArray = array();
	if ($result->num_rows > 0) {
		// Datos resultantes
		while($row = $result->fetch_assoc()) {
			$myPost = new BlogPost($row["id"], $row['title'], $row['post'], $row['post'], $row["author_id"], $row['date_posted']);
			array_push($postArray, $myPost);
		}
	}
	$conn->close();
	return $postArray;
}
?>