<?php
global $con;
include("config.php");

if (isset($_POST['pay'])) {

    
    $user = $_POST['user'];
    $amount = $_POST['amount'];

    $query=mysqli_query($con,"SELECT * FROM user WHERE uid=$user");
    $row=mysqli_fetch_array($query);

    $queryhouse=mysqli_query($con,"SELECT * FROM property WHERE pid=$row[house_rented]");
    $house=mysqli_fetch_array($queryhouse);
    
    

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paymongo.com/v1/checkout_sessions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'data' => [
                'attributes' => [
                        'send_email_receipt' => true,
                        'show_description' => true,
                        'show_line_items' => true,
                        'cancel_url' => 'http://localhost/PhilipAndAurea/feature.php',
                        'line_items' => [
                                        [
                                                                        'currency' => 'PHP',
                                                                        'amount' => $amount * 100,
                                                                        'description' => 'Philip and Aurea',
                                                                        'quantity' => 1,
                                                                        'name' => $house['title']
                                        ]
                        ],
                        'success_url' => 'http://localhost/PhilipAndAurea/payment_success.php?user=' . urlencode($user) . '&amount=' . urlencode($amount),
                        'payment_method_types' => [
                                'card',
                                'gcash',
                                'paymaya'
                        ],
                            'description' => 'Philip and Aurea'
                ]
            ]
        ]),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "accept: application/json",
            "authorization: Basic c2tfdGVzdF9uZlI2VnhxSlczMWNZazd5aDE5aUZTVEU6"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $responseData = json_decode($response, true);
        
        if (isset($responseData['data']['attributes']['checkout_url'])) {
            header('Location: ' . $responseData['data']['attributes']['checkout_url']);

        } else {
            echo json_encode(['error' => 'Failed to create a payment link']);
        }
    }

} else {
    echo json_encode(['error' => 'Invalid request method']);
}

