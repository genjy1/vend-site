<?php
class ModelToolImage extends Model {
	public function resize($filename, $width, $height) {

		if (!is_file(DIR_IMAGE . $filename)) {
			$ext = explode(".", $filename);
			$ext = end($ext);
			$bigext = strtoupper($ext);
			$smallext = strtolower($ext);

			$filename1 = str_replace(".".$ext, ".".$smallext, $filename);
			$filename2 = str_replace(".".$ext, ".".$bigext, $filename);

			if ((!is_file(DIR_IMAGE . $filename1)) && (!is_file(DIR_IMAGE . $filename2)) ) {
				// echo $filename1;
				// echo "    ".$filename2;
				return;
			} else {
				if (is_file(DIR_IMAGE . $filename1)) {
					$filename = $filename1;
				} elseif (is_file(DIR_IMAGE . $filename2)) {
					$filename = $filename2;
				}
			}
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$old_image = $filename;
		$new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $new_image) || (filectime(DIR_IMAGE . $old_image) > filectime(DIR_IMAGE . $new_image))) {
			$path = '';

			$directories = explode('/', dirname(str_replace('../', '', $new_image)));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $new_image);
			} else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
			}
		}

		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'image/' . $new_image;
		} else {
			return $this->config->get('config_url') . 'image/' . $new_image;
		}
	}
}
