<?php

require 'vendor/autoload.php';

if(isset($_FILES["file"])) {
    $allowedExts = array("mp4");
    $extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);


    if ((($_FILES["file"]["type"] == "video/mp4")) && in_array($extension, $allowedExts))
    {
    if ($_FILES["file"]["error"] > 0)
        {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
        }
    else
        {
        echo "Upload: " . $_FILES["file"]["name"] . "<br />";
        echo "Type: " . $_FILES["file"]["type"] . "<br />";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

        if (file_exists("video/" . $_FILES["file"]["name"]))
        {
            echo $_FILES["file"]["name"] . " already exists. ";
        }
        else
        {
        move_uploaded_file($_FILES["file"]["tmp_name"],
        "video/" . $_FILES["file"]["name"]);
        // echo "Stored in: " . "video/" . $_FILES["file"]["name"];
            $ffmpeg = FFMpeg\FFMpeg::create(array(
                'ffmpeg.binaries'  => 'C:/FFmpeg/bin/ffmpeg.exe',
                'ffprobe.binaries' => 'C:/FFmpeg/bin/ffprobe.exe',
                'timeout'          => 12000, // The timeout for the underlying process
                'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
            ));
            $output = "output";
            $format=new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264');
            $video = $ffmpeg->open("video/" . $_FILES["file"]["name"]);
            $video->filters()->clip(FFMpeg\Coordinate\TimeCode::fromSeconds($_POST["from"]), FFMpeg\Coordinate\TimeCode::fromSeconds($_POST["to"]));
            $video-> save($format, $output . ".mp4");
            echo "done";

            header('Content-type: video/flv');
            header("Content-Disposition:attachment;filename=\"output.mp4\"");
            //allways a good idea to let the browser know how much data to expect
            header("Content-length: " . filesize("localhost/video/output.mp4") . "\n\n"); 
            echo file_get_contents("localhost/video/output.mp4");
            
            // $filepath = "output.mp4";

            // // Process download
            // if(file_exists($filepath)) {
            //     header('Content-Description: File Transfer');
            //     header('Content-Type: video/mp4');
            //     header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
            //     header('Expires: 0');
            //     header('Cache-Control: must-revalidate');
            //     header('Pragma: public');
            //     header('Content-Length: ' . filesize($filepath));
            //     flush(); // Flush system output buffer
            //     readfile($filepath);
            // }
            
            $url = "output.mp4";
            header('Location: ' . $url, true, $permanent ? 301 : 302);
            die();           
        }
        }
    }
    else
    {
    echo "Invalid file";
    }
} 
?>