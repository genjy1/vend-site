<?php
class ControllerFeedRobots extends Controller {
	public function index() {
		$host = $_SERVER['HTTP_HOST'];

		$file=$_SERVER['SCRIPT_FILENAME'];
		$last_modified_time = filemtime($file);
		//$etag = md5_file($file);
		$etag = $last_modified_time;

		header("Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8");
		header( 'Content-Type: text/plain; charset=utf-8' );
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
		header("Etag: $etag");

		// $output = "User-agent: *\nDisallow: \n\nUser-agent: Yandex \nDisallow: \nHost: $host \nSitemap: https://vend-shop.com/sitemap.xml\n";
        $output = "User-agent: *\nDisallow: \n\nUser-agent: Yandex \nDisallow: \nSitemap: https://vend-shop.com/sitemap.xml\n";

		echo $output;
	}

}
