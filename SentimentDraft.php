<?php

session_start();
require("config.php");
require 'vendor/autoload.php'; 

use GuzzleHttp\Client;

$client = new Client();
$endpoint = 'https://api.mymemory.translated.net/get';

// $name=$_POST['name'];
// $phone=$_POST['phone'];

//$content=$_POST['content'];
	
$uid=$_SESSION['uid'];

$original_text = $_POST['content'];

try {
    
    $response = $client->get($endpoint, [
        'query' => [
            'q' => $original_text,
            'langpair' => 'tl|en', 
        ],
    ]);

    $data = json_decode($response->getBody(), true);

    
    if (isset($data['responseData']['translatedText'])) {
        $translated_text = $data['responseData']['translatedText'];

        $escaped_text = escapeshellarg($translated_text);

        $output = shell_exec("python sentiment_analysis.py $escaped_text");

    } else {
        $translated_text = "Translation not found.";
    }

    
    //echo "Original Text: $original_text\n" . "<br>";
    //echo "Sentiment Analysis: $output";

	
	
	if(!empty($original_text))
	{
		
		$sql="INSERT INTO feedback (uid,fdescription,status, sentiment) VALUES ('$uid','$original_text','0', '$output')";
		    $result=mysqli_query($con, $sql);
		    if($result){
			    $msg = "<p class='alert alert-success'>Feedback Send Successfully</p> ";
                header("Location: profile.php?message=$msg");
		    }
		    else{
		        $error = "<p class='alert alert-warning'>Feedback Not Send Successfully</p> ";
                header("Location: profile.php?error=$error");
		    }
	}else{
		$error = "<p class='alert alert-warning'>Please Fill all the fields</p>";
        header("Location: profile.php?error=$error");
	}


} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
