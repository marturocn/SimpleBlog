<?php

class BlogPost {
	public $id;
	public $title;
	public $post;
	public $author;
	public $tags;
	public $datePosted;

	function __construct($inId=null, $inTitle=null, $inPost=null, $inPostFull=null, $inAuthorId=null, $inDatePosted=null) {
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

		if (!empty($inId)) {
			$this->id = $inId;
		}
		if (!empty($inTitle)) {
			$this->title = $inTitle;
		}
		if (!empty($inPost)) {
			$this->post = $inPost;
		}

		if (!empty($inDatePosted)) {
			$splitDate = explode("-", $inDatePosted);
			$this->datePosted = $splitDate[1] . "/" . $splitDate[2] . "/" . $splitDate[0];
		}

		if (!empty($inAuthorId)) {
			$sql = "SELECT first_name, last_name FROM people WHERE id = " . $inAuthorId;
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				// Datos resultantes
				while($row = $result->fetch_assoc()) {
					$this->author = $row["first_name"] . " " . $row["last_name"];
				}
			}
		}

		$postTags = "No Tags";
		if (!empty($inId)) {
			$tagArray = array();
			$tagIDArray = array();
			$sql = "SELECT tags.* FROM blog_post_tags LEFT JOIN (tags) ON (blog_post_tags.tag_id = tags.id) WHERE blog_post_tags.blog_post_id = " . $inId;
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				// Datos resultantes
				while($row = $result->fetch_assoc()) {
					array_push($tagArray, $row["name"]);
					array_push($tagIDArray, $row["id"]);
				}
			}

			if (sizeof($tagArray) > 0) {
				foreach ($tagArray as $tag) {
					if ($postTags == "No Tags") {
						$postTags = $tag;
					} else {
						$postTags = $postTags . ", " . $tag;
					}
				}
			}
		}
		$this->tags = $postTags;
		$conn->close();
	}
}

?>
